<script setup>
import { computed } from 'vue';
import TeamStatsGraph from './TeamStatsGraph.vue';
import { teamImage } from '@/utils/helpers';

const props = defineProps(['topTeam', 'currentGameweek']);
const topPlayers = computed(() => {
    return props.topTeam.players ? props.topTeam.players.slice(0, 5) : [];
});
const upcomingFixtures = computed(() => {
    return props.topTeam.upcomingFixtures
});
</script>

<template>
    <div
        class="flex flex-col justify-start items-center rounded-md sm:p-5 pt-5 border-1 border-gray-200 dark:border-gray-900 dark:bg-primary-foreground shadow-md">

        <div class="flex justify-center items-center gap-3 w-full pb-5">

            <img :src="teamImage(topTeam.id, topTeam.name)" alt="Team Image" class="size-12"
                @error="e => e.target.src = '/images/players/profileplaceholder.png'">

            <div class="flex flex-col justify-center items-center">
                <p class="text-xl font-bold">{{ topTeam.name }}</p>
            </div>
            <div class="text-2xl">|</div>
            <p class="text-2xl">{{ topTeam.points }} Points</p>
        </div>

        <div class="w-full pt-5 bg-primary/5">

            <table class="table-auto w-full h-60 text-center text-xs">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>Goals</th>
                        <th>Assists</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="player in topPlayers" :key="player.id">
                        <td>{{ player.name }}</td>
                        <td>{{ player.goals_scored }}</td>
                        <td>{{ player.assists }}</td>
                        <td>{{ player.total_points }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="w-full">
                <TeamStatsGraph :stats="topTeam.trend" />
            </div>

            <div
                class="flex flex-col gap-5 py-4 w-75 mx-auto mb-10 rounded-lg bg-slate-800 p-6 shadow-[0_0_15px_rgba(59,130,246,0.5)] hover:shadow-[0_0_25px_rgba(96,165,250,0.7)] transition-shadow duration-300">
                <h2 class="text-center text-lg font-bold">NEXT</h2>
                <div class="flex flex-col justify-center items-center">
                    <div v-for="fixture in upcomingFixtures" class="flex flex-col justify-center items-center">
                        <div class="flex gap-7">
                            <div class="flex flex-5 flex-col gap-1 justify-center items-center">
                                <img :src="teamImage(fixture.team_h_id, fixture.team_h)" alt="Home Team" class="size-12"
                                    @error="e => e.target.src = '/images/players/profileplaceholder.png'">
                            </div>

                            <span class="flex flex-col justify-center items-center text-base">
                                <p>{{ fixture.kickoff_date }}</p>
                                <p>{{ fixture.kickoff_time }}</p>
                            </span>

                            <div class="flex flex-5 flex-col gap-1 justify-center items-center">
                                <img :src="teamImage(fixture.team_a_id, fixture.team_a)" alt="" class="size-12"
                                    @error="e => e.target.src = '/images/players/profileplaceholder.png'">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
