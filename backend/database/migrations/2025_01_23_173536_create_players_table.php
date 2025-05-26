<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name_player');
            $table->enum('position', ['Goalkeeper', 'Defender', 'Midfielder', 'Forward']);
            $table->integer('market_value');
            $table->string('image')->nullable();
            $table->foreignId('club_id')->constrained();
            $table->integer('api_id')->unique();
            $table->string('nationality', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
