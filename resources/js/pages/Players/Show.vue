<script setup>
import PlayerNews from '@/components/player/PlayerNews.vue';
import PlayerProfile from '@/components/player/PlayerProfile.vue';
import PlayerProfileStats from '@/components/player/PlayerProfileStats.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const breadcrumbs = [
    {
        title: 'Stats',
        href: '/stats',
    },
];

defineProps(['player']);


</script>

<template>

    <Head title="Player" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="pt-6 grid">
            <div class="px-5 sm:px-15 flex flex-wrap justify-between gap-5 mb-5">
                <PlayerProfile :player="player.data" />
                <PlayerNews :player="player.data" />
            </div>
            <div class="dark:bg-black/30 min-h-screen px-5 sm:px-15 py-3">
                <PlayerProfileStats :stats="player.data.performances"/>
            </div>
            <hr>

        </div>
    </AppLayout>
</template>