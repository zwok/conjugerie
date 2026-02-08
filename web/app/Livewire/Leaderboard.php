<?php

namespace App\Livewire;

use App\Models\StudentAnswer;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Leaderboard extends Component
{
    public string $type = 'weekly';
    public string $scope = 'class';
    public array $leaderboard = [];

    public function mount(): void
    {
        $this->loadLeaderboard();
    }

    public function toggleScope(): void
    {
        $this->scope = $this->scope === 'class' ? 'year' : 'class';
        $this->loadLeaderboard();
    }

    public function loadLeaderboard(): void
    {
        $user = Auth::user();

        if (! $user || ! $user->main_group_id) {
            $this->leaderboard = [];
            return;
        }

        $user->loadMissing('mainGroup');

        $query = StudentAnswer::query()
            ->where('is_correct', true)
            ->selectRaw('user_id, COUNT(*) as correct_count')
            ->groupBy('user_id')
            ->orderByDesc('correct_count')
            ->limit(100);

        if ($this->type === 'weekly') {
            $query->where('created_at', '>=', Carbon::now()->startOfWeek());
        }

        if ($this->scope === 'class') {
            $query->whereIn('user_id', function ($sub) use ($user) {
                $sub->select('id')
                    ->from('users')
                    ->where('main_group_id', $user->main_group_id);
            });
        } else {
            $year = $user->mainGroup?->year;
            if (! $year) {
                $this->leaderboard = [];
                return;
            }

            $query->whereIn('user_id', function ($sub) use ($year) {
                $sub->select('users.id')
                    ->from('users')
                    ->join('groups', 'users.main_group_id', '=', 'groups.id')
                    ->where('groups.year', $year);
            });
        }

        $results = $query->get();

        $userIds = $results->pluck('user_id')->all();
        $users = User::whereIn('id', $userIds)->pluck('name', 'id');

        $this->leaderboard = $results->map(function ($row) use ($users) {
            return [
                'user_id' => $row->user_id,
                'name' => $users[$row->user_id] ?? 'Inconnu',
                'count' => $row->correct_count,
            ];
        })->all();
    }

    public function render()
    {
        return view('livewire.leaderboard');
    }
}
