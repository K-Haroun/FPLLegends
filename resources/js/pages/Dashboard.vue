<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { computed, ref } from 'vue';
import { UserRoundPlus, X } from 'lucide-vue-next';

const breadcrumbs = [
    {
        title: 'My Team',
        href: dashboard().url,
    },
];

const props = defineProps(['all_players']);

const search = ref('');
const muted = true;
const userTeam = null;
const addingPlayerPositionID = ref('');
const positions = {
    1: 'Goalkeeper',
    2: 'Defender',
    3: 'Midfielder',
    4: 'Forward',
};

const addedPlayers = ref([]);
const addedGk = ref([]);
const addedDef = ref([]);
const addedMid = ref([]);
const addedFor = ref([]);

const filteredPlayers = computed(() => {
    const list = props.all_players.data;

    return list.filter(player => {
        const matchesSearch =
            !search.value ||
            (player.first_name ?? '').toLowerCase().includes(search.value.toLowerCase()) || (player.second_name ?? '').toLowerCase().includes(search.value.toLowerCase());

        const matchesPosition = player.position == positions[addingPlayerPositionID.value];

        return matchesSearch && matchesPosition;
    });
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

const addingPlayer = (id) => {
    
    addingPlayerPositionID.value = id;
}

const addPlayer = (player) => {

    if (addingPlayerPositionID.value == 1) {
        if (addedGk.value.length < 1) {
            addedGk.value.push(player);
            addedPlayers.value.push(player);
        } else {
            alert('Cannot add more than 1 Goalkeeper');
        }
    }
    if (addingPlayerPositionID.value == 2) {
        if (addedDef.value.length < 4) {
            addedDef.value.push(player);
            addedPlayers.value.push(player);
        } else {
            alert('Cannot add more than 4 Defenders');
        }
    }
    if (addingPlayerPositionID.value == 3) {
        if (addedMid.value.length < 4) {
            addedMid.value.push(player);
            addedPlayers.value.push(player);
        } else {
            alert('Cannot add more than 4 Midfielders');
        }
    }
    if (addingPlayerPositionID.value == 4) {
        if (addedFor.value.length < 4) {
            addedFor.value.push(player);
            addedPlayers.value.push(player);
        } else {
            alert('Cannot add more than 4 Forwards');
        }
    }
}

const removePlayer = (player) => {
    if (player.position == 'Goalkeeper') {
        addedGk.value = [];
    }
    if (player.position == 'Defender') {
        addedDef.value = addedDef.value.filter(p => p !== player);
    }
    if (player.position == 'Midfielder') {
        addedMid.value = addedMid.value.filter(p => p !== player);
    }
    if (player.position == 'Forward') {
        addedFor.value = addedFor.value.filter(p => p !== player);
    }
}

const saveTeam = () => {
    addedPlayers.value.length = 0;
}

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col overflow-x-auto rounded-xl p-4">
            <div class="auto-rows-min">
                <div class="flex flex-col justify-center items-center min-h-screen">
                    <div v-if="userTeam == null" class="flex-6 grid content-center gap-5">
                        <button class="btn bg-blue-800 hover:bg-blue-800/80 rounded-lg"
                            onclick="my_modal_1.showModal()">Create Team</button>
                        <dialog id="my_modal_1" class="modal">
                            <div class="modal-box">
                                <h3 class="text-lg font-bold">Create a team</h3>
                                <form action="">
                                    <div class="flex flex-col gap-4">
                                        <fieldset class="fieldset">
                                            <legend class="fieldset-legend">What do you want to call your team?</legend>
                                            <input type="text" class="input bg-blue-400/5" placeholder="Team name..." />
                                        </fieldset>
                                        <div class="flex flex-col gap-3">
                                            <hr class="border-blue-400/40">
                                            <div class="grid grid-cols-4 justify-between text-center">

                                                <div class="flex flex-col gap-2">
                                                    <h3 class="text-base">GK</h3>
                                                    <div>
                                                        <div
                                                            class="grid grid-flow-row grid-rows-4 gap-3 content-center">
                                                            <div class="w-30 flex justify-center items-start"
                                                                v-for="p in addedGk">
                                                                <div
                                                                    class="flex flex-col justify-center items-center gap-1 pl-6">
                                                                    <img :src="imageFile(p.fpl_id, p.name)"
                                                                        :alt="`${p.name} profile`" @error="onImageError"
                                                                        class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                                                    <div>

                                                                    </div>
                                                                    <h3 class="truncate text-center text-xs w-15">
                                                                        {{
                                                                            p.name }}</h3>
                                                                </div>
                                                                <div @click="removePlayer(p)" class="cursor-pointer">
                                                                    <X />
                                                                </div>
                                                            </div>
                                                            <div v-if="addedGk.length < 1" @click="addingPlayer(1)"
                                                                onclick="my_modal_2.showModal()"
                                                                class="flex justify-center">
                                                                <UserRoundPlus class="cursor-pointer" />
                                                            </div>
                                                            <dialog id="my_modal_2" class="modal">
                                                                <div class="modal-box">
                                                                    <div>
                                                                        <h3 class="text-lg font-bold">Pick a player</h3>
                                                                        <div
                                                                            class="modal-action flex flex-col justify-start content-start">
                                                                            <!-- Search -->
                                                                            <input v-model="search" type="text"
                                                                                placeholder="Search players..."
                                                                                class="border border-gray-400 px-4 py-2 rounded w-full" />

                                                                            <div
                                                                                class="flex-1 sm:flex-2 lg:flex-3 flex items-center gap-2">

                                                                            </div>
                                                                            <div
                                                                                class="flex justify-start items-start gap-3">
                                                                                <div v-for="p in addedPlayers"
                                                                                    class="w-20 cursor-pointer flex flex-col justify-center items-center gap-1">
                                                                                    <img :src="imageFile(p.fpl_id, p.name)"
                                                                                        :alt="`${p.name} profile`"
                                                                                        @error="onImageError"
                                                                                        class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                                                                    <h3
                                                                                        class="truncate text-center text-xs">
                                                                                        {{
                                                                                            p.name }}</h3>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="flex flex-wrap overflow-auto gap-3 justify-center items-start bg-gray-900/30 py-5 mb-3 rounded-lg h-100">
                                                                                <div v-for="player in filteredPlayers"
                                                                                    @click="addPlayer(player)"
                                                                                    class="w-20 cursor-pointer flex flex-col justify-center items-center gap-1">
                                                                                    <img :src="imageFile(player.fpl_id, player.name)"
                                                                                        :alt="`${player.name} profile`"
                                                                                        @error="onImageError"
                                                                                        class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                                                                    <h3
                                                                                        class="truncate text-center text-xs">
                                                                                        {{
                                                                                            player.name }}</h3>
                                                                                </div>
                                                                            </div>
                                                                            <form method="dialog"
                                                                                class="flex gap-3 justify-end">
                                                                                <button
                                                                                    class="btn btn-soft">Close</button>
                                                                                <button @click="saveTeam"
                                                                                    class="btn btn-soft btn-success">Save</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </dialog>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col gap-2">
                                                    <h3 class="text-base">DEF</h3>
                                                    <div>
                                                        <div class="grid grid-flow-row grid-rows-4 gap-4">
                                                            <div class="w-30 flex justify-center items-start"
                                                                v-for="p in addedDef">
                                                                <div
                                                                    class="flex flex-col justify-center items-center gap-1 pl-6">
                                                                    <img :src="imageFile(p.fpl_id, p.name)"
                                                                        :alt="`${p.name} profile`" @error="onImageError"
                                                                        class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                                                    <div>

                                                                    </div>
                                                                    <h3 class="truncate text-center text-xs w-15">
                                                                        {{
                                                                            p.name }}</h3>
                                                                </div>
                                                                <div @click="removePlayer(p)" class="cursor-pointer">
                                                                    <X />
                                                                </div>
                                                            </div>
                                                            <div v-if="addedDef.length < 4" @click="addingPlayer(2)"
                                                                onclick="my_modal_2.showModal()"
                                                                class="flex justify-center">
                                                                <UserRoundPlus class="cursor-pointer" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col gap-2">
                                                    <h3 class="text-base">MID</h3>
                                                    <div>
                                                        <div class="grid grid-flow-row grid-rows-4 gap-4">
                                                            <div class="w-30 flex justify-center items-start"
                                                                v-for="p in addedMid">
                                                                <div
                                                                    class="flex flex-col justify-center items-center gap-1 pl-6">
                                                                    <img :src="imageFile(p.fpl_id, p.name)"
                                                                        :alt="`${p.name} profile`" @error="onImageError"
                                                                        class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                                                    <div>

                                                                    </div>
                                                                    <h3 class="truncate text-center text-xs w-15">
                                                                        {{
                                                                            p.name }}</h3>
                                                                </div>
                                                                <div @click="removePlayer(p)" class="cursor-pointer">
                                                                    <X />
                                                                </div>
                                                            </div>
                                                            <div v-if="addedMid.length < 4" @click="addingPlayer(3)"
                                                                onclick="my_modal_2.showModal()"
                                                                class="flex justify-center">
                                                                <UserRoundPlus class="cursor-pointer" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col gap-2">
                                                    <h3 class="text-base">FOR</h3>
                                                    <div>
                                                        <div class="grid grid-flow-row grid-rows-4 gap-4">
                                                            <div class="w-30 flex justify-center items-start"
                                                                v-for="p in addedFor">
                                                                <div
                                                                    class="flex flex-col justify-center items-center gap-1 pl-6">
                                                                    <img :src="imageFile(p.fpl_id, p.name)"
                                                                        :alt="`${p.name} profile`" @error="onImageError"
                                                                        class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                                                    <div>

                                                                    </div>
                                                                    <h3 class="truncate text-center text-xs w-15">
                                                                        {{
                                                                            p.name }}</h3>
                                                                </div>
                                                                <div @click="removePlayer(p)" class="cursor-pointer">
                                                                    <X />
                                                                </div>
                                                            </div>
                                                            <div v-if="addedFor.length < 4" @click="addingPlayer(4)"
                                                                onclick="my_modal_2.showModal()"
                                                                class="flex justify-center">
                                                                <UserRoundPlus class="cursor-pointer" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="modal-action">
                                    <form method="dialog" class="flex gap-3 justify-end">
                                        <button class="btn btn-soft">Close</button>
                                        <button class="btn btn-soft btn-success">Create Team</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                        <h2>You have no team created</h2>
                    </div>
                    <div class="flex-6 w-full px-30">
                        <div class="flex justify-center items-center rotate-90">
                            <img src="/images/football-pitch-95x68.svg" alt="Football Pitch" class="w-full">
                            <div v-if="muted" class="absolute w-full h-full bg-black/70 grid content-center gap-5">
                                <h3 class="text-center -rotate-90">You have no players in your squad</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
