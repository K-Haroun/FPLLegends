<script setup>
import { computed } from 'vue';


const props = defineProps({
    stats: {
        type: Array,
        default: () => []
    }
});

const pointsPerGameweek = computed(() => {
    return props.stats.map(s => s.total_points);
});

const series =
    [
        {
            data: pointsPerGameweek.value.reverse()
        }
    ]
const options = {
    chart: {
        height: 400,
        type: 'line',
        dropShadow: {
            enabled: true,
            color: '#fff',
            top: 5,
            left: 3,
            blur: 5,
            opacity: 0.3
        },
        zoom: {
            enabled: false
        },
        toolbar: {
            show: false
        }
    },
    colors: ['#77B6EA'],
    dataLabels: {
        enabled: true,
    },
    stroke: {
        curve: 'straight'
    },
    grid: {
        borderColor: '#97BFE6',
        row: {
            colors: ['#151b26', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5,
        },
    },
    markers: {
        size: 1
    },
    xaxis: {
        categories: ['GW1', 'GW2', 'GW3', 'GW4', 'GW5', 'GW6', 'GW7', 'GW8', 'GW9', 'GW10', 'GW11',],
        labels: {
            style: {
                colors: '#A0AEC0',
                fontSize: '8px',
            },
        },
        title: {
            text: 'Gameweeks',
            style: {
                color: '#A0AEC0',
                fontSize: '8px',
            },
        }
    },
    yaxis: {
        labels: {
            style: {
                colors: '#A0AEC0',
                fontSize: '8px',
            },
        },
        title: {
            text: 'Points',
            style: {
                color: '#A0AEC0',
                fontSize: '8px',
            },
        },
        min: 0,
        max: 30
    },
}
</script>
<template>
    <div>
        <apexchart type="line" :options="options" :series="series"></apexchart>
    </div>
</template>