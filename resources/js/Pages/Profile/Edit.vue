<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateApplicantInformationForm from './Partials/UpdateApplicantInformationForm.vue';
import UpdateSponsorInformationForm from './Partials/UpdateSponsorInformationForm.vue';
import ProfileAvatar from '@/Components/ProfileAvatar.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { User, Mail, Calendar, Shield, Award, FileText } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';

const { isDark } = useTheme();

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    applicant: {
        type: Object,
        default: null,
    },
    sponsor: {
        type: Object,
        default: null,
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// Get role badge class
const roleBadgeClass = computed(() => {
    const role = user.value.role || 'applicant';
    return `profile-badge-${role}`;
});

// Format join date
const joinDate = computed(() => {
    if (user.value.created_at) {
        return new Date(user.value.created_at).toLocaleDateString('en-US', {
            month: 'long',
            year: 'numeric'
        });
    }
    return 'Recently';
});

// Role-specific stats (placeholder data - you can fetch real data from backend)
const stats = computed(() => {
    const role = user.value.role || 'applicant';
    
    if (role === 'admin') {
        return [
            { label: 'Total Users', value: '1,234', icon: User },
            { label: 'Active Awards', value: '45', icon: Award },
            { label: 'Applications', value: '892', icon: FileText },
        ];
    } else if (role === 'sponsor') {
        return [
            { label: 'Awards Created', value: '8', icon: Award },
            { label: 'Total Funding', value: '₦2.5M', icon: Shield },
            { label: 'Applicants', value: '156', icon: User },
        ];
    } else {
        return [
            { label: 'Applications', value: '3', icon: FileText },
            { label: 'Awards Won', value: '1', icon: Award },
            { label: 'Profile Views', value: '24', icon: User },
        ];
    }
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div :class="['min-h-screen py-12 transition-colors duration-500', isDark ? 'bg-slate-950' : 'bg-slate-50']">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                
                <!-- Simple Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                    <div class="flex items-center gap-6">
                        <ProfileAvatar 
                            :name="user.name" 
                            size="xl"
                            :show-status="true"
                            :is-online="true"
                            class="shadow-lg"
                        />
                        <div>
                            <h1 :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                                {{ user.name }}
                            </h1>
                            <div class="flex items-center gap-2 mt-1">
                                <span :class="['text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                                    {{ user.email }}
                                </span>
                                <span :class="['px-2 py-0.5 rounded-full text-xs font-bold border capitalize', roleBadgeClass]">
                                    {{ user.role || 'Member' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Simple Stats -->
                    <div class="flex gap-8">
                         <div v-for="stat in stats" :key="stat.label" :class="isDark ? 'text-slate-300' : 'text-slate-600'">
                            <div :class="['text-2xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">{{ stat.value }}</div>
                            <div class="text-xs font-medium uppercase tracking-wider opacity-70">{{ stat.label }}</div>
                         </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">
                        
                         <!-- Role Specific Information -->
                        <div v-if="applicant" class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
                            <UpdateApplicantInformationForm :applicant="applicant" />
                        </div>

                        <div v-if="sponsor" class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
                            <UpdateSponsorInformationForm :sponsor="sponsor" />
                        </div>

                        <!-- Profile Information -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                            />
                        </div>

                        <!-- Update Password -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
                            <UpdatePasswordForm />
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="space-y-8 lg:sticky lg:top-8 lg:self-start">
                        <!-- Account Security -->
                         <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                            <h3 :class="['text-lg font-bold mb-6', isDark ? 'text-white' : 'text-slate-900']">
                                Account Security
                            </h3>
                            <div class="space-y-6">
                                <div class="flex gap-4">
                                    <Shield class="w-5 h-5 text-emerald-500 mt-1" />
                                    <div>
                                        <div :class="['text-sm font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                                            Email Verified
                                        </div>
                                        <div :class="['text-xs mt-1', isDark ? 'text-slate-400' : 'text-slate-500']">
                                            Your email address has been verified.
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <Shield class="w-5 h-5 text-blue-500 mt-1" />
                                    <div>
                                        <div :class="['text-sm font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                                            Secure Password
                                        </div>
                                        <div :class="['text-xs mt-1', isDark ? 'text-slate-400' : 'text-slate-500']">
                                            Use a strong, unique password.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Actions -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                            <DeleteUserForm />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
