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
