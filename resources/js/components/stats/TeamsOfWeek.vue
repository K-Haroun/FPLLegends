<script setup>
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement
} from 'chart.js'
import { computed } from 'vue';

const props = defineProps(['topTeam', 'currentGameweek']);
const topPlayers = computed(() => {
    return props.topTeam.players ? props.topTeam.players.slice(0, 5) : [];
});
const upcomingFixtures = computed(() => {
    return props.topTeam.upcomingFixtures
});

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

const getChartData = (team) => {
    const labels = team.trend
        .filter(item => item.gameweek <= props.currentGameweek)
        .map(item => `GW ${item.gameweek}`) ?? []

    const data = {
        labels,
        datasets: [{
            label: team.name,
            data: team.trend
                .filter(t => t.gameweek <= props.currentGameweek)
                .map(t => t.points),
            borderColor: 'lightblue',
            backgroundColor: [
                'gray'
            ],
            tension: 0.1,
            fill: true
        }]
    }

    return {
        data,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: team.name
                }
            },
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    }
}

const positions = {
    1: 'Goalkeeper',
    2: 'Defender',
    3: 'Midfielder',
    4: 'Forward',
};

const safeName = (team_name) => team_name.replace(/\s+/g, '_');
const teamImage = (team_id, team_name) => `/images/teams/${team_id}_${safeName(team_name)}.png`;
</script>

<template>
    <div
        class="flex flex-col justify-start items-center gap-6 rounded-md sm:p-5 py-5 border-1 border-gray-200 dark:border-gray-900 dark:bg-primary-foreground shadow-md">

        <div class="flex justify-center items-center gap-3 w-full">

            <img :src="teamImage(topTeam.id, topTeam.name)" alt="Team Image" class="size-12"
                @error="e => e.target.src = '/images/players/profileplaceholder.png'">
                
            <div class="flex flex-col justify-center items-center">
                <p class="text-xl font-bold">{{ topTeam.name }}</p>
            </div>
            <div class="text-2xl">|</div>
            <p class="text-2xl">{{ topTeam.points }} Points</p>
        </div>

        <table class="table-auto w-full h-60 text-center text-xs">
            <thead class="border-b border-gray-200">
                <tr>
                    <th>Player</th>
                    <th>Position</th>
                    <th>Goals</th>
                    <th>Assists</th>
                    <th>Saves</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="player in topPlayers" :key="player.id">
                    <td>{{ player.name }}</td>
                    <td>{{ positions[player.position].slice(0, 3) }}</td>
                    <td>{{ player.goals_scored }}</td>
                    <td>{{ player.assists }}</td>
                    <td>{{ player.saves }}</td>
                    <td>{{ player.total_points }}</td>
                </tr>
            </tbody>
        </table>

        <div class="bg-primary-foreground dark:bg-gray-50 rounded px-2">
            <Line :data="getChartData(topTeam).data" :options="getChartData(topTeam).options" />
        </div>

        <div class="flex flex-col gap-10 py-4 w-85 mb-auto ">
            <h2 class="text-center text-lg font-bold border-b-1 border-gray-200">NEXT FIXTURE</h2>
            <div class="flex flex-col justify-center items-center">
                <div v-for="fixture in upcomingFixtures" class="flex flex-col justify-center items-center gap-3">
                    <div class="flex gap-5">
                        <div class="flex flex-col gap-1 justify-center items-center">
                            <img :src="teamImage(fixture.team_h_id, fixture.team_h)" alt="Home Team" class="size-7"
                                @error="e => e.target.src = '/images/players/profileplaceholder.png'">
                            <h3 class="font-bold">
                                {{ fixture.team_h }}
                            </h3>
                        </div>

                        <span class="flex flex-col justify-between items-center text-sm">
                            <p>{{ fixture.kickoff_date }}</p>
                            <p>{{ fixture.kickoff_time }}</p>
                        </span>

                        <div class="flex flex-col gap-1 justify-center items-center">
                            <img :src="teamImage(fixture.team_a_id, fixture.team_a)" alt="" class="size-7"
                                @error="e => e.target.src = '/images/players/profileplaceholder.png'">
                            <h3 class="font-bold">
                                {{ fixture.team_a }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
