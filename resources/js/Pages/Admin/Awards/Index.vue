<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/UI/Table.vue';
import { useTheme } from '@/Composables/useTheme';
import { Award, Plus, Search, Edit, Trash2, Users } from 'lucide-vue-next';
import debounce from 'lodash/debounce';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

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
                <h1 :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">Awards</h1>
                <p :class="['mt-1', isDark ? 'text-slate-400' : 'text-slate-500']">Manage scholarships and grants.</p>
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <Search :class="['w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2', isDark ? 'text-slate-500' : 'text-slate-400']" />
                    <input 
                        v-model="search"
                        type="text" 
                        placeholder="Search awards..." 
                        :class="['pl-9 pr-4 py-2 rounded-lg border focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm w-64', isDark ? 'bg-slate-800 border-slate-700 text-white placeholder-slate-500' : 'bg-white border-slate-200 text-slate-800 placeholder-slate-400']"
                    >
                </div>
                <Link 
                    :href="route('admin.awards.create')"
                    class="px-4 py-2 bg-brand-600 text-white rounded-lg font-semibold hover:bg-brand-700 transition shadow-lg shadow-brand-600/20 flex items-center gap-2"
                >
                    <Plus class="w-5 h-5" />
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
            :clickable="true"
            @row-click="(item) => router.visit(route('admin.awards.show', item.id))"
        >
            <template #title="{ item }">
                <Link 
                    :href="route('admin.awards.show', item.id)"
                    :class="['font-semibold hover:underline', isDark ? 'text-white hover:text-brand-400' : 'text-slate-800 hover:text-brand-600']"
                >
                    {{ item.title }}
                </Link>
                <div :class="['text-xs truncate max-w-xs', isDark ? 'text-slate-400' : 'text-slate-500']">{{ item.description }}</div>
            </template>

            <template #type="{ item }">
                <span :class="['capitalize', isDark ? 'text-slate-300' : 'text-slate-600']">{{ item.award_type }}</span>
            </template>

            <template #amount="{ item }">
                <span :class="['font-mono font-medium', isDark ? 'text-slate-300' : 'text-slate-700']">₦{{ Number(item.amount).toLocaleString() }}</span>
            </template>

            <template #applicants="{ item }">
                <Link 
                    v-if="item.applications_count > 0"
                    :href="route('admin.applications.index', { award_id: item.id })"
                    :class="['flex items-center gap-1 hover:underline', isDark ? 'text-brand-400' : 'text-brand-600']"
                    @click.stop
                >
                    <Users class="w-3 h-3" />
                    {{ item.applications_count }}
                </Link>
                 <div v-else :class="['flex items-center gap-1', isDark ? 'text-slate-400' : 'text-slate-600']">
                    <Users class="w-3 h-3" />
                    0
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
                        :class="['p-2 rounded-full transition-colors', isDark ? 'text-slate-400 hover:text-brand-400 hover:bg-slate-700' : 'text-slate-400 hover:text-brand-600 hover:bg-brand-50']"
                        title="Edit"
                        @click.stop
                    >
                        <Edit class="w-5 h-5" />
                    </Link>
                    <button 
                        :class="['p-2 rounded-full transition-colors', isDark ? 'text-slate-400 hover:text-rose-400 hover:bg-rose-900/30' : 'text-slate-400 hover:text-rose-600 hover:bg-rose-50']"
                        title="Delete"
                        @click.stop
                    >
                        <Trash2 class="w-5 h-5" />
                    </button>
                </div>
            </template>
        </Table>
    </div>
</template>
