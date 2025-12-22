<script setup>
import { defineOptions } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    FileText, Award, CheckCircle, XCircle, Clock, Eye, ArrowRight, Filter 
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    applications: Object,
    filters: Object
});

const statusColors = {
    pending: 'warning',
    approved: 'success',
    rejected: 'danger',
    under_review: 'info'
};

const statusIcons = {
    pending: Clock,
    approved: CheckCircle,
    rejected: XCircle,
    under_review: Eye
};

const statuses = [
    { value: '', label: 'All' },
    { value: 'pending', label: 'Pending' },
    { value: 'approved', label: 'Approved' },
    { value: 'rejected', label: 'Rejected' }
];

const filterByStatus = (status) => {
    router.get(route('applicant.applications.index'), { status }, { preserveState: true, replace: true });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <div class="space-y-8">
        <!-- Header -->
        <div class="animate-fade-in-up">
            <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                My Applications
            </h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">
                Track the status of your scholarship applications
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button
                v-for="status in statuses"
                :key="status.value"
                @click="filterByStatus(status.value)"
                :class="[
                    'p-4 rounded-xl text-left transition-all',
                    filters?.status === status.value || (!filters?.status && status.value === '')
                        ? 'ring-2 ring-brand-500'
                        : '',
                    isDark ? 'bg-slate-800 hover:bg-slate-700' : 'bg-white border border-slate-200 hover:border-slate-300'
                ]"
            >
                <p :class="['text-sm uppercase tracking-wider mb-1', isDark ? 'text-slate-400' : 'text-slate-500']">
                    {{ status.label }}
                </p>
                <p :class="['text-2xl font-bold', isDark ? 'text-white' : 'text-slate-900']">
                    {{ status.value ? applications?.data?.filter(a => a.application_status === status.value).length || 0 : applications?.data?.length || 0 }}
                </p>
            </button>
        </div>

        <!-- Applications List -->
        <div v-if="applications?.data?.length" class="space-y-4">
            <Link
                v-for="app in applications.data"
                :key="app.id"
                :href="route('applicant.applications.show', app.id)"
                :class="[
                    'block p-6 rounded-2xl transition-all hover:-translate-y-1',
                    isDark ? 'bg-slate-800/50 border border-slate-700 hover:border-slate-600' : 'bg-white border border-slate-200 shadow-sm hover:shadow-md'
                ]"
            >
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div :class="['w-12 h-12 rounded-xl flex items-center justify-center shrink-0', isDark ? 'bg-brand-600/20 text-brand-400' : 'bg-brand-100 text-brand-600']">
                            <Award class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 :class="['text-lg font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                                {{ app.award?.title || 'Award' }}
                            </h3>
                            <div :class="['flex flex-wrap items-center gap-3 mt-1 text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                <span>{{ app.application_number }}</span>
                                <span>•</span>
                                <span>Applied {{ formatDate(app.application_date) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Badge :variant="statusColors[app.application_status]" class="flex items-center gap-1">
                            <component :is="statusIcons[app.application_status]" class="w-3 h-3" />
                            {{ app.application_status }}
                        </Badge>
                        <ArrowRight :class="['w-5 h-5', isDark ? 'text-slate-500' : 'text-slate-400']" />
                    </div>
                </div>
            </Link>
        </div>

        <!-- Empty State -->
        <div v-else :class="['text-center py-16 rounded-2xl', isDark ? 'bg-slate-800/50' : 'bg-slate-50']">
            <FileText :class="['w-16 h-16 mx-auto mb-4', isDark ? 'text-slate-600' : 'text-slate-300']" />
            <h3 :class="['text-xl font-semibold mb-2', isDark ? 'text-white' : 'text-slate-900']">No applications yet</h3>
            <p :class="['mb-6', isDark ? 'text-slate-400' : 'text-slate-600']">Browse available awards and submit your first application.</p>
            <Link :href="route('applicant.awards.index')" class="btn-primary inline-flex">
                <Award class="w-5 h-5" /> Browse Awards
            </Link>
        </div>

        <!-- Pagination -->
        <div v-if="applications?.links?.length > 3" class="flex justify-center gap-2">
            <Link
                v-for="link in applications.links"
                :key="link.label"
                :href="link.url || '#'"
                :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium',
                    link.active ? 'bg-brand-600 text-white' : link.url ? isDark ? 'bg-slate-800 text-slate-300' : 'bg-white text-slate-600 border border-slate-200' : 'opacity-50'
                ]"
                v-html="link.label"
            />
        </div>
    </div>
</template>
