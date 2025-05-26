<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_player', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->foreignId('player_id')->constrained();
            $table->foreignId('league_id')->constrained();
            $table->date('date_signing')->default(now());
            $table->timestamps();

            $table->primary(['user_id', 'player_id', 'league_id']);

            // Asegura que un jugador solo esté en una liga
            $table->unique(['player_id', 'league_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_player');
    }
};
