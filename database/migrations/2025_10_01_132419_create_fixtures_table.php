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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('fpl_id')->unique();
            $table->unsignedBigInteger('team_h')->nullable();
            $table->unsignedBigInteger('team_a')->nullable();
            $table->unsignedInteger('event')->nullable();
            $table->timestamp('kickoff_time')->nullable();
            $table->tinyInteger('team_h_score')->nullable();
            $table->tinyInteger('team_a_score')->nullable();
            $table->boolean('finished')->default(false);
            $table->timestamps();

            $table->foreign('team_h')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('team_a')->references('id')->on('teams')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
