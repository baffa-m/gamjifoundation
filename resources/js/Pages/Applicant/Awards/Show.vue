<script setup>
import { defineOptions, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    Award, Calendar, DollarSign, Users, CheckCircle, ArrowLeft, 
    Building2, Clock, FileText, Target, Send, AlertCircle 
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    award: Object,
    hasApplied: Boolean,
    canApply: Boolean
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-NG', { 
        weekday: 'long',
        month: 'long', 
        day: 'numeric', 
        year: 'numeric' 
    });
};

const formatAmount = (amount) => {
    return new Intl.NumberFormat('en-NG', { 
        style: 'currency', 
        currency: 'NGN',
        maximumFractionDigits: 0
    }).format(amount);
};

const daysRemaining = computed(() => {
    const end = new Date(props.award.application_end_date);
    const now = new Date();
    const diff = Math.ceil((end - now) / (1000 * 60 * 60 * 24));
    return diff > 0 ? diff : 0;
});

import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/UI/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

// ... existing props ...

const showModal = ref(false);

const form = useForm({
    jamb_score: props.applicant?.documents?.find(d => d.type === 'jamb')?.score || '',
    jamb_file: null,
    waec_file: null,
});

const openApplyModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    form.clearErrors();
};

const submitApplication = () => {
    form.post(route('applicant.applications.store', props.award.id), {
        onSuccess: () => closeModal(),
        forceFormData: true,
    });
};
</script>

<template>
    <div class="space-y-8">
        <!-- Back Button -->
        <Link 
            :href="route('applicant.awards.index')" 
            :class="['inline-flex items-center gap-2 text-sm font-medium transition-colors', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']"
        >
            <ArrowLeft class="w-4 h-4" />
            Back to Awards
        </Link>

        <!-- Main Content -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column - Award Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Award Header Card -->
                <div :class="[
                    'rounded-2xl overflow-hidden',
                    isDark ? 'bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm'
                ]">
                    <!-- Top Gradient -->
                    <div class="h-2 w-full bg-gradient-to-r from-brand-600 via-brand-400 to-accent-400" />
                    
                    <div class="p-8">
                        <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                            <div>
                                <Badge :variant="award.category === 'stem' ? 'info' : award.category === 'jamb' ? 'success' : 'default'" class="mb-3">
                                    {{ award.category?.toUpperCase() }}
                                </Badge>
                                <h1 :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                                    {{ award.title }}
                                </h1>
                            </div>
                            
                            <Badge v-if="hasApplied" variant="success" class="flex items-center gap-1">
                                <CheckCircle class="w-4 h-4" />
                                Applied
                            </Badge>
                        </div>

                        <!-- Sponsor Info -->
                        <div v-if="award.sponsor" :class="['flex items-center gap-3 p-4 rounded-xl mb-6', isDark ? 'bg-slate-800' : 'bg-slate-50']">
                            <div :class="['w-12 h-12 rounded-full flex items-center justify-center', isDark ? 'bg-brand-600/20 text-brand-400' : 'bg-brand-100 text-brand-600']">
                                <Building2 class="w-6 h-6" />
                            </div>
                            <div>
                                <p :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">Sponsored by</p>
                                <p :class="['font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                                    {{ award.sponsor.user?.name || 'Anonymous Sponsor' }}
                                </p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="prose prose-slate max-w-none" :class="isDark && 'prose-invert'">
                            <h3 :class="['text-lg font-semibold mb-3', isDark ? 'text-white' : 'text-slate-900']">About This Award</h3>
                            <p :class="['leading-relaxed', isDark ? 'text-slate-300' : 'text-slate-600']">
                                {{ award.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Eligibility Section -->
                <div v-if="award.eligibility_criteria?.length" :class="[
                    'p-8 rounded-2xl',
                    isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm'
                ]">
                    <h3 :class="['text-lg font-semibold mb-4 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <Target class="w-5 h-5 text-brand-500" />
                        Eligibility Criteria
                    </h3>
                    <ul class="space-y-3">
                        <li v-for="(criteria, i) in award.eligibility_criteria" :key="i" :class="['flex items-start gap-3', isDark ? 'text-slate-300' : 'text-slate-600']">
                            <CheckCircle class="w-5 h-5 text-brand-500 shrink-0 mt-0.5" />
                            <span>{{ criteria }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Required Documents -->
                <div v-if="award.required_documents" :class="[
                    'p-8 rounded-2xl',
                    isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm'
                ]">
                    <h3 :class="['text-lg font-semibold mb-4 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                        <FileText class="w-5 h-5 text-brand-500" />
                        Required Documents
                    </h3>
                    <p :class="['leading-relaxed', isDark ? 'text-slate-300' : 'text-slate-600']">
                        {{ award.required_documents }}
                    </p>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Award Value Card -->
                <div :class="[
                    'p-6 rounded-2xl text-center',
                    isDark ? 'bg-gradient-to-br from-brand-900/50 to-slate-900 border border-brand-800' : 'bg-gradient-to-br from-brand-50 to-white border border-brand-200'
                ]">
                    <p :class="['text-sm uppercase tracking-wider mb-2', isDark ? 'text-brand-300' : 'text-brand-600']">Award Value</p>
                    <p class="text-4xl font-bold font-display text-brand-600">
                        {{ formatAmount(award.amount) }}
                    </p>
                    <p :class="['text-sm mt-2', isDark ? 'text-slate-400' : 'text-slate-500']">
                        {{ award.number_of_awards }} award{{ award.number_of_awards > 1 ? 's' : '' }} available
                    </p>
                </div>

                <!-- Timeline Card -->
                <div :class="[
                    'p-6 rounded-2xl',
                    isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm'
                ]">
                    <h3 :class="['text-lg font-semibold mb-4', isDark ? 'text-white' : 'text-slate-900']">
                        Important Dates
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <Calendar :class="['w-5 h-5 shrink-0 mt-0.5', isDark ? 'text-brand-400' : 'text-brand-600']" />
                            <div>
                                <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Opens</p>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">
                                    {{ formatDate(award.application_start_date) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <Clock :class="['w-5 h-5 shrink-0 mt-0.5', isDark ? 'text-red-400' : 'text-red-600']" />
                            <div>
                                <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Deadline</p>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">
                                    {{ formatDate(award.application_end_date) }}
                                </p>
                                <p v-if="daysRemaining > 0" class="text-sm text-amber-500 font-medium mt-1">
                                    {{ daysRemaining }} days remaining
                                </p>
                            </div>
                        </div>
                        <div v-if="award.announcement_date" class="flex items-start gap-3">
                            <Award :class="['w-5 h-5 shrink-0 mt-0.5', isDark ? 'text-accent-400' : 'text-accent-600']" />
                            <div>
                                <p :class="['text-xs uppercase tracking-wider', isDark ? 'text-slate-500' : 'text-slate-400']">Results</p>
                                <p :class="['font-medium', isDark ? 'text-white' : 'text-slate-900']">
                                    {{ formatDate(award.announcement_date) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Apply Button -->
                <div v-if="canApply">
                    <Button @click="openApplyModal" class="w-full justify-center" size="lg">
                        <Send class="w-5 h-5" />
                        Apply Now
                    </Button>
                </div>

                <div v-else-if="hasApplied" :class="['p-4 rounded-xl text-center', isDark ? 'bg-emerald-900/20 border border-emerald-800' : 'bg-emerald-50 border border-emerald-200']">
                    <CheckCircle class="w-8 h-8 mx-auto mb-2 text-emerald-500" />
                    <p :class="['font-medium', isDark ? 'text-emerald-400' : 'text-emerald-700']">
                        You have already applied
                    </p>
                    <p :class="['text-sm mt-1', isDark ? 'text-emerald-500' : 'text-emerald-600']">
                        Check your applications for status updates
                    </p>
                </div>

                <div v-else :class="['p-4 rounded-xl text-center', isDark ? 'bg-amber-900/20 border border-amber-800' : 'bg-amber-50 border border-amber-200']">
                    <AlertCircle class="w-8 h-8 mx-auto mb-2 text-amber-500" />
                    <p :class="['font-medium', isDark ? 'text-amber-400' : 'text-amber-700']">
                        Applications Closed
                    </p>
                    <p :class="['text-sm mt-1', isDark ? 'text-amber-500' : 'text-amber-600']">
                        This award is no longer accepting applications
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Modal -->
    <Modal :show="showModal" @close="closeModal">
        <div class="p-6">
            <h2 :class="['text-2xl font-bold mb-4', isDark ? 'text-white' : 'text-slate-900']">
                Complete Your Application
            </h2>
            
            <p :class="['mb-6', isDark ? 'text-slate-400' : 'text-slate-600']">
                To apply for <span class="font-semibold">{{ award.title }}</span>, please provide the required information below.
            </p>

            <form @submit.prevent="submitApplication" class="space-y-6">
                <!-- JAMB Requirements -->
                <div v-if="award.category?.toLowerCase() === 'jamb'" class="space-y-4">
                    <div>
                        <InputLabel value="JAMB Score" />
                        <TextInput 
                            v-model="form.jamb_score"
                            type="number"
                            min="0"
                            max="400"
                            class="mt-1 block w-full"
                            placeholder="e.g. 280"
                            required
                        />
                        <InputError :message="form.errors.jamb_score" class="mt-2" />
                    </div>
                    
                    <div>
                        <InputLabel value="Upload JAMB Result" />
                        <div v-if="applicant?.documents?.find(d => d.type === 'jamb')" class="mb-2 flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                            <CheckCircle class="w-4 h-4" />
                            <span>Document already uploaded</span>
                        </div>
                        <input 
                            type="file" 
                            @change="e => form.jamb_file = e.target.files[0]"
                            accept=".pdf,.jpg,.jpeg,.png"
                            :class="['mt-1 block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold transition-colors', isDark ? 'text-slate-400 file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600' : 'text-slate-500 file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100']"
                            :required="!applicant?.documents?.find(d => d.type === 'jamb')"
                        />
                        <p class="mt-1 text-xs text-slate-500">PDF or Image (Max 2MB)</p>
                        <InputError :message="form.errors.jamb_file" class="mt-2" />
                    </div>
                </div>

                <!-- WAEC Requirements -->
                <div v-if="award.category?.toLowerCase() === 'waec'" class="space-y-4">
                     <div>
                        <InputLabel value="Upload WAEC Result" />
                        <div v-if="applicant?.documents?.find(d => d.type === 'waec')" class="mb-2 flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                            <CheckCircle class="w-4 h-4" />
                            <span>Document already uploaded</span>
                        </div>
                        <input 
                            type="file" 
                            @change="e => form.waec_file = e.target.files[0]"
                            accept=".pdf,.jpg,.jpeg,.png"
                            :class="['mt-1 block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold transition-colors', isDark ? 'text-slate-400 file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600' : 'text-slate-500 file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100']"
                             :required="!applicant?.documents?.find(d => d.type === 'waec')"
                        />
                        <p class="mt-1 text-xs text-slate-500">PDF or Image (Max 2MB)</p>
                        <InputError :message="form.errors.waec_file" class="mt-2" />
                    </div>
                </div>

                <!-- General/Other Requirements -->
                <div v-if="!['jamb', 'waec'].includes(award.category?.toLowerCase())">
                    <p :class="['text-sm italic', isDark ? 'text-slate-400' : 'text-slate-500']">
                        No additional documents are required for this category at this stage. Click submit to proceed.
                    </p>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <Button 
                        type="button" 
                        variant="secondary" 
                        @click="closeModal"
                    >
                        Cancel
                    </Button>
                    <Button 
                        type="submit" 
                        :disabled="form.processing"
                        :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                    >
                        <span v-if="form.processing">Submitting...</span>
                        <span v-else>Submit Application</span>
                    </Button>
                </div>
            </form>
        </div>
    </Modal>
</template>
