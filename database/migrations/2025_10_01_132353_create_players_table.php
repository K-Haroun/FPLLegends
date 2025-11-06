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
            $table->unsignedInteger('fpl_id')->unique();
            $table->string('web_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedSmallInteger('position')->nullable(); // 1=GK,2=DEF,3=MID,4=FWD (FPL uses element_type)
            $table->unsignedInteger('now_cost')->nullable(); // stored as integer (FPL uses tenths)
            $table->string('birth_date')->nullable();
            $table->string('news')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
            $table->index('team_id');
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
