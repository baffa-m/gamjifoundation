<script setup>
import { defineOptions } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Users, Search, Eye, Trash2, Mail, GraduationCap, MapPin } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    applicants: Object,
    filters: Object
});

const searchQuery = (e) => {
    router.get(route('admin.applicants.index'), { search: e.target.value }, { preserveState: true, replace: true });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { month: 'short', day: 'numeric', year: 'numeric' });
};

const deleteApplicant = (applicant) => {
    if (confirm('Are you sure you want to delete this applicant?')) {
        router.delete(route('admin.applicants.destroy', applicant.id));
    }
};
</script>

<template>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="animate-fade-in-up">
                <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                    Applicants
                </h1>
                <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">
                    Manage registered applicants
                </p>
            </div>
        </div>

        <!-- Search -->
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <Search :class="['w-5 h-5', isDark ? 'text-slate-500' : 'text-slate-400']" />
            </div>
            <input
                type="text"
                :value="filters?.search"
                @input="searchQuery"
                class="input-premium pl-12"
                placeholder="Search by name or email..."
            />
        </div>

        <!-- Table -->
        <div v-if="applicants?.data?.length" :class="['rounded-2xl overflow-hidden', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="isDark ? 'bg-slate-800' : 'bg-slate-50'">
                        <tr>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Applicant</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">State</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Applications</th>
                            <th :class="['px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Joined</th>
                            <th :class="['px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider', isDark ? 'text-slate-400' : 'text-slate-500']">Actions</th>
                        </tr>
                    </thead>
                    <tbody :class="['divide-y', isDark ? 'divide-slate-700' : 'divide-slate-100']">
                        <tr v-for="applicant in applicants.data" :key="applicant.id" :class="['transition-colors', isDark ? 'hover:bg-slate-800/50' : 'hover:bg-slate-50']">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold', isDark ? 'bg-brand-600/20 text-brand-400' : 'bg-brand-100 text-brand-600']">
                                        {{ (applicant.full_name || applicant.user?.name)?.charAt(0) || '?' }}
                                    </div>
                                    <div>
                                        <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                                            {{ applicant.full_name || applicant.user?.name }}
                                        </p>
                                        <p :class="['text-sm flex items-center gap-1', isDark ? 'text-slate-400' : 'text-slate-500']">
                                            <Mail class="w-3 h-3" />
                                            {{ applicant.email || applicant.user?.email }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="['flex items-center gap-1', isDark ? 'text-slate-300' : 'text-slate-600']">
                                    <MapPin class="w-4 h-4 text-slate-400" />
                                    {{ applicant.state_of_origin || 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <Badge variant="default">{{ applicant.applications?.length || 0 }}</Badge>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                    {{ formatDate(applicant.created_at) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link :href="route('admin.applicants.show', applicant.id)"
                                        :class="['p-2 rounded-lg transition-colors', isDark ? 'hover:bg-slate-700 text-slate-400 hover:text-white' : 'hover:bg-slate-100 text-slate-500 hover:text-slate-700']">
                                        <Eye class="w-4 h-4" />
                                    </Link>
                                    <button v-if="!applicant.applications?.length" @click="deleteApplicant(applicant)"
                                        :class="['p-2 rounded-lg transition-colors', isDark ? 'hover:bg-red-900/30 text-slate-400 hover:text-red-400' : 'hover:bg-red-50 text-slate-500 hover:text-red-600']">
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
            <Users :class="['w-16 h-16 mx-auto mb-4', isDark ? 'text-slate-600' : 'text-slate-300']" />
            <h3 :class="['text-xl font-semibold mb-2', isDark ? 'text-white' : 'text-slate-900']">No applicants found</h3>
            <p :class="isDark ? 'text-slate-400' : 'text-slate-600'">Try adjusting your search.</p>
        </div>

        <!-- Pagination -->
        <div v-if="applicants?.links?.length > 3" class="flex justify-center gap-2">
            <Link v-for="link in applicants.links" :key="link.label" :href="link.url || '#'"
                :class="['px-4 py-2 rounded-lg text-sm font-medium', link.active ? 'bg-brand-600 text-white' : link.url ? isDark ? 'bg-slate-800 text-slate-300' : 'bg-white text-slate-600 border border-slate-200' : 'opacity-50']"
                v-html="link.label" />
        </div>
    </div>
</template>
