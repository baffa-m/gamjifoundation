<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/UI/Table.vue';
import { useTheme } from '@/Composables/useTheme';
import { Users, Search, CheckCircle, XCircle, Eye } from 'lucide-vue-next';
import debounce from 'lodash/debounce';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    sponsors: Object,
    filters: Object
});

const search = ref(props.filters.search);
const status = ref(props.filters.status);

const headers = [
    { key: 'organization', label: 'Organization' },
    { key: 'type', label: 'Type' },
    { key: 'contact', label: 'Contact' },
    { key: 'status', label: 'Status' }
];

const watchFilters = debounce(() => {
    router.get(route('admin.sponsors.index'), { 
        search: search.value, 
        status: status.value 
    }, { preserveState: true, replace: true });
}, 300);

watch([search, status], watchFilters);
</script>

<template>
    <Head title="Sponsors" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">Sponsors</h1>
                <p :class="['mt-1', isDark ? 'text-slate-400' : 'text-slate-500']">Manage partner organizations and sponsors.</p>
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <Search :class="['w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2', isDark ? 'text-slate-500' : 'text-slate-400']" />
                    <input 
                        v-model="search"
                        type="text" 
                        placeholder="Search sponsors..." 
                        :class="['pl-9 pr-4 py-2 rounded-lg border focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm w-64', isDark ? 'bg-slate-800 border-slate-700 text-white placeholder-slate-500' : 'bg-white border-slate-200 text-slate-800 placeholder-slate-400']"
                    >
                </div>
                <select 
                    v-model="status"
                    :class="['pl-4 pr-8 py-2 rounded-lg border focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm', isDark ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-200 text-slate-800']"
                >
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <Table 
            :headers="headers" 
            :items="sponsors.data" 
            :pagination="sponsors"
            :actions="true"
            :clickable="true"
            @row-click="(item) => router.visit(route('admin.sponsors.show', item.id))"
        >
            <template #organization="{ item }">
                <div class="flex items-center gap-3">
                    <div :class="['w-9 h-9 rounded-lg flex items-center justify-center border', isDark ? 'bg-brand-900/30 text-brand-400 border-brand-800' : 'bg-brand-50 text-brand-600 border-brand-100']">
                        <Users class="w-5 h-5" />
                    </div>
                    <div>
                        <div :class="['font-semibold text-sm', isDark ? 'text-white' : 'text-slate-800']">{{ item.organization_name }}</div>
                        <div :class="['text-xs', isDark ? 'text-slate-400' : 'text-slate-500']">{{ item.website || 'No website' }}</div>
                    </div>
                </div>
            </template>

            <template #type="{ item }">
                <span :class="['capitalize', isDark ? 'text-slate-300' : 'text-slate-600']">{{ item.sponsor_type }}</span>
            </template>

            <template #contact="{ item }">
                <div class="text-sm">
                    <div :class="isDark ? 'text-slate-300' : 'text-slate-700'">{{ item.contact_person_name }}</div>
                    <div :class="['text-xs', isDark ? 'text-slate-500' : 'text-slate-500']">{{ item.contact_email }}</div>
                </div>
            </template>

            <template #status="{ item }">
                <Badge :variant="item.status === 'approved' ? 'success' : item.status === 'rejected' ? 'danger' : 'warning'">
                    {{ item.status }}
                </Badge>
            </template>

            <template #actions="{ item }">
                <div class="flex justify-end gap-2">
                    <Link 
                        :href="route('admin.sponsors.show', item.id)"
                        :class="['p-2 rounded-full transition-colors', isDark ? 'text-slate-400 hover:text-brand-400 hover:bg-slate-700' : 'text-slate-400 hover:text-brand-600 hover:bg-brand-50']"
                        title="View Details"
                        @click.stop
                    >
                        <Eye class="w-5 h-5" />
                    </Link>
                    <template v-if="item.status === 'pending'">
                        <button
                            @click.stop="verifySponsor(item)"
                            :class="['p-2 rounded-full transition-colors', isDark ? 'text-slate-400 hover:text-emerald-400 hover:bg-emerald-900/30' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50']"
                            title="Approve"
                        >
                            <CheckCircle class="w-5 h-5" />
                        </button>
                        <button
                            @click.stop="rejectSponsor(item)"
                            :class="['p-2 rounded-full transition-colors', isDark ? 'text-slate-400 hover:text-rose-400 hover:bg-rose-900/30' : 'text-slate-400 hover:text-rose-600 hover:bg-rose-50']"
                            title="Reject"
                        >
                            <XCircle class="w-5 h-5" />
                        </button>
                    </template>
                </div>
            </template>
        </Table>
    </div>
</template>
