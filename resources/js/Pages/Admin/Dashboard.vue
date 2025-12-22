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
    pending_sponsors: Array
});

const statCards = [
    {
        label: 'Total Awards',
        value: (stats) => stats.total_awards,
        change: '+12.5%',
        trend: 'up',
        icon: Award,
        color: 'text-blue-600',
        bg: 'bg-blue-50',
        border: 'border-blue-100'
    },
    {
        label: 'Pending Reviews',
        value: (stats) => stats.pending_applications,
        change: '-2.4%',
        trend: 'down',
        icon: FileText,
        color: 'text-amber-600',
        bg: 'bg-amber-50',
        border: 'border-amber-100'
    },
    {
        label: 'Total Applicants',
        value: (stats) => stats.total_applicants,
        change: '+8.2%',
        trend: 'up',
        icon: Users,
        color: 'text-emerald-600',
        bg: 'bg-emerald-50',
        border: 'border-emerald-100'
    },
    {
        label: 'Pending Sponsors',
        value: (stats) => stats.pending_sponsors,
        change: '+4.1%',
        trend: 'up',
        icon: TrendingUp,
        color: 'text-purple-600',
        bg: 'bg-purple-50',
        border: 'border-purple-100'
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

// Mock data for chart - in a real app this would come from props
const chartData = [40, 65, 45, 80, 55, 90, 60, 75, 50, 85, 65, 95];
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
                class="card-premium p-6 group relative overflow-hidden"
            >
                <!-- Shine Effect -->
                <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div :class="[stat.bg, stat.color, 'w-12 h-12 rounded-xl flex items-center justify-center transition-transform duration-300 group-hover:scale-110 shadow-sm']">
                            <component :is="stat.icon" class="w-6 h-6" />
                        </div>
                        <div :class="['text-sm font-semibold px-2 py-1 rounded-full', stat.trend === 'up' ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50']">
                            {{ stat.change }}
                        </div>
                    </div>
                    <div class="text-3xl font-bold font-display text-slate-900 mb-1">
                        {{ stat.value(stats) }}
                    </div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">
                        {{ stat.label }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Charts & Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Bar Chart -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-slate-800 text-lg">Application Analytics</h3>
                    <select class="text-xs border-none bg-slate-50 rounded-lg text-slate-500 outline-none p-2 font-medium hover:bg-slate-100 cursor-pointer transition-colors">
                        <option>Last 6 Months</option>
                        <option>This Year</option>
                    </select>
                </div>
                
                <div class="h-64 flex items-end justify-between gap-2 md:gap-4 px-2">
                    <div v-for="(h, i) in chartData" :key="i" class="w-full flex flex-col justify-end group cursor-pointer">
                        <div class="w-full bg-slate-100 rounded-t-sm relative overflow-hidden group-hover:bg-brand-50 transition-colors" :style="{ height: `${h}%` }">
                            <div class="absolute bottom-0 left-0 w-full bg-brand-500/80 group-hover:bg-brand-600 transition-colors rounded-t-md" :style="{ height: `${h - 20}%` }"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mt-4 text-xs text-slate-400 font-medium px-1 uppercase tracking-wider">
                    <span>Jan</span><span>Mar</span><span>May</span><span>Jul</span><span>Sep</span><span>Nov</span>
                </div>
            </div>

            <!-- Live Activity List -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-slate-800 text-lg">Live Activity</h3>
                    <button class="text-slate-400 hover:text-brand-600 transition">
                        <MoreVertical class="w-5 h-5" />
                    </button>
                </div>
                
                <div class="flex-1 space-y-6 relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-2.5 top-2 bottom-2 w-0.5 bg-slate-100"></div>
                    
                    <div v-for="i in 4" :key="i" class="flex gap-4 relative group">
                        <div class="w-5 h-5 rounded-full bg-white border-2 border-brand-500 z-10 mt-1 group-hover:scale-110 transition-transform shadow-sm"></div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-800 font-medium group-hover:text-brand-700 transition-colors">New Application Submitted</p>
                            <p class="text-xs text-slate-500 mt-0.5">Student #293{{i}} applied for JAMB Grant</p>
                            <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1">
                                <Clock class="w-3 h-3" /> Just now
                            </p>
                        </div>
                    </div>
                </div>
                <button class="w-full mt-6 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                    View All Logs
                </button>
            </div>
        </div>

        <!-- 3. Recent Applications Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between md:items-center gap-4 bg-slate-50/30">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <FileText class="w-5 h-5 text-brand-600" />
                    Recent Applications
                </h3>
                <div class="flex gap-3">
                    <button class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition flex items-center gap-2">
                        <Filter class="w-4 h-4" /> Filter
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition shadow-lg shadow-brand-600/20 flex items-center gap-2">
                        <Download class="w-4 h-4" /> Export CSV
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-semibold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Award Type</th>
                            <th class="px-6 py-4">Date Applied</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr 
                            v-for="app in recent_applications" 
                            :key="app.id" 
                            class="hover:bg-slate-50/80 transition-colors group"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-bold border border-brand-200">
                                        {{ app.applicant.full_name.charAt(0) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 text-sm group-hover:text-brand-700 transition-colors">
                                            {{ app.applicant.full_name }}
                                        </div>
                                        <div class="text-xs text-slate-500">ID: #{{ app.id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                {{ app.award.title }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
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
                                    class="text-slate-400 hover:text-brand-600 transition-colors inline-flex p-2 hover:bg-brand-50 rounded-full"
                                >
                                    <ArrowRight class="w-5 h-5" />
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="!recent_applications?.length">
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <FileText class="w-12 h-12 mx-auto mb-3 opacity-20" />
                                No recent applications found
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-center">
                <Link 
                    :href="route('admin.applications.index')"
                    class="text-sm font-bold text-brand-600 hover:text-brand-700 hover:underline"
                >
                    View All Applications
                </Link>
            </div>
        </div>
    </div>
</template>