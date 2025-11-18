<script setup>
import Pagination from "@/components/ui/pagination/Pagination.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const breadcrumbs = [
    {
        title: "Players",
        href: "/players",
    },
];

const props = defineProps(["players"]);
const search = ref('');

const filteredPlayers = computed(() => {
    if (!search.value) return props.players.data
    return props.players.data.filter(player =>
        player.first_name.toLowerCase().includes(search.value.toLowerCase()) ||
        player.second_name.toLowerCase().includes(search.value.toLowerCase())
    )
});

const imageFile = (fpl_id, name) => {
    const sanitizedName = name
        .toLowerCase()
        .replace(/[^a-z0-9]/g, "_");
    const capitalisedName = sanitizedName.charAt(0).toUpperCase() + sanitizedName.slice(1);
    return `/images/players/${fpl_id}_${capitalisedName}.png`;
};

const fallbackImage = ref("/images/players/profileplaceholder.png");

const onImageError = (event) => {
    event.target.src = fallbackImage.value;
};
</script>

<template>

    <Head title="Players" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-8 flex flex-col items-center min-h-screen">
            <input v-model="search" type="text" placeholder="Search players..."
                class="border border-gray-400 px-4 py-2 rounded mb-7 w-2/3" />
            <div class="flex flex-wrap justify-center gap-3">
                <div v-if="search && filteredPlayers.length === 0">
                    <h3>No players match your search</h3>
                </div>
                <div v-for="player in filteredPlayers" :key="player.id">
                    <Link :href="route('player.show', player.id)">
                    <div
                        class="flex justify-start items-center gap-3 border rounded shadow-md w-55 h-20 bg-primary-foreground border-gray-900 hover:bg-primary-foreground/70">
                        <img :src="imageFile(player.fpl_id, player.name)" :alt="`${player.name} profile`"
                            @error="onImageError"
                            class="h-10 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1 ml-5" />
                        <div>
                            <h3 class="text-md text-wrap font-bold">{{ player.name }}</h3>
                            <h3 class="text-sm text-wrap">{{ player.team }}</h3>
                        </div>
                    </div>
                    </Link>
                </div>
            </div>
        </div>

        <Pagination :meta="players.meta" :links="players.links" />
    </AppLayout>
</template>
