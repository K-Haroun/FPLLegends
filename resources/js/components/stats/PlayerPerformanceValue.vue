<script setup>
import { Scatter } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    PointElement,
    LinearScale
} from 'chart.js'
import { ref, Transition } from 'vue';
import { Vue3SlideUpDown } from "vue3-slide-up-down";
import { ChevronDown, ChevronUp } from 'lucide-vue-next';

// ChartJS.register(Title, Tooltip, Legend, PointElement, LinearScale)

const props = defineProps(['overAndUnderPerformers']);
const overPerformers = props.overAndUnderPerformers.overperformers;
const underPerformers = props.overAndUnderPerformers.underperformers;

const imageFile = (fpl_id, name) => `/images/players/${fpl_id}_${name}.png`;

// const datasets = [{
//     label: 'Players',
//     data: props.playerData.map(player => ({
//         x: player.price / 10,
//         y: player.total_points,
//         name: player.name
//     })),
//     backgroundColor: props.playerData.map(player =>
//         player.total_points / (player.price / 10) > pointsPerMillionThreshold ? '#2A9D8F' : '#E63946'
//     ),
//     pointRadius: 4,
//     pointHoverRadius: 6
// }]

// const chartData = { datasets }

// const chartOptions = {
//     responsive: true,
//     plugins: {
//         title: {
//             display: true,
//             text: 'Underperforming vs Overperforming Players'
//         },
//         tooltip: {
//             callbacks: {
//                 label: (context) => `${context.raw.name}: Cost £${context.raw.x}M, Points ${context.raw.y}`
//             }
//         },
//         legend: { display: false }
//     },
//     scales: {
//         x: {
//             title: { display: true, text: 'Cost (£M)' },
//             min: 4,
//             max: 15
//         },
//         y: {
//             title: { display: true, text: 'Total Points' },
//             min: 0,
//             max: 20
//         }
//     }
// }

const safeName = (team_name) => team_name.replace(/\s+/g, '_');
const teamImage = (team_id, team_name) => `/images/teams/${team_id}_${safeName(team_name)}.png`;

// Opening player stats
const show = ref(false);
const openPlayerId = ref(null);
const openStats = (id) => {
    openPlayerId.value = id;
    show.value = !show.value;
};
</script>

<template>
    <div class="flex flex-col justify-center gap-2">
        <div v-for="player in overPerformers" :key="player.id" @click="openStats(player.id)">
            <div
                class="flex flex-wrap flex-col justify-center items-start pl-5 py-2 bg-green-500/20 shadow-md hover:bg-green-500/30">

                <div class="flex justify-center items-center gap-2 w-full pr-5">

                    <img :src="imageFile(player.fpl_id, player.web_name)" alt=""
                        class="h-8 sm:h-15 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1"
                        @error="e => e.target.src = '/images/players/profileplaceholder.png'">

                    <img :src="teamImage(player.team_id, player.team)" alt="Team Image" class="size-6 sm:size-8"
                        @error="e => e.target.src = '/images/players/profileplaceholder.png'">

                    <div>
                        <p class="text-md text-wrap">{{ player.web_name }}</p>
                    </div>

                    <div class="flex text-md font-bold ml-auto">
                        <!-- <p>£{{ player.price / 10 }} M</p>
                    <p class="mx-3">|</p> -->
                        <p>{{ player.performances[0].total_points }} Points</p>
                    </div>

                    <div>
                        <div v-if="openPlayerId === player.id && show === true">
                            <ChevronDown />
                        </div>
                        <div v-else>
                            <ChevronUp />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transitioned content -->
            <Vue3SlideUpDown v-model="show">
                <div v-if="openPlayerId === player.id" class="bg-green-800/20 text-white p-3">
                    <table class="table-fixed w-full h-10 text-center text-xs">
                        <thead class="border-b border-gray-200">
                            <tr>
                                <th>MP</th>
                                <th>GS</th>
                                <th>A</th>
                                <th>xG</th>
                                <th>xA</th>
                                <th>DC</th>
                                <th>BPS</th>
                                <th>£</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ player.performances[0].minutes }}</td>
                                <td>{{ player.performances[0].goals_scored }}</td>
                                <td>{{ player.performances[0].assists }}</td>
                                <td>{{ player.performances[0].expected_goals }}</td>
                                <td>{{ player.performances[0].expected_assists }}</td>
                                <td>{{ player.performances[0].defensive_contribution }}</td>
                                <td>{{ player.performances[0].bps }}</td>
                                <td>{{ player.cost }}M</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Vue3SlideUpDown>
        </div>
    </div>

    <hr class="my-5 bg-gray-400">

    <div class="flex flex-col justify-center gap-2">
        <div v-for="player in underPerformers" :key="player.id" @click="openStats(player.id)">
            <div
                class="flex flex-wrap flex-col justify-center items-start pl-5 py-2 bg-red-500/20 shadow-md hover:bg-red-500/30">

                <div class="flex justify-center items-center gap-2 w-full pr-5">

                    <img :src="imageFile(player.fpl_id, player.web_name)" alt=""
                        class="h-8 sm:h-15 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1"
                        @error="e => e.target.src = '/images/players/profileplaceholder.png'">

                    <img :src="teamImage(player.team_id, player.team)" alt="Team Image" class="size-6 sm:size-8"
                        @error="e => e.target.src = '/images/players/profileplaceholder.png'">

                    <div>
                        <p class="text-md text-wrap">{{ player.web_name }}</p>
                    </div>

                    <div class="flex text-md font-bold ml-auto">
                        <!-- <p>£{{ player.price / 10 }} M</p>
                    <p class="mx-3">|</p> -->
                        <p>{{ player.performances[0].total_points }} Points</p>
                    </div>

                    <div>
                        <div v-if="openPlayerId === player.id && show === true">
                            <ChevronDown />
                        </div>
                        <div v-else>
                            <ChevronUp />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transitioned content -->
            <Vue3SlideUpDown v-model="show">
                <div v-if="openPlayerId === player.id" class="bg-red-800/20 text-white p-3">
                    <table class="table-fixed w-full h-10 text-center text-xs">
                        <thead class="border-b border-gray-200">
                            <tr>
                                <th>MP</th>
                                <th>GS</th>
                                <th>A</th>
                                <th>xG</th>
                                <th>xA</th>
                                <th>DC</th>
                                <th>BPS</th>
                                <th>£</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ player.performances[0].minutes }}</td>
                                <td>{{ player.performances[0].goals_scored }}</td>
                                <td>{{ player.performances[0].assists }}</td>
                                <td>{{ player.performances[0].expected_goals }}</td>
                                <td>{{ player.performances[0].expected_assists }}</td>
                                <td>{{ player.performances[0].defensive_contribution }}</td>
                                <td>{{ player.performances[0].bps }}</td>
                                <td>{{ player.cost }}M</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Vue3SlideUpDown>
        </div>
    </div>


    <!-- <div class="p-5 dark:bg-gray-900 rounded-md shadow-md">
        <Scatter :data="chartData" :options="chartOptions" />
    </div> -->
</template>