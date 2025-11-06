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

ChartJS.register(Title, Tooltip, Legend, PointElement, LinearScale)

const props = defineProps({
    playerData: Array
})

const pointsPerMillionThreshold = 1.5

const overPerformers = props.playerData.filter(player => player.total_points / (player.price / 10) > pointsPerMillionThreshold).sort((a, b) => {
    return b.total_points - a.total_points
}).slice(0, 5);
const underPerformers = props.playerData.filter(player => player.total_points / (player.price / 10) < pointsPerMillionThreshold).sort((a, b) => {
    return b.price - a.price
}).slice(0, 5);

const imageFile = (fpl_id, name) => `/images/players/${fpl_id}_${name}.png`;

const datasets = [{
    label: 'Players',
    data: props.playerData.map(player => ({
        x: player.price / 10,
        y: player.total_points,
        name: player.name
    })),
    backgroundColor: props.playerData.map(player =>
        player.total_points / (player.price / 10) > pointsPerMillionThreshold ? '#2A9D8F' : '#E63946'
    ),
    pointRadius: 4,
    pointHoverRadius: 6
}]

const chartData = { datasets }

const chartOptions = {
    responsive: true,
    plugins: {
        title: {
            display: true,
            text: 'Underperforming vs Overperforming Players'
        },
        tooltip: {
            callbacks: {
                label: (context) => `${context.raw.name}: Cost £${context.raw.x}M, Points ${context.raw.y}`
            }
        },
        legend: { display: false }
    },
    scales: {
        x: {
            title: { display: true, text: 'Cost (£M)' },
            min: 4,
            max: 15
        },
        y: {
            title: { display: true, text: 'Total Points' },
            min: 0,
            max: 20
        }
    }
}
</script>

<template>
    <div class="flex gap-5 justify-center">
        <div v-for="player in overPerformers" :key="player.id"
            class="flex flex-wrap flex-col justify-center items-center rounded-md mb-10 w-47 py-5 bg-green-500/20 shadow-md">
            <div class="flex justify-center items-center gap-5">
                <img :src="imageFile(player.fpl_id, player.name)" alt=""
                    class="h-15 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1">
                <div>
                    <p class="text-md font-bold">{{ player.name }}</p>
                    <p class="text-sm">{{ player.total_points }} Points</p>
                    <p class="text-sm">£{{ player.price / 10 }} M</p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="flex gap-5 justify-center">
        <div v-for="player in underPerformers" :key="player.id"
            class="flex flex-wrap flex-col justify-center items-center rounded-md mb-10 mt-10 w-47 py-5 bg-red-500/20 shadow-md">
            <div class="flex justify-center items-center gap-5">
                <img :src="imageFile(player.fpl_id, player.name)" alt=""
                    class="h-15 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1">
                <div>
                    <p class="text-md font-bold">{{ player.name }}</p>
                    <p class="text-sm">£{{ player.price / 10 }} M</p>
                    <p class="text-sm">{{ player.total_points }} Points</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-5 dark:bg-gray-900 rounded-md shadow-md">
        <Scatter :data="chartData" :options="chartOptions" />
    </div>
</template>