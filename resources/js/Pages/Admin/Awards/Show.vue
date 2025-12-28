<script setup>
import { defineOptions, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    ArrowLeft, Award, Calendar, DollarSign, Users, Edit, Trash2,
    CheckCircle, Clock, XCircle, AlertCircle, Building2, Mail, Eye
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    award: Object
});

const statusColors = {
    draft: 'warning',
    active: 'success',
    closed: 'default',
    suspended: 'danger'
};

const applicationStatusColors = {
    pending: 'warning',
    approved: 'success',
    rejected: 'danger'
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
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

const deleteAward = () => {
    if (confirm('Are you sure you want to delete this award?')) {
        router.delete(route('admin.awards.destroy', props.award.id));
    }
};
</script>

<template>
    <div class="space-y-8">
        <!-- Back Button -->
        <Link 
            :href="route('admin.awards.index')" 
            :class="['inline-flex items-center gap-2 text-sm font-medium transition-colors', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']"
        >
            <ArrowLeft class="w-4 h-4" />
            Back to Awards
        </Link>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <Badge :variant="statusColors[award.status]">{{ award.status }}</Badge>
                    <Badge variant="default">{{ award.category?.toUpperCase() }}</Badge>
                </div>
                <h1 :class="['text-4xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                    {{ award.title }}
                </h1>
                <div v-if="award.sponsor" :class="['flex items-center gap-2 mt-2', isDark ? 'text-slate-400' : 'text-slate-500']">
                    <Building2 class="w-4 h-4" />
                    Sponsored by {{ award.sponsor.user?.name }}
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <Link :href="route('admin.awards.edit', award.id)" class="btn-primary">
                    <Edit class="w-5 h-5" /> Edit
                </Link>
                <button 
                    v-if="!award.applications?.length"
                    @click="deleteAward"
                    :class="['px-4 py-2 rounded-xl font-semibold transition-colors', isDark ? 'bg-red-900/30 text-red-400 hover:bg-red-900/50' : 'bg-red-50 text-red-600 hover:bg-red-100']"
                >
                    <Trash2 class="w-5 h-5 inline mr-1" /> Delete
                </button>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h2 :class="['text-lg font-semibold mb-4', isDark ? 'text-white' : 'text-slate-900']">Description</h2>
                    <p :class="['leading-relaxed', isDark ? 'text-slate-300' : 'text-slate-600']">{{ award.description }}</p>
                </div>

                <!-- Applications -->
                <div :class="['rounded-2xl overflow-hidden', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <div :class="['px-8 py-6 border-b', isDark ? 'border-slate-700' : 'border-slate-200']">
                        <h2 :class="['text-lg font-semibold flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                            <Users class="w-5 h-5 text-brand-500" />
                            Applications ({{ award.applications?.length || 0 }})
                        </h2>
                    </div>

                    <div v-if="award.applications?.length" class="divide-y" :class="isDark ? 'divide-slate-700' : 'divide-slate-100'">
                        <div 
                            v-for="application in award.applications" 
                            :key="application.id"
                            :class="['px-8 py-4 flex items-center justify-between', isDark ? 'hover:bg-slate-800' : 'hover:bg-slate-50']"
                        >
                            <div class="flex items-center gap-4">
                                <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm', isDark ? 'bg-brand-600/20 text-brand-400' : 'bg-brand-100 text-brand-600']">
                                    {{ application.applicant?.user?.name?.charAt(0) || '?' }}
                                </div>
                                <div>
                                    <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">
                                        {{ application.applicant?.user?.name || 'Unknown' }}
                                    </p>
                                    <p :class="['text-sm flex items-center gap-1', isDark ? 'text-slate-400' : 'text-slate-500']">
                                        <Mail class="w-3 h-3" />
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <Badge :variant="applicationStatusColors[application.status] || 'default'">
                                    {{ application.status }}
                                </Badge>
                                <Link 
                                    :href="route('admin.applications.show', application.id)"
                                    :class="['p-2 rounded-lg transition-colors', isDark ? 'bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white' : 'bg-white border hover:bg-slate-50 text-slate-600 hover:text-slate-900']"
                                    title="Review Application"
                                >
                                    <Eye class="w-4 h-4" />
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div v-else class="px-8 py-12 text-center">
                        <Users :class="['w-12 h-12 mx-auto mb-3', isDark ? 'text-slate-600' : 'text-slate-300']" />
                        <p :class="['font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">No applications yet</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Stats Card -->
                <div :class="['p-6 rounded-2xl', isDark ? 'bg-gradient-to-br from-brand-900/50 to-slate-900 border border-brand-800' : 'bg-gradient-to-br from-brand-50 to-white border border-brand-200']">
                    <p :class="['text-sm uppercase tracking-wider mb-2', isDark ? 'text-brand-300' : 'text-brand-600']">Award Value</p>
                    <p class="text-4xl font-bold font-display text-brand-600">{{ formatAmount(award.amount) }}</p>
                    <p :class="['text-sm mt-2', isDark ? 'text-slate-400' : 'text-slate-500']">
                        {{ award.number_of_awards }} award{{ award.number_of_awards > 1 ? 's' : '' }} available
                    </p>
                </div>

                <!-- Timeline -->
                <div :class="['p-6 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h3 :class="['text-lg font-semibold mb-4', isDark ? 'text-white' : 'text-slate-900']">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <Calendar :class="['w-5 h-5 shrink-0 mt-0.5', isDark ? 'text-brand-400' : 'text-brand-600']" />
                            <div>
                                <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Opens</p>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ formatDate(award.application_start_date) }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <Clock :class="['w-5 h-5 shrink-0 mt-0.5', isDark ? 'text-red-400' : 'text-red-600']" />
                            <div>
                                <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Deadline</p>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ formatDate(award.application_end_date) }}</p>
                            </div>
                        </div>
                        <div v-if="award.announcement_date" class="flex items-start gap-3">
                            <Award :class="['w-5 h-5 shrink-0 mt-0.5', isDark ? 'text-accent-400' : 'text-accent-600']" />
                            <div>
                                <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Results</p>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ formatDate(award.announcement_date) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eligibility -->
                <div v-if="award.eligibility_criteria?.length" :class="['p-6 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h3 :class="['text-lg font-semibold mb-4', isDark ? 'text-white' : 'text-slate-900']">Eligibility</h3>
                    <ul class="space-y-2">
                        <li v-for="(criteria, i) in award.eligibility_criteria" :key="i" :class="['flex items-start gap-2 text-sm', isDark ? 'text-slate-300' : 'text-slate-600']">
                            <CheckCircle class="w-4 h-4 text-brand-500 shrink-0 mt-0.5" />
                            <span>{{ criteria }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
