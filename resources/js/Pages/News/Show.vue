<script setup>
import { defineOptions } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ArrowLeft, Calendar, User, Award, Newspaper, Twitter, Facebook, Linkedin, Link2 as LinkIcon } from 'lucide-vue-next';
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



const share = (platform) => {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(props.article.title);
    
    switch(platform) {
        case 'twitter':
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
            break;
        case 'facebook':
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
            break;
        case 'linkedin':
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank');
            break;
        case 'copy':
            navigator.clipboard.writeText(window.location.href);
            // Optional: Show toast
            break;
    }
};
</script>

<template>
    <Head :title="`${article.title} - Gamji Foundation`" />

    <div class="min-h-screen">
        <!-- Hero -->
        <section class="relative py-16 overflow-hidden">
            <!-- Dynamic Background Image -->
            <div v-if="article.featured_image" class="absolute inset-0 z-0">
                <img :src="`/storage/${article.featured_image}`" class="w-full h-full object-cover opacity-30 blur-xl" />
                <div class="absolute inset-0 bg-slate-900/60 mix-blend-multiply" />
            </div>
            
            <div class="absolute inset-0 bg-gradient-to-br from-brand-900/90 via-brand-800/90 to-slate-900/90 z-0" />
            <div class="absolute inset-0 opacity-10 mix-blend-overlay">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-500 rounded-full blur-3xl" />
            </div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-start">
                <Link :href="route('news.index')" class="inline-flex items-center gap-2 text-brand-300 hover:text-white mb-8 transition-colors">
                    <ArrowLeft class="w-4 h-4" /> Back to News
                </Link>

                <Badge variant="default" class="mb-6">{{ categoryLabels[article.category] || article.category }}</Badge>

                <h1 class="text-4xl md:text-5xl font-bold font-display text-white mb-6 leading-tight">
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
        <section :class="['relative z-10 pb-20', isDark ? 'bg-slate-900' : 'bg-white', article.featured_image ? 'pt-12' : 'pt-16']">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Featured Image -->
                <div v-if="article.featured_image" class="mb-10 -mt-20 relative z-10">
                    <div class="rounded-2xl overflow-hidden shadow-2xl">
                        <img :src="`/storage/${article.featured_image}`" :alt="article.title" class="w-full h-auto" />
                    </div>
                </div>

                <!-- Article Content -->
                <article :class="['prose prose-lg max-w-none', isDark ? 'prose-invert prose-headings:text-white prose-p:text-slate-300 prose-strong:text-white prose-a:text-brand-400' : 'prose-headings:text-brand-900 prose-p:text-slate-600 prose-strong:text-brand-900 prose-a:text-brand-600']">
                    <p v-if="article.description" class="text-xl md:text-2xl leading-relaxed font-light mb-8 not-prose" :class="isDark ? 'text-slate-200' : 'text-slate-700'">
                        {{ article.description }}
                    </p>

                    <div v-html="article.content?.replace(/\n/g, '<br/>')" class="whitespace-pre-wrap leading-relaxed"></div>
                </article>

                <!-- Related Award CTA -->
                <div v-if="article.related_award" class="mt-16 not-prose">
                    <div :class="['relative overflow-hidden p-8 rounded-2xl border transition-all duration-300 group hover:-translate-y-1', isDark ? 'bg-slate-800/50 border-slate-700 hover:border-brand-500/50' : 'bg-gradient-to-br from-brand-50 to-white border-brand-100 hover:border-brand-300 hover:shadow-lg']">
                        <!-- Decorative bg -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 rounded-full blur-2xl -mr-16 -mt-16 transition-all group-hover:bg-brand-500/20" />
                        
                        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="flex-1">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-100 dark:bg-brand-900/30 text-brand-700 dark:text-brand-300 text-xs font-bold uppercase tracking-wider mb-3">
                                    <Award class="w-3 h-3" /> Related Opportunity
                                </div>
                                <h3 :class="['text-xl md:text-2xl font-bold mb-2', isDark ? 'text-white' : 'text-slate-900']">
                                    {{ article.related_award.title }}
                                </h3>
                                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-600']">
                                    Don't miss out on this scholarship opportunity. Check eligibility and apply now.
                                </p>
                            </div>
                            <Link 
                                :href="route('awards.show', article.related_award.slug)" 
                                class="shrink-0 px-6 py-3 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-500 shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2"
                            >
                                View Award <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Share & Interaction -->
                <div :class="['mt-12 pt-8 border-t flex flex-col md:flex-row items-center justify-between gap-6', isDark ? 'border-slate-700' : 'border-slate-200']">
                    
                    <div class="flex items-center gap-4">
                        <span :class="['text-sm font-medium uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Share this article</span>
                        <div class="flex gap-2">
                            <button @click="share('twitter')" :class="['p-2 rounded-full transition-colors', isDark ? 'bg-slate-800 text-slate-400 hover:bg-brand-500 hover:text-white' : 'bg-slate-100 text-slate-600 hover:bg-brand-600 hover:text-white']">
                                <Twitter class="w-4 h-4" />
                            </button>
                            <button @click="share('facebook')" :class="['p-2 rounded-full transition-colors', isDark ? 'bg-slate-800 text-slate-400 hover:bg-brand-500 hover:text-white' : 'bg-slate-100 text-slate-600 hover:bg-brand-600 hover:text-white']">
                                <Facebook class="w-4 h-4" />
                            </button>
                            <button @click="share('linkedin')" :class="['p-2 rounded-full transition-colors', isDark ? 'bg-slate-800 text-slate-400 hover:bg-brand-500 hover:text-white' : 'bg-slate-100 text-slate-600 hover:bg-brand-600 hover:text-white']">
                                <Linkedin class="w-4 h-4" />
                            </button>
                            <button @click="share('copy')" :class="['p-2 rounded-full transition-colors', isDark ? 'bg-slate-800 text-slate-400 hover:bg-brand-500 hover:text-white' : 'bg-slate-100 text-slate-600 hover:bg-brand-600 hover:text-white']">
                                <LinkIcon class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <Link :href="route('news.index')" :class="['inline-flex items-center gap-2 font-medium px-6 py-3 rounded-xl transition-all', isDark ? 'bg-slate-800 text-brand-400 hover:bg-slate-700' : 'bg-slate-100 text-brand-600 hover:bg-slate-200']">
                        <ArrowLeft class="w-4 h-4" /> Back to All News
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>
