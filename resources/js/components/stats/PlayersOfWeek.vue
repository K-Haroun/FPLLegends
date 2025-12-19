<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { imageFile, teamImage } from '@/utils/helpers';

const props = defineProps(['topPlayer']);

const positions = {
    1: 'Goalkeeper',
    2: 'Defender',
    3: 'Midfielder',
    4: 'Forward',
};

const fallbackImage = ref("/images/players/profileplaceholder.png");

const onImageError = (event) => {
    event.target.src = fallbackImage.value; // Switch to placeholder on error
};

</script>

<template>
    <Link :href="route('player.show', topPlayer.id)">
    <div
        class="flex flex-col justify-center items-center rounded-md sm:pb-8 pb-4 py-3 sm:pt-3 sm:w-70 w-40 border-1 border-gray-200 dark:border-gray-900 dark:bg-primary-foreground shadow-md dark:hover:bg-primary-foreground/60 cursor-pointer">

        <h3 class="text-md mb-3 sm:mb-3 sm:text-xl text-wrap font-bold"> {{ positions[topPlayer.position] }}</h3>


        <div class="flex justify-center items-center gap-3 sm:gap-5">
            <img :src="imageFile(topPlayer.fpl_id, topPlayer.name)" :alt="`${name} profile`"
                class="h-13 sm:h-25 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1">
            <div>
                <p class="text-md sm:text-2xl font-bold truncate">{{ topPlayer.name }}</p>
                <p class="text-xs sm:text-lg">{{ topPlayer.total_points }} Points</p>
                <p class="text-xs sm:text-lg">{{ topPlayer.team }}</p>
            </div>
        </div>

    </div>
    </Link>
</template>