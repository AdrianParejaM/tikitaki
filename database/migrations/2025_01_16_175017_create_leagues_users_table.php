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
        Schema::create('leagues_users', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->foreignId('league_id')->constrained();
            $table->enum('role', ['admin', 'player']);
            $table->date('union_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leagues_users');
    }
};
