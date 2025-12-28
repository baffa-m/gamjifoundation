<script setup>
import { defineOptions } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    FileText, ArrowLeft, User, Mail, Calendar, Award, CheckCircle, 
    XCircle, Clock, Building2, GraduationCap
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    application: Object
});

const statusColors = {
    pending: 'warning',
    approved: 'success',
    rejected: 'danger',
    under_review: 'info',
    shortlisted: 'default'
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' 
    });
};

const getFileName = (path) => {
    return path.split('/').pop();
};

const getFileUrl = (path) => {
    return `/storage/${path}`;
};

const approve = () => {
    if (confirm('Approve this application?')) {
        router.patch(route('admin.applications.approve', props.application.id));
    }
};

const shortlist = () => {
    if (confirm('Shortlist this application?')) {
        router.patch(route('admin.applications.shortlist', props.application.id));
    }
};

const reject = () => {
    if (confirm('Reject this application?')) {
        router.patch(route('admin.applications.reject', props.application.id));
    }
};
</script>

<template>
    <div class="space-y-8">
        <Link :href="route('admin.applications.index')" :class="['inline-flex items-center gap-2 text-sm font-medium', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']">
            <ArrowLeft class="w-4 h-4" /> Back to Applications
        </Link>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <Badge :variant="statusColors[application.application_status] || 'default'" class="flex items-center gap-1">
                        <CheckCircle v-if="application.application_status === 'approved'" class="w-3 h-3" />
                        <XCircle v-else-if="application.application_status === 'rejected'" class="w-3 h-3" />
                        <CheckCircle v-else-if="application.application_status === 'shortlisted'" class="w-3 h-3" />
                        <Clock v-else class="w-3 h-3" />
                        {{ application.application_status }}
                    </Badge>
                </div>
                <h1 :class="['text-4xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                    Application {{ application.application_number }}
                </h1>
                <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-500']">
                    Submitted {{ formatDate(application.application_date) }}
                </p>
            </div>
            
            <div v-if="application.application_status === 'pending'" class="flex items-center gap-3">
                <button @click="shortlist" :class="['btn-secondary', isDark? 'bg-brand-900/40 text-brand-400 border-brand-800 hover:bg-brand-900/60' : 'bg-white border-brand-200 text-brand-700 hover:bg-brand-50']">
                    <CheckCircle class="w-5 h-5 opacity-50" style="color:currentColor" /> Shortlist
                </button>
                <button @click="approve" class="btn-primary">
                    <CheckCircle class="w-5 h-5" /> Approve
                </button>
                <button @click="reject" :class="['px-4 py-2 rounded-xl font-semibold transition-colors', isDark ? 'bg-red-900/30 text-red-400' : 'bg-red-50 text-red-600 hover:bg-red-100']">
                    <XCircle class="w-5 h-5 inline mr-1" /> Reject
                </button>
            </div>
            
            <div v-else-if="application.application_status === 'shortlisted'" class="flex items-center gap-3">
                 <button @click="approve" class="btn-primary">
                    <CheckCircle class="w-5 h-5" /> Approve Selected
                </button>
                 <button @click="reject" :class="['px-4 py-2 rounded-xl font-semibold transition-colors', isDark ? 'bg-red-900/30 text-red-400' : 'bg-red-50 text-red-600 hover:bg-red-100']">
                    <XCircle class="w-5 h-5 inline mr-1" /> Reject
                </button>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Applicant Info -->
                <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h2 :class="['text-lg font-semibold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <User class="w-5 h-5 text-brand-500" /> Applicant Information
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Full Name</p>
                            <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">
                                {{ application.applicant?.user?.name || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Email</p>
                            <p :class="['font-medium flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                <Mail class="w-4 h-4 text-slate-400" />
                                {{ application.applicant?.user?.email || 'N/A' }}
                            </p>
                        </div>
                        <div v-if="application.jamb_score">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">JAMB Score</p>
                            <p :class="['font-medium flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                <GraduationCap class="w-4 h-4 text-slate-400" />
                                {{ application.jamb_score }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Supporting Documents -->
                <div v-if="application.supporting_documents" :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h2 :class="['text-lg font-semibold mb-4 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <FileText class="w-5 h-5 text-brand-500" /> Supporting Documents
                    </h2>
                    <p v-if="application.supporting_documents" :class="['whitespace-pre-wrap mb-4', isDark ? 'text-slate-300' : 'text-slate-600']">
                        {{ application.supporting_documents }}
                    </p>

                    <div v-if="application.applicant?.documents?.length" class="space-y-3">
                        <div v-for="doc in application.applicant.documents" :key="doc.id" 
                            :class="['flex items-center justify-between p-3 rounded-lg border', isDark ? 'bg-slate-700/50 border-slate-600' : 'bg-slate-50 border-slate-200']">
                            <div class="flex items-center gap-3">
                                <FileText class="w-5 h-5 text-brand-500" />
                                <div>
                                    <p :class="['font-medium text-sm capitalize', isDark ? 'text-white' : 'text-slate-900']">
                                        {{ doc.type }} Result
                                    </p>
                                    <p :class="['text-xs truncate max-w-[200px]', isDark ? 'text-slate-400' : 'text-slate-500']">
                                        {{ getFileName(doc.file_path) }}
                                    </p>
                                </div>
                            </div>
                            <a :href="getFileUrl(doc.file_path)" target="_blank" 
                                :class="['text-sm font-medium hover:underline', isDark ? 'text-brand-400' : 'text-brand-600']">
                                Download
                            </a>
                        </div>
                    </div>
                    <div v-else-if="!application.supporting_documents && !application.applicant?.documents?.length">
                         <p :class="[isDark ? 'text-slate-400' : 'text-slate-500']">No documents available.</p>
                    </div>
                </div>

                <!-- Admin Notes -->
                <div v-if="application.admin_notes" :class="['p-8 rounded-2xl', isDark ? 'bg-amber-900/20 border border-amber-800' : 'bg-amber-50 border border-amber-200']">
                    <h2 :class="['text-lg font-semibold mb-4', isDark ? 'text-amber-400' : 'text-amber-700']">Admin Notes</h2>
                    <p :class="isDark ? 'text-amber-300' : 'text-amber-800'">{{ application.admin_notes }}</p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Award Info -->
                <div :class="['p-6 rounded-2xl', isDark ? 'bg-gradient-to-br from-brand-900/50 to-slate-900 border border-brand-800' : 'bg-gradient-to-br from-brand-50 to-white border border-brand-200']">
                    <div class="flex items-center gap-3 mb-4">
                        <Award :class="['w-8 h-8', isDark ? 'text-brand-400' : 'text-brand-600']" />
                        <h3 :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                            {{ application.award?.title || 'Award' }}
                        </h3>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Category</p>
                            <p :class="['font-medium', isDark ? 'text-brand-400' : 'text-brand-600']">
                                {{ application.award?.category?.toUpperCase() }}
                            </p>
                        </div>
                        <div v-if="application.award?.sponsor">
                            <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Sponsor</p>
                            <p :class="['font-medium flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                <Building2 class="w-4 h-4" />
                                {{ application.award.sponsor.user?.name }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div :class="['p-6 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h3 :class="['text-lg font-semibold mb-4', isDark ? 'text-white' : 'text-slate-900']">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 rounded-full bg-brand-500 mt-2" />
                            <div>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">Applied</p>
                                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                    {{ formatDate(application.application_date) }}
                                </p>
                            </div>
                        </div>
                        <div v-if="application.reviewed_at" class="flex items-start gap-3">
                            <div :class="['w-2 h-2 rounded-full mt-2', application.application_status === 'approved' ? 'bg-emerald-500' : 'bg-red-500']" />
                            <div>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">
                                    {{ application.application_status === 'approved' ? 'Approved' : 'Rejected' }}
                                </p>
                                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                    {{ formatDate(application.reviewed_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
