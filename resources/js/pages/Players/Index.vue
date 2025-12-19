<script setup>
import Pagination from "@/components/ui/pagination/Pagination.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { imageFile } from "@/utils/helpers";

const breadcrumbs = [
    {
        title: "Players",
        href: "/players",
    },
];

const props = defineProps(["players", "allPlayers"]);
const search = ref('');
const teamFilter = ref('');
const positionFilter = ref('');
const positions = {
    1: 'Goalkeeper',
    2: 'Defender',
    3: 'Midfielder',
    4: 'Forward',
};

const filteredPlayers = computed(() => {
    const list = (!search.value && !teamFilter.value && !positionFilter.value) ? props.players.data : props.allPlayers.data;

    return list.filter(player => {
        const matchesSearch =
            !search.value ||
            (player.first_name ?? '').toLowerCase().includes(search.value.toLowerCase()) || (player.second_name ?? '').toLowerCase().includes(search.value.toLowerCase());

        const matchesTeam =
            !teamFilter.value || player.team === teamFilter.value;

        const matchesPosition =
            !positionFilter.value || player.position == positionFilter.value;

        return matchesSearch && matchesTeam && matchesPosition;
    });
});

const fallbackImage = ref("/images/players/profileplaceholder.png");

const onImageError = (event) => {
    event.target.src = fallbackImage.value;
};

const safeName = (team_name) => team_name.replace(/\s+/g, '_');
const teamImage = (player) => `/images/teams/${player.team_id}_${safeName(player.team)}.png`;
</script>

<template>

    <Head title="Players" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="sm:p-8 flex flex-col items-center min-h-screen">
            <div class="flex flex-col sm:flex-row justify-center items-center w-full gap-4 mt-5 sm:px-6 mb-7 text-center">

                <!-- Search -->
                <input v-model="search" type="text" placeholder="Search players..."
                    class="border border-gray-400 px-4 py-2 rounded w-2/3 sm:w-fit sm:ml-20 sm:flex-5" />

                <div class="flex-1 sm:flex-2 lg:flex-3 flex items-center gap-2">
                    <!-- Team Filter -->
                    <select v-model="teamFilter"
                        class="border p-2 rounded text-xs bg-gray-700 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                        <option value="">All Teams</option>
                        <option v-for="team in [...new Set(props.allPlayers.data.map(p => p.team))]" :key="team"
                            :value="team">
                            {{ team }}
                        </option>
                    </select>

                    <!-- Position Filter -->
                    <select v-model="positionFilter" class="border rounded p-2 text-xs bg-gray-700">
                        <option value="">All Positions</option>
                        <option v-for="(key, pos) in positions" :key="key" :value="key">
                            {{ key }}
                        </option>
                    </select>

                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-3">
            
                <div v-if="search && filteredPlayers.length === 0">
                    <h3>No players match your search</h3>
                </div>

                <div v-for="player in filteredPlayers" :key="player.id">
                    <Link :href="route('player.show', player.id)">
                    <div
                        class="flex justify-start items-center gap-3 border rounded shadow-md w-45 h-16 sm:w-55 sm:h-16 md:w-60 md:h-20 sm:pr-3 bg-primary-foreground border-gray-900 hover:bg-primary-foreground/70">

                        <img :src="imageFile(player.fpl_id, player.name)" :alt="`${player.name} profile`"
                            @error="onImageError"
                            class="h-7 sm:h-10 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1 ml-3 sm:ml-5" />

                        <div class="flex-3">
                            <h3 class="text-sm md:text-md text-wrap font-bold">{{ player.name }}</h3>
                            <h3 class="text-xs md:text-sm text-wrap">{{ positions[player.position] }}</h3>
                        </div>

                        <div class="flex-1">
                            <img :src="teamImage(player)" alt="Player Team" class="size-5 sm:size-8"></img>
                        </div>
                    </div>
                    </Link>
                </div>

            </div>
        </div>

        <Pagination :meta="players.meta" :links="players.links" />
    </AppLayout>
</template>
