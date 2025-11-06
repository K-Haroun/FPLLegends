<script setup>
import PlayerStatBox from './PlayerStatBox.vue';

defineProps(['stats']);
</script>

<template>

    <div>
        <h2 class="text-xl">Current Gameweek</h2>
        <hr class="mt-4">

        <div class="grid grid-cols-6 gap-8 mt-5">

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
        </div>
    </div>
</template>