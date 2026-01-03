<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tense extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the conjugations for the tense.
     */
    public function conjugations()
    {
        return $this->hasMany(Conjugation::class);
    }
}
