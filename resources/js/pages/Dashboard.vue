<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { dashboard } from "@/routes";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { Pencil, Trash2, Plus, X, Settings } from "lucide-vue-next";
import CreateTeamModal from "@/components/team/CreateTeamModal.vue";
import { useToast } from "vue-toastification";
import PlayersOnPitch from "@/components/team/PlayersOnPitch.vue";

const toast = useToast();

const breadcrumbs = [
    {
        title: "My Team",
        href: dashboard().url,
    },
];

const props = defineProps(["all_players", "user_team"]);

const fabOpen = ref(false);

const toggleFab = () => {
    fabOpen.value = !fabOpen.value;
};

const deleteTeam = () => {
    if (confirm('Are you sure you want to delete this team? This action cannot be undone.')) {
        router.delete(`/userTeam/${props.user_team.team.id}`, {
            onSuccess: () => {
                toast.success('Team deleted successfully');
                fabOpen.value = false;
            },
            onError: () => {
                toast.error('Failed to delete team');
            }
        });
    }
};

const editTeam = () => {
    // You can implement edit functionality later
    toast.info('Edit team feature coming soon!');
    fabOpen.value = false;
};
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col overflow-x-auto rounded-xl p-4 relative">
            <!-- Team Name -->
            <div class="text-center py-6">
                <h2 v-if="user_team && user_team.team" class="text-3xl md:text-4xl font-bold capitalize">
                    {{ user_team.team.name }}
                </h2>
                <div v-else class="space-y-4">
                    <h2 class="text-2xl md:text-3xl font-semibold">You have no team created</h2>
                    <button class="btn bg-blue-800 hover:bg-blue-800/80 rounded-lg px-6 py-3"
                        onclick="create_team_modal.showModal()">
                        Create Team
                    </button>
                    <CreateTeamModal id="create_team_modal" :all_players="all_players" />
                </div>
            </div>

            <!-- Football Pitch -->
            <div class="flex-1 flex items-center justify-center px-4 py-8">
                <div class="relative w-full max-w-4xl aspect-[2/3] md:aspect-[3/4]">
                    <!-- Pitch Background -->
                    <div class="absolute inset-0 rounded-lg overflow-hidden shadow-[0_0_20px_rgba(0,255,0,0.3)]">
                        <img src="/images/football-pitch-95x68.svg" alt="Football Pitch"
                            class="w-full h-full object-cover" />
                    </div>

                    <!-- No Team Overlay -->
                    <div v-if="!user_team"
                        class="absolute inset-0 bg-black/70 flex items-center justify-center rounded-lg">
                        <h3 class="text-lg md:text-xl text-white text-center px-4">
                            You have no players in your squad
                        </h3>
                    </div>

                    <!-- Players on Pitch -->
                    <PlayersOnPitch v-else :user_team="user_team"/>
                </div>
            </div>

            <!-- Floating Action Button Menu (only shows when team exists) -->
            <div v-if="user_team" class="fixed bottom-6 right-6 z-50">
                <!-- Action Buttons (appear when FAB is open) -->
                <div v-show="fabOpen" class="absolute bottom-16 right-0 flex flex-col gap-3 mb-2">
                    <!-- Edit Team -->
                    <button @click="editTeam"
                        class="group flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full px-4 py-3 shadow-lg transition-all duration-200 transform hover:scale-105">
                        <span class="text-sm font-medium whitespace-nowrap">Edit Team</span>
                        <div class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center">
                            <Pencil :size="20" />
                        </div>
                    </button>

                    <!-- Delete Team -->
                    <button @click="deleteTeam"
                        class="group flex items-center gap-3 bg-red-600 hover:bg-red-700 text-white rounded-full px-4 py-3 shadow-lg transition-all duration-200 transform hover:scale-105">
                        <span class="text-sm font-medium whitespace-nowrap">Delete Team</span>
                        <div class="w-10 h-10 bg-red-700 rounded-full flex items-center justify-center">
                            <Trash2 :size="20" />
                        </div>
                    </button>
                </div>

                <!-- Main FAB Button -->
                <button @click="toggleFab"
                    class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200 transform hover:scale-110"
                    :class="{ 'rotate-45': fabOpen }">
                    <Settings v-if="!fabOpen" :size="24" />
                    <X v-else :size="24" />
                </button>
            </div>

            <!-- Player Statistics Section (placeholder for now) -->
            <div v-if="user_team" class="max-w-4xl mx-auto w-full mt-8 px-4 pb-8">
                <h3 class="text-2xl font-bold mb-4">Squad Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div
                        class="bg-gradient-to-br from-blue-600/20 to-blue-800/20 rounded-lg p-4 border border-blue-500/30">
                        <p class="text-sm text-gray-400">Total Players</p>
                        <p class="text-3xl font-bold">
                            {{ (user_team.players.goalkeeper?.length || 0) +
                                (user_team.players.defenders?.length || 0) +
                                (user_team.players.midfielders?.length || 0) +
                                (user_team.players.forwards?.length || 0) }}
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-green-600/20 to-green-800/20 rounded-lg p-4 border border-green-500/30">
                        <p class="text-sm text-gray-400">Squad Strength</p>
                        <p class="text-3xl font-bold">Coming Soon</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-purple-600/20 to-purple-800/20 rounded-lg p-4 border border-purple-500/30">
                        <p class="text-sm text-gray-400">Team Rating</p>
                        <p class="text-3xl font-bold">Coming Soon</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>