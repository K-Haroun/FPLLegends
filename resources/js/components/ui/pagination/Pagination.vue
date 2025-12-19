<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps(['meta', 'links']);

onMounted(() => {
    console.log('links:', props.meta.links.slice(1, props.meta.links.length - 1))
}
);

</script>

<template>
    <div class="flex items-center justify-between border-t border-white/10 px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <Link :href="links.prev ? links.prev : '/'"
                class="relative rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-200 hover:bg-white/10"
                :class="!links.prev ? 'hidden' : 'mr-auto'">
            Previous
            </Link>
            <Link :href="links.next ? links.next : '/'"
                class="relative ml-3 rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-200 hover:bg-white/10"
                :class="!links.next ? 'hidden' : 'ml-auto'">
            Next
            </Link>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-300">
                    Showing
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.from }}</span>
                    {{ ' ' }}
                    to
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.to }}</span>
                    {{ ' ' }}
                    of
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.total }}</span>
                    {{ ' ' }}
                    results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md text-sm" aria-label="Pagination">
                    <!-- Previous -->
                    <Link v-if="meta.links[0].url" :href="meta.links[0].url"
                        class="relative inline-flex items-center rounded-l-md px-3 py-2" :class="{
                            'z-10 text-primary-foreground bg-primary-foreground': meta.links[0].active,
                            'text-primary-foreground bg-primary hover:bg-primary-foreground hover:text-primary': !meta.links[0].active
                        }" preserve-scroll>&laquo;</Link>

                    <!-- Middle Page Links -->
                    <div v-if="meta?.links && Array.isArray(meta.links)">
                        <template v-for="(link, index) in meta.links.slice(1, meta.links.length - 1)" :key="index">
                            <Link v-if="link.url" :href="link.url" v-html="link.label"
                                class="relative inline-flex items-center px-3 py-2" :class="{
                                    'z-10 text-primary bg-primary-foreground': link.active,
                                    'text-primary-foreground bg-primary hover:bg-primary-foreground hover:text-primary': !link.active
                                }" preserve-scroll />
                            <span v-else v-html="link.label"
                                class="relative inline-flex items-center px-3 py-2 text-gray-500" />
                        </template>
                    </div>

                    <!-- Next -->
                    <Link v-if="meta.links[meta.links.length - 1].url" :href="meta.links[meta.links.length - 1].url"
                        class="relative inline-flex items-center rounded-r-md px-3 py-2" :class="{
                            'z-10 text-primary-foreground bg-primary-foreground': meta.links[meta.links.length - 1].active,
                            'text-primary-foreground bg-primary hover:bg-primary-foreground hover:text-primary': !meta.links[meta.links.length - 1].active
                        }" preserve-scroll>&raquo;</Link>
                </nav>
            </div>
        </div>
    </div>
</template>