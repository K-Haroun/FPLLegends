<script setup>
import { ref } from 'vue';
import PlayerStatBox from './PlayerStatBox.vue';
import StatsGraph from './StatsGraph.vue';
import StatsPerFixture from './StatsPerFixture.vue';

const props = defineProps(['stats']);
const gameweekFilter = ref('');

</script>

<template>

    <div>
        <h3>Season Overview</h3>

        <div class="text-center">
            <StatsGraph :stats="stats.performances"/>
        </div>
        <div class="w-sm sm:w-full mx-auto">
            <StatsPerFixture :fixtures="stats.fixtures" :performances="stats.performances" />
        </div>
    </div>
</template>