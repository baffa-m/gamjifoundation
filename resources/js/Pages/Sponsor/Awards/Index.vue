<script setup>
import { defineOptions, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    Award, Plus, Search, Filter, Eye, Edit, Trash2, 
    CheckCircle, Clock, XCircle, AlertCircle, Users 
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    awards: Object,
    filters: Object,
    sponsor: Object
});

const statusColors = {
    draft: 'warning',
    active: 'success',
    closed: 'default',
    suspended: 'danger'
};

const statusIcons = {
    draft: Clock,
    active: CheckCircle,
    closed: XCircle,
    suspended: AlertCircle
};

const filterByStatus = (status) => {
    router.get(route('sponsor.awards.index'), {
        status: status
    }, { preserveState: true, replace: true });
};

const formatAmount = (amount) => {
    return new Intl.NumberFormat('en-NG', { 
        style: 'currency', 
        currency: 'NGN',
        maximumFractionDigits: 0
    }).format(amount);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        month: 'short', 
        day: 'numeric', 
        year: 'numeric' 
    });
};

const deleteAward = (award) => {
    if (confirm('Are you sure you want to delete this award?')) {
        router.delete(route('sponsor.awards.destroy', award.id));
    }
};
</script>

<template>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="animate-fade-in-up">
                <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                    My Awards
                </h1>
                <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">
                    Manage your scholarship programs and track applications
                </p>
            </div>

            <Link 
                v-if="sponsor?.verification_status === 'verified'"
                :href="route('sponsor.awards.create')"
                class="btn-primary"
            >
                <Plus class="w-5 h-5" />
                Create New Award
            </Link>
            <div v-else :class="['flex items-center gap-2 px-4 py-2 rounded-xl text-sm', isDark ? 'bg-amber-900/20 border border-amber-800 text-amber-400' : 'bg-amber-50 border border-amber-200 text-amber-700']">
                <AlertCircle class="w-4 h-4" />
                Verify your account to create awards
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-fade-in-up" style="animation-delay: 0.1s;">
            <button
                @click="filterByStatus('')"
                :class="[
                    'p-4 rounded-xl text-left transition-all',
                    !filters?.status
                        ? 'ring-2 ring-brand-500'
                        : '',
                    isDark ? 'bg-slate-800 hover:bg-slate-700' : 'bg-white border border-slate-200 hover:border-slate-300'
                ]"
            >
                <Award :class="['w-6 h-6 mb-2', isDark ? 'text-brand-400' : 'text-brand-600']" />
                <p :class="['text-2xl font-bold', isDark ? 'text-white' : 'text-slate-900']">
                    {{ awards?.data?.length || 0 }}
                </p>
                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">Total Awards</p>
            </button>
            
            <button
                @click="filterByStatus('draft')"
                :class="[
                    'p-4 rounded-xl text-left transition-all',
                    filters?.status === 'draft'
                        ? 'ring-2 ring-amber-500'
                        : '',
                    isDark ? 'bg-slate-800 hover:bg-slate-700' : 'bg-white border border-slate-200 hover:border-slate-300'
                ]"
            >
                <Clock :class="['w-6 h-6 mb-2', isDark ? 'text-amber-400' : 'text-amber-600']" />
                <p :class="['text-2xl font-bold', isDark ? 'text-white' : 'text-slate-900']">
                    {{ awards?.data?.filter(a => a.status === 'draft').length || 0 }}
                </p>
                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">Drafts</p>
            </button>

            <button
                @click="filterByStatus('active')"
                :class="[
                    'p-4 rounded-xl text-left transition-all',
                    filters?.status === 'active'
                        ? 'ring-2 ring-emerald-500'
                        : '',
                    isDark ? 'bg-slate-800 hover:bg-slate-700' : 'bg-white border border-slate-200 hover:border-slate-300'
                ]"
            >
                <CheckCircle :class="['w-6 h-6 mb-2', isDark ? 'text-emerald-400' : 'text-emerald-600']" />
                <p :class="['text-2xl font-bold', isDark ? 'text-white' : 'text-slate-900']">
                    {{ awards?.data?.filter(a => a.status === 'active').length || 0 }}
                </p>
                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">Active</p>
            </button>

            <div :class="['p-4 rounded-xl', isDark ? 'bg-slate-800' : 'bg-white border border-slate-200']">
                <Users :class="['w-6 h-6 mb-2', isDark ? 'text-blue-400' : 'text-blue-600']" />
                <p :class="['text-2xl font-bold', isDark ? 'text-white' : 'text-slate-900']">
                    {{ awards?.data?.reduce((sum, a) => sum + (a.applications_count || 0), 0) || 0 }}
                </p>
                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">Applications</p>
            </div>
        </div>

        <!-- Awards Table -->
        <div v-if="awards?.data?.length" :class="['rounded-2xl overflow-hidden', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="isDark ? 'bg-slate-800' : 'bg-slate-50'">
                        <tr>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Award</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Amount</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Status</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Applications</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Deadline</th>
                            <th :class="['px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Actions</th>
                        </tr>
                    </thead>
                    <tbody :class="['divide-y', isDark ? 'divide-slate-700' : 'divide-slate-100']">
                        <tr v-for="award in awards.data" :key="award.id" :class="['transition-colors', isDark ? 'hover:bg-slate-800/50' : 'hover:bg-slate-50']">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-10 h-10 rounded-lg flex items-center justify-center shrink-0', isDark ? 'bg-brand-600/20 text-brand-400' : 'bg-brand-100 text-brand-600']">
                                        <Award class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">{{ award.title }}</p>
                                        <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">{{ award.category?.toUpperCase() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="['font-semibold', isDark ? 'text-brand-400' : 'text-brand-600']">
                                    {{ formatAmount(award.amount) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <Badge :variant="statusColors[award.status]" class="flex items-center gap-1 w-fit">
                                    <component :is="statusIcons[award.status]" class="w-3 h-3" />
                                    {{ award.status }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="isDark ? 'text-slate-300' : 'text-slate-600'">
                                    {{ award.applications_count || 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                    {{ formatDate(award.application_end_date) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link 
                                        :href="route('sponsor.awards.show', award.id)"
                                        :class="['p-2 rounded-lg transition-colors', isDark ? 'hover:bg-slate-700 text-slate-400 hover:text-white' : 'hover:bg-slate-100 text-slate-500 hover:text-slate-700']"
                                    >
                                        <Eye class="w-4 h-4" />
                                    </Link>
                                    <Link 
                                        v-if="award.status === 'draft'"
                                        :href="route('sponsor.awards.edit', award.id)"
                                        :class="['p-2 rounded-lg transition-colors', isDark ? 'hover:bg-slate-700 text-slate-400 hover:text-white' : 'hover:bg-slate-100 text-slate-500 hover:text-slate-700']"
                                    >
                                        <Edit class="w-4 h-4" />
                                    </Link>
                                    <button 
                                        v-if="!award.applications_count"
                                        @click="deleteAward(award)"
                                        :class="['p-2 rounded-lg transition-colors', isDark ? 'hover:bg-red-900/30 text-slate-400 hover:text-red-400' : 'hover:bg-red-50 text-slate-500 hover:text-red-600']"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else :class="['text-center py-16 rounded-2xl', isDark ? 'bg-slate-800/50' : 'bg-slate-50']">
            <Award :class="['w-16 h-16 mx-auto mb-4', isDark ? 'text-slate-600' : 'text-slate-300']" />
            <h3 :class="['text-xl font-semibold mb-2', isDark ? 'text-white' : 'text-slate-900']">
                No awards yet
            </h3>
            <p :class="['mb-6', isDark ? 'text-slate-400' : 'text-slate-600']">
                Create your first award to start receiving applications.
            </p>
            <Link 
                v-if="sponsor?.verification_status === 'verified'"
                :href="route('sponsor.awards.create')"
                class="btn-primary inline-flex"
            >
                <Plus class="w-5 h-5" />
                Create Your First Award
            </Link>
        </div>
    </div>
</template>
