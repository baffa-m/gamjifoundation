<script setup>
import { defineOptions } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    ArrowLeft, User, Mail, Phone, MapPin, Calendar, GraduationCap, 
    Award, FileText, Trash2, CheckCircle, XCircle, Clock
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    applicant: Object
});

const statusColors = {
    pending: 'warning',
    approved: 'success',
    rejected: 'danger'
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        month: 'long', day: 'numeric', year: 'numeric' 
    });
};

const deleteApplicant = () => {
    if (confirm('Are you sure you want to delete this applicant?')) {
        router.delete(route('admin.applicants.destroy', props.applicant.id));
    }
};
</script>

<template>
    <div class="space-y-8">
        <Link :href="route('admin.applicants.index')" :class="['inline-flex items-center gap-2 text-sm font-medium', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']">
            <ArrowLeft class="w-4 h-4" /> Back to Applicants
        </Link>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div class="flex items-center gap-4">
                <div :class="['w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-bold', isDark ? 'bg-brand-600/20 text-brand-400' : 'bg-brand-100 text-brand-600']">
                    {{ (applicant.full_name || applicant.user?.name)?.charAt(0) || '?' }}
                </div>
                <div>
                    <h1 :class="['text-4xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                        {{ applicant.full_name || applicant.user?.name }}
                    </h1>
                    <p :class="['mt-1 flex items-center gap-2', isDark ? 'text-slate-400' : 'text-slate-500']">
                        <Mail class="w-4 h-4" />
                        {{ applicant.email || applicant.user?.email }}
                    </p>
                </div>
            </div>
            
            <button v-if="!applicant.applications?.length" @click="deleteApplicant"
                :class="['px-4 py-2 rounded-xl font-semibold', isDark ? 'bg-red-900/30 text-red-400' : 'bg-red-50 text-red-600']">
                <Trash2 class="w-5 h-5 inline mr-1" /> Delete
            </button>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Info -->
                <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h2 :class="['text-lg font-semibold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <User class="w-5 h-5 text-brand-500" /> Personal Information
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div v-if="applicant.phone_number">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Phone</p>
                            <p :class="['font-medium flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                <Phone class="w-4 h-4 text-slate-400" /> {{ applicant.phone_number }}
                            </p>
                        </div>
                        <div v-if="applicant.date_of_birth">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Date of Birth</p>
                            <p :class="['font-medium flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                <Calendar class="w-4 h-4 text-slate-400" /> {{ formatDate(applicant.date_of_birth) }}
                            </p>
                        </div>
                        <div v-if="applicant.state_of_origin">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">State of Origin</p>
                            <p :class="['font-medium flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                <MapPin class="w-4 h-4 text-slate-400" /> {{ applicant.state_of_origin }}
                            </p>
                        </div>
                        <div v-if="applicant.lga">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">LGA</p>
                            <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ applicant.lga }}</p>
                        </div>
                    </div>
                </div>

                <!-- Education -->
                <div v-if="applicant.school_name || applicant.current_class" :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h2 :class="['text-lg font-semibold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <GraduationCap class="w-5 h-5 text-brand-500" /> Education
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div v-if="applicant.school_name">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">School</p>
                            <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ applicant.school_name }}</p>
                        </div>
                        <div v-if="applicant.current_class">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Class/Level</p>
                            <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ applicant.current_class }}</p>
                        </div>
                    </div>
                </div>

                <!-- Applications -->
                <div :class="['rounded-2xl overflow-hidden', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <div :class="['px-8 py-6 border-b', isDark ? 'border-slate-700' : 'border-slate-200']">
                        <h2 :class="['text-lg font-semibold flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                            <FileText class="w-5 h-5 text-brand-500" />
                            Applications ({{ applicant.applications?.length || 0 }})
                        </h2>
                    </div>
                    <div v-if="applicant.applications?.length" class="divide-y" :class="isDark ? 'divide-slate-700' : 'divide-slate-100'">
                        <Link v-for="app in applicant.applications" :key="app.id" :href="route('admin.applications.show', app.id)"
                            :class="['block px-8 py-4 transition-colors', isDark ? 'hover:bg-slate-800' : 'hover:bg-slate-50']">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">{{ app.award?.title }}</p>
                                    <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">{{ app.application_number }}</p>
                                </div>
                                <Badge :variant="statusColors[app.application_status]" class="flex items-center gap-1">
                                    <CheckCircle v-if="app.application_status === 'approved'" class="w-3 h-3" />
                                    <XCircle v-else-if="app.application_status === 'rejected'" class="w-3 h-3" />
                                    <Clock v-else class="w-3 h-3" />
                                    {{ app.application_status }}
                                </Badge>
                            </div>
                        </Link>
                    </div>
                    <div v-else class="px-8 py-12 text-center">
                        <Award :class="['w-12 h-12 mx-auto mb-3', isDark ? 'text-slate-600' : 'text-slate-300']" />
                        <p :class="isDark ? 'text-slate-400' : 'text-slate-500'">No applications yet</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div :class="['p-6 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h3 :class="['text-lg font-semibold mb-4', isDark ? 'text-white' : 'text-slate-900']">Register Info</h3>
                    <div class="flex items-start gap-3">
                        <Calendar :class="['w-5 h-5 shrink-0 mt-0.5', isDark ? 'text-slate-500' : 'text-slate-400']" />
                        <div>
                            <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Joined</p>
                            <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ formatDate(applicant.created_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
