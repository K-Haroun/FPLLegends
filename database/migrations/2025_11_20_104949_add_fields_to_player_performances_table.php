<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('player_performances', function (Blueprint $table) {
            $table->float('influence')->nullable()->after('bps');
            $table->float('creativity')->nullable()->after('influence');
            $table->float('threat')->nullable()->after('creativity');
            $table->float('ict_index')->nullable()->after('threat');
            $table->integer('clearances_blocks_interceptions')->nullable()->after('ict_index');
            $table->integer('recoveries')->nullable()->after('clearances_blocks_interceptions');
            $table->integer('tackles')->nullable()->after('recoveries');
            $table->integer('defensive_contribution')->nullable()->after('tackles');
            $table->integer('starts')->nullable()->after('defensive_contribution');
            $table->float('expected_goals')->nullable()->after('starts');
            $table->float('expected_assists')->nullable()->after('expected_goals');
            $table->float('expected_goal_involvements')->nullable()->after('expected_assists');
            $table->float('expected_goals_conceded')->nullable()->after('expected_goal_involvements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_performance', function (Blueprint $table) {
            //
        });
    }
};
