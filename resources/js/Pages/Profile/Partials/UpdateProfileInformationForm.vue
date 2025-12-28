<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { User, Mail, CheckCircle, Clock } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import { computed } from 'vue';

const { isDark } = useTheme();

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

// Format last updated time
const lastUpdated = computed(() => {
    if (user.updated_at) {
        return new Date(user.updated_at).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }
    return 'Never';
});
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 :class="['text-lg font-bold', isDark ? 'text-white' : 'text-slate-900']">
                Profile Information
            </h2>
            <p :class="['mt-1 text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="space-y-6"
        >
            <!-- Name -->
            <div class="space-y-2">
                <label for="name" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                    Full Name
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <User :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                    </div>
                    <input
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        class="input-premium pl-12"
                        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.name }"
                        placeholder="Your full name"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <label for="email" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                    Email Address
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <Mail :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                    </div>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        class="input-premium pl-12"
                        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.email }"
                        placeholder="your.email@example.com"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Email Verification Alert -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="alert alert-warning">
                <div class="flex items-start gap-3">
                    <Mail class="w-5 h-5 flex-shrink-0 mt-0.5" />
                    <div class="flex-grow">
                        <p class="text-sm font-medium">
                            Your email address is unverified.
                        </p>
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="mt-2 text-sm font-semibold text-amber-700 dark:text-amber-300 underline hover:text-amber-800 dark:hover:text-amber-200 transition-colors"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Verification Success -->
            <Transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 transform scale-95"
                enter-to-class="opacity-100 transform scale-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100 transform scale-100"
                leave-to-class="opacity-0 transform scale-95"
            >
                <div
                    v-show="status === 'verification-link-sent'"
                    class="alert alert-success"
                >
                    <CheckCircle class="w-5 h-5 flex-shrink-0" />
                    <span class="text-sm font-medium">A new verification link has been sent to your email address.</span>
                </div>
            </Transition>

            <!-- Submit Button -->
            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton 
                    :disabled="form.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                >
                    <span v-if="!form.processing">Save Changes</span>
                    <span v-else class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="opacity-0 translate-x-2"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="opacity-100 translate-x-0"
                    leave-to-class="opacity-0 translate-x-2"
                >
                    <div
                        v-if="form.recentlySuccessful"
                        class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-medium text-sm"
                    >
                        <CheckCircle class="w-5 h-5" />
                        Saved successfully!
                    </div>
                </Transition>
            </div>
        </form>
    </section>
</template>
