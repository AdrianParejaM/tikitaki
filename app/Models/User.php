<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    //A esto se le llama trails y es una forma de hacer herencia multiple
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nickname',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Relación para obtener las ligas en las que participa el usuario.
     * @return BelongsToMany
     */
    public function leagues(): BelongsToMany
    {
        return $this->belongsToMany(League::class)
            ->withPivot('is_admin')
            ->withTimestamps();
    }

    /**
     *  Relación para obtener las alineaciones creadas por el usuario.
     * @return HasMany
     */
    public function lineups(): HasMany
    {
        return $this->hasMany(Lineup::class);
    }


    /**
     *  Relación para obtener los jugadores asociados al usuario.
     * @return BelongsToMany
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'user_player', 'user_id', 'player_id')
            ->using(UserPlayer::class)
            ->withPivot('league_id', 'date_signing');
    }

// Añade este método para verificar jugadores únicos
    public function hasPlayerInLeague($playerId, $leagueId)
    {
        return $this->players()
            ->where('player_id', $playerId)
            ->where('league_id', $leagueId)
            ->exists();
    }

}
