<script setup>
import PlayerStatBox from './PlayerStatBox.vue';

defineProps(['stats']);
</script>

<template>

    <div v-for="(gameweek, index) in stats">
        <h2 class="text-xl my-6">Gameweek {{ stats[index].gameweek_id }}</h2>
        <!-- <div class="grid grid-cols-6 gap-8 mt-5">

            <PlayerStatBox>
                <h2>Minutes</h2>
                <p>{{ stats.minutes }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Goals</h2>
                <p>{{ stats.goals_scored }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Assists</h2>
                <p>{{ stats.assists }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Clean Sheets</h2>
                <p>{{ stats.clean_sheets }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Yellow Cards</h2>
                <p>{{ stats.yellow_cards }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Red Cards</h2>
                <p>{{ stats.red_cards }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Own Goals</h2>
                <p>{{ stats.own_goals }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Penalties Missed</h2>
                <p>{{ stats.penalties_missed }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Goals Conceded</h2>
                <p>{{ stats.goals_conceded }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Saves</h2>
                <p>{{ stats.saves }}</p>
            </PlayerStatBox>

            <PlayerStatBox>
                <h2>Penalties Saved</h2>
                <p>{{ stats.penalties_saved }}</p>
            </PlayerStatBox>
        </div> -->

        <div class="overflow-x-auto">
            <div class="block space-y-4">
                <div class="border rounded py-2">
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">FPL Points</span>
                        <span>{{ stats[index].total_points }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">FPL Bonus Points</span>
                        <span>{{ stats[index].bps }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Starts</span>
                        <span>{{ stats[index].starts }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Minutes</span>
                        <span>{{ stats[index].minutes }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Goals</span>
                        <span>{{ stats[index].goals_scored }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Assists</span>
                        <span>{{ stats[index].assists }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Clean Sheets</span>
                        <span>{{ stats[index].clean_sheets }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Influence, Creativity, Threat</span>
                        <span>{{ stats[index].ict_index }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Defensive Contributions</span>
                        <span>{{ stats[index].defensive_contribution }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Tackles</span>
                        <span>{{ stats[index].tackles }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Recoveries</span>
                        <span>{{ stats[index].recoveries }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Saves</span>
                        <span>{{ stats[index].saves }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Goals Conceded</span>
                        <span>{{ stats[index].goals_conceded }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Own Goals</span>
                        <span>{{ stats[index].own_goals }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Yellow Cards</span>
                        <span>{{ stats[index].yellow_cards }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Red Cards</span>
                        <span>{{ stats[index].red_cards }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Expected Goals</span>
                        <span>{{ stats[index].expected_goals }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Expected Goal Involvments</span>
                        <span>{{ stats[index].expected_goal_involvements }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Expected Assists</span>
                        <span>{{ stats[index].expected_assists }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Blocks & Interceptions</span>
                        <span>{{ stats[index].clearances_blocks_interceptions }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Penalties Saved</span>
                        <span>{{ stats[index].penalties_saved }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Penalties Missed</span>
                        <span>{{ stats[index].penalties_missed }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>