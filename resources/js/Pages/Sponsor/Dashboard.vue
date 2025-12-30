<script setup>
import { defineOptions } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import { Link, Head } from '@inertiajs/vue3';
import { Award, FileText, CheckCircle, AlertCircle } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

defineProps({
    sponsor: Object,
    stats: Object,
    recent_awards: Array,
    verification_status: String
});

const getVerificationVariant = (status) => {
    const variants = {
        pending: 'warning',
        verified: 'success',
        rejected: 'danger'
    };
    return variants[status] || 'default';
};
</script>

<template>
    <Head title="Sponsor Dashboard" />
    <div>
        <!-- Verification Alert -->
        <div
            v-if="verification_status !== 'verified'"
            class="mb-6 p-4 rounded-xl border animate-fade-in"
            :class="{
                'bg-amber-50 border-amber-200': verification_status === 'pending' && !isDark,
                'bg-amber-950/30 border-amber-800': verification_status === 'pending' && isDark,
                'bg-rose-50 border-rose-200': verification_status === 'rejected' && !isDark,
                'bg-rose-950/30 border-rose-800': verification_status === 'rejected' && isDark
            }"
        >
            <div class="flex items-start">
                <AlertCircle
                    class="h-5 w-5 flex-shrink-0 mr-3"
                    :class="{
                        'text-amber-600': verification_status === 'pending',
                        'text-rose-600': verification_status === 'rejected'
                    }"
                />
                <div class="flex-1">
                    <h3 class="font-medium" :class="{
                        'text-amber-800': verification_status === 'pending' && !isDark,
                        'text-amber-300': verification_status === 'pending' && isDark,
                        'text-rose-800': verification_status === 'rejected' && !isDark,
                        'text-rose-300': verification_status === 'rejected' && isDark
                    }">
                        {{ verification_status === 'pending' ? 'Account Pending Verification' : 'Account Verification Rejected' }}
                    </h3>
                    <p class="text-sm mt-1" :class="{
                        'text-amber-700': verification_status === 'pending' && !isDark,
                        'text-amber-400': verification_status === 'pending' && isDark,
                        'text-rose-700': verification_status === 'rejected' && !isDark,
                        'text-rose-400': verification_status === 'rejected' && isDark
                    }">
                        {{ verification_status === 'pending' 
                            ? 'Your sponsor account is being reviewed by our team. You can create awards once verified.' 
                            : 'Your sponsor account was rejected. Please contact support for more information.' 
                        }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mb-8 animate-fade-in-up">
            <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">Sponsor Dashboard</h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-500']">Manage your awards and track applications.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 stagger-animation">
            <div class="card-premium p-6 group relative overflow-hidden">
                <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                <div class="relative z-10 flex items-center">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600 mr-4 shadow-sm transition-transform duration-300 group-hover:scale-110">
                        <Award class="h-6 w-6" />
                    </div>
                    <div>
                        <p :class="['text-xs uppercase tracking-wide font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">Total Awards</p>
                        <p :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">{{ stats.total_awards }}</p>
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
                        <p :class="['text-xs uppercase tracking-wide font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">Active Awards</p>
                        <p :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">{{ stats.active_awards }}</p>
                    </div>
                </div>
            </div>

            <div class="card-premium p-6 group relative overflow-hidden">
                <div class="absolute inset-0 gradient-shine opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                <div class="relative z-10 flex items-center">
                    <div class="p-3 rounded-xl bg-purple-50 text-purple-600 mr-4 shadow-sm transition-transform duration-300 group-hover:scale-110">
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
                        <FileText class="h-6 w-6" />
                    </div>
                    <div>
                        <p :class="['text-xs uppercase tracking-wide font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">Pending Review</p>
                        <p :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">{{ stats.pending_applications }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Awards List -->
        <div :class="['rounded-2xl border shadow-sm', isDark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200']">
            <div :class="['px-6 py-4 border-b', isDark ? 'border-slate-700' : 'border-slate-200']">
                <div class="flex items-center justify-between">
                    <h2 :class="['text-lg font-semibold', isDark ? 'text-white' : 'text-slate-900']">My Awards</h2>
                    <Link :href="route('sponsor.awards.create')">
                        <Button
                            variant="primary"
                            size="sm"
                            :disabled="verification_status !== 'verified'"
                        >
                            Create Award
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-3">
                    <div
                        v-for="award in recent_awards"
                        :key="award.id"
                        :class="['p-4 border rounded-xl transition-all hover-lift', isDark ? 'border-slate-700 hover:border-brand-500 hover:bg-slate-700/50' : 'border-slate-200 hover:border-brand-300 hover:bg-brand-50/30']"
                    >
                        <div class="flex items-start justify-between mb-2">
                            <Link
                                :href="route('sponsor.awards.show', award.id)"
                                :class="['font-medium transition-colors', isDark ? 'text-white hover:text-brand-400' : 'text-slate-900 hover:text-brand-600']"
                            >
                                {{ award.title }}
                            </Link>
                            <Badge :variant="award.status === 'active' ? 'success' : 'warning'">
                                {{ award.status }}
                            </Badge>
                        </div>
                        <div :class="['flex items-center justify-between text-sm', isDark ? 'text-slate-400' : 'text-slate-600']">
                            <span>₦{{ award.amount.toLocaleString() }}</span>
                            <span>{{ award.applications_count }} applications</span>
                        </div>
                    </div>

                    <div v-if="!recent_awards?.length" class="text-center py-12">
                        <Award :class="['h-12 w-12 mx-auto mb-3', isDark ? 'text-slate-600' : 'text-slate-400']" />
                        <p :class="['mb-4', isDark ? 'text-slate-400' : 'text-slate-600']">No awards created yet</p>
                        <Link :href="route('sponsor.awards.create')">
                            <Button
                                variant="primary"
                                :disabled="verification_status !== 'verified'"
                            >
                                Create Your First Award
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>