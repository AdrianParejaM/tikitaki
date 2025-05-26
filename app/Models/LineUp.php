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

    protected $fillable = [
        'name_lineUp',  // Asegúrate de que coincida con el nombre en la migración
        'description',
        'user_id',
        'league_id'
    ];


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
