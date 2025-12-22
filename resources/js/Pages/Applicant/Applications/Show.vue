<script setup>
import { defineOptions } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    ArrowLeft, Award, CheckCircle, XCircle, Clock, Calendar, 
    FileText, Building2, DollarSign, Eye
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    application: Object
});

const statusColors = {
    pending: 'warning',
    approved: 'success',
    rejected: 'danger',
    under_review: 'info'
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' 
    });
};

const formatAmount = (amount) => {
    return new Intl.NumberFormat('en-NG', { 
        style: 'currency', currency: 'NGN', maximumFractionDigits: 0 
    }).format(amount);
};
</script>

<template>
    <div class="space-y-8">
        <Link :href="route('applicant.applications.index')" :class="['inline-flex items-center gap-2 text-sm font-medium', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']">
            <ArrowLeft class="w-4 h-4" /> Back to Applications
        </Link>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <Badge :variant="statusColors[application.application_status]" class="mb-3 flex items-center gap-1 w-fit">
                    <CheckCircle v-if="application.application_status === 'approved'" class="w-3 h-3" />
                    <XCircle v-else-if="application.application_status === 'rejected'" class="w-3 h-3" />
                    <Clock v-else class="w-3 h-3" />
                    {{ application.application_status }}
                </Badge>
                <h1 :class="['text-4xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                    {{ application.award?.title }}
                </h1>
                <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-500']">
                    Application {{ application.application_number }}
                </p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Timeline -->
                <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h2 :class="['text-lg font-semibold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <Clock class="w-5 h-5 text-brand-500" /> Application Status
                    </h2>

                    <div class="relative">
                        <div :class="['absolute left-3 top-0 bottom-0 w-0.5', isDark ? 'bg-slate-700' : 'bg-slate-200']" />

                        <div class="space-y-6">
                            <!-- Applied -->
                            <div class="relative flex gap-4 pl-8">
                                <div class="absolute left-0 top-0 w-6 h-6 rounded-full bg-brand-600 flex items-center justify-center">
                                    <CheckCircle class="w-3 h-3 text-white" />
                                </div>
                                <div>
                                    <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">Application Submitted</p>
                                    <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                        {{ formatDate(application.application_date) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Under Review -->
                            <div class="relative flex gap-4 pl-8">
                                <div :class="[
                                    'absolute left-0 top-0 w-6 h-6 rounded-full flex items-center justify-center',
                                    application.application_status !== 'pending' ? 'bg-brand-600' : isDark ? 'bg-slate-700' : 'bg-slate-200'
                                ]">
                                    <Eye :class="['w-3 h-3', application.application_status !== 'pending' ? 'text-white' : isDark ? 'text-slate-500' : 'text-slate-400']" />
                                </div>
                                <div>
                                    <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">Under Review</p>
                                    <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                        {{ application.application_status === 'pending' ? 'Awaiting review' : 'Reviewed' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Decision -->
                            <div class="relative flex gap-4 pl-8">
                                <div :class="[
                                    'absolute left-0 top-0 w-6 h-6 rounded-full flex items-center justify-center',
                                    application.application_status === 'approved' ? 'bg-emerald-600' :
                                    application.application_status === 'rejected' ? 'bg-red-600' :
                                    isDark ? 'bg-slate-700' : 'bg-slate-200'
                                ]">
                                    <CheckCircle v-if="application.application_status === 'approved'" class="w-3 h-3 text-white" />
                                    <XCircle v-else-if="application.application_status === 'rejected'" class="w-3 h-3 text-white" />
                                    <Clock v-else :class="['w-3 h-3', isDark ? 'text-slate-500' : 'text-slate-400']" />
                                </div>
                                <div>
                                    <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                                        {{ application.application_status === 'approved' ? 'Approved!' :
                                           application.application_status === 'rejected' ? 'Not Selected' : 'Decision Pending' }}
                                    </p>
                                    <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                        {{ application.reviewed_at ? formatDate(application.reviewed_at) : 'Check back later' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div v-if="application.supporting_documents" :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h2 :class="['text-lg font-semibold mb-4 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <FileText class="w-5 h-5 text-brand-500" /> Supporting Documents
                    </h2>
                    <p :class="['whitespace-pre-wrap', isDark ? 'text-slate-300' : 'text-slate-600']">
                        {{ application.supporting_documents }}
                    </p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Award Card -->
                <div :class="['p-6 rounded-2xl', isDark ? 'bg-gradient-to-br from-brand-900/50 to-slate-900 border border-brand-800' : 'bg-gradient-to-br from-brand-50 to-white border border-brand-200']">
                    <div class="flex items-center gap-3 mb-4">
                        <Award :class="['w-8 h-8', isDark ? 'text-brand-400' : 'text-brand-600']" />
                        <div>
                            <p :class="['text-sm', isDark ? 'text-brand-300' : 'text-brand-600']">Award Value</p>
                            <p class="text-2xl font-bold text-brand-600">{{ formatAmount(application.award?.amount || 0) }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Category</p>
                            <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">
                                {{ application.award?.category?.toUpperCase() }}
                            </p>
                        </div>
                        <div v-if="application.award?.sponsor">
                            <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Sponsor</p>
                            <p :class="['font-medium flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                <Building2 class="w-4 h-4" />
                                {{ application.award.sponsor.user?.name }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Back to Awards -->
                <Link :href="route('applicant.awards.index')" :class="['block p-4 rounded-xl text-center font-medium transition-colors', isDark ? 'bg-slate-800 text-brand-400 hover:bg-slate-700' : 'bg-brand-50 text-brand-600 hover:bg-brand-100']">
                    Browse More Awards
                </Link>
            </div>
        </div>
    </div>
</template>
