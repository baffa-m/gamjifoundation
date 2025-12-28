<script setup>
import { defineOptions, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    ArrowLeft, Building2, Mail, Globe, MapPin, Calendar, Award,
    CheckCircle, XCircle, Clock, AlertTriangle, FileText
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    sponsor: Object
});

const statusColors = {
    pending: 'warning',
    verified: 'success',
    rejected: 'danger',
    suspended: 'danger'
};

const sponsorTypes = {
    individual: 'Individual',
    corporate: 'Corporate',
    government: 'Government',
    ngo: 'NGO'
};

const rejectNotes = ref('');
const showRejectModal = ref(false);

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        month: 'long', day: 'numeric', year: 'numeric' 
    });
};

const approve = () => {
    if (confirm('Approve this sponsor?')) {
        router.patch(route('admin.sponsors.approve', props.sponsor.id));
    }
};

const reject = () => {
    if (rejectNotes.value.trim()) {
        router.patch(route('admin.sponsors.reject', props.sponsor.id), {
            notes: rejectNotes.value
        });
        showRejectModal.value = false;
    }
};

const suspend = () => {
    if (confirm('Suspend this sponsor?')) {
        router.patch(route('admin.sponsors.suspend', props.sponsor.id));
    }
};
</script>

<template>
    <div class="space-y-8">
        <Link :href="route('admin.sponsors.index')" :class="['inline-flex items-center gap-2 text-sm font-medium', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']">
            <ArrowLeft class="w-4 h-4" /> Back to Sponsors
        </Link>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <Badge :variant="statusColors[sponsor.verification_status]" class="flex items-center gap-1">
                        <CheckCircle v-if="sponsor.verification_status === 'verified'" class="w-3 h-3" />
                        <XCircle v-else-if="sponsor.verification_status === 'rejected'" class="w-3 h-3" />
                        <Clock v-else class="w-3 h-3" />
                        {{ sponsor.verification_status }}
                    </Badge>
                    <Badge variant="default">{{ sponsorTypes[sponsor.sponsor_type] }}</Badge>
                </div>
                <h1 :class="['text-4xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                    {{ sponsor.organization_name }}
                </h1>
                <p :class="['mt-2 flex items-center gap-2', isDark ? 'text-slate-400' : 'text-slate-500']">
                    <Mail class="w-4 h-4" />
                    {{ sponsor.user?.email }}
                </p>
            </div>
            
            <div v-if="sponsor.verification_status === 'pending'" class="flex items-center gap-3">
                <button @click="approve" class="btn-primary">
                    <CheckCircle class="w-5 h-5" /> Approve
                </button>
                <button @click="showRejectModal = true" :class="['px-4 py-2 rounded-xl font-semibold', isDark ? 'bg-red-900/30 text-red-400' : 'bg-red-50 text-red-600']">
                    <XCircle class="w-5 h-5 inline mr-1" /> Reject
                </button>
            </div>
            <div v-else-if="sponsor.verification_status === 'verified'" class="flex items-center gap-3">
                <button @click="suspend" :class="['px-4 py-2 rounded-xl font-semibold', isDark ? 'bg-amber-900/30 text-amber-400' : 'bg-amber-50 text-amber-600']">
                    <AlertTriangle class="w-5 h-5 inline mr-1" /> Suspend
                </button>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Details -->
                <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h2 :class="['text-lg font-semibold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <Building2 class="w-5 h-5 text-brand-500" /> Organization Details
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Name</p>
                            <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ sponsor.user?.name }}</p>
                        </div>
                        <div v-if="sponsor.registration_number">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Registration No.</p>
                            <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ sponsor.registration_number }}</p>
                        </div>
                        <div v-if="sponsor.website">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Website</p>
                            <a :href="sponsor.website" target="_blank" :class="['font-medium flex items-center gap-2', isDark ? 'text-brand-400' : 'text-brand-600']">
                                <Globe class="w-4 h-4" /> {{ sponsor.website }}
                            </a>
                        </div>
                        <div v-if="sponsor.address">
                            <p :class="['text-xs uppercase tracking-wider mb-1', isDark ? 'text-slate-500' : 'text-slate-400']">Address</p>
                            <p :class="['font-medium flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                                <MapPin class="w-4 h-4 text-slate-400" /> {{ sponsor.address }}
                            </p>
                        </div>
                    </div>
                    <div v-if="sponsor.description" class="mt-6 pt-6 border-t" :class="isDark ? 'border-slate-700' : 'border-slate-200'">
                        <p :class="['text-xs uppercase tracking-wider mb-2', isDark ? 'text-slate-500' : 'text-slate-400']">Description</p>
                        <p :class="['leading-relaxed', isDark ? 'text-slate-300' : 'text-slate-600']">{{ sponsor.description }}</p>
                    </div>
                </div>

                <!-- Awards -->
                <div :class="['rounded-2xl overflow-hidden', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <div :class="['px-8 py-6 border-b', isDark ? 'border-slate-700' : 'border-slate-200']">
                        <h2 :class="['text-lg font-semibold flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                            <Award class="w-5 h-5 text-brand-500" />
                            Awards ({{ sponsor.awards?.length || 0 }})
                        </h2>
                    </div>
                    <div v-if="sponsor.awards?.length" class="divide-y" :class="isDark ? 'divide-slate-700' : 'divide-slate-100'">
                        <Link v-for="award in sponsor.awards" :key="award.id" :href="route('admin.awards.show', award.id)"
                            :class="['block px-8 py-4 transition-colors', isDark ? 'hover:bg-slate-800' : 'hover:bg-slate-50']">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">{{ award.title }}</p>
                                    <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                        {{ award.applications_count }} applications
                                    </p>
                                </div>
                                <Badge :variant="award.status === 'active' ? 'success' : 'warning'">{{ award.status }}</Badge>
                            </div>
                        </Link>
                    </div>
                    <div v-else class="px-8 py-12 text-center">
                        <Award :class="['w-12 h-12 mx-auto mb-3', isDark ? 'text-slate-600' : 'text-slate-300']" />
                        <p :class="isDark ? 'text-slate-400' : 'text-slate-500'">No awards created yet</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div :class="['p-6 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                    <h3 :class="['text-lg font-semibold mb-4', isDark ? 'text-white' : 'text-slate-900']">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <Calendar :class="['w-5 h-5 shrink-0 mt-0.5', isDark ? 'text-slate-500' : 'text-slate-400']" />
                            <div>
                                <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Registered</p>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ formatDate(sponsor.created_at) }}</p>
                            </div>
                        </div>
                        <div v-if="sponsor.verified_at" class="flex items-start gap-3">
                            <CheckCircle :class="['w-5 h-5 shrink-0 mt-0.5 text-emerald-500']" />
                            <div>
                                <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Verified</p>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">{{ formatDate(sponsor.verified_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="sponsor.verification_notes" :class="['p-6 rounded-2xl', isDark ? 'bg-amber-900/20 border border-amber-800' : 'bg-amber-50 border border-amber-200']">
                    <h3 :class="['font-semibold mb-2 flex items-center gap-2', isDark ? 'text-amber-400' : 'text-amber-700']">
                        <FileText class="w-4 h-4" /> Verification Notes
                    </h3>
                    <p :class="isDark ? 'text-amber-300' : 'text-amber-800'">{{ sponsor.verification_notes }}</p>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div v-if="showRejectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div :class="['w-full max-w-md p-6 rounded-2xl', isDark ? 'bg-slate-800' : 'bg-white']">
                <h3 :class="['text-xl font-bold mb-4', isDark ? 'text-white' : 'text-slate-900']">Reject Sponsor</h3>
                <textarea v-model="rejectNotes" rows="4" class="input-premium mb-4" placeholder="Reason for rejection..." />
                <div class="flex justify-end gap-3">
                    <button @click="showRejectModal = false" :class="['px-4 py-2 rounded-xl', isDark ? 'text-slate-400' : 'text-slate-600']">Cancel</button>
                    <button @click="reject" class="px-4 py-2 rounded-xl bg-red-600 text-white">Reject</button>
                </div>
            </div>
        </div>
    </div>
</template>
