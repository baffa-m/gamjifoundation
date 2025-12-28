<script setup>
import { defineOptions, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { 
    Award, Users, FileText, TrendingUp, ArrowRight, 
    CheckCircle, XCircle, Clock, MoreVertical, Filter, Download 
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    stats: Object,
    recent_applications: Array,
    recent_awards: Array,
    pending_sponsors: Array,
    chart_data: Array,
    activities: Array,
    trends: Object
});

const statCards = [
    {
        label: 'Total Awards',
        value: (stats) => stats.total_awards,
        change: (trends) => trends.awards === 'New' ? 'New' : `${trends.awards > 0 ? '+' : ''}${trends.awards}%`,
        trend: (trends) => trends.awards === 'New' || trends.awards >= 0 ? 'up' : 'down',
        icon: Award,
        color: 'text-blue-600 dark:text-blue-400',
        bg: 'bg-blue-50 dark:bg-blue-900/20',
        border: 'border-blue-100 dark:border-blue-900/20'
    },
    {
        label: 'Pending Reviews',
        value: (stats) => stats.pending_applications,
        change: (trends) => trends.applications === 'New' ? 'New' : `${trends.applications > 0 ? '+' : ''}${trends.applications}%`,
        trend: (trends) => trends.applications === 'New' || trends.applications >= 0 ? 'up' : 'down',
        icon: FileText,
        color: 'text-amber-600 dark:text-amber-400',
        bg: 'bg-amber-50 dark:bg-amber-900/20',
        border: 'border-amber-100 dark:border-amber-900/20'
    },
    {
        label: 'Total Applicants',
        value: (stats) => stats.total_applicants,
        change: (trends) => trends.applicants === 'New' ? 'New' : `${trends.applicants > 0 ? '+' : ''}${trends.applicants}%`,
        trend: (trends) => trends.applicants === 'New' || trends.applicants >= 0 ? 'up' : 'down',
        icon: Users,
        color: 'text-emerald-600 dark:text-emerald-400',
        bg: 'bg-emerald-50 dark:bg-emerald-900/20',
        border: 'border-emerald-100 dark:border-emerald-900/20'
    },
    {
        label: 'Pending Sponsors',
        value: (stats) => stats.pending_sponsors,
        change: (trends) => trends.sponsors === 'New' ? 'New' : `${trends.sponsors > 0 ? '+' : ''}${trends.sponsors}%`,
        trend: (trends) => trends.sponsors === 'New' || trends.sponsors >= 0 ? 'up' : 'down',
        icon: TrendingUp,
        color: 'text-purple-600 dark:text-purple-400',
        bg: 'bg-purple-50 dark:bg-purple-900/20',
        border: 'border-purple-100 dark:border-purple-900/20'
    }
];

const getStatusColor = (status) => {
    switch (status) {
        case 'accepted': return 'bg-emerald-100 text-emerald-700 border-emerald-200';
        case 'pending': return 'bg-amber-100 text-amber-700 border-amber-200';
        case 'rejected': return 'bg-rose-100 text-rose-700 border-rose-200';
        default: return 'bg-slate-100 text-slate-700 border-slate-200';
    }
};

// Chart handling
const maxChartValue = computed(() => {
    return Math.max(...(props.chart_data || [10]), 10);
});

import { computed } from 'vue';
import { formatDistanceToNow } from 'date-fns';
</script>

<template>
    <div class="space-y-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in-up">
            <h1 class="text-4xl font-bold font-display bg-gradient-to-r from-slate-900 to-brand-700 bg-clip-text text-transparent">Admin Dashboard</h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-500']">Overview of platform activity and metrics</p>
        </div>

        <!-- 1. Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 stagger-animation">
            <div 
                v-for="stat in statCards" 
                :key="stat.label"
                class="card-premium p-6 group relative overflow-hidden dark:bg-slate-800 dark:border-slate-700"
            >
                <!-- Shine Effect -->
                <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div :class="[stat.bg, stat.color, 'w-12 h-12 rounded-xl flex items-center justify-center transition-transform duration-300 group-hover:scale-110 shadow-sm']">
                            <component :is="stat.icon" class="w-6 h-6" />
                        </div>
                        <div :class="['text-sm font-semibold px-2 py-1 rounded-full transition-colors', stat.trend(trends) === 'up' ? 'text-emerald-600 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-900/20' : 'text-rose-600 bg-rose-50 dark:text-rose-400 dark:bg-rose-900/20']">
                            {{ stat.change(trends) }}
                        </div>
                    </div>
                    <div class="text-3xl font-bold font-display text-slate-900 dark:text-white mb-1">
                        {{ stat.value(stats) }}
                    </div>
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                        {{ stat.label }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Charts -->
        <div class="grid grid-cols-1 gap-6">
            
            <!-- Bar Chart -->
            <div :class="['p-6 rounded-2xl border shadow-sm', isDark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100']">
                <div class="flex justify-between items-center mb-6">
                    <h3 :class="['font-bold text-lg', isDark ? 'text-white' : 'text-slate-800']">Application Analytics</h3>
                    <select :class="['text-xs border-none rounded-lg outline-none p-2 font-medium cursor-pointer transition-colors', isDark ? 'bg-slate-700 text-slate-300 hover:bg-slate-600' : 'bg-slate-50 text-slate-500 hover:bg-slate-100']">
                        <option>Last 6 Months</option>
                        <option>This Year</option>
                    </select>
                </div>
                
                <div class="h-64 flex items-end justify-between gap-2 md:gap-4 px-2">
                    <div v-for="(count, i) in (props.chart_data || [])" :key="i" class="w-full flex flex-col justify-end group cursor-pointer">
                        <div 
                            :class="['w-full rounded-t-sm relative overflow-hidden transition-colors', isDark ? 'bg-slate-700 group-hover:bg-brand-900/30' : 'bg-slate-100 group-hover:bg-brand-50']" 
                            :style="{ height: `${maxChartValue > 0 ? (count / maxChartValue) * 100 : 0}%` }"
                        >
                            <div 
                                class="absolute bottom-0 left-0 w-full bg-brand-500/80 group-hover:bg-brand-600 transition-colors rounded-t-md" 
                                :style="{ height: '100%' }"
                            ></div>
                        </div>
                         <!-- Tooltip -->
                         <div class="invisible group-hover:visible absolute -mt-8 text-xs font-bold bg-slate-900 text-white px-2 py-1 rounded">
                            {{ count }}
                        </div>
                    </div>
                </div>
                <div :class="['flex justify-between mt-4 text-xs font-medium px-1 uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">
                    <span>Jan</span><span>Mar</span><span>May</span><span>Jul</span><span>Sep</span><span>Nov</span>
                </div>
            </div>
        </div>

        <!-- 3. Recent Applications Table -->
        <div :class="['rounded-2xl border shadow-sm overflow-hidden', isDark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100']">
            <div :class="['p-6 border-b flex flex-col md:flex-row justify-between md:items-center gap-4', isDark ? 'border-slate-700 bg-slate-800' : 'border-slate-100 bg-slate-50/30']">
                <h3 :class="['font-bold text-lg flex items-center gap-2', isDark ? 'text-white' : 'text-slate-800']">
                    <FileText class="w-5 h-5 text-brand-600" />
                    Recent Applications
                </h3>
                <div class="flex gap-3">
                    <button :class="['px-4 py-2 text-sm font-medium border rounded-lg transition flex items-center gap-2', isDark ? 'text-slate-300 bg-slate-700 border-slate-600 hover:bg-slate-600' : 'text-slate-600 bg-white border-slate-200 hover:bg-slate-50']">
                        <Filter class="w-5 h-5" /> Filter
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition shadow-lg shadow-brand-600/20 flex items-center gap-2">
                        <Download class="w-5 h-5" /> Export CSV
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead :class="['text-xs uppercase font-semibold tracking-wider', isDark ? 'bg-slate-700/50 text-slate-400' : 'bg-slate-50 text-slate-500']">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Award Type</th>
                            <th class="px-6 py-4">Date Applied</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody :class="['divide-y', isDark ? 'divide-slate-700' : 'divide-slate-100']">
                        <tr 
                            v-for="app in recent_applications" 
                            :key="app.id" 
                            :class="['transition-colors group', isDark ? 'hover:bg-slate-700/50' : 'hover:bg-slate-50/80']"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold border', isDark ? 'bg-brand-900/30 text-brand-400 border-brand-800' : 'bg-brand-100 text-brand-700 border-brand-200']">
                                        {{ app.applicant.full_name.charAt(0) }}
                                    </div>
                                    <div>
                                        <div :class="['font-semibold text-sm transition-colors', isDark ? 'text-slate-200 group-hover:text-brand-400' : 'text-slate-800 group-hover:text-brand-700']">
                                            {{ app.applicant.full_name }}
                                        </div>
                                        <div :class="['text-xs', isDark ? 'text-slate-500' : 'text-slate-500']">ID: #{{ app.id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td :class="['px-6 py-4 text-sm font-medium', isDark ? 'text-slate-400' : 'text-slate-600']">
                                {{ app.award.title }}
                            </td>
                            <td :class="['px-6 py-4 text-sm', isDark ? 'text-slate-500' : 'text-slate-500']">
                                {{ new Date(app.created_at).toLocaleDateString() }}
                            </td>
                            <td class="px-6 py-4">
                                <span :class="['px-2.5 py-1 rounded-full text-xs font-bold border capitalize', getStatusColor(app.application_status)]">
                                    {{ app.application_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link 
                                    :href="route('admin.applications.show', app.id)"
                                    :class="['transition-colors inline-flex p-2 rounded-full', isDark ? 'text-slate-500 hover:text-brand-400 hover:bg-slate-700' : 'text-slate-400 hover:text-brand-600 hover:bg-brand-50']"
                                >
                                    <ArrowRight class="w-5 h-5" />
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="!recent_applications?.length">
                            <td colspan="5" :class="['px-6 py-12 text-center', isDark ? 'text-slate-600' : 'text-slate-400']">
                                <FileText class="w-12 h-12 mx-auto mb-3 opacity-20" />
                                No recent applications found
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div :class="['p-4 border-t flex justify-center', isDark ? 'border-slate-700 bg-slate-800' : 'border-slate-100 bg-slate-50']">
                <Link 
                    :href="route('admin.applications.index')"
                    :class="['text-sm font-bold hover:underline', isDark ? 'text-brand-400 hover:text-brand-300' : 'text-brand-600 hover:text-brand-700']"
                >
                    View All Applications
                </Link>
            </div>
        </div>
    </div>
</template>