<script setup>
import { defineOptions } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    Newspaper, Plus, Eye, Edit, Trash2, CheckCircle, Clock, Search, Image
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    news: Object,
    filters: Object
});

const categoryLabels = {
    award_announcement: 'Award Announcement',
    winner_announcement: 'Winner Announcement',
    general: 'General',
    event: 'Event'
};

const filterByStatus = (status) => {
    router.get(route('admin.news.index'), { status }, { preserveState: true, replace: true });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        month: 'short', day: 'numeric', year: 'numeric' 
    });
};

const deleteNews = (news) => {
    if (confirm('Are you sure you want to delete this article?')) {
        router.delete(route('admin.news.destroy', news.id));
    }
};
</script>

<template>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="animate-fade-in-up">
                <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                    News Management
                </h1>
                <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">
                    Create and manage news articles
                </p>
            </div>

            <Link :href="route('admin.news.create')" class="btn-primary">
                <Plus class="w-5 h-5" />
                Create Article
            </Link>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-3">
            <button
                @click="filterByStatus('')"
                :class="[
                    'px-4 py-2 rounded-xl text-sm font-medium transition-all',
                    !filters?.status
                        ? 'bg-brand-600 text-white'
                        : isDark ? 'bg-slate-800 text-slate-300 hover:bg-slate-700' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'
                ]"
            >
                All
            </button>
            <button
                @click="filterByStatus('published')"
                :class="[
                    'px-4 py-2 rounded-xl text-sm font-medium transition-all',
                    filters?.status === 'published'
                        ? 'bg-emerald-600 text-white'
                        : isDark ? 'bg-slate-800 text-slate-300 hover:bg-slate-700' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'
                ]"
            >
                Published
            </button>
            <button
                @click="filterByStatus('draft')"
                :class="[
                    'px-4 py-2 rounded-xl text-sm font-medium transition-all',
                    filters?.status === 'draft'
                        ? 'bg-amber-600 text-white'
                        : isDark ? 'bg-slate-800 text-slate-300 hover:bg-slate-700' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'
                ]"
            >
                Drafts
            </button>
        </div>

        <!-- News Table -->
        <div v-if="news?.data?.length" :class="['rounded-2xl overflow-hidden', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="isDark ? 'bg-slate-800' : 'bg-slate-50'">
                        <tr>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Article</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Category</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Status</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Author</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Date</th>
                            <th :class="['px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Actions</th>
                        </tr>
                    </thead>
                    <tbody :class="['divide-y', isDark ? 'divide-slate-700' : 'divide-slate-100']">
                        <tr 
                            v-for="article in news.data" 
                            :key="article.id" 
                            @click="router.visit(route('admin.news.edit', article.id))"
                            :class="['transition-colors cursor-pointer', isDark ? 'hover:bg-slate-800/50' : 'hover:bg-slate-50']"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div v-if="article.featured_image" class="w-16 h-12 rounded-lg overflow-hidden bg-slate-100 shrink-0">
                                        <img :src="`/storage/${article.featured_image}`" :alt="article.title" class="w-full h-full object-cover" />
                                    </div>
                                    <div v-else :class="['w-16 h-12 rounded-lg flex items-center justify-center shrink-0', isDark ? 'bg-slate-700' : 'bg-slate-100']">
                                        <Image :class="['w-6 h-6', isDark ? 'text-slate-500' : 'text-slate-400']" />
                                    </div>
                                    <div class="min-w-0">
                                        <p :class="['font-semibold truncate', isDark ? 'text-white' : 'text-slate-900']">{{ article.title }}</p>
                                        <p :class="['text-sm truncate', isDark ? 'text-slate-400' : 'text-slate-500']">{{ article.description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <Badge variant="default">{{ categoryLabels[article.category] || article.category }}</Badge>
                            </td>
                            <td class="px-6 py-4">
                                <Badge :variant="article.is_published ? 'success' : 'warning'" class="flex items-center gap-1 w-fit">
                                    <CheckCircle v-if="article.is_published" class="w-3 h-3" />
                                    <Clock v-else class="w-3 h-3" />
                                    {{ article.is_published ? 'Published' : 'Draft' }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="isDark ? 'text-slate-300' : 'text-slate-600'">{{ article.author?.name || 'Unknown' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                    {{ formatDate(article.created_at) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link 
                                        :href="route('admin.news.edit', article.id)"
                                        :class="['p-2 rounded-lg transition-colors', isDark ? 'hover:bg-slate-700 text-slate-400 hover:text-white' : 'hover:bg-slate-100 text-slate-500 hover:text-slate-700']"
                                        @click.stop
                                    >
                                        <Edit class="w-5 h-5" />
                                    </Link>
                                    <button 
                                        @click.stop="deleteNews(article)"
                                        :class="['p-2 rounded-lg transition-colors', isDark ? 'hover:bg-red-900/30 text-slate-400 hover:text-red-400' : 'hover:bg-red-50 text-slate-500 hover:text-red-600']"
                                    >
                                        <Trash2 class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else :class="['text-center py-16 rounded-2xl', isDark ? 'bg-slate-800/50' : 'bg-slate-50']">
            <Newspaper :class="['w-16 h-16 mx-auto mb-4', isDark ? 'text-slate-600' : 'text-slate-300']" />
            <h3 :class="['text-xl font-semibold mb-2', isDark ? 'text-white' : 'text-slate-900']">No articles yet</h3>
            <p :class="['mb-6', isDark ? 'text-slate-400' : 'text-slate-600']">Create your first news article to get started.</p>
            <Link :href="route('admin.news.create')" class="btn-primary inline-flex">
                <Plus class="w-5 h-5" /> Create Article
            </Link>
        </div>

        <!-- Pagination -->
        <div v-if="news?.links?.length > 3" class="flex justify-center gap-2">
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
</template>
