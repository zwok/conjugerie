<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ConjugationSet extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the verbs that belong to this conjugation set.
     */
    /**
     * Enabled conjugations covered by this set (its verbs × its tenses).
     */
    public function conjugationsQuery(): Builder
    {
        return Conjugation::query()
            ->where('enabled', true)
            ->whereIn('verb_id', $this->verbs()->pluck('verbs.id'))
            ->whereIn('tense_id', $this->tenses()->pluck('tenses.id'));
    }

    /**
     * Share (0-100) of this set's conjugations the user has answered correctly at least once.
     */
    public function progressFor(User $user): int
    {
        $ids = $this->conjugationsQuery()->pluck('id');

        if ($ids->isEmpty()) {
            return 0;
        }

        $mastered = StudentAnswer::where('user_id', $user->id)
            ->where('is_correct', true)
            ->whereIn('conjugation_id', $ids)
            ->distinct()
            ->count('conjugation_id');

        return (int) round($mastered / $ids->count() * 100);
    }

    public function verbs(): BelongsToMany
    {
        return $this->belongsToMany(Verb::class, 'conjugation_set_verb')
            ->withTimestamps();
    }

    /**
     * Get the tenses that belong to this conjugation set.
     */
    public function tenses(): BelongsToMany
    {
        return $this->belongsToMany(Tense::class, 'conjugation_set_tense')
            ->withTimestamps();
    }

    /**
     * Get the groups that this conjugation set is assigned to.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'conjugation_set_group')
            ->withTimestamps();
    }
}
