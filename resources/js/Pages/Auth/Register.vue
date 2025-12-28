```
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { User, Mail, Lock, Eye, EyeOff, Check, ArrowRight, Sparkles } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Checkbox from '@/Components/Checkbox.vue';
import TextInput from '@/Components/TextInput.vue';

import logo from '@/../images/logo.jpeg';

const { isDark } = useTheme();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    account_type: 'applicant',
    terms: false,
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);
const currentStep = ref(1);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const accountTypes = [
    {
        value: 'applicant',
        label: 'Applicant',
        description: 'Apply for scholarships and awards',
        icon: '🎓',
        color: 'blue',
        benefits: ['Browse awards', 'Track applications', 'Access resources']
    },
    {
        value: 'sponsor',
        label: 'Sponsor',
        description: 'Create and fund award programs',
        icon: '💼',
        color: 'emerald',
        benefits: ['Create awards', 'Manage applications', 'Impact lives']
    }
];

const passwordStrength = (password) => {
    if (!password) return 0;
    let strength = 0;
    if (password.length >= 8) strength += 25;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
    if (/\d/.test(password)) strength += 25;
    if (/[^a-zA-Z0-9]/.test(password)) strength += 25;
    return strength;
};

const passwordStrengthText = (strength) => {
    if (strength === 0) return { text: '', color: '' };
    if (strength <= 25) return { text: 'Weak', color: 'text-red-600' };
    if (strength <= 50) return { text: 'Fair', color: 'text-amber-600' };
    if (strength <= 75) return { text: 'Good', color: 'text-blue-600' };
    return { text: 'Strong', color: 'text-emerald-600' };
};
</script>

<template>
    <Head title="Create Account - GAMJI Foundation" />

    <div :class="['min-h-screen flex transition-colors duration-500', isDark ? 'bg-slate-950' : 'auth-page-bg']">
        <!-- Left Side - Hero -->
        <div class="hidden lg:flex lg:flex-1 bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 relative overflow-hidden">
            <!-- Animated Background -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse-slow"></div>
                <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-white rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s;"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center p-16 text-white max-w-lg mx-auto">
                <div class="space-y-6 animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-lg rounded-full border border-white/20 text-sm font-semibold">
                        <Sparkles class="w-4 h-4" />
                        <span>Join 12,000+ students</span>
                    </div>

                    <h1 class="text-5xl font-bold font-display leading-tight">
                        Your Journey to Academic Excellence Starts Here
                    </h1>

                    <p class="text-xl text-brand-100 leading-relaxed">
                        Create your free account and unlock access to scholarships, mentorship, and career opportunities.
                    </p>

                    <!-- Benefits -->
                    <div class="space-y-4 pt-8">
                        <div v-for="(benefit, index) in ['Zero application fees', 'Real-time tracking', 'Expert guidance', 'Trusted by top universities']" 
                             :key="index"
                             class="flex items-center gap-3 animate-fade-in"
                             :style="`animation-delay: ${0.1 * (index + 1)}s`">
                            <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-lg flex items-center justify-center">
                                <Check class="w-5 h-5 text-white" />
                            </div>
                            <span class="text-brand-50 font-medium">{{ benefit }}</span>
                        </div>
                    </div>

                    <!-- Social Proof -->
                    <div class="flex items-center gap-4 pt-8">
                        <div class="flex -space-x-3">
                            <div v-for="i in 4" :key="i" class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ String.fromCharCode(65 + i) }}
                            </div>
                        </div>
                        <div class="text-sm">
                            <div class="font-semibold">2,345 joined this month</div>
                            <div class="text-brand-200">Be part of the movement</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
            <div class="w-full max-w-md">
                <!-- Logo (Mobile) -->
                <Link href="/" class="lg:hidden inline-flex items-center gap-3 mb-8 group animate-fade-in">
                    <div class="relative">
                        <div class="absolute inset-0 bg-brand-500 rounded-2xl blur-xl opacity-50"></div>
                        <div class="relative w-12 h-12 bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl shadow-xl flex items-center justify-center overflow-hidden">
                            <img :src="logo" alt="Gamji" class="w-full h-full object-cover" />
                        </div>
                    </div>
                    <span :class="['text-xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">GAMJI</span>
                </Link>

                <!-- Auth Card -->
                <div :class="['auth-card animate-fade-in-up p-8 rounded-2xl shadow-xl transition-all duration-300', isDark ? 'bg-slate-900 border border-slate-800' : 'bg-white/80 backdrop-blur-xl border border-white/50']" style="animation-delay: 0.1s;">
                    <!-- Header -->
                    <div class="mb-8">
                        <h2 :class="['text-3xl font-bold font-display mb-2', isDark ? 'text-white' : 'text-slate-900']">
                            Create your account ✨
                        </h2>
                        <p :class="isDark ? 'text-slate-400' : 'text-slate-600'">
                            Start your journey to academic excellence
                        </p>
                    </div>

                    <!-- Progress Steps -->
                    <div class="flex items-center gap-2 mb-8">
                        <div :class="['flex-1 h-2 rounded-full transition-all duration-500', currentStep >= 1 ? 'bg-brand-500' : (isDark ? 'bg-slate-700' : 'bg-slate-200')]"></div>
                        <div :class="['flex-1 h-2 rounded-full transition-all duration-500', currentStep >= 2 ? 'bg-brand-500' : (isDark ? 'bg-slate-700' : 'bg-slate-200')]"></div>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Step 1: Account Type -->
                        <div v-show="currentStep === 1" class="space-y-6">
                            <div>
                                <label :class="['block text-sm font-semibold mb-4', isDark ? 'text-slate-300' : 'text-slate-700']">
                                    Choose your account type
                                </label>
                                <div class="grid grid-cols-1 gap-4">
                                    <label
                                        v-for="type in accountTypes"
                                        :key="type.value"
                                        class="relative cursor-pointer group"
                                    >
                                        <input
                                            type="radio"
                                            v-model="form.account_type"
                                            :value="type.value"
                                            class="sr-only"
                                        />
                                        <div
                                            :class="[
                                                'p-5 rounded-2xl border-2 transition-all duration-300',
                                                form.account_type === type.value
                                                    ? (isDark ? 'border-brand-500 bg-brand-900/20 ring-4 ring-brand-500/10' : 'border-brand-500 bg-brand-50 ring-4 ring-brand-100')
                                                    : (isDark ? 'border-slate-700 bg-slate-800 hover:border-slate-600' : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-md')
                                            ]"
                                        >
                                            <div class="flex items-start gap-4">
                                                <div class="text-4xl">{{ type.icon }}</div>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <h3 :class="['font-bold', isDark ? 'text-white' : 'text-slate-900']">{{ type.label }}</h3>
                                                        <div
                                                            v-if="form.account_type === type.value"
                                                            class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center animate-fade-in"
                                                        >
                                                            <Check class="w-4 h-4 text-white" />
                                                        </div>
                                                    </div>
                                                    <p :class="['text-sm mb-3', isDark ? 'text-slate-400' : 'text-slate-600']">{{ type.description }}</p>
                                                    <div class="space-y-1.5">
                                                        <div v-for="benefit in type.benefits" :key="benefit" :class="['flex items-center gap-2 text-xs', isDark ? 'text-slate-500' : 'text-slate-500']">
                                                            <div class="w-1 h-1 rounded-full bg-brand-500"></div>
                                                            <span>{{ benefit }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <button
                                type="button"
                                @click="currentStep = 2"
                                class="w-full bg-gradient-to-r from-brand-600 to-brand-500 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-brand-700 hover:to-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-100 transition-all duration-300 shadow-lg shadow-brand-600/30 hover:shadow-xl hover:shadow-brand-600/40 hover:-translate-y-0.5 flex items-center justify-center gap-2 group"
                            >
                                <span>Continue</span>
                                <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                            </button>
                        </div>

                        <!-- Step 2: Details -->
                        <div v-show="currentStep === 2" class="space-y-5">
                            <!-- Name -->
                            <div class="space-y-2">
                                <label for="name" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                                    Full Name
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <User :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                                    </div>
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    autocomplete="name"
                                    class="pl-12"
                                    :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.name }"
                                    placeholder="John Doe"
                                />
                                </div>
                                <p v-if="form.errors.name" class="text-sm text-red-600 flex items-center gap-1 animate-fade-in">
                                    <span class="w-1 h-1 rounded-full bg-red-600"></span>
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                                    Email address
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <Mail :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                                    </div>
                                <TextInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autocomplete="username"
                                    class="pl-12"
                                    :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.email }"
                                    placeholder="your.email@example.com"
                                />
                                </div>
                                <p v-if="form.errors.email" class="text-sm text-red-600 flex items-center gap-1 animate-fade-in">
                                    <span class="w-1 h-1 rounded-full bg-red-600"></span>
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <label for="password" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                                    Password
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <Lock :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                                    </div>
                                <TextInput
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    autocomplete="new-password"
                                    class="pl-12 pr-12"
                                    :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.password }"
                                    placeholder="Create a strong password"
                                />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        :class="['absolute inset-y-0 right-0 pr-4 flex items-center transition-colors', isDark ? 'text-slate-500 hover:text-slate-300' : 'text-slate-400 hover:text-slate-600']"
                                    >
                                        <Eye v-if="!showPassword" class="h-5 w-5" />
                                        <EyeOff v-else class="h-5 w-5" />
                                    </button>
                                </div>
                                <!-- Password Strength -->
                                <div v-if="form.password" class="space-y-2 animate-fade-in">
                                    <div :class="['progress-bar h-1 rounded-full overflow-hidden', isDark ? 'bg-slate-700' : 'bg-slate-100']">
                                        <div 
                                            class="progress-bar-fill h-full transition-all duration-300"
                                            :class="{
                                                'bg-red-500': passwordStrength(form.password) <= 25,
                                                'bg-amber-500': passwordStrength(form.password) > 25 && passwordStrength(form.password) <= 50,
                                                'bg-blue-500': passwordStrength(form.password) > 50 && passwordStrength(form.password) <= 75,
                                                'bg-emerald-500': passwordStrength(form.password) > 75
                                            }"
                                            :style="{ width: `${passwordStrength(form.password)}%` }"
                                        ></div>
                                    </div>
                                    <p :class="['text-xs font-semibold', passwordStrengthText(passwordStrength(form.password)).color]">
                                        {{ passwordStrengthText(passwordStrength(form.password)).text }}
                                    </p>
                                </div>
                                <p v-if="form.errors.password" class="text-sm text-red-600 flex items-center gap-1 animate-fade-in">
                                    <span class="w-1 h-1 rounded-full bg-red-600"></span>
                                    {{ form.errors.password }}
                                </p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label for="password_confirmation" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                                    Confirm Password
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <Lock :class="['h-5 w-5 transition-colors', isDark ? 'text-slate-500 group-focus-within:text-brand-400' : 'text-slate-400 group-focus-within:text-brand-500']" />
                                    </div>
                                <TextInput
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    :type="showPasswordConfirmation ? 'text' : 'password'"
                                    required
                                    autocomplete="new-password"
                                    class="pl-12 pr-12"
                                    placeholder="Confirm your password"
                                />
                                    <button
                                        type="button"
                                        @click="showPasswordConfirmation = !showPasswordConfirmation"
                                        :class="['absolute inset-y-0 right-0 pr-4 flex items-center transition-colors', isDark ? 'text-slate-500 hover:text-slate-300' : 'text-slate-400 hover:text-slate-600']"
                                    >
                                        <Eye v-if="!showPasswordConfirmation" class="h-5 w-5" />
                                        <EyeOff v-else class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <!-- Terms -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <Checkbox
                                    name="terms"
                                    v-model:checked="form.terms"
                                    required
                                    class="w-5 h-5 mt-0.5"
                                />
                                <span :class="['text-sm leading-relaxed', isDark ? 'text-slate-400' : 'text-slate-600']">
                                    I agree to the
                                    <Link href="/terms" class="font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                                        Terms of Service
                                    </Link>
                                    and
                                    <Link href="/privacy" class="font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                                        Privacy Policy
                                    </Link>
                                </span>
                            </label>

                            <!-- Buttons -->
                            <div class="flex gap-3">
                                <button
                                    type="button"
                                    @click="currentStep = 1"
                                    :class="['px-6 py-3.5 border-2 font-semibold rounded-xl transition-all duration-300', isDark ? 'border-slate-700 text-slate-300 hover:border-slate-600 hover:bg-slate-800' : 'border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-50']"
                                >
                                    Back
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing || !form.terms"
                                    class="flex-1 bg-gradient-to-r from-brand-600 to-brand-500 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-brand-700 hover:to-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 shadow-lg shadow-brand-600/30 hover:shadow-xl hover:shadow-brand-600/40 hover:-translate-y-0.5 flex items-center justify-center gap-2 group"
                                    :class="{ 'btn-loading': form.processing }"
                                >
                                    <span>Create Account</span>
                                    <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div :class="['mt-8 text-center text-sm', isDark ? 'text-slate-400' : 'text-slate-600']">
                        Already have an account?
                        <Link
                            :href="route('login')"
                            class="font-semibold text-brand-600 hover:text-brand-700 transition-colors"
                        >
                            Sign in
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>