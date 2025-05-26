<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class League extends Model
{
    /** @use HasFactory<\Database\Factories\LeagueFactory> */
    use HasFactory;

    protected $fillable = [
        'name_league',
        'description',
        'user_id',
        'creation_date'
    ];

    /**
     *  Relación para obtener el usuario administrador de la liga.
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *  Relación para obtener los jugadores asociados a la liga.
     * @return BelongsToMany
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'user_player')
            ->withPivot('user_id', 'date_signing');
    }

    /**
     *  Relación para obtener los usuarios asociados a la liga.
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'union_date')
            ->withTimestamps();
    }

    /**
     *  Relación para obtener las alineaciones de la liga.
     * @return HasMany
     */
    public function lineups(): HasMany
    {
        return $this->hasMany(Lineup::class);
    }

    public function assignedPlayers()
    {
        return $this->hasManyThrough(
            Player::class,
            UserPlayer::class,
            'league_id',
            'id',
            'id',
            'player_id'
        );
    }
}
