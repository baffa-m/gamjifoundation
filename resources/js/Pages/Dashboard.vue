<script setup>
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Award, FileText, Calendar, ArrowRight, Clock } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';

const page = usePage();
const user = page.props.auth.user;
const { isDark } = useTheme();

const stats = [
    { label: 'Active Applications', value: '2', icon: FileText, color: 'text-blue-600', bg: 'bg-blue-100' },
    { label: 'Saved Awards', value: '5', icon: Award, color: 'text-brand-600', bg: 'bg-brand-100' },
    { label: 'Upcoming Events', value: '1', icon: Calendar, color: 'text-purple-600', bg: 'bg-purple-100' },
];

const recentActivity = [
    { title: 'Application Submitted', desc: 'Gamji Scholarship 2025', time: '2 days ago', icon: FileText },
    { title: 'Profile Updated', desc: 'Added new academic records', time: '1 week ago', icon: Clock },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- Welcome Section -->
        <div class="mb-8 animate-fade-in-up">
            <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                Welcome back, {{ user.name }}!
            </h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-500']">Here's what's happening with your applications today.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 stagger-animation">
            <div 
                v-for="stat in stats" 
                :key="stat.label" 
                class="card-premium p-6 group relative overflow-hidden"
            >
                <!-- Shine Effect -->
                <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div :class="['p-3 rounded-xl shadow-sm transition-transform duration-300 group-hover:scale-110', stat.bg, stat.color]">
                            <component :is="stat.icon" class="w-6 h-6" />
                        </div>
                        <span :class="['text-4xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">{{ stat.value }}</span>
                    </div>
                    <h3 :class="['font-medium text-sm uppercase tracking-wide', isDark ? 'text-slate-400' : 'text-slate-500']">{{ stat.label }}</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recommended Awards -->
                <div :class="['rounded-2xl p-6 shadow-sm border', isDark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100']">
                    <div class="flex justify-between items-center mb-6">
                        <h2 :class="['text-xl font-bold', isDark ? 'text-white' : 'text-slate-900']">Recommended for You</h2>
                        <a href="#" class="text-brand-500 hover:text-brand-600 font-medium text-sm flex items-center gap-1 transition-colors">
                            View All <ArrowRight class="w-4 h-4" />
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        <div :class="['group p-4 rounded-xl border transition-all cursor-pointer hover-lift', isDark ? 'border-slate-700 hover:border-brand-500 hover:bg-slate-700/50' : 'border-slate-100 hover:border-brand-200 hover:bg-brand-50/50']">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="inline-block px-2 py-1 rounded-md bg-brand-100 text-brand-700 text-xs font-bold mb-2">Scholarship</span>
                                    <h3 :class="['font-bold transition-colors', isDark ? 'text-white group-hover:text-brand-400' : 'text-slate-900 group-hover:text-brand-700']">STEM Excellence Grant</h3>
                                    <p :class="['text-sm mt-1', isDark ? 'text-slate-400' : 'text-slate-500']">For undergraduate students in Engineering</p>
                                </div>
                                <span :class="['font-bold', isDark ? 'text-white' : 'text-slate-900']">₦150,000</span>
                            </div>
                        </div>
                        
                        <div :class="['group p-4 rounded-xl border transition-all cursor-pointer hover-lift', isDark ? 'border-slate-700 hover:border-brand-500 hover:bg-slate-700/50' : 'border-slate-100 hover:border-brand-200 hover:bg-brand-50/50']">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="inline-block px-2 py-1 rounded-md bg-purple-100 text-purple-700 text-xs font-bold mb-2">Mentorship</span>
                                    <h3 :class="['font-bold transition-colors', isDark ? 'text-white group-hover:text-brand-400' : 'text-slate-900 group-hover:text-brand-700']">Future Leaders Program</h3>
                                    <p :class="['text-sm mt-1', isDark ? 'text-slate-400' : 'text-slate-500']">6-month mentorship with industry leaders</p>
                                </div>
                                <span :class="['font-bold', isDark ? 'text-white' : 'text-slate-900']">Free</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Area -->
            <div class="space-y-8">
                <!-- Recent Activity -->
                <div :class="['rounded-2xl p-6 shadow-sm border', isDark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-100']">
                    <h2 :class="['text-xl font-bold mb-6', isDark ? 'text-white' : 'text-slate-900']">Recent Activity</h2>
                    <div class="space-y-6">
                        <div v-for="(activity, i) in recentActivity" :key="i" class="flex gap-4">
                            <div class="mt-1">
                                <div :class="['w-8 h-8 rounded-full flex items-center justify-center', isDark ? 'bg-slate-700 text-slate-400' : 'bg-slate-100 text-slate-500']">
                                    <component :is="activity.icon" class="w-4 h-4" />
                                </div>
                            </div>
                            <div>
                                <h4 :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ activity.title }}</h4>
                                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">{{ activity.desc }}</p>
                                <span :class="['text-xs mt-1 block', isDark ? 'text-slate-500' : 'text-slate-400']">{{ activity.time }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Completion -->
                <div class="bg-gradient-to-br from-brand-600 to-brand-700 rounded-2xl p-6 text-white relative overflow-hidden shadow-glow">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-400/20 rounded-full blur-2xl -mr-10 -mt-10"></div>
                    
                    <h3 class="font-bold text-lg mb-2 relative z-10">Complete Your Profile</h3>
                    <p class="text-brand-100 text-sm mb-4 relative z-10">You are 80% there! Add your transcript to unlock more opportunities.</p>
                    
                    <div class="w-full bg-brand-900/50 rounded-full h-2 mb-4 relative z-10">
                        <div class="bg-brand-300 h-2 rounded-full shadow-glow" style="width: 80%"></div>
                    </div>
                    
                    <button class="w-full py-2.5 bg-white text-brand-700 rounded-lg font-bold text-sm hover:bg-brand-50 transition-all hover:shadow-md relative z-10">
                        Update Profile
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
