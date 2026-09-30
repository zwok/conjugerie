<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tense extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the conjugations for the tense.
     */
    public function conjugations(): HasMany
    {
        return $this->hasMany(Conjugation::class);
    }

    /**
     * Get the conjugation sets that include this tense.
     */
    public function conjugationSets(): BelongsToMany
    {
        return $this->belongsToMany(ConjugationSet::class, 'conjugation_set_tense')
            ->withTimestamps();
    }
}
