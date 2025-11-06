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
    <div class="flex gap-5">
        <img :src="imageFile(player.fpl_id, player.name)" :alt="`${name} profile`" @error="onImageError"
            class="h-50 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1" />

        <div class="flex flex-col justify-between gap-3">
            <div class="flex gap-2 text-4xl">
                <h2 class="font-bold">{{ player.first_name }}</h2>
                <h2 class="font-bold">{{ player.second_name }}</h2>
            </div>

            <div class="flex justify-start items-center gap-3 text-2xl font-bold">
                <span class="flex justify-start items-center gap-2">
                    <img :src="teamImage" alt="Player Team" class="size-8">
                    {{ player.team }}
                </span>
                <span>•</span>
                <h3 class="border-1 border-gray-100/15 rounded-full p-2">{{ player.squad_number }}</h3>
                <h3>{{ positions[player.position] }}</h3>
            </div>


            <div class="flex justify-start items-start gap-5">
                <div class="flex flex-col justify-center items-center">
                    <p class="text-white/40">Nationality</p>
                    <country-flag :country='country' size='normal' />
                </div>

                <div class="h-full flex flex-col justify-between items-center">
                    <p class="text-white/40">Date of birth</p>
                    <p class="text-xs">{{ player.birth_date }}</p>
                </div>
            </div>
        </div>
    </div>
</template>