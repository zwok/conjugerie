<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conjugation extends Model
{
    protected $fillable = [
        'verb_id',
        'tense',
        'person',
        'conjugated_form',
    ];

    /**
     * Get the verb that owns the conjugation.
     */
    public function verb()
    {
        return $this->belongsTo(Verb::class);
    }

    /**
     * Get the student answers for the conjugation.
     */
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
