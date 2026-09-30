<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Smartschool fields
        'smartschool_id',
        'smartschool_username',
        'smartschool_platform',
        'main_group_id',
        'is_teacher',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_teacher' => 'boolean',
        ];
    }

    /**
     * Get the student answers for the user.
     */
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    /**
     * The user's main group (used for leaderboards).
     */
    public function mainGroup()
    {
        return $this->belongsTo(Group::class, 'main_group_id');
    }

    public function totalXp(): int
    {
        return (int) $this->studentAnswers()->sum('xp');
    }

    public function weeklyXp(): int
    {
        return (int) $this->studentAnswers()
            ->where('created_at', '>=', now()->startOfWeek())
            ->sum('xp');
    }

    /**
     * Days (Y-m-d) on which the user answered at least one question, newest first.
     *
     * @return list<string>
     */
    public function practiceDays(int $limit = 400): array
    {
        return $this->studentAnswers()
            ->selectRaw('DATE(created_at) as day')
            ->groupBy('day')
            ->orderByDesc('day')
            ->limit($limit)
            ->pluck('day')
            ->all();
    }

    /**
     * Number of consecutive days practiced, counting back from today
     * (or from yesterday when today has no answers yet).
     */
    public function streak(): int
    {
        $days = array_flip($this->practiceDays());
        $cursor = today();

        if (! isset($days[$cursor->toDateString()])) {
            $cursor = $cursor->subDay();
        }

        $streak = 0;
        while (isset($days[$cursor->toDateString()])) {
            $streak++;
            $cursor = $cursor->subDay();
        }

        return $streak;
    }

    /**
     * Computed property to determine if the user is a teacher.
     *
     * Usage: $user->is_teacher
     */
    public function getIsTeacherAttribute(): bool
    {
        return (bool) $this->attributes['is_teacher'];
    }
}
