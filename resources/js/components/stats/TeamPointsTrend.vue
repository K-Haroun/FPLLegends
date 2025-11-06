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

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

const props = defineProps({
    teamPointsTrend: Array,
    currentGameWeek: Number
})

const getChartData = (team) => {
    const labels = team.trend
        .filter(item => item.gameweek <= props.currentGameWeek)
        .map(item => `GW ${item.gameweek}`) ?? []

    const data = {
        labels,
        datasets: [{
            label: team.team,
            data: team.trend
                .filter(t => t.gameweek <= props.currentGameWeek)
                .map(t => t.points),
            borderColor: '#2A9D8F',
            tension: 0.3,
            fill: false
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
                    text: team.team
                }
            },
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    }
}

const colors = ['#E63946', '#F4A261', '#2A9D8F', '#457B9D', '#264653', '#A8DADC', '#E76F51', '#F1FAEE', '#A2D2D2', '#BBE1FA', '#B7B7B7', '#D4A59A', '#8D99AE', '#2E4057', '#F4E0B9', '#C9CBA3', '#6D8299', '#D3A588', '#9A8C98', '#4A5859']
</script>

<template>
    <div class="p-5 dark:bg-gray-900 rounded-md shadow-md grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="(team, index) in teamPointsTrend" :key="team.team" class="bg-gray-800 p-4 rounded-md">
            <Line :data="getChartData(team).data" :options="getChartData(team).options" />
        </div>
    </div>
</template>