<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conjugation extends Model
{
    protected $fillable = [
        'verb_id',
        'tense_id',
        'person',
        'conjugated_form',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Get the verb that owns the conjugation.
     */
    public function verb()
    {
        return $this->belongsTo(Verb::class);
    }

    /**
     * The tense this conjugation belongs to.
     */
    public function tense()
    {
        return $this->belongsTo(Tense::class);
    }

    /**
     * Get the student answers for the conjugation.
     */
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
