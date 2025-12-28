<script setup>
import { defineOptions, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ArrowLeft, Newspaper, FileText, Image, Tag, Save, Eye } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import InputError from '@/Components/InputError.vue';
import Button from '@/Components/UI/Button.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    news: Object,
    awards: Array
});

const form = useForm({
    title: props.news.title || '',
    description: props.news.description || '',
    content: props.news.content || '',
    category: props.news.category || 'general',
    related_award_id: props.news.related_award_id || '',
    featured_image: null,
    is_published: props.news.is_published || false
});

const categories = [
    { value: 'award_announcement', label: 'Award Announcement', emoji: '🏆' },
    { value: 'winner_announcement', label: 'Winner Announcement', emoji: '🎉' },
    { value: 'general', label: 'General News', emoji: '📰' },
    { value: 'event', label: 'Event', emoji: '📅' }
];

const imagePreview = ref(props.news.featured_image ? `/storage/${props.news.featured_image}` : null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.featured_image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.put(route('admin.news.update', props.news.id));
};
</script>

<template>
    <div class="max-w-4xl mx-auto space-y-8">
        <Link :href="route('admin.news.index')" :class="['inline-flex items-center gap-2 text-sm font-medium', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']">
            <ArrowLeft class="w-4 h-4" /> Back to News
        </Link>

        <div>
            <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                Edit Article
            </h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">Update article details</p>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <!-- Article Details -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Newspaper class="w-5 h-5 text-brand-500" /> Article Details
                </h2>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Title *</label>
                        <input v-model="form.title" type="text" class="input-premium" />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Description</label>
                        <textarea v-model="form.description" rows="2" class="input-premium" />
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Content *</label>
                        <textarea v-model="form.content" rows="10" class="input-premium" />
                        <InputError :message="form.errors.content" />
                    </div>
                </div>
            </div>

            <!-- Category -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Tag class="w-5 h-5 text-brand-500" /> Category & Links
                </h2>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Category *</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button v-for="cat in categories" :key="cat.value" type="button" @click="form.category = cat.value"
                                :class="['p-4 rounded-xl text-center transition-all', form.category === cat.value ? 'bg-brand-600 text-white ring-2 ring-brand-500' : isDark ? 'bg-slate-700 text-slate-300' : 'bg-slate-100 text-slate-600']">
                                <div class="text-2xl mb-1">{{ cat.emoji }}</div>
                                <div class="text-xs font-medium">{{ cat.label }}</div>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Related Award</label>
                        <select v-model="form.related_award_id" class="input-premium">
                            <option value="">No related award</option>
                            <option v-for="award in awards" :key="award.id" :value="award.id">{{ award.title }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Image -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Image class="w-5 h-5 text-brand-500" /> Featured Image
                </h2>
                <div class="flex items-center gap-6">
                    <div v-if="imagePreview" class="w-40 h-28 rounded-xl overflow-hidden">
                        <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                    </div>
                    <div>
                        <input type="file" accept="image/*" @change="handleImageChange" class="hidden" id="featured_image" />
                        <label for="featured_image" :class="['inline-flex items-center gap-2 px-4 py-2 rounded-xl cursor-pointer', isDark ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700']">
                            <Image class="w-4 h-4" /> Change Image
                        </label>
                    </div>
                </div>
            </div>

            <!-- Publishing -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Eye class="w-5 h-5 text-brand-500" /> Publishing
                </h2>
                <label class="flex items-center gap-4 cursor-pointer">
                    <div :class="['relative w-14 h-8 rounded-full transition-colors', form.is_published ? 'bg-brand-600' : isDark ? 'bg-slate-700' : 'bg-slate-300']">
                        <input type="checkbox" v-model="form.is_published" class="sr-only" />
                        <div :class="['absolute top-1 left-1 w-6 h-6 rounded-full bg-white shadow transition-transform', form.is_published ? 'translate-x-6' : '']" />
                    </div>
                    <div>
                        <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">{{ form.is_published ? 'Published' : 'Draft' }}</p>
                        <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">{{ form.is_published ? 'Visible to everyone' : 'Not visible' }}</p>
                    </div>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-4">
                <Link :href="route('admin.news.index')" :class="['px-6 py-3 rounded-xl font-semibold', isDark ? 'text-slate-400' : 'text-slate-600']">Cancel</Link>
                <Button type="submit" :disabled="form.processing" size="lg">
                    <Save class="w-5 h-5" /> Save Changes
                </Button>
            </div>
        </form>
    </div>
</template>
