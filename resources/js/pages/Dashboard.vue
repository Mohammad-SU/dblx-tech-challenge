<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import Card from '@/components/Card.vue';
import { ref, watch } from 'vue';
import { FilterX } from 'lucide-vue-next';

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
        type?: string;
    };
}>();

const search = ref(props.filters?.search || '');
const selectedType = ref(props.filters?.type || '');

const types = [
    { value: '', label: 'All Types' },
    { value: 'download', label: 'Downloads' },
    { value: 'info', label: 'Infos' },
    { value: 'WEBLINK', label: 'Weblinks' },
];

// Debounce search and type filter to prevent too many requests
let timeout: NodeJS.Timeout;
watch([search, selectedType], ([newSearch, newType]) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get('/dashboard', 
            { 
                search: newSearch,
                type: newType
            }, 
            {
                preserveState: true,
                preserveScroll: true,
                replace: true
            }
        );
    }, 300);
});

const deleteItem = async (item: any) => {
    if (confirm('Are you sure you want to delete this item?')) {
        router.delete(`/items/${item.id}`, {
            data: {
                search: search.value,
                type: selectedType.value
            }
        });
    }
};

const clearFilters = () => {
    search.value = '';
    selectedType.value = '';
    router.get('/dashboard', 
        { 
            search: '',
            type: ''
        }, 
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    );
};

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="p-6 bg-white rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h3 class="text-2xl mb-4 text-black">Search</h3>
                <div class="flex gap-4">
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Search by name..."
                            class="w-full px-4 py-2 border text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <select
                        v-model="selectedType"
                        class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 text-black focus:ring-blue-500 bg-white hover:bg-gray-100 cursor-pointer"
                    >
                        <option 
                            v-for="type in types" 
                            :key="type.value" 
                            :value="type.value"
                        >
                            {{ type.label }}
                        </option>
                    </select>
                    <button
                        @click="clearFilters"
                        class="px-4 py-2 border text-black rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-2 cursor-pointer"
                    >
                        <FilterX class="h-5 w-5" />
                        Clear
                    </button>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h3 class="text-2xl text-black">Latest Items</h3>
                
                <div v-if="items.length === 0" class="mt-4 text-center text-gray-500">
                    No items found
                </div>

                <div v-else class="grid gap-4 grid-cols-6 mt-4">
                    <Card 
                        v-for="item in items" 
                        :key="item.id" 
                        :item="item"
                        @delete="deleteItem"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>