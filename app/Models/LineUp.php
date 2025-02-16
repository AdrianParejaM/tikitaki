<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class LineUp extends Model
{
    /** @use HasFactory<\Database\Factories\LineUpFactory> */
    use HasFactory;
    protected $table = 'lineups';


    /**
     *  Relación para obtener el usuario al que pertenece la alineación.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     *  Relación para obtener la liga a la que pertenece la alineación.
     * @return BelongsTo
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }
}
