<script setup>
import { computed, ref } from 'vue';
import { UserRoundPlus, X } from "lucide-vue-next";
import { Link, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const toast = useToast();
const props = defineProps(["all_players"]);

const addedPlayers = ref([]);
const addedGk = ref([]);
const addedDef = ref([]);
const addedMid = ref([]);
const addedFor = ref([]);

// Temporary selections while modal is open
const tempAddedPlayers = ref([]);
const tempAddedGk = ref([]);
const tempAddedDef = ref([]);
const tempAddedMid = ref([]);
const tempAddedFor = ref([]);

// Initialize Inertia form
const form = useForm({
    name: '',
    players: {
        Goalkeeper: [],
        Defenders: [],
        Midfielders: [],
        Forwards: [],
    },
});

const positions = {
    1: 'Goalkeeper',
    2: 'Defender',
    3: 'Midfielder',
    4: 'Forward',
};

const search = ref("");
const addingPlayerPositionID = ref("");

const filteredPlayers = computed(() => {
    const list = props.all_players.data;

    return list.filter((player) => {
        const matchesSearch =
            !search.value ||
            (player.first_name ?? "").toLowerCase().includes(search.value.toLowerCase()) ||
            (player.second_name ?? "").toLowerCase().includes(search.value.toLowerCase());

        const matchesPosition = player.position == positions[addingPlayerPositionID.value];

        return matchesSearch && matchesPosition;
    });
});

const imageFile = (fpl_id, name) => {
    const sanitizedName = name.toLowerCase().replace(/[^a-z0-9]/g, "_");
    const capitalisedName = sanitizedName.charAt(0).toUpperCase() + sanitizedName.slice(1);
    return `/images/players/${fpl_id}_${capitalisedName}.png`;
};

const fallbackImage = ref("/images/players/profileplaceholder.png");

const onImageError = (event) => {
    event.target.src = fallbackImage.value;
};

const addingPlayer = (id) => {
    addingPlayerPositionID.value = id;
    // Initialize temp arrays with current selections for THIS position only
    tempAddedGk.value = [...addedGk.value];
    tempAddedDef.value = [...addedDef.value];
    tempAddedMid.value = [...addedMid.value];
    tempAddedFor.value = [...addedFor.value];
    // Clear the preview - only show new selections in this session
    tempAddedPlayers.value = [];
};

const addPlayer = (player) => {
    // Check if player is already added to the temp team
    const isAlreadyAdded = tempAddedPlayers.value.some(p => p.id === player.id);

    if (isAlreadyAdded) {
        return;
    }

    if (addingPlayerPositionID.value == 1) {
        if (tempAddedGk.value.length < 1) {
            tempAddedGk.value.push(player);
            tempAddedPlayers.value.push(player);
        }
    }
    if (addingPlayerPositionID.value == 2) {
        if (tempAddedDef.value.length < 4) {
            tempAddedDef.value.push(player);
            tempAddedPlayers.value.push(player);
        }
    }
    if (addingPlayerPositionID.value == 3) {
        if (tempAddedMid.value.length < 4) {
            tempAddedMid.value.push(player);
            tempAddedPlayers.value.push(player);
        }
    }
    if (addingPlayerPositionID.value == 4) {
        if (tempAddedFor.value.length < 4) {
            tempAddedFor.value.push(player);
            tempAddedPlayers.value.push(player);
        }
    }
};

const removePlayer = (player) => {
    if (player.position == "Goalkeeper") {
        tempAddedGk.value = [];
    }
    if (player.position == "Defender") {
        tempAddedDef.value = tempAddedDef.value.filter((p) => p !== player);
    }
    if (player.position == "Midfielder") {
        tempAddedMid.value = tempAddedMid.value.filter((p) => p !== player);
    }
    if (player.position == "Forward") {
        tempAddedFor.value = tempAddedFor.value.filter((p) => p !== player);
    }
    tempAddedPlayers.value = tempAddedPlayers.value.filter((p) => p !== player);
};

const saveTeam = () => {
    // Commit temp selections to actual arrays
    addedGk.value = [...tempAddedGk.value];
    addedDef.value = [...tempAddedDef.value];
    addedMid.value = [...tempAddedMid.value];
    addedFor.value = [...tempAddedFor.value];

    // Rebuild the main addedPlayers array from all positions
    addedPlayers.value = [
        ...tempAddedGk.value,
        ...tempAddedDef.value,
        ...tempAddedMid.value,
        ...tempAddedFor.value
    ];

    // Clear temp preview for next time
    tempAddedPlayers.value = [];

    document.getElementById('my_modal_2').close();
};

const cancelPlayerSelection = () => {
    // Discard changes and clear temp arrays
    tempAddedPlayers.value = [];
    tempAddedGk.value = [];
    tempAddedDef.value = [];
    tempAddedMid.value = [];
    tempAddedFor.value = [];
};

// Validate team name (no special characters)
const validateTeamName = () => {
    const regex = /^[a-zA-Z0-9\s]*$/;
    if (!regex.test(form.name)) {
        form.setError('name', 'Team name cannot contain special characters');
        return false;
    }
    if (form.name.trim() === '') {
        form.setError('name', 'Team name is required');
        return false;
    }
    form.clearErrors('name');
    return true;
};

// Submit form
const submitTeam = () => {
    if (!validateTeamName()) {
        return;
    }

    // Update form data with current players
    form.players.Goalkeeper = addedGk.value;
    form.players.Defenders = addedDef.value;
    form.players.Midfielders = addedMid.value;
    form.players.Forwards = addedFor.value;

    form.post('/userTeam', {
        onSuccess: () => {
            toast.success('Team created successfully!');
            // Reset after successful submission
            addedPlayers.value = [];
            addedGk.value = [];
            addedDef.value = [];
            addedMid.value = [];
            addedFor.value = [];
            form.reset();
            document.getElementById('create_team_modal').close();
        },
        onError: (errors) => {
            toast.error('Failed to create team. Please try again.');
            console.error('Submission errors:', errors);
        }
    });
};
</script>

<template>
    <dialog id="create_team_modal" class="modal">
        <div class="modal-box max-w-4xl">
            <h3 class="text-lg font-bold mb-4">Create a team</h3>

            <div class="flex flex-col gap-4">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        What do you want to call your team?
                    </legend>
                    <input v-model="form.name" type="text" class="input bg-blue-400/5 w-full"
                        :class="{ 'border-red-500': form.errors.name }" placeholder="Team name..." />
                    <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                        {{ form.errors.name }}
                    </p>
                </fieldset>

                <div class="flex flex-col gap-3">
                    <hr class="border-blue-400/40" />
                    <div class="grid grid-cols-4 justify-between text-center gap-2">
                        <!-- Goalkeeper Section -->
                        <div class="flex flex-col gap-2">
                            <h3 class="text-base font-semibold">GK</h3>
                            <div class="flex flex-col gap-3">
                                <div class="flex justify-center items-center" v-for="p in addedGk" :key="p.id">
                                    <div class="flex flex-col justify-center items-center gap-1">
                                        <img :src="imageFile(p.fpl_id, p.name)" :alt="`${p.name} profile`"
                                            @error="onImageError"
                                            class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                        <h3 class="truncate text-center text-xs w-15">
                                            {{ p.name }}
                                        </h3>
                                    </div>
                                    <div @click="removePlayer(p)" class="cursor-pointer hover:text-red-500 ml-1">
                                        <X :size="16" />
                                    </div>
                                </div>
                                <div v-if="addedGk.length < 1" @click="addingPlayer(1)" onclick="my_modal_2.showModal()"
                                    class="flex justify-center">
                                    <UserRoundPlus class="cursor-pointer hover:text-blue-500" />
                                </div>
                            </div>
                        </div>

                        <!-- Defender Section -->
                        <div class="flex flex-col gap-2">
                            <h3 class="text-base font-semibold">DEF</h3>
                            <div class="flex flex-col gap-3">
                                <div class="flex justify-center items-center" v-for="p in addedDef" :key="p.id">
                                    <div class="flex flex-col justify-center items-center gap-1">
                                        <img :src="imageFile(p.fpl_id, p.name)" :alt="`${p.name} profile`"
                                            @error="onImageError"
                                            class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                        <h3 class="truncate text-center text-xs w-15">
                                            {{ p.name }}
                                        </h3>
                                    </div>
                                    <div @click="removePlayer(p)" class="cursor-pointer hover:text-red-500 ml-1">
                                        <X :size="16" />
                                    </div>
                                </div>
                                <div v-if="addedDef.length < 4" @click="addingPlayer(2)"
                                    onclick="my_modal_2.showModal()" class="flex justify-center">
                                    <UserRoundPlus class="cursor-pointer hover:text-blue-500" />
                                </div>
                            </div>
                        </div>

                        <!-- Midfielder Section -->
                        <div class="flex flex-col gap-2">
                            <h3 class="text-base font-semibold">MID</h3>
                            <div class="flex flex-col gap-3">
                                <div class="flex justify-center items-center" v-for="p in addedMid" :key="p.id">
                                    <div class="flex flex-col justify-center items-center gap-1">
                                        <img :src="imageFile(p.fpl_id, p.name)" :alt="`${p.name} profile`"
                                            @error="onImageError"
                                            class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                        <h3 class="truncate text-center text-xs w-15">
                                            {{ p.name }}
                                        </h3>
                                    </div>
                                    <div @click="removePlayer(p)" class="cursor-pointer hover:text-red-500 ml-1">
                                        <X :size="16" />
                                    </div>
                                </div>
                                <div v-if="addedMid.length < 4" @click="addingPlayer(3)"
                                    onclick="my_modal_2.showModal()" class="flex justify-center">
                                    <UserRoundPlus class="cursor-pointer hover:text-blue-500" />
                                </div>
                            </div>
                        </div>

                        <!-- Forward Section -->
                        <div class="flex flex-col gap-2">
                            <h3 class="text-base font-semibold">FOR</h3>
                            <div class="flex flex-col gap-3">
                                <div class="flex justify-center items-center" v-for="p in addedFor" :key="p.id">
                                    <div class="flex flex-col justify-center items-center gap-1">
                                        <img :src="imageFile(p.fpl_id, p.name)" :alt="`${p.name} profile`"
                                            @error="onImageError"
                                            class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                                        <h3 class="truncate text-center text-xs w-15">
                                            {{ p.name }}
                                        </h3>
                                    </div>
                                    <div @click="removePlayer(p)" class="cursor-pointer hover:text-red-500 ml-1">
                                        <X :size="16" />
                                    </div>
                                </div>
                                <div v-if="addedFor.length < 4" @click="addingPlayer(4)"
                                    onclick="my_modal_2.showModal()" class="flex justify-center">
                                    <UserRoundPlus class="cursor-pointer hover:text-blue-500" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-action">
                <form method="dialog" class="flex gap-3 justify-end">
                    <button class="btn btn-soft">Close</button>
                    <button type="button" @click="submitTeam" :disabled="form.processing"
                        class="btn btn-soft btn-success" :class="{ 'opacity-50 cursor-not-allowed': form.processing }">
                        <span v-if="form.processing">Creating...</span>
                        <span v-else>Create Team</span>
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Player Selection Modal -->
    <dialog id="my_modal_2" class="modal">
        <div class="modal-box max-w-3xl">
            <h3 class="text-lg font-bold mb-4">Pick a player</h3>

            <div class="flex flex-col gap-4">
                <!-- Search -->
                <input v-model="search" type="text" placeholder="Search players..."
                    class="border border-gray-400 px-4 py-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500" />

                <!-- Selected Players Preview -->
                <div v-if="tempAddedPlayers.length > 0" class="flex flex-wrap gap-3 p-3 bg-blue-400/10 rounded-lg">
                    <div v-for="p in tempAddedPlayers" :key="p.id" class="flex items-center gap-1">
                        <div class="w-20 flex flex-col justify-center items-center gap-1">
                            <img :src="imageFile(p.fpl_id, p.name)" :alt="`${p.name} profile`" @error="onImageError"
                                class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                            <h3 class="truncate text-center text-xs">
                                {{ p.name }}
                            </h3>
                        </div>
                        <div @click="removePlayer(p)" class="cursor-pointer hover:text-red-500">
                            <X :size="16" />
                        </div>
                    </div>
                </div>

                <!-- Available Players -->
                <div
                    class="flex flex-wrap overflow-auto gap-3 justify-center items-start bg-gray-900/30 py-5 mb-3 rounded-lg max-h-96">
                    <div v-for="player in filteredPlayers" :key="player.id" @click="addPlayer(player)"
                        class="w-20 cursor-pointer flex flex-col justify-center items-center gap-1 hover:bg-gray-700/50 p-2 rounded transition-colors">
                        <img :src="imageFile(player.fpl_id, player.name)" :alt="`${player.name} profile`"
                            @error="onImageError"
                            class="h-7 sm:h-10 border rounded-lg border-gray-100 bg-gray-100 px-1 pt-1" />
                        <h3 class="truncate text-center text-xs">
                            {{ player.name }}
                        </h3>
                    </div>
                </div>

                <form method="dialog" class="flex gap-3 justify-end">
                    <button @click="cancelPlayerSelection" class="btn btn-soft">Close</button>
                    <button type="button" @click="saveTeam" class="btn btn-soft btn-success">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </dialog>
</template>