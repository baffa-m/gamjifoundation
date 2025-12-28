<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Lock, Eye, EyeOff, CheckCircle, Shield, X, Check } from 'lucide-vue-next';
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

// Password strength calculation
const passwordStrength = computed(() => {
    const password = form.password;
    if (!password) return { level: 0, label: '', class: '' };
    
    let strength = 0;
    
    // Length check
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // Complexity checks
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    const levels = [
        { level: 0, label: '', class: '' },
        { level: 1, label: 'Weak', class: 'password-strength-weak' },
        { level: 2, label: 'Weak', class: 'password-strength-weak' },
        { level: 3, label: 'Medium', class: 'password-strength-medium' },
        { level: 4, label: 'Good', class: 'password-strength-good' },
        { level: 5, label: 'Strong', class: 'password-strength-strong' }
    ];
    
    return levels[strength];
});

// Password requirements
const requirements = computed(() => [
    { 
        label: 'At least 8 characters', 
        met: form.password.length >= 8 
    },
    { 
        label: 'Contains uppercase & lowercase', 
        met: /[a-z]/.test(form.password) && /[A-Z]/.test(form.password)
    },
    { 
        label: 'Contains a number', 
        met: /[0-9]/.test(form.password)
    },
    { 
        label: 'Contains special character', 
        met: /[^a-zA-Z0-9]/.test(form.password)
    }
]);

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
        <header class="mb-6">
            <h2 :class="['text-lg font-bold', isDark ? 'text-white' : 'text-slate-900']">
                Update Password
            </h2>
            <p :class="['mt-1 text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-6">
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
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
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
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                    >
                        <Eye v-if="!showNewPassword" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                    </button>
                </div>
                
                <!-- Password Strength Indicator -->
                <div v-if="form.password" class="mt-3 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span :class="['font-medium', isDark ? 'text-slate-400' : 'text-slate-600']">
                            Password Strength:
                        </span>
                        <span :class="[
                            'font-bold',
                            passwordStrength.level <= 2 ? 'text-red-500' : '',
                            passwordStrength.level === 3 ? 'text-amber-500' : '',
                            passwordStrength.level === 4 ? 'text-emerald-500' : '',
                            passwordStrength.level === 5 ? 'text-green-600' : ''
                        ]">
                            {{ passwordStrength.label }}
                        </span>
                    </div>
                    <div class="h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div :class="['password-strength-bar', passwordStrength.class]"></div>
                    </div>
                </div>
                
                <!-- Password Requirements -->
                <div v-if="form.password" :class="['mt-3 p-3 rounded-lg border', isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-slate-50 border-slate-200']">
                    <div class="text-xs font-semibold mb-2" :class="isDark ? 'text-slate-300' : 'text-slate-700'">
                        Password Requirements:
                    </div>
                    <div class="space-y-1">
                        <div 
                            v-for="(req, index) in requirements" 
                            :key="index"
                            class="flex items-center gap-2 text-xs"
                        >
                            <Check v-if="req.met" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" />
                            <X v-else class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" />
                            <span :class="req.met ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400'">
                                {{ req.label }}
                            </span>
                        </div>
                    </div>
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
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                    >
                        <Eye v-if="!showConfirmPassword" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton 
                    :disabled="form.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                >
                    <span v-if="!form.processing">Update Password</span>
                    <span v-else class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Updating...
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
                        Password updated!
                    </div>
                </Transition>
            </div>
        </form>
    </section>
</template>
