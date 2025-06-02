<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LineUp extends Model
{
    use HasFactory;

    protected $table = 'lineups';

    protected $fillable = [
        'name_lineUp',
        'description',
        'user_id',
        'league_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'lineup_player')
            ->withPivot('position');
    }
}
