<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;


    /**
     *  Relación para obtener el club al que pertenece el jugador.
     * @return BelongsTo
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    protected $fillable = [
        'api_id',
        'name_player',
        'position',
        'market_value',
        'club_id',
        'image',
        'nationality'
    ];

    /**
     *  Relación para obtener los usuarios asociados al jugador.
     * @return BelongsToMany
     */
    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_player', 'player_id', 'user_id')
            ->using(UserPlayer::class)
            ->withPivot('league_id', 'date_signing');
    }
}
