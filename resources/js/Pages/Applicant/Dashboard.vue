<script setup>
import { defineOptions } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import { Link, Head } from '@inertiajs/vue3';
import { Award, FileText, CheckCircle, XCircle, Clock } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

defineProps({
    stats: Object,
    recent_applications: Array,
    available_awards: Array
});

const getStatusVariant = (status) => {
    const variants = {
        pending: 'warning',
        under_review: 'info',
        accepted: 'success',
        rejected: 'danger'
    };
    return variants[status] || 'default';
};
</script>

<template>
    <Head title="Applicant Dashboard" />
    <div>
        <div class="mb-8 animate-fade-in-up">
            <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">My Dashboard</h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-500']">Track your applications and discover new opportunities.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 stagger-animation">
            <div class="card-premium p-6 group relative overflow-hidden">
                <!-- Shine Effect -->
                <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                
                <div class="relative z-10 flex items-center">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600 mr-4 shadow-sm transition-transform duration-300 group-hover:scale-110">
                        <FileText class="h-6 w-6" />
                    </div>
                    <div>
                        <p :class="['text-xs uppercase tracking-wide font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">Total Applications</p>
                        <p :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">{{ stats.total_applications }}</p>
                    </div>
                </div>
            </div>

            <div class="card-premium p-6 group relative overflow-hidden">
                <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                <div class="relative z-10 flex items-center">
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-600 mr-4 shadow-sm transition-transform duration-300 group-hover:scale-110">
                        <Clock class="h-6 w-6" />
                    </div>
                    <div>
                        <p :class="['text-xs uppercase tracking-wide font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">Pending</p>
                        <p :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">{{ stats.pending }}</p>
                    </div>
                </div>
            </div>

            <div class="card-premium p-6 group relative overflow-hidden">
                <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                <div class="relative z-10 flex items-center">
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 mr-4 shadow-sm transition-transform duration-300 group-hover:scale-110">
                        <CheckCircle class="h-6 w-6" />
                    </div>
                    <div>
                        <p :class="['text-xs uppercase tracking-wide font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">Approved</p>
                        <p :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">{{ stats.approved }}</p>
                    </div>
                </div>
            </div>

            <div class="card-premium p-6 group relative overflow-hidden">
                <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                <div class="relative z-10 flex items-center">
                    <div class="p-3 rounded-xl bg-rose-50 text-rose-600 mr-4 shadow-sm transition-transform duration-300 group-hover:scale-110">
                        <XCircle class="h-6 w-6" />
                    </div>
                    <div>
                        <p :class="['text-xs uppercase tracking-wide font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">Rejected</p>
                        <p :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">{{ stats.rejected }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Applications -->
            <div class="lg:col-span-2">
                <div :class="['rounded-2xl border shadow-sm', isDark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200']">
                    <div :class="['px-6 py-4 border-b', isDark ? 'border-slate-700' : 'border-slate-200']">
                        <div class="flex items-center justify-between">
                            <h2 :class="['text-lg font-semibold', isDark ? 'text-white' : 'text-slate-900']">My Recent Applications</h2>
                            <Link
                                :href="route('applicant.applications.index')"
                                class="text-sm text-brand-500 hover:text-brand-600 font-medium transition-colors"
                            >
                                View all
                            </Link>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3">
                            <div
                                v-for="application in recent_applications"
                                :key="application.id"
                                :class="['p-4 border rounded-xl transition-all hover-lift', isDark ? 'border-slate-700 hover:border-brand-500 hover:bg-slate-700/50' : 'border-slate-200 hover:border-brand-300 hover:bg-brand-50/30']"
                            >
                                <div class="flex items-start justify-between mb-2">
                                    <Link
                                        :href="route('applicant.applications.show', application.id)"
                                        :class="['font-medium transition-colors', isDark ? 'text-white hover:text-brand-400' : 'text-slate-900 hover:text-brand-600']"
                                    >
                                        {{ application.award.title }}
                                    </Link>
                                    <Badge :variant="getStatusVariant(application.application_status)">
                                        {{ application.application_status }}
                                    </Badge>
                                </div>
                                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                    Applied: {{ new Date(application.application_date).toLocaleDateString() }}
                                </p>
                            </div>

                            <div v-if="!recent_applications?.length" class="text-center py-12">
                                <FileText :class="['h-12 w-12 mx-auto mb-3', isDark ? 'text-slate-600' : 'text-slate-400']" />
                                <p :class="isDark ? 'text-slate-400' : 'text-slate-600'">No applications yet</p>
                                <Link :href="route('applicant.awards.index')">
                                    <Button variant="primary" size="sm" class="mt-4">
                                        Browse Awards
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Awards -->
            <div>
                <div :class="['rounded-2xl border shadow-sm', isDark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200']">
                    <div :class="['px-6 py-4 border-b', isDark ? 'border-slate-700' : 'border-slate-200']">
                        <div class="flex items-center justify-between">
                            <h2 :class="['text-lg font-semibold', isDark ? 'text-white' : 'text-slate-900']">Available Awards</h2>
                            <Link
                                :href="route('applicant.awards.index')"
                                class="text-sm text-brand-500 hover:text-brand-600 font-medium transition-colors"
                            >
                                View all
                            </Link>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3">
                            <Link
                                v-for="award in available_awards"
                                :key="award.id"
                                :href="route('applicant.awards.show', award.id)"
                                :class="['block p-3 border rounded-xl transition-all hover-lift', isDark ? 'border-slate-700 hover:border-brand-500 hover:bg-slate-700/50' : 'border-slate-200 hover:border-brand-300 hover:bg-brand-50/30']"
                            >
                                <h3 :class="['font-medium text-sm mb-1', isDark ? 'text-white' : 'text-slate-900']">
                                    {{ award.title }}
                                </h3>
                                <p :class="['text-xs', isDark ? 'text-brand-400' : 'text-brand-600']">
                                    ₦{{ award.amount.toLocaleString() }}
                                </p>
                                <p :class="['text-xs mt-1', isDark ? 'text-slate-500' : 'text-slate-500']">
                                    Deadline: {{ new Date(award.application_end_date).toLocaleDateString() }}
                                </p>
                            </Link>

                            <div v-if="!available_awards?.length" class="text-center py-8">
                                <Award :class="['h-12 w-12 mx-auto mb-3', isDark ? 'text-slate-600' : 'text-slate-400']" />
                                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-600']">No awards available</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>