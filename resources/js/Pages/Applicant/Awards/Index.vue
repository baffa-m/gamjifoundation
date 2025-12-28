<script setup>
import { defineOptions, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Search, Filter, Award, Calendar, DollarSign, Users, CheckCircle, ArrowRight, Building2 } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    awards: Object,
    filters: Object,
    appliedAwardIds: Array
});

const categories = [
    { value: '', label: 'All Categories' },
    { value: 'jamb', label: 'JAMB' },
    { value: 'waec', label: 'WAEC' },
    { value: 'general', label: 'General' }
];

const search = (e) => {
    router.get(route('applicant.awards.index'), {
        search: e.target.value,
        category: props.filters?.category
    }, { preserveState: true, replace: true });
};

const filterByCategory = (category) => {
    router.get(route('applicant.awards.index'), {
        category: category,
        search: props.filters?.search
    }, { preserveState: true, replace: true });
};

const hasApplied = (awardId) => {
    return props.appliedAwardIds?.includes(awardId);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        month: 'short', 
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
</script>

<template>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div class="animate-fade-in-up">
                <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                    Browse Awards
                </h1>
                <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">
                    Discover and apply for scholarships that match your profile
                </p>
            </div>

            <!-- Search -->
            <div class="relative group w-full md:w-80 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <Search :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                </div>
                <input
                    type="text"
                    :value="filters?.search"
                    @input="search"
                    class="input-premium pl-12"
                    placeholder="Search awards..."
                />
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-2 animate-fade-in-up" style="animation-delay: 0.15s;">
            <button
                v-for="cat in categories"
                :key="cat.value"
                @click="filterByCategory(cat.value)"
                :class="[
                    'px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300',
                    filters?.category === cat.value || (!filters?.category && cat.value === '')
                        ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30'
                        : isDark 
                            ? 'bg-slate-800 text-slate-300 hover:bg-slate-700' 
                            : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'
                ]"
            >
                {{ cat.label }}
            </button>
        </div>

        <!-- Awards Grid -->
        <div v-if="awards?.data?.length" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-animation">
            <Link
                v-for="award in awards.data"
                :key="award.id"
                :href="route('applicant.awards.show', award.id)"
                :class="[
                    'group relative rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-2',
                    isDark 
                        ? 'bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 hover:shadow-[0_0_40px_rgba(34,197,94,0.15)]' 
                        : 'bg-white border border-slate-200 hover:shadow-xl'
                ]"
            >
                <!-- Top Badge Bar -->
                <div class="h-1.5 w-full bg-gradient-to-r from-brand-600 via-brand-400 to-accent-400" />
                
                <!-- Applied Badge -->
                <div v-if="hasApplied(award.id)" class="absolute top-4 right-4 z-10">
                    <Badge variant="success" class="flex items-center gap-1">
                        <CheckCircle class="w-3 h-3" />
                        Applied
                    </Badge>
                </div>

                <div class="p-6">
                    <!-- Category & Deadline -->
                    <div class="flex items-center justify-between mb-4">
                        <Badge :variant="award.category === 'jamb' ? 'success' : 'default'">
                            {{ award.category?.toUpperCase() }}
                        </Badge>
                        <div :class="['flex items-center gap-1 text-xs', isDark ? 'text-slate-400' : 'text-slate-500']">
                            <Calendar class="w-3 h-3" />
                            <span>Ends {{ formatDate(award.application_end_date) }}</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h3 :class="['text-xl font-bold mb-2 group-hover:text-brand-600 transition-colors font-display', isDark ? 'text-white' : 'text-slate-900']">
                        {{ award.title }}
                    </h3>

                    <!-- Sponsor -->
                    <div v-if="award.sponsor" :class="['flex items-center gap-2 text-sm mb-4', isDark ? 'text-slate-400' : 'text-slate-500']">
                        <Building2 class="w-4 h-4" />
                        <span>{{ award.sponsor.user?.name || 'Anonymous Sponsor' }}</span>
                    </div>

                    <!-- Stats -->
                    <div :class="['flex items-center justify-between pt-4 border-t', isDark ? 'border-slate-700' : 'border-slate-100']">
                        <div>
                            <span :class="['block text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">
                                Award Value
                            </span>
                            <span :class="['font-bold text-lg font-display', isDark ? 'text-accent-400' : 'text-brand-700']">
                                {{ formatAmount(award.amount) }}
                            </span>
                        </div>
                        <div :class="[
                            'w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300',
                            isDark 
                                ? 'bg-slate-700 group-hover:bg-brand-600 text-slate-400 group-hover:text-white' 
                                : 'bg-slate-100 group-hover:bg-brand-600 text-slate-500 group-hover:text-white'
                        ]">
                            <ArrowRight class="w-5 h-5" />
                        </div>
                    </div>
                </div>
            </Link>
        </div>

        <!-- Empty State -->
        <div v-else :class="['text-center py-16 rounded-2xl', isDark ? 'bg-slate-800/50' : 'bg-slate-50']">
            <Award :class="['w-16 h-16 mx-auto mb-4', isDark ? 'text-slate-600' : 'text-slate-300']" />
            <h3 :class="['text-xl font-semibold mb-2', isDark ? 'text-white' : 'text-slate-900']">
                No awards found
            </h3>
            <p :class="['', isDark ? 'text-slate-400' : 'text-slate-600']">
                Try adjusting your filters or check back later for new opportunities.
            </p>
        </div>

        <!-- Pagination -->
        <div v-if="awards?.links?.length > 3" class="flex justify-center gap-2">
            <Link
                v-for="link in awards.links"
                :key="link.label"
                :href="link.url || '#'"
                :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-all',
                    link.active 
                        ? 'bg-brand-600 text-white' 
                        : link.url 
                            ? isDark ? 'bg-slate-800 text-slate-300 hover:bg-slate-700' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'
                            : 'opacity-50 cursor-not-allowed'
                ]"
                v-html="link.label"
            />
        </div>
    </div>
</template>
