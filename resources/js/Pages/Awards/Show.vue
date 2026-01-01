<script setup>
import { defineOptions, computed } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { 
    ArrowLeft, Calendar, Building2, Target, FileText, 
    CheckCircle, AlertCircle, ArrowRight, Share2,
    Twitter, Facebook, Linkedin, Link2 as LinkIcon
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: GuestLayout });

const { isDark } = useTheme();

const props = defineProps({
    award: Object
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        weekday: 'long',
        month: 'long', 
        day: 'numeric', 
        year: 'numeric' 
    });
};

const formatAmount = (amount) => {
    return new Intl.NumberFormat('en-NG', { 
        style: 'currency', 
        currency: 'NGN',
        maximumFractionDigits: 0
    }).format(amount);
};

const daysRemaining = computed(() => {
    const end = new Date(props.award.application_end_date);
    const now = new Date();
    const diff = Math.ceil((end - now) / (1000 * 60 * 60 * 24));
    return diff > 0 ? diff : 0;
});

const share = (platform) => {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(`Check out this opportunity: ${props.award.title}`);
    
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
    <Head :title="`${award.title} - Gamji Foundation`" />

    <div class="min-h-screen">
        <!-- Header / Breadcrumb -->
        <div :class="['py-6 border-b', isDark ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-100']">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <Link 
                    :href="route('awards.index')" 
                    :class="['inline-flex items-center gap-2 text-sm font-medium transition-colors', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-brand-600']"
                >
                    <ArrowLeft class="w-4 h-4" />
                    Back to Opportunities
                </Link>
            </div>
        </div>

        <section :class="['py-12 pb-24', isDark ? 'bg-slate-950' : 'bg-slate-50']">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 gap-8">
                    
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Title Card -->
                        <div :class="['p-8 rounded-3xl border shadow-xl shadow-brand-900/5', isDark ? 'bg-slate-900 border-slate-800' : 'bg-white border-white']">
                            <div class="flex items-start justify-between gap-4 mb-6">
                                <div>
                                    <Badge variant="default" class="mb-4">{{ award.type || 'Scholarship' }}</Badge>
                                    <h1 :class="['text-3xl md:text-4xl font-bold font-display leading-tight', isDark ? 'text-white' : 'text-slate-900']">
                                        {{ award.title }}
                                    </h1>
                                </div>
                            </div>

                            <div v-if="award.sponsor" :class="['flex items-center gap-4 p-4 rounded-2xl mb-8', isDark ? 'bg-slate-800' : 'bg-slate-50']">
                                <div :class="['w-12 h-12 rounded-full flex items-center justify-center shrink-0', isDark ? 'bg-brand-500/10 text-brand-400' : 'bg-brand-100 text-brand-600']">
                                    <Building2 class="w-6 h-6" />
                                </div>
                                <div>
                                    <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">Sponsored by</p>
                                    <p :class="['font-bold', isDark ? 'text-white' : 'text-slate-900']">
                                        {{ award.sponsor?.user?.name || award.sponsor || 'Gamji Foundation' }}
                                    </p>
                                </div>
                            </div>

                            <div class="prose prose-lg max-w-none" :class="isDark && 'prose-invert'">
                                <h3 :class="['text-xl font-bold mb-4', isDark ? 'text-white' : 'text-slate-900']">About this Opportunity</h3>
                                <p :class="['leading-relaxed', isDark ? 'text-slate-300' : 'text-slate-600']">
                                    {{ award.description }}
                                </p>
                            </div>
                        </div>

                        <!-- Requirements Grid -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Eligibility -->
                            <div :class="['p-8 rounded-3xl border', isDark ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200']">
                                <h3 :class="['text-lg font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                    <Target class="w-5 h-5 text-brand-500" />
                                    Eligibility Criteria
                                </h3>
                                <div v-if="award.eligibility_criteria && award.eligibility_criteria.length">
                                     <ul class="space-y-4">
                                        <li v-for="(criteria, i) in award.eligibility_criteria" :key="i" :class="['flex items-start gap-3 text-sm', isDark ? 'text-slate-300' : 'text-slate-600']">
                                            <CheckCircle class="w-5 h-5 text-green-500 shrink-0" />
                                            <span>{{ criteria }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div v-else class="text-slate-500 text-sm italic">
                                    No specific criteria listed.
                                </div>
                            </div>

                            <!-- Documents -->
                            <div :class="['p-8 rounded-3xl border', isDark ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200']">
                                <h3 :class="['text-lg font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                    <FileText class="w-5 h-5 text-accent-500" />
                                    Required Documents
                                </h3>
                                 <div v-if="award.required_documents && award.required_documents.length">
                                    <ul class="space-y-4">
                                        <li v-for="(doc, i) in award.required_documents" :key="i" :class="['flex items-start gap-3 text-sm', isDark ? 'text-slate-300' : 'text-slate-600']">
                                            <div class="p-1 rounded bg-slate-100 dark:bg-slate-800">
                                                <div class="w-2 h-2 rounded-full bg-accent-500" />
                                            </div>
                                            <span>{{ doc }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div v-else class="text-slate-500 text-sm italic">
                                    No documents listed.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Value Card -->
                        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 to-brand-800 p-8 text-white shadow-xl shadow-brand-900/20">
                            <div class="relative z-10">
                                <p class="text-brand-100 text-sm font-medium uppercase tracking-wider mb-2">Total Value</p>
                                <p class="text-4xl font-bold font-display mb-2">{{ formatAmount(award.amount) }}</p>
                                <p class="text-brand-100 text-sm">{{ award.number_of_awards }} Awards Available</p>
                            </div>
                            <!-- Decor -->
                            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/10 rounded-full blur-2xl" />
                        </div>

                        <!-- Action Card -->
                        <div :class="['p-6 rounded-3xl border shadow-lg', isDark ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-100']">
                            <div class="space-y-6 mb-8">
                                <div>
                                    <p :class="['text-xs uppercase tracking-wider font-bold mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Application Deadline</p>
                                    <p :class="['text-lg font-bold flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                        <Calendar class="w-5 h-5 text-brand-500" />
                                        {{ formatDate(award.application_end_date) }}
                                    </p>
                                    <p v-if="daysRemaining > 0" class="text-sm text-green-600 font-medium mt-1">
                                        {{ daysRemaining }} days remaining
                                    </p>
                                    <p v-else class="text-sm text-red-500 font-medium mt-1">
                                        Application Closed
                                    </p>
                                </div>
                            </div>

                            <div v-if="daysRemaining > 0" class="space-y-3">
                                <a href="/login" class="block w-full py-4 px-6 bg-brand-600 hover:bg-brand-500 text-white font-bold text-center rounded-xl transition-all hover:scale-[1.02] shadow-lg shadow-brand-500/25">
                                    Apply Now
                                </a>
                                <p :class="['text-xs text-center', isDark ? 'text-slate-500' : 'text-slate-400']">
                                    You'll need to login or create an account
                                </p>
                            </div>
                            <div v-else class="p-4 rounded-xl bg-slate-100 dark:bg-slate-800 text-center">
                                <AlertCircle class="w-8 h-8 mx-auto mb-2 text-slate-400" />
                                <span class="font-medium text-slate-600 dark:text-slate-300">Applications have closed</span>
                            </div>
                        </div>

                         <!-- Share -->
                        <div :class="['p-6 rounded-3xl border text-center', isDark ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200']">
                           <p :class="['text-sm font-medium mb-4', isDark ? 'text-slate-400' : 'text-slate-600']">Know someone who deserves this?</p>
                           <div class="flex justify-center gap-3">
                                <button @click="share('twitter')" :class="['p-2.5 rounded-full transition-all hover:scale-110', isDark ? 'bg-slate-800 text-slate-400 hover:bg-[#1DA1F2] hover:text-white' : 'bg-slate-100 text-slate-600 hover:bg-[#1DA1F2] hover:text-white']" title="Share on Twitter">
                                    <Twitter class="w-5 h-5" />
                                </button>
                                <button @click="share('facebook')" :class="['p-2.5 rounded-full transition-all hover:scale-110', isDark ? 'bg-slate-800 text-slate-400 hover:bg-[#4267B2] hover:text-white' : 'bg-slate-100 text-slate-600 hover:bg-[#4267B2] hover:text-white']" title="Share on Facebook">
                                    <Facebook class="w-5 h-5" />
                                </button>
                                <button @click="share('linkedin')" :class="['p-2.5 rounded-full transition-all hover:scale-110', isDark ? 'bg-slate-800 text-slate-400 hover:bg-[#0077b5] hover:text-white' : 'bg-slate-100 text-slate-600 hover:bg-[#0077b5] hover:text-white']" title="Share on LinkedIn">
                                    <Linkedin class="w-5 h-5" />
                                </button>
                                <button @click="share('copy')" :class="['p-2.5 rounded-full transition-all hover:scale-110', isDark ? 'bg-slate-800 text-slate-400 hover:bg-brand-500 hover:text-white' : 'bg-slate-100 text-slate-600 hover:bg-brand-600 hover:text-white']" title="Copy Link">
                                    <LinkIcon class="w-5 h-5" />
                                </button>
                           </div>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    </div>
</template>
