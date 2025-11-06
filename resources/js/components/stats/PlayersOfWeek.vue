<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps(['topPlayer']);

const positions = {
    1: 'Goalkeeper',
    2: 'Defender',
    3: 'Midfielder',
    4: 'Forward',
};

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
    <Link :href="route('player.show', topPlayer.id)">
    <div
        class="flex flex-col justify-center items-center rounded-md pb-10 mb-10 w-62 border-1 border-gray-200 dark:border-gray-900 dark:bg-primary-foreground shadow-md dark:hover:bg-primary-foreground/60 cursor-pointer">
        <h3 class="text-xl my-3"> {{ positions[topPlayer.position] }} of the week </h3>


        <div class="flex justify-center items-center gap-5">
            <img :src="imageFile(topPlayer.fpl_id, topPlayer.name)" :alt="`${name} profile`"
                class="h-20 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1">
            <div>
                <p class="text-lg font-bold">{{ topPlayer.name }}</p>
                <p class="text-md">{{ topPlayer.total_points }} Points</p>
                <p class="text-sm">{{ topPlayer.team }}</p>
            </div>
        </div>
    </div>
    </Link>
</template>