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
        Schema::table('players', function (Blueprint $table) {
            $table->string('nationality')->nullable()->after('birth_date');
            $table->integer('height_cm')->nullable()->after('nationality');
            $table->integer('weight_kg')->nullable()->after('height_cm');
            $table->string('preferred_foot')->nullable()->after('weight_kg');
            $table->string('birth_place')->nullable()->after('preferred_foot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            //
        });
    }
};
