<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/UI/Table.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Award, Plus, Search, Edit, Trash2, Users } from 'lucide-vue-next';
import debounce from 'lodash/debounce';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    awards: Object,
    filters: Object
});

const search = ref(props.filters.search);

const headers = [
    { key: 'title', label: 'Award Title' },
    { key: 'type', label: 'Type' },
    { key: 'amount', label: 'Amount' },
    { key: 'applicants', label: 'Applicants' },
    { key: 'status', label: 'Status' }
];

const watchFilters = debounce(() => {
    router.get(route('admin.awards.index'), { search: search.value }, { preserveState: true, replace: true });
}, 300);

watch(search, watchFilters);
</script>

<template>
    <Head title="Awards" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold font-display text-slate-900">Awards</h1>
                <p class="text-slate-500 mt-1">Manage scholarships and grants.</p>
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input 
                        v-model="search"
                        type="text" 
                        placeholder="Search awards..." 
                        class="pl-9 pr-4 py-2 rounded-lg border border-slate-200 bg-white focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm w-64"
                    >
                </div>
                <Link 
                    :href="route('admin.awards.create')"
                    class="px-4 py-2 bg-brand-600 text-white rounded-lg font-semibold hover:bg-brand-700 transition shadow-lg shadow-brand-600/20 flex items-center gap-2"
                >
                    <Plus class="w-4 h-4" />
                    Create Award
                </Link>
            </div>
        </div>

        <!-- Table -->
        <Table 
            :headers="headers" 
            :items="awards.data" 
            :pagination="awards"
            :actions="true"
        >
            <template #title="{ item }">
                <div class="font-semibold text-slate-800">{{ item.title }}</div>
                <div class="text-xs text-slate-500 truncate max-w-xs">{{ item.description }}</div>
            </template>

            <template #type="{ item }">
                <span class="capitalize">{{ item.award_type }}</span>
            </template>

            <template #amount="{ item }">
                <span class="font-mono font-medium text-slate-700">₦{{ Number(item.amount).toLocaleString() }}</span>
            </template>

            <template #applicants="{ item }">
                <div class="flex items-center gap-1 text-slate-600">
                    <Users class="w-3 h-3" />
                    {{ item.applications_count || 0 }}
                </div>
            </template>

            <template #status="{ item }">
                <Badge :variant="item.is_active ? 'success' : 'default'">
                    {{ item.is_active ? 'Active' : 'Inactive' }}
                </Badge>
            </template>

            <template #actions="{ item }">
                <div class="flex justify-end gap-2">
                    <Link 
                        :href="route('admin.awards.edit', item.id)"
                        class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-full transition-colors"
                        title="Edit"
                    >
                        <Edit class="w-4 h-4" />
                    </Link>
                    <button 
                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition-colors"
                        title="Delete"
                    >
                        <Trash2 class="w-4 h-4" />
                    </button>
                </div>
            </template>
        </Table>
    </div>
</template>
