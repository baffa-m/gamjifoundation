<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/UI/Table.vue';
import { useTheme } from '@/Composables/useTheme';
import { Plus, Edit, Trash2, Image as ImageIcon, Eye } from 'lucide-vue-next';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

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
                <h1 :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">Hero Slides</h1>
                <p :class="['mt-1', isDark ? 'text-slate-400' : 'text-slate-500']">Manage the homepage slider content.</p>
            </div>
            
            <Link 
                :href="route('admin.hero-slides.create')"
                class="px-4 py-2 bg-brand-600 text-white rounded-lg font-semibold hover:bg-brand-700 transition shadow-lg shadow-brand-600/20 flex items-center gap-2"
            >
                <Plus class="w-5 h-5" />
                Add Slide
            </Link>
        </div>

        <!-- Table -->
        <Table 
            :headers="headers" 
            :items="slides.data" 
            :pagination="slides"
            :actions="true"
            :clickable="true"
            @row-click="(item) => router.visit(route('admin.hero-slides.edit', item.id))"
        >
            <template #image="{ item }">
                <div :class="['w-24 h-16 rounded-lg overflow-hidden border relative group', isDark ? 'bg-slate-800 border-slate-700' : 'bg-slate-100 border-slate-200']">
                    <img 
                        v-if="item.image_url" 
                        :src="item.image_url" 
                        class="w-full h-full object-cover"
                        alt="Slide preview"
                    />
                    <div v-else :class="['w-full h-full flex items-center justify-center', isDark ? 'text-slate-600' : 'text-slate-400']">
                        <ImageIcon class="w-6 h-6" />
                    </div>
                </div>
            </template>

            <template #content="{ item }">
                <div class="max-w-md">
                    <div :class="['font-bold truncate', isDark ? 'text-white' : 'text-slate-800']">{{ item.title }}</div>
                    <div :class="['text-sm line-clamp-2', isDark ? 'text-slate-400' : 'text-slate-500']">{{ item.subtitle }}</div>
                </div>
            </template>

            <template #cta="{ item }">
                <div v-if="item.cta_text" class="text-sm">
                    <span :class="['font-medium', isDark ? 'text-brand-400' : 'text-brand-600']">{{ item.cta_text }}</span>
                    <div :class="['text-xs truncate max-w-[150px]', isDark ? 'text-slate-500' : 'text-slate-400']">{{ item.cta_link }}</div>
                </div>
                <span v-else :class="['text-xs italic', isDark ? 'text-slate-600' : 'text-slate-400']">No CTA</span>
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
                        :class="['p-2 rounded-full transition-colors', isDark ? 'text-slate-400 hover:text-brand-400 hover:bg-slate-700' : 'text-slate-400 hover:text-brand-600 hover:bg-brand-50']"
                        title="Edit"
                        @click.stop
                    >
                        <Edit class="w-5 h-5" />
                    </Link>
                    <button 
                        @click.stop="deleteSlide(item.id)"
                        :class="['p-2 rounded-full transition-colors', isDark ? 'text-slate-400 hover:text-rose-400 hover:bg-rose-900/30' : 'text-slate-400 hover:text-rose-600 hover:bg-rose-50']"
                        title="Delete"
                    >
                        <Trash2 class="w-5 h-5" />
                    </button>
                </div>
            </template>
        </Table>
    </div>
</template>
