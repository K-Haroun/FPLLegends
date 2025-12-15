<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { dashboard } from "@/routes";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import CreateTeamModal from "@/components/team/CreateTeamModal.vue";

const breadcrumbs = [
    {
        title: "My Team",
        href: dashboard().url,
    },
];

const props = defineProps(["all_players", "message"]);

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

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col overflow-x-auto rounded-xl p-4">
            <div class="auto-rows-min">
                <div class="flex flex-col justify-center items-center min-h-screen pt-10">
                    <div v-if="user_team">
                        <h2 class="text-4xl capitalize">
                            {{ user_team.team.name }}
                        </h2>
                    </div>

                    <div v-else class="flex-6 grid content-center gap-5">
                        <button class="btn bg-blue-800 hover:bg-blue-800/80 rounded-lg"
                            onclick="create_team_modal.showModal()">
                            Create Team
                        </button>
                        
                        <CreateTeamModal id="create_team_modal" :all_players="all_players"/>

                        <h2>You have no team created</h2>
                    </div>
                    <div class="flex-6 w-full px-30">
                        <div class="flex justify-center items-center rotate-90">
                            <img src="/images/football-pitch-95x68.svg" alt="Football Pitch"
                                class="w-full rounded-lg shadow-[0_0_15px_rgba(0,255,0,1)]" />
                            <div v-if="!user_team" class="absolute w-full h-full bg-black/70 grid content-center gap-5">
                                <h3 class="text-center -rotate-90">You have no players in your squad</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
