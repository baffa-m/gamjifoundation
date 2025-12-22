<script setup>
import { defineOptions, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    ArrowLeft, Newspaper, FileText, Image, Tag, Link2, Save, Eye, EyeOff
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import InputError from '@/Components/InputError.vue';
import Button from '@/Components/UI/Button.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    awards: Array
});

const form = useForm({
    title: '',
    description: '',
    content: '',
    category: 'general',
    related_award_id: '',
    featured_image: null,
    is_published: false
});

const categories = [
    { value: 'award_announcement', label: 'Award Announcement', emoji: '🏆' },
    { value: 'winner_announcement', label: 'Winner Announcement', emoji: '🎉' },
    { value: 'general', label: 'General News', emoji: '📰' },
    { value: 'event', label: 'Event', emoji: '📅' }
];

const imagePreview = ref(null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.featured_image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('admin.news.store'));
};
</script>

<template>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Back Button -->
        <Link :href="route('admin.news.index')" :class="['inline-flex items-center gap-2 text-sm font-medium', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']">
            <ArrowLeft class="w-4 h-4" /> Back to News
        </Link>

        <!-- Header -->
        <div class="animate-fade-in-up">
            <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                Create Article
            </h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">
                Write and publish a new news article
            </p>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="space-y-8">
            <!-- Basic Info -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Newspaper class="w-5 h-5 text-brand-500" />
                    Article Details
                </h2>

                <div class="space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Title *</label>
                        <input v-model="form.title" type="text" class="input-premium" placeholder="Enter article title..." />
                        <InputError :message="form.errors.title" />
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Description</label>
                        <textarea v-model="form.description" rows="2" class="input-premium" placeholder="Brief summary for previews..." />
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Content -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Content *</label>
                        <textarea v-model="form.content" rows="10" class="input-premium" placeholder="Write your article content..." />
                        <InputError :message="form.errors.content" />
                    </div>
                </div>
            </div>

            <!-- Category & Related Award -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Tag class="w-5 h-5 text-brand-500" />
                    Category & Links
                </h2>

                <div class="space-y-6">
                    <!-- Category -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Category *</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button
                                v-for="cat in categories"
                                :key="cat.value"
                                type="button"
                                @click="form.category = cat.value"
                                :class="[
                                    'p-4 rounded-xl text-center transition-all',
                                    form.category === cat.value
                                        ? 'bg-brand-600 text-white ring-2 ring-brand-500 ring-offset-2' + (isDark ? ' ring-offset-slate-900' : ' ring-offset-white')
                                        : isDark ? 'bg-slate-700 text-slate-300' : 'bg-slate-100 text-slate-600'
                                ]"
                            >
                                <div class="text-2xl mb-1">{{ cat.emoji }}</div>
                                <div class="text-xs font-medium">{{ cat.label }}</div>
                            </button>
                        </div>
                    </div>

                    <!-- Related Award -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Related Award (Optional)</label>
                        <select v-model="form.related_award_id" class="input-premium">
                            <option value="">No related award</option>
                            <option v-for="award in awards" :key="award.id" :value="award.id">{{ award.title }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Image class="w-5 h-5 text-brand-500" />
                    Featured Image
                </h2>

                <div class="flex items-center gap-6">
                    <div v-if="imagePreview" class="w-40 h-28 rounded-xl overflow-hidden">
                        <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                    </div>
                    <div v-else :class="['w-40 h-28 rounded-xl flex items-center justify-center', isDark ? 'bg-slate-700' : 'bg-slate-100']">
                        <Image :class="['w-8 h-8', isDark ? 'text-slate-500' : 'text-slate-400']" />
                    </div>
                    <div>
                        <input type="file" accept="image/*" @change="handleImageChange" class="hidden" id="featured_image" />
                        <label for="featured_image" :class="['inline-flex items-center gap-2 px-4 py-2 rounded-xl cursor-pointer', isDark ? 'bg-slate-700 hover:bg-slate-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700']">
                            <Image class="w-4 h-4" /> Upload Image
                        </label>
                        <p :class="['text-sm mt-2', isDark ? 'text-slate-500' : 'text-slate-400']">Max 2MB. JPG, PNG, or GIF.</p>
                    </div>
                </div>
                <InputError :message="form.errors.featured_image" />
            </div>

            <!-- Publishing -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Eye class="w-5 h-5 text-brand-500" />
                    Publishing
                </h2>

                <label class="flex items-center gap-4 cursor-pointer">
                    <div :class="[
                        'relative w-14 h-8 rounded-full transition-colors',
                        form.is_published ? 'bg-brand-600' : isDark ? 'bg-slate-700' : 'bg-slate-300'
                    ]">
                        <input type="checkbox" v-model="form.is_published" class="sr-only" />
                        <div :class="[
                            'absolute top-1 left-1 w-6 h-6 rounded-full bg-white shadow transition-transform',
                            form.is_published ? 'translate-x-6' : ''
                        ]" />
                    </div>
                    <div>
                        <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                            {{ form.is_published ? 'Published' : 'Draft' }}
                        </p>
                        <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                            {{ form.is_published ? 'Article will be visible to everyone' : 'Article is saved but not visible' }}
                        </p>
                    </div>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-4">
                <Link :href="route('admin.news.index')" :class="['px-6 py-3 rounded-xl font-semibold', isDark ? 'text-slate-400' : 'text-slate-600']">Cancel</Link>
                <Button type="submit" :disabled="form.processing" size="lg">
                    <Save class="w-5 h-5" /> Create Article
                </Button>
            </div>
        </form>
    </div>
</template>
