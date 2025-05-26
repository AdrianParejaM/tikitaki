<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    /** @use HasFactory<\Database\Factories\ClubFactory> */
    use HasFactory;

    protected $fillable = [
        'api_id',
        'name_club',
        'city',
        'foundation',
        'image',
        'venue_name'
    ];

    /**
     *  Relación para obtener los jugadores que pertenecen al club.
     * @return HasMany
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
