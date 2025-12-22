<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { User, Mail, CheckCircle } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';

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
</script>

<template>
    <section>
        <header>
            <h2 :class="['text-lg font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                Profile Information
            </h2>

            <p :class="['mt-1 text-sm', isDark ? 'text-slate-400' : 'text-slate-600']">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-5"
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

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p :class="['mt-2 text-sm', isDark ? 'text-slate-300' : 'text-slate-800']">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-brand-600 underline hover:text-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="alert alert-success mt-2"
                >
                    <CheckCircle class="w-5 h-5" />
                    <span class="text-sm font-medium">A new verification link has been sent to your email address.</span>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton :disabled="form.processing">
                    Save Changes
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-emerald-600 font-medium flex items-center gap-1"
                    >
                        <CheckCircle class="w-4 h-4" />
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
