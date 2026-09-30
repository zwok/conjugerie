<?php

namespace App\Livewire;

use App\Models\StudentAnswer;
use App\Models\User;
use App\Support\Avatar;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Leaderboard extends Component
{
    #[Locked]
    public string $type = 'weekly';
    public string $scope = 'class';
    public array $leaderboard = [];

    public function mount(): void
    {
        $this->loadLeaderboard();
    }

    public function setScope(string $scope): void
    {
        $this->scope = $scope === 'year' ? 'year' : 'class';
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

        if ($this->scope === 'year' && ! $user->mainGroup?->year) {
            $this->leaderboard = [];
            return;
        }

        $results = $this->ranking($user);

        // Ranks as they were before today, to show who moved up or down
        $previousRanks = array_flip($this->ranking($user, Carbon::today())->pluck('user_id')->all());

        $users = User::whereIn('id', $results->pluck('user_id'))->pluck('name', 'id');

        $this->leaderboard = $results->values()->map(function ($row, $index) use ($users, $previousRanks) {
            $name = $users[$row->user_id] ?? 'Inconnu';

            return [
                'user_id' => $row->user_id,
                'name' => $name,
                'initials' => Avatar::initials($name),
                'color' => Avatar::color($row->user_id),
                'xp' => (int) $row->total_xp,
                // Positive = moved up; null when the user was not ranked before today
                'delta' => isset($previousRanks[$row->user_id]) ? $previousRanks[$row->user_id] - $index : null,
            ];
        })->all();
    }

    protected function ranking(User $user, ?Carbon $before = null)
    {
        $query = StudentAnswer::query()
            ->where('is_correct', true)
            ->selectRaw('user_id, SUM(xp) as total_xp')
            ->groupBy('user_id')
            ->orderByDesc('total_xp')
            ->orderBy('user_id')
            ->limit(100);

        if ($this->type === 'weekly') {
            $query->where('created_at', '>=', Carbon::now()->startOfWeek());
        }

        if ($before) {
            $query->where('created_at', '<', $before);
        }

        if ($this->scope === 'class') {
            $query->whereIn('user_id', function ($sub) use ($user) {
                $sub->select('id')
                    ->from('users')
                    ->where('main_group_id', $user->main_group_id);
            });
        } else {
            $year = $user->mainGroup->year;

            $query->whereIn('user_id', function ($sub) use ($year) {
                $sub->select('users.id')
                    ->from('users')
                    ->join('groups', 'users.main_group_id', '=', 'groups.id')
                    ->where('groups.year', $year);
            });
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.leaderboard');
    }
}
