<script setup>
import { computed, ref } from 'vue';
import { threeToTwoLetter, countryNames } from '@/utils/countryCodes';
import CountryFlag from 'vue-country-flag-next';

const props = defineProps(['player']);
const country = threeToTwoLetter[props.player.nationality];

const imageFile = (fpl_id, name) => {
    const sanitizedName = name.toLowerCase().replace(/[^a-z0-9]/g, "_");
    return `/images/players/${fpl_id}_${sanitizedName}.png`;
};

const fallbackImage = ref("/images/players/profileplaceholder.png");

const onImageError = (event) => {
    event.target.src = fallbackImage.value; // Switch to placeholder on error
};

const safeName = (team_name) => team_name.replace(/\s+/g, '_');
const teamImage = `/images/teams/${props.player.team_id}_${safeName(props.player.team)}.png`;

const positions = {
    1: 'Goalkeeper',
    2: 'Defender',
    3: 'Midfielder',
    4: 'Forward',
};
</script>

<template>
    <div class="flex gap-5 w-full pb-6 border-b-1 border-gray-200">
        <img :src="imageFile(player.fpl_id, player.name)" :alt="`${name} profile`" @error="onImageError"
            class="h-20 sm:h-50 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1" />

        <div class="flex flex-col justify-start gap-5">
            <div class="flex gap-2 text-2xl sm:text-4xl">
                <h2 class="font-bold">{{ player.first_name }}</h2>
                <h2 class="font-bold">{{ player.second_name }}</h2>
            </div>

            <div class="flex justify-start items-center gap-2 text-lg sm:text-2xl font-bold">
                <span class="flex justify-start items-center gap-1">
                    <img :src="teamImage" alt="Player Team" class="size-5 sm:size-8">
                    {{ player.team }}
                </span>
            </div>
        </div>
    </div>

    <div class="flex justify-start items-start gap-5 text-xs w-full px-3">
        <div class="h-full flex flex-col justify-between items-center">
            <p class="text-white/40">Squad number</p>
            <h3>{{ player.squad_number }}</h3>
        </div>

        <div class="h-full flex flex-col justify-between items-center">
            <p class="text-white/40">Position</p>
            <h3>{{ positions[player.position] }}</h3>
        </div>

        <div class="h-full flex flex-col justify-between items-center">
            <p class="text-white/40">Date of birth</p>
            <p>{{ player.birth_date }}</p>
        </div>
        
        <div class="h-full flex flex-col justify-between items-center">
            <p class="text-white/40">Nationality</p>
            <country-flag :country='country' size='normal' />
        </div>

    </div>
</template>