<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_id',
        'name',
        'description',
        'code',
        'platform',
        'year',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    /**
     * The users whose main group is this group.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'main_group_id');
    }
}
