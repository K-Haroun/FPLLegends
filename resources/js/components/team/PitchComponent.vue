<script setup>
import { ref } from "vue";

defineProps(["user_team"]);

const imageFile = (fpl_id, name) => {
    const sanitizedName = name.toLowerCase().replace(/[^a-z0-9]/g, "_");
    const capitalisedName = sanitizedName.charAt(0).toUpperCase() + sanitizedName.slice(1);
    return `/images/players/${fpl_id}_${capitalisedName}.png`;
};
const fallbackImage = ref("/images/players/profileplaceholder.png");
const onImageError = (event) => {
    event.target.src = fallbackImage.value;
};
</script>
<template>
    <div class="relative w-full max-w-4xl aspect-[2/3] md:aspect-[3/4]">
        <!-- Pitch Background -->
        <div class="absolute inset-0 rounded-lg overflow-hidden shadow-[0_0_20px_rgba(0,255,0,0.3)]">
            <img src="/images/football-pitch-95x68.svg" alt="Football Pitch" class="w-full h-full object-cover" />
        </div>

        <!-- No Team Overlay -->
        <div v-if="!user_team" class="absolute inset-0 bg-black/70 flex items-center justify-center rounded-lg">
            <h3 class="text-lg md:text-xl text-white text-center px-4">
                You have no players in your squad
            </h3>
        </div>

        <!-- Players on Pitch -->
        <div v-else class="absolute inset-0 flex flex-col justify-evenly py-8 md:py-12">
            <!-- Forwards -->
            <div class="flex justify-center gap-4 md:gap-8 px-4">
                <div v-for="f in user_team.players['forwards']" :key="f.id" class="flex flex-col items-center gap-1">
                    <img :src="imageFile(f.fpl_id, f.web_name)" :alt="`${f.web_name} profile`" @error="onImageError"
                        class="w-12 h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 border-2 rounded-lg border-white bg-white object-cover shadow-lg" />
                    <h3
                        class="text-xs md:text-sm font-semibold text-white text-center max-w-[60px] md:max-w-[80px] truncate bg-black/50 px-2 py-1 rounded">
                        {{ f.web_name }}
                    </h3>
                </div>
            </div>

            <!-- Midfielders -->
            <div class="flex justify-center gap-4 md:gap-8 px-4">
                <div v-for="m in user_team.players['midfielders']" :key="m.id" class="flex flex-col items-center gap-1">
                    <img :src="imageFile(m.fpl_id, m.web_name)" :alt="`${m.web_name} profile`" @error="onImageError"
                        class="w-12 h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 border-2 rounded-lg border-white bg-white object-cover shadow-lg" />
                    <h3
                        class="text-xs md:text-sm font-semibold text-white text-center max-w-[60px] md:max-w-[80px] truncate bg-black/50 px-2 py-1 rounded">
                        {{ m.web_name }}
                    </h3>
                </div>
            </div>

            <!-- Defenders -->
            <div class="flex justify-center gap-4 md:gap-8 px-4">
                <div v-for="d in user_team.players['defenders']" :key="d.id" class="flex flex-col items-center gap-1">
                    <img :src="imageFile(d.fpl_id, d.web_name)" :alt="`${d.web_name} profile`" @error="onImageError"
                        class="w-12 h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 border-2 rounded-lg border-white bg-white object-cover shadow-lg" />
                    <h3
                        class="text-xs md:text-sm font-semibold text-white text-center max-w-[60px] md:max-w-[80px] truncate bg-black/50 px-2 py-1 rounded">
                        {{ d.web_name }}
                    </h3>
                </div>
            </div>

            <!-- Goalkeeper -->
            <div class="flex justify-center px-4">
                <div v-for="g in user_team.players['goalkeeper']" :key="g.id" class="flex flex-col items-center gap-1">
                    <img :src="imageFile(g.fpl_id, g.web_name)" :alt="`${g.web_name} profile`" @error="onImageError"
                        class="w-12 h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 border-2 rounded-lg border-white bg-white object-cover shadow-lg" />
                    <h3
                        class="text-xs md:text-sm font-semibold text-white text-center max-w-[60px] md:max-w-[80px] truncate bg-black/50 px-2 py-1 rounded">
                        {{ g.web_name }}
                    </h3>
                </div>
            </div>
        </div>
    </div>
</template>