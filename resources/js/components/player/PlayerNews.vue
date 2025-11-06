<script setup>
defineProps(['player']);
</script>

<template>
    <div class="border-1 border-gray-50/40 rounded p-5 h-fit bg-gray-700/20">
        <h3 class="text-md font-bold mb-3">NEWS</h3>
        <p v-if="player.news" class="text-sm">{{ player.news }}</p>
        <p v-else class="text-gray-400"> No news on this player</p>
    </div>
</template>