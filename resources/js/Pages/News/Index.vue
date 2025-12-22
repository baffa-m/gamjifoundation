<script setup>
import { defineOptions } from 'vue';
import { Link, router, Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Newspaper, Calendar, User, Search, Tag, ArrowRight } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: GuestLayout });

const { isDark } = useTheme();

const props = defineProps({
    news: Object,
    filters: Object
});

const categories = [
    { value: '', label: 'All' },
    { value: 'award_announcement', label: 'Award Announcements' },
    { value: 'winner_announcement', label: 'Winners' },
    { value: 'general', label: 'General' },
    { value: 'event', label: 'Events' }
];

const categoryLabels = {
    award_announcement: 'Award',
    winner_announcement: 'Winners',
    general: 'News',
    event: 'Event'
};

const search = (e) => {
    router.get(route('news.index'), { search: e.target.value, category: props.filters?.category }, { preserveState: true, replace: true });
};

const filterByCategory = (category) => {
    router.get(route('news.index'), { category, search: props.filters?.search }, { preserveState: true, replace: true });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { month: 'long', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <Head title="News - Gamji Foundation" />

    <div class="min-h-screen">
        <!-- Hero -->
        <section class="relative py-24 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-900 via-brand-800 to-slate-900" />
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-500 rounded-full blur-3xl animate-pulse" />
                <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-accent-500 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;" />
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl md:text-6xl font-bold font-display text-white mb-6 animate-fade-in-up">
                    Latest <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-accent-400">News</span>
                </h1>
                <p class="text-xl text-brand-100 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay: 0.1s;">
                    Stay updated with announcements, events, and success stories
                </p>

                <!-- Search -->
                <div class="mt-8 max-w-xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <Search class="h-5 w-5 text-slate-400" />
                        </div>
                        <input
                            type="text"
                            :value="filters?.search"
                            @input="search"
                            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent"
                            placeholder="Search articles..."
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Content -->
        <section :class="['py-16', isDark ? 'bg-slate-900' : 'bg-slate-50']">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="flex flex-wrap gap-2 mb-8">
                    <button
                        v-for="cat in categories"
                        :key="cat.value"
                        @click="filterByCategory(cat.value)"
                        :class="[
                            'px-4 py-2 rounded-xl text-sm font-medium transition-all',
                            filters?.category === cat.value || (!filters?.category && cat.value === '')
                                ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30'
                                : isDark ? 'bg-slate-800 text-slate-300' : 'bg-white text-slate-600 border border-slate-200'
                        ]"
                    >
                        {{ cat.label }}
                    </button>
                </div>

                <!-- Grid -->
                <div v-if="news?.data?.length" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <Link
                        v-for="article in news.data"
                        :key="article.id"
                        :href="route('news.show', article.slug)"
                        :class="[
                            'group rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-2',
                            isDark ? 'bg-slate-800 hover:shadow-[0_0_40px_rgba(34,197,94,0.15)]' : 'bg-white shadow-sm hover:shadow-xl'
                        ]"
                    >
                        <!-- Image -->
                        <div class="relative h-48 overflow-hidden">
                            <img
                                v-if="article.featured_image"
                                :src="`/storage/${article.featured_image}`"
                                :alt="article.title"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <div v-else :class="['w-full h-full flex items-center justify-center', isDark ? 'bg-slate-700' : 'bg-slate-100']">
                                <Newspaper :class="['w-12 h-12', isDark ? 'text-slate-600' : 'text-slate-300']" />
                            </div>
                            <div class="absolute top-4 left-4">
                                <Badge variant="default">{{ categoryLabels[article.category] || article.category }}</Badge>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div :class="['flex items-center gap-4 text-sm mb-3', isDark ? 'text-slate-400' : 'text-slate-500']">
                                <span class="flex items-center gap-1">
                                    <Calendar class="w-4 h-4" />
                                    {{ formatDate(article.published_at || article.created_at) }}
                                </span>
                                <span v-if="article.author" class="flex items-center gap-1">
                                    <User class="w-4 h-4" />
                                    {{ article.author.name }}
                                </span>
                            </div>

                            <h3 :class="['text-xl font-bold mb-2 group-hover:text-brand-600 transition-colors line-clamp-2', isDark ? 'text-white' : 'text-slate-900']">
                                {{ article.title }}
                            </h3>

                            <p :class="['text-sm line-clamp-2 mb-4', isDark ? 'text-slate-400' : 'text-slate-600']">
                                {{ article.description }}
                            </p>

                            <span :class="['inline-flex items-center gap-1 text-sm font-medium', isDark ? 'text-brand-400' : 'text-brand-600']">
                                Read More <ArrowRight class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- Empty -->
                <div v-else :class="['text-center py-16 rounded-2xl', isDark ? 'bg-slate-800/50' : 'bg-white']">
                    <Newspaper :class="['w-16 h-16 mx-auto mb-4', isDark ? 'text-slate-600' : 'text-slate-300']" />
                    <h3 :class="['text-xl font-semibold mb-2', isDark ? 'text-white' : 'text-slate-900']">No articles found</h3>
                    <p :class="isDark ? 'text-slate-400' : 'text-slate-600'">Try adjusting your search or filters.</p>
                </div>

                <!-- Pagination -->
                <div v-if="news?.links?.length > 3" class="flex justify-center gap-2 mt-12">
                    <Link
                        v-for="link in news.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'px-4 py-2 rounded-lg text-sm font-medium transition-all',
                            link.active ? 'bg-brand-600 text-white' : link.url ? isDark ? 'bg-slate-800 text-slate-300' : 'bg-white text-slate-600 border border-slate-200' : 'opacity-50 cursor-not-allowed'
                        ]"
                        v-html="link.label"
                    />
                </div>
            </div>
        </section>
    </div>
</template>
