<script setup>
import SectionHeader from '@/components/stats/SectionHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import PlayersOfWeek from '@/components/stats/PlayersOfWeek.vue';
import TeamsOfWeek from '@/components/stats/TeamsOfWeek.vue';
import TeamPointsTrend from '@/components/stats/TeamPointsTrend.vue';
import PlayerPerformanceValue from '@/components/stats/PlayerPerformanceValue.vue';

const breadcrumbs = [
    {
        title: 'Stats',
        href: '/stats',
    },
];

const props = defineProps(['allPlayers', 'topPlayers', 'topTeams', 'topTeamsAllWeeks', 'overAndUnderPerformers']);

const currentGameWeek = props.topPlayers.gameweek;

</script>

<template>

    <Head title="Stats" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 px-8 sm:px-11">
            <SectionHeader>Players of the week</SectionHeader>
            <div class="flex flex-wrap justify-between gap-5 sm:gap-5 mb-8">
                <PlayersOfWeek v-for="(player, position) in topPlayers.top_players" :key="position"
                    :topPlayer="player" />
            </div>

            <SectionHeader>Teams of the week</SectionHeader>
            <div class="flex flex-col flex-wrap justify-between gap-2 mb-10">
                <TeamsOfWeek v-for="(team, id) in topTeams" :key="id" :topTeam="team" :currentGameweek="currentGameWeek"/>
            </div>

                <SectionHeader>Overperformers & Underperformers</SectionHeader>
            <div class="mb-10">
                <PlayerPerformanceValue :overAndUnderPerformers="overAndUnderPerformers" />
            </div>

            <!-- <SectionHeader>Team points trends</SectionHeader>
            <TeamPointsTrend :team-points-trend="topTeamsAllWeeks" :current-game-week="currentGameWeek" /> -->
        </div>
    </AppLayout>
</template>
