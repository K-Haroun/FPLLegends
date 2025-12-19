<script setup>
import { computed } from 'vue';


const props = defineProps({
    stats: {
        type: Array,
        default: () => []
    }
});

const currentGameweek = 15;

const pointsPerGameweek = computed(() => {
    let filteredStats = props.stats.filter((s) => {
        return s.gameweek <= currentGameweek;
    })
    return filteredStats.map(s => s.points);
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
            enabled: false,
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
    tooltip: {
        theme: 'dark',
        style: {
            fontSize: '12px',
            fontFamily: undefined,
            color: '#ffffff'
        },
        x: {
            show: true,
        },
        y: {
            formatter: undefined,
            title: {
                formatter: () => 'Points:'
            },
        },
    },
    colors: ['#77B6EA'],
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: 'smooth'
    },
    grid: {
        show: false,
        borderColor: '#97BFE6',
        row: {
            colors: ['transparent'],
            opacity: 0.5,
        },
    },
    markers: {
        size: 1
    },
    xaxis: {
        categories: ['GW1', 'GW2', 'GW3', 'GW4', 'GW5', 'GW6', 'GW7', 'GW8', 'GW9', 'GW10', 'GW11', 'GW12', 'GW13', 'GW14', 'GW15'],
        labels: {
            style: {
                colors: '#A0AEC0',
                fontSize: '8px',
            },
        },
        title: {
            style: {
                color: '#A0AEC0',
                fontSize: '8px',
            },
        }
    },
    yaxis: {
        labels: {
            show: false,
            style: {
                colors: '#A0AEC0',
                fontSize: '8px',
            },
        },
        title: {
            show: false,
            style: {
                color: '#A0AEC0',
                fontSize: '8px',
            },
        },
        min: 0,
        max: 100
    },
}
</script>
<template>
    <div>
        <apexchart type="line" :options="options" :series="series"></apexchart>
    </div>
</template>