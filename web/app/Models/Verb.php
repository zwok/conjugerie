<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verb extends Model
{
    protected $fillable = [
        'infinitive',
        'english_translation',
        'group',
        'is_irregular',
        'is_auxiliary',
    ];

    protected $casts = [
        'is_irregular' => 'boolean',
        'is_auxiliary' => 'boolean',
    ];

    /**
     * Get the conjugations for the verb.
     */
    public function conjugations()
    {
        return $this->hasMany(Conjugation::class);
    }
}
