<script setup lang="ts">
import { BookOpen, Download, ExternalLink, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    item: object
}>();

const isHovered = ref(false);

const iconMap = {
    "book-open": BookOpen,
    "download": Download,
    "external-link": ExternalLink,
};
</script>

<template>
    <div 
        class="relative w-full group" 
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
    >
        <p class="absolute inset-x-0 bottom-0 bg-black text-white p-4">{{ item.name }}</p>
        <div class="bg-black p-4 absolute left-0 top-0">
            <component :is="iconMap[item.type_icon]" class="h-5 w-5 text-white"/>
        </div>
        
        <!-- Delete button - emits event instead of handling delete -->
        <button 
            v-show="isHovered"
            @click="$emit('delete', item)"
            class="absolute right-2 top-2 p-2 bg-red-500 hover:bg-red-600 rounded-full transition-colors duration-200 group-hover:opacity-100 opacity-0"
            title="Delete item"
        >
            <Trash2 class="h-5 w-5 text-white" />
        </button>

        <img class="w-full" :src="item.image_url" :alt="item.name" />
    </div>
</template>