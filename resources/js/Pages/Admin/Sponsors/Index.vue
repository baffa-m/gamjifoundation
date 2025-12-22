<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/UI/Table.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Users, Search, CheckCircle, XCircle, Eye } from 'lucide-vue-next';
import debounce from 'lodash/debounce';

defineOptions({ layout: AuthenticatedLayout });

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
                <h1 class="text-3xl font-bold font-display text-slate-900">Sponsors</h1>
                <p class="text-slate-500 mt-1">Manage partner organizations and sponsors.</p>
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input 
                        v-model="search"
                        type="text" 
                        placeholder="Search sponsors..." 
                        class="pl-9 pr-4 py-2 rounded-lg border border-slate-200 bg-white focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm w-64"
                    >
                </div>
                <select 
                    v-model="status"
                    class="pl-4 pr-8 py-2 rounded-lg border border-slate-200 bg-white focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm"
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
        >
            <template #organization="{ item }">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600 border border-brand-100">
                        <Users class="w-5 h-5" />
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800 text-sm">{{ item.organization_name }}</div>
                        <div class="text-xs text-slate-500">{{ item.website || 'No website' }}</div>
                    </div>
                </div>
            </template>

            <template #type="{ item }">
                <span class="capitalize">{{ item.sponsor_type }}</span>
            </template>

            <template #contact="{ item }">
                <div class="text-sm">
                    <div class="text-slate-700">{{ item.contact_person_name }}</div>
                    <div class="text-xs text-slate-500">{{ item.contact_email }}</div>
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
                        class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-full transition-colors"
                        title="View Details"
                    >
                        <Eye class="w-4 h-4" />
                    </Link>
                    <template v-if="item.status === 'pending'">
                        <Link 
                            :href="route('admin.sponsors.approve', item.id)"
                            method="patch"
                            as="button"
                            class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-full transition-colors"
                            title="Approve"
                        >
                            <CheckCircle class="w-4 h-4" />
                        </Link>
                        <Link 
                            :href="route('admin.sponsors.reject', item.id)"
                            method="patch"
                            as="button"
                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition-colors"
                            title="Reject"
                        >
                            <XCircle class="w-4 h-4" />
                        </Link>
                    </template>
                </div>
            </template>
        </Table>
    </div>
</template>
