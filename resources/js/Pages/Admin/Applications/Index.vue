<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/UI/Table.vue';
import { useTheme } from '@/Composables/useTheme';
import { FileText, Search, Filter, Eye, CheckCircle, XCircle } from 'lucide-vue-next';
import debounce from 'lodash/debounce';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    applications: Object,
    filters: Object
});

const search = ref(props.filters.search);
const status = ref(props.filters.status);

const headers = [
    { key: 'applicant', label: 'Applicant' },
    { key: 'award', label: 'Award' },
    { key: 'date', label: 'Date Applied' },
    { key: 'status', label: 'Status' }
];

const watchFilters = debounce(() => {
    router.get(route('admin.applications.index'), { 
        search: search.value, 
        status: status.value 
    }, { preserveState: true, replace: true });
}, 300);

watch([search, status], watchFilters);
</script>

<template>
    <Head title="Applications" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">Applications</h1>
                <p :class="['mt-1', isDark ? 'text-slate-400' : 'text-slate-500']">Manage and review scholarship applications.</p>
            </div>
            
            <!-- Filters -->
            <div class="flex gap-3">
                <div class="relative">
                    <Search :class="['w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2', isDark ? 'text-slate-500' : 'text-slate-400']" />
                    <input 
                        v-model="search"
                        type="text" 
                        placeholder="Search applicants..." 
                        :class="['pl-9 pr-4 py-2 rounded-lg border focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm w-64', isDark ? 'bg-slate-800 border-slate-700 text-white placeholder-slate-500' : 'bg-white border-slate-200 text-slate-800 placeholder-slate-400']"
                    >
                </div>
                <select 
                    v-model="status"
                    :class="['pl-4 pr-8 py-2 rounded-lg border focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm', isDark ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-200 text-slate-800']"
                >
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="shortlisted">Shortlisted</option>
                    <option value="reviewing">Reviewing</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <Table 
            :headers="headers" 
            :items="applications.data" 
            :pagination="applications"
            :actions="true"
            :clickable="true"
            @row-click="(item) => router.visit(route('admin.applications.show', item.id))"
        >
            <template #applicant="{ item }">
                <div class="flex items-center gap-3">
                    <div :class="['w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold border', isDark ? 'bg-brand-900/30 text-brand-400 border-brand-800' : 'bg-brand-100 text-brand-700 border-brand-200']">
                        {{ item.applicant.full_name.charAt(0) }}
                    </div>
                    <div>
                        <div :class="['font-semibold text-sm', isDark ? 'text-white' : 'text-slate-800']">{{ item.applicant.full_name }}</div>
                        <div :class="['text-xs', isDark ? 'text-slate-400' : 'text-slate-500']">{{ item.applicant.email }}</div>
                    </div>
                </div>
            </template>

            <template #award="{ item }">
                <span :class="['font-medium', isDark ? 'text-slate-300' : 'text-slate-700']">{{ item.award.title }}</span>
            </template>

            <template #date="{ item }">
                <span :class="isDark ? 'text-slate-400' : 'text-slate-600'">{{ new Date(item.created_at).toLocaleDateString() }}</span>
            </template>

            <template #status="{ item }">
                <Badge :variant="item.application_status === 'approved' ? 'success' : item.application_status === 'rejected' ? 'danger' : item.application_status === 'shortlisted' ? 'default' : 'warning'">
                    {{ item.application_status }}
                </Badge>
            </template>

            <template #actions="{ item }">
                <div class="flex justify-end gap-2">
                    <Link 
                        :href="route('admin.applications.show', item.id)"
                        :class="['p-2 rounded-full transition-colors', isDark ? 'text-slate-400 hover:text-brand-400 hover:bg-slate-700' : 'text-slate-400 hover:text-brand-600 hover:bg-brand-50']"
                        title="View Details"
                        @click.stop
                    >
                        <Eye class="w-5 h-5" />
                    </Link>
                </div>
            </template>
        </Table>
    </div>
</template>
