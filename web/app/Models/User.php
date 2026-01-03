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
     * The groups that the user belongs to.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Computed property to determine if the user is a teacher.
     * A user is a teacher if they belong to a group with code 'LKR'.
     *
     * Usage: $user->is_teacher
     */
    public function getIsTeacherAttribute(): bool
    {
        // If groups are already loaded, avoid an extra query
        if ($this->relationLoaded('groups')) {
            return (bool) $this->groups->firstWhere('code', 'LKR');
        }

        // Otherwise check via an existence query
        return $this->groups()->where('code', 'LKR')->exists();
    }
}
