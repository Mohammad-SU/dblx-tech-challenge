<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import Card from '@/components/Card.vue';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps<{
    name?: string;
    items: Array;
    filters?: {
        search: string;
    };
}>();

const search = ref(props.filters?.search || '');

// Debounce search to prevent too many requests
let timeout: NodeJS.Timeout;
watch(search, (value) => {
    timeout = setTimeout(() => {
        router.get('/dashboard', { search: value }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, 300);
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="p-6 bg-white rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h3 class="text-2xl mb-4">Search</h3>
                <input
                    type="text"
                    v-model="search"
                    placeholder="Search by name..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="p-6 bg-white rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h3 class="text-2xl">Latest Items</h3>
                
                <div v-if="items.length === 0" class="mt-4 text-center text-gray-500">
                    No items found
                </div>

                <div v-else class="grid gap-4 grid-cols-6 mt-4">
                    <Card v-for="item in items" :key="item.id" :item="item" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>