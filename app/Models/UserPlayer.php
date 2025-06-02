<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPlayer extends Pivot
{
    protected $table = 'user_player';

    protected $fillable = [
        'user_id',
        'player_id',
        'league_id',
        'date_signing'
    ];
}
