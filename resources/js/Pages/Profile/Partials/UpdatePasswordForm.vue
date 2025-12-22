<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Lock, Eye, EyeOff, CheckCircle } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';

const { isDark } = useTheme();

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
            }
            if (form.errors.current_password) {
                form.reset('current_password');
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 :class="['text-lg font-semibold', isDark ? 'text-white' : 'text-slate-900']">
                Update Password
            </h2>

            <p :class="['mt-1 text-sm', isDark ? 'text-slate-400' : 'text-slate-600']">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-5">
            <!-- Current Password -->
            <div class="space-y-2">
                <label for="current_password" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                    Current Password
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <Lock :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                    </div>
                    <input
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        :type="showCurrentPassword ? 'text' : 'password'"
                        class="input-premium pl-12 pr-12"
                        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.current_password }"
                        autocomplete="current-password"
                        placeholder="Enter current password"
                    />
                    <button
                        type="button"
                        @click="showCurrentPassword = !showCurrentPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                    >
                        <Eye v-if="!showCurrentPassword" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <!-- New Password -->
            <div class="space-y-2">
                <label for="password" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                    New Password
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <Lock :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                    </div>
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        :type="showNewPassword ? 'text' : 'password'"
                        class="input-premium pl-12 pr-12"
                        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.password }"
                        autocomplete="new-password"
                        placeholder="Enter new password"
                    />
                    <button
                        type="button"
                        @click="showNewPassword = !showNewPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                    >
                        <Eye v-if="!showNewPassword" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                    Confirm New Password
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <Lock :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                    </div>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        class="input-premium pl-12 pr-12"
                        autocomplete="new-password"
                        placeholder="Confirm new password"
                    />
                    <button
                        type="button"
                        @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                    >
                        <Eye v-if="!showConfirmPassword" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton :disabled="form.processing">
                    Update Password
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
                        Password Updated.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
