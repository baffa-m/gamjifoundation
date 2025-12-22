<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/UI/Table.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Plus, Edit, Trash2, Image as ImageIcon, Eye } from 'lucide-vue-next';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    slides: Object
});

const headers = [
    { key: 'image', label: 'Image' },
    { key: 'content', label: 'Content' },
    { key: 'cta', label: 'Call to Action' },
    { key: 'status', label: 'Status' }
];

const deleteSlide = (id) => {
    if (confirm('Are you sure you want to delete this slide?')) {
        router.delete(route('admin.hero-slides.destroy', id));
    }
};
</script>

<template>
    <Head title="Hero Slides" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold font-display text-slate-900">Hero Slides</h1>
                <p class="text-slate-500 mt-1">Manage the homepage slider content.</p>
            </div>
            
            <Link 
                :href="route('admin.hero-slides.create')"
                class="px-4 py-2 bg-brand-600 text-white rounded-lg font-semibold hover:bg-brand-700 transition shadow-lg shadow-brand-600/20 flex items-center gap-2"
            >
                <Plus class="w-4 h-4" />
                Add Slide
            </Link>
        </div>

        <!-- Table -->
        <Table 
            :headers="headers" 
            :items="slides.data" 
            :pagination="slides"
            :actions="true"
        >
            <template #image="{ item }">
                <div class="w-24 h-16 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 relative group">
                    <img 
                        v-if="item.image_url" 
                        :src="item.image_url" 
                        class="w-full h-full object-cover"
                        alt="Slide preview"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                        <ImageIcon class="w-6 h-6" />
                    </div>
                </div>
            </template>

            <template #content="{ item }">
                <div class="max-w-md">
                    <div class="font-bold text-slate-800 truncate">{{ item.title }}</div>
                    <div class="text-sm text-slate-500 line-clamp-2">{{ item.subtitle }}</div>
                </div>
            </template>

            <template #cta="{ item }">
                <div v-if="item.cta_text" class="text-sm">
                    <span class="font-medium text-brand-600">{{ item.cta_text }}</span>
                    <div class="text-xs text-slate-400 truncate max-w-[150px]">{{ item.cta_link }}</div>
                </div>
                <span v-else class="text-slate-400 text-xs italic">No CTA</span>
            </template>

            <template #status="{ item }">
                <Badge :variant="item.is_active ? 'success' : 'default'">
                    {{ item.is_active ? 'Active' : 'Inactive' }}
                </Badge>
            </template>

            <template #actions="{ item }">
                <div class="flex justify-end gap-2">
                    <Link 
                        :href="route('admin.hero-slides.edit', item.id)"
                        class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-full transition-colors"
                        title="Edit"
                    >
                        <Edit class="w-4 h-4" />
                    </Link>
                    <button 
                        @click="deleteSlide(item.id)"
                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition-colors"
                        title="Delete"
                    >
                        <Trash2 class="w-4 h-4" />
                    </button>
                </div>
            </template>
        </Table>
    </div>
</template>
