<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verb extends Model
{
    protected $fillable = [
        'infinitive',
        'group',
    ];

    /**
     * Get the conjugations for the verb.
     */
    public function conjugations()
    {
        return $this->hasMany(Conjugation::class);
    }
}
