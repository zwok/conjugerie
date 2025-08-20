<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'conjugation_id',
        'student_answer',
        'is_correct',
        'attempt_count',
        'last_practiced_at',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'last_practiced_at' => 'datetime',
    ];

    /**
     * Get the user that owns the student answer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the conjugation that owns the student answer.
     */
    public function conjugation()
    {
        return $this->belongsTo(Conjugation::class);
    }
}
