<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Verb extends Model
{
    protected $fillable = [
        'infinitive',
    ];

    /**
     * Get the conjugations for the verb.
     */
    public function conjugations(): HasMany
    {
        return $this->hasMany(Conjugation::class);
    }

    /**
     * Get the conjugation sets that include this verb.
     */
    public function conjugationSets(): BelongsToMany
    {
        return $this->belongsToMany(ConjugationSet::class, 'conjugation_set_verb')
            ->withTimestamps();
    }
}
