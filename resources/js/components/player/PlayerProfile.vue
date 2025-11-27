<script setup>
import { computed, ref } from 'vue';
import { threeToTwoLetter, countryNames } from '@/utils/countryCodes';
import CountryFlag from 'vue-country-flag-next';

const props = defineProps(['player']);
const country = threeToTwoLetter[props.player.nationality];

const imageFile = (fpl_id, name) => {
    const sanitizedName = name
        .toLowerCase()
        .replace(/[^a-z0-9]/g, "_");
    const capitalisedName = sanitizedName.charAt(0).toUpperCase() + sanitizedName.slice(1);
    return `/images/players/${fpl_id}_${capitalisedName}.png`;
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
    <div class="bg-[#2A2F3A] w-full rounded-md shadow-2xl p-3">
        <div class="flex items-center gap-5 w-full border-gray-200">
            <img :src="imageFile(player.fpl_id, player.name)" :alt="`${name} profile`" @error="onImageError"
                class="h-20 sm:h-50 border rounded-md border-gray-100 bg-gray-100 px-1 pt-1" />

            <div class="flex flex-col justify-start gap-2">
                <div class="flex gap-2 text-2xl sm:text-4xl">
                    <h2 class="font-bold">{{ player.first_name }}</h2>
                    <h2 class="font-bold">{{ player.second_name }}</h2>
                </div>

                <div class="flex justify-start items-center gap-2 text-lg sm:text-2xl">
                    <span class="flex justify-start items-center gap-1">
                        <img :src="teamImage" alt="Player Team" class="size-5 sm:size-8">
                        {{ player.team }}
                    </span>

                    <span class="text-xl font-extralight">|</span>

                    <div class="h-full flex flex-col justify-between items-center">
                        <h3>{{ player.squad_number }}</h3>
                    </div>

                    <span class="text-xl font-extralight">|</span>

                    <div class="h-full flex flex-col justify-between items-center">
                        <h3>{{ positions[player.position] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-start items-start gap-6 text-xs md:text-base w-full px-3">
        <div class="flex flex-col items-center">
            <p class="text-gray-500">NAT</p>
            <country-flag :country='country' size='normal' />
        </div>
        <div class="flex flex-col items-center gap-3">
            <p class="text-gray-500">DOB</p>
            <p>{{ player.birth_date }}</p>
        </div>
        <div class="flex flex-col items-center gap-3">
            <p class="text-gray-500">FPL Cost</p>
            <p>£ {{ player.now_cost / 10 }}M</p>
        </div>
    </div>
</template>