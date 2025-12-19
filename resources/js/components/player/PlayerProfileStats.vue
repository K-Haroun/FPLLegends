<script setup>
import StatsGraph from './StatsGraph.vue';
import StatsPerFixture from './StatsPerFixture.vue';
import NumberAnimation from '../ui/NumberAnimation.vue';

const props = defineProps(['stats', 'fixtures', 'season_stats']);

</script>

<template>

    <div>
        <h3 class="mb-8">Season Overview</h3>

        <div class="flex gap-3 justify-center items-center">
            <div
                class="flex flex-col justify-center items-center gap-2 rounded-full border border-blue-500/30 inset-shadow-xs inset-shadow-blue-500 bg-blue-500/5 size-25 sm:size-60">
                <h2 class="text-xs sm:text-lg">Points</h2>
                <h2 class="text-2xl sm:text-4xl font-bold text-blue-200">
                    <NumberAnimation :targetNumber="season_stats.season_points" />
                </h2>
            </div>
            <div
                class="flex flex-col justify-center items-center gap-2 rounded-full border border-blue-500/30 inset-shadow-xs inset-shadow-blue-500 bg-blue-500/5 size-25 sm:size-60">
                <h2 class="text-xs sm:text-lg">Goals</h2>
                <h2 class="text-2xl sm:text-4xl font-bold text-blue-200">
                    <NumberAnimation :targetNumber="season_stats.season_goals" />
                </h2>
            </div>
            <div
                class="flex flex-col justify-center items-center gap-2 rounded-full border border-blue-500/30 inset-shadow-xs inset-shadow-blue-500 bg-blue-500/5 size-25 sm:size-60">
                <h2 class="text-xs sm:text-lg">Assists</h2>
                <h2 class="text-2xl sm:text-4xl font-bold text-blue-200">
                    <NumberAnimation :targetNumber="season_stats.season_assists" />
                </h2>
            </div>
        </div>

        <div class="text-center">
            <StatsGraph :stats="stats.performances" />
        </div>
        <div class="w-sm sm:w-2xl mx-auto">
            <StatsPerFixture :fixtures="fixtures" :performances="stats.performances" />
        </div>
    </div>
</template>

<style scoped>
.motion-pre {
    font-size: 64px;
    color: #4ff0b7;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>