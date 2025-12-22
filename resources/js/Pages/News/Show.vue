<script setup>
import { defineOptions } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ArrowLeft, Calendar, User, Tag, Award, Share2 } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: GuestLayout });

const { isDark } = useTheme();

const props = defineProps({
    article: Object
});

const categoryLabels = {
    award_announcement: 'Award Announcement',
    winner_announcement: 'Winner Announcement',
    general: 'General News',
    event: 'Event'
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' 
    });
};
</script>

<template>
    <Head :title="`${article.title} - Gamji Foundation`" />

    <div class="min-h-screen">
        <!-- Hero -->
        <section class="relative py-16 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-900 via-brand-800 to-slate-900" />
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-500 rounded-full blur-3xl" />
            </div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <Link :href="route('news.index')" class="inline-flex items-center gap-2 text-brand-300 hover:text-white mb-8 transition-colors">
                    <ArrowLeft class="w-4 h-4" /> Back to News
                </Link>

                <Badge variant="default" class="mb-4">{{ categoryLabels[article.category] || article.category }}</Badge>

                <h1 class="text-4xl md:text-5xl font-bold font-display text-white mb-6">
                    {{ article.title }}
                </h1>

                <div class="flex flex-wrap items-center gap-6 text-brand-200">
                    <span class="flex items-center gap-2">
                        <Calendar class="w-5 h-5" />
                        {{ formatDate(article.published_at || article.created_at) }}
                    </span>
                    <span v-if="article.author" class="flex items-center gap-2">
                        <User class="w-5 h-5" />
                        {{ article.author.name }}
                    </span>
                </div>
            </div>
        </section>

        <!-- Content -->
        <section :class="['py-12', isDark ? 'bg-slate-900' : 'bg-white']">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Featured Image -->
                <div v-if="article.featured_image" class="mb-10 -mt-20 relative z-10">
                    <div class="rounded-2xl overflow-hidden shadow-2xl">
                        <img :src="`/storage/${article.featured_image}`" :alt="article.title" class="w-full h-auto" />
                    </div>
                </div>

                <!-- Article Content -->
                <div :class="['prose prose-lg max-w-none', isDark && 'prose-invert']">
                    <p v-if="article.description" :class="['text-xl leading-relaxed mb-8', isDark ? 'text-slate-300' : 'text-slate-600']">
                        {{ article.description }}
                    </p>

                    <div :class="['whitespace-pre-wrap leading-relaxed', isDark ? 'text-slate-300' : 'text-slate-700']">
                        {{ article.content }}
                    </div>
                </div>

                <!-- Related Award -->
                <div v-if="article.related_award" :class="['mt-12 p-6 rounded-2xl', isDark ? 'bg-slate-800 border border-slate-700' : 'bg-brand-50 border border-brand-200']">
                    <h3 :class="['text-lg font-semibold mb-3 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <Award :class="['w-5 h-5', isDark ? 'text-brand-400' : 'text-brand-600']" />
                        Related Award
                    </h3>
                    <p :class="['font-medium', isDark ? 'text-brand-400' : 'text-brand-700']">
                        {{ article.related_award.title }}
                    </p>
                </div>

                <!-- Share -->
                <div :class="['mt-12 pt-8 border-t', isDark ? 'border-slate-700' : 'border-slate-200']">
                    <div class="flex items-center justify-between">
                        <Link :href="route('news.index')" :class="['inline-flex items-center gap-2 font-medium', isDark ? 'text-brand-400' : 'text-brand-600']">
                            <ArrowLeft class="w-4 h-4" /> More Articles
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
