<script setup>
import { ref } from 'vue';
import PlayerStatBox from './PlayerStatBox.vue';

const props = defineProps(['stats']);
const gameweekFilter = ref('');

</script>

<template>

    <select v-model="gameweekFilter"
        class="border p-2 rounded text-xs bg-gray-700 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
        <option value="">All Gameweeks</option>
        <option v-for="gameweek in [...new Set(props.stats.map(g => g.gameweek_id))]" :key="gameweek" :value="gameweek">
            Gameweek {{ gameweek }}
        </option>
    </select>

    <div v-if="gameweekFilter === ''" v-for="(gameweek, index) in stats" :key="index">
        <h2 class="text-xl my-6">Gameweek {{ stats[index].gameweek_id }}</h2>

        <div class="overflow-x-auto">
            <div class="block space-y-4 text-lg">
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

    <div v-else>
        <h2 class="text-xl my-6">Gameweek {{ gameweekFilter }}</h2>

        <div v-for="gameweek in stats" class="overflow-x-auto">
            <div v-if="gameweek.gameweek_id === gameweekFilter" class="block space-y-4 text-lg">
                <div class="border rounded py-2">
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">FPL Points</span>
                        <span>{{ gameweek.total_points }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">FPL Bonus Points</span>
                        <span>{{ gameweek.bps }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Starts</span>
                        <span>{{ gameweek.starts }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Minutes</span>
                        <span>{{ gameweek.minutes }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Goals</span>
                        <span>{{ gameweek.goals_scored }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Assists</span>
                        <span>{{ gameweek.assists }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Clean Sheets</span>
                        <span>{{ gameweek.clean_sheets }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Influence, Creativity, Threat</span>
                        <span>{{ gameweek.ict_index }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Defensive Contributions</span>
                        <span>{{ gameweek.defensive_contribution }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Tackles</span>
                        <span>{{ gameweek.tackles }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Recoveries</span>
                        <span>{{ gameweek.recoveries }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Saves</span>
                        <span>{{ gameweek.saves }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Goals Conceded</span>
                        <span>{{ gameweek.goals_conceded }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Own Goals</span>
                        <span>{{ gameweek.own_goals }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Yellow Cards</span>
                        <span>{{ gameweek.yellow_cards }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Red Cards</span>
                        <span>{{ gameweek.red_cards }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Expected Goals</span>
                        <span>{{ gameweek.expected_goals }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Expected Goal Involvments</span>
                        <span>{{ gameweek.expected_goal_involvements }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Expected Assists</span>
                        <span>{{ gameweek.expected_assists }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Blocks & Interceptions</span>
                        <span>{{ gameweek.clearances_blocks_interceptions }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Penalties Saved</span>
                        <span>{{ gameweek.penalties_saved }}</span>
                    </div>
                    <div class="flex justify-between hover:bg-accent px-4">
                        <span class="font-semibold">Penalties Missed</span>
                        <span>{{ gameweek.penalties_missed }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>