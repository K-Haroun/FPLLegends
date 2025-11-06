<script setup>
import Pagination from "@/components/ui/pagination/Pagination.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const breadcrumbs = [
    {
        title: "Players",
        href: "/players",
    },
];

const props = defineProps(["players"]);

const imageFile = (fpl_id, name) => {
    const sanitizedName = name.toLowerCase().replace(/[^a-z0-9]/g, "_");
    return `/images/players/${fpl_id}_${sanitizedName}.png`;
};

const fallbackImage = ref("/images/players/profileplaceholder.png");

const onImageError = (event) => {
    event.target.src = fallbackImage.value; // Switch to placeholder on error
};
</script>

<template>

    <Head title="Players" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-wrap justify-center p-8 gap-3">
            <div v-for="player in players.data" :key="player.id">
            <Link :href="route('player.show', player.id)">
                <div
                    class="flex justify-start items-center gap-3 border rounded shadow-md w-55 h-20 bg-primary-foreground border-gray-900 hover:bg-primary-foreground/70">
                    <img :src="imageFile(player.fpl_id, player.name)" :alt="`${name} profile`" @error="onImageError"
                        class="h-10 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1 ml-5" />
                    <div>
                        <h3 class="text-md text-wrap font-bold">{{ player.name }}</h3>
                        <h3 class="text-sm text-wrap">{{ player.team }}</h3>
                    </div>
                </div>
            </Link>
            </div>
        </div>

        <Pagination :meta="players.meta" :links="players.links" />
    </AppLayout>
</template>
