<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import FootballField from '@/components/FootballField.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const muted = true;
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col overflow-x-auto rounded-xl p-4">
            <h1 class="text-xl text-red-500 animate-pulse">This page is still under production, the features are being implemented and tested...</h1>
            <h2 class="text-sm mb-6">My Squad</h2>
            <div class="auto-rows-min">
                <div class="flex flex-col justify-center items-center">
                    <div class="flex justify-center items-center">
                        <img src="/images/football-pitch-95x68.svg" alt="Football Pitch" class="w-70">
                        <div v-if="muted" class="absolute w-70 h-95 bg-black/70 grid content-center gap-5">
                            <h3 class="text-center">You have no players in your squad</h3>
                            <button
                                class="text-xs border rounded-lg border-red-600 bg-red-600/40 hover:bg-red-600/60 px-4 py-2 w-1/2 mx-auto">Add
                                Player</button>
                        </div>
                    </div>
                    <div>
                        <div>
                            <div class="flex w-full justify-start items-center gap-6 mt-5">
                                <div class="flex flex-col justify-center items-center gap-3">
                                    <h2>GK</h2>
                                    <div
                                        class="rounded-full overflow-hidden h-10 w-10 bg-white p-1 shadow-[0_0_20px_rgba(60,179,113,1)]">
                                        <img src="/images/players/1_Raya.png" alt="">
                                    </div>
                                </div>
                                <div class="flex flex-col justify-center items-center gap-3">
                                    <h2>DEF</h2>
                                    <div
                                        class="rounded-full overflow-hidden h-10 w-10 bg-white p-1 shadow-[0_0_20px_rgba(60,179,113,1)]">
                                        <img src="/images/players/1_Raya.png" alt="">
                                    </div>
                                </div>
                                <div class="flex flex-col justify-center items-center gap-3">
                                    <h2>MID</h2>
                                    <div
                                        class="rounded-full overflow-hidden h-10 w-10 bg-white p-1 shadow-[0_0_20px_rgba(60,179,113,1)]">
                                        <img src="/images/players/1_Raya.png" alt="">
                                    </div>
                                </div>
                                <div class="flex flex-col justify-center items-center gap-3">
                                    <h2>FOR</h2>
                                    <div
                                        class="rounded-full overflow-hidden h-10 w-10 bg-white p-1 shadow-[0_0_20px_rgba(60,179,113,1)]">
                                        <img src="/images/players/1_Raya.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
