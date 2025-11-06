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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_team_id')->constrained()->onDelete('cascade');
            $table->foreignId('gameweek_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_in_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('player_out_id')->constrained('players')->onDelete('cascade');
            $table->integer('cost')->default(0); // points deduction for extra transfers
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
