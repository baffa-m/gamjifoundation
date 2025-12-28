<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Mail, Lock, Eye, EyeOff, ArrowRight, CheckCircle, Shield, Zap } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import Checkbox from '@/Components/Checkbox.vue';
import TextInput from '@/Components/TextInput.vue';

import logo from '@/../images/logo.jpeg';

const { isDark } = useTheme();

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);
const isSubmitting = ref(false);

const submit = () => {
    isSubmitting.value = true;
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
            isSubmitting.value = false;
        },
    });
};

const features = [
    { icon: Zap, text: 'Lightning fast' },
    { icon: CheckCircle, text: 'Trusted by 12K+ students' }
];
</script>

<template>
    <Head title="Sign In - GAMJI Foundation" />

    <div :class="['min-h-screen flex transition-colors duration-500', isDark ? 'bg-slate-950' : 'auth-page-bg']">
        <!-- Left Side - Form -->
        <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <Link href="/" class="inline-flex items-center gap-3 mb-8 group animate-fade-in">
                    <div class="relative">
                        <div class="absolute inset-0 bg-brand-500 rounded-2xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                        <div class="relative w-14 h-14 bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl shadow-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300 overflow-hidden">
                            <img :src="logo" alt="Gamji" class="w-full h-full object-cover" />
                        </div>
                    </div>
                    <div>
                        <h1 :class="['text-2xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">GAMJI</h1>
                        <p :class="['text-xs font-medium', isDark ? 'text-brand-400' : 'text-slate-500']">Foundation</p>
                    </div>
                </Link>

                <!-- Auth Card -->
                <div :class="['auth-card animate-fade-in-up p-8 rounded-2xl shadow-xl transition-all duration-300', isDark ? 'bg-slate-900 border border-slate-800' : 'bg-white/80 backdrop-blur-xl border border-white/50']" style="animation-delay: 0.1s;">
                    <!-- Header -->
                    <div class="mb-8">
                        <h2 :class="['text-3xl font-bold font-display mb-2', isDark ? 'text-white' : 'text-slate-900']">
                            Welcome back! 👋
                        </h2>
                        <p :class="isDark ? 'text-slate-400' : 'text-slate-600'">
                            Sign in to continue your journey
                        </p>
                    </div>

                    <!-- Status Message -->
                    <div v-if="status" class="alert alert-success mb-6 animate-slide-in-right">
                        <CheckCircle class="w-5 h-5 flex-shrink-0" />
                        <p class="text-sm font-medium">{{ status }}</p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-5">
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
                                    autofocus
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
                                    autocomplete="current-password"
                                    class="pl-12 pr-12"
                                    :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.password }"
                                    placeholder="Enter your password"
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
                            <p v-if="form.errors.password" class="text-sm text-red-600 flex items-center gap-1 animate-fade-in">
                                <span class="w-1 h-1 rounded-full bg-red-600"></span>
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer group">
                                <Checkbox
                                    name="remember"
                                    v-model:checked="form.remember"
                                />
                                <span :class="['ml-2 text-sm transition-colors', isDark ? 'text-slate-400 group-hover:text-slate-200' : 'text-slate-600 group-hover:text-slate-900']">
                                    Remember me
                                </span>
                            </label>

                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                :class="['text-sm font-semibold transition-colors', isDark ? 'text-brand-400 hover:text-brand-300' : 'text-brand-600 hover:text-brand-700']"
                            >
                                Forgot password?
                            </Link>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="group relative w-full bg-gradient-to-r from-brand-600 to-brand-500 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-brand-700 hover:to-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 shadow-lg shadow-brand-600/30 hover:shadow-xl hover:shadow-brand-600/40 hover:-translate-y-0.5"
                            :class="{ 'btn-loading': form.processing }"
                        >
                            <span class="flex items-center justify-center gap-2">
                                <span>Sign in to your account</span>
                                <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                            </span>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="divider my-8 flex items-center text-sm text-slate-500">
                        <div :class="['flex-1 h-px', isDark ? 'bg-slate-700' : 'bg-slate-200']"></div>
                        <span :class="['px-4', isDark ? 'text-slate-400' : 'text-slate-500']">New to GAMJI?</span>
                        <div :class="['flex-1 h-px', isDark ? 'bg-slate-700' : 'bg-slate-200']"></div>
                    </div>

                    <!-- Register Link -->
                    <Link
                        :href="route('register')"
                        :class="['block w-full text-center py-3.5 px-6 border-2 font-semibold rounded-xl transition-all duration-300', isDark ? 'border-slate-700 text-slate-300 hover:border-brand-500 hover:bg-slate-800 hover:text-brand-400' : 'border-slate-200 text-slate-700 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700']"
                    >
                        Create a free account
                    </Link>
                </div>

                <!-- Features -->
                <div :class="['mt-8 flex items-center justify-center gap-6 text-xs animate-fade-in', isDark ? 'text-slate-400' : 'text-slate-600']" style="animation-delay: 0.3s;">
                    <div v-for="feature in features" :key="feature.text" class="flex items-center gap-1.5">
                        <component :is="feature.icon" :class="['w-4 h-4', isDark ? 'text-brand-400' : 'text-brand-600']" />
                        <span>{{ feature.text }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Hero Image/Illustration -->
        <div class="hidden lg:flex lg:flex-1 bg-gradient-to-br from-brand-600 to-brand-800 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center justify-center p-16 text-white">
                <div class="max-w-md space-y-8 animate-fade-in-up">
                    <div class="w-20 h-20 bg-white/10 backdrop-blur-lg rounded-2xl flex items-center justify-center mb-8 overflow-hidden">
                        <img :src="logo" alt="Gamji" class="w-full h-full object-cover" />
                    </div>

                    <h2 class="text-4xl font-bold font-display leading-tight">
                        Empowering Northern Nigerian Students to Achieve Excellence
                    </h2>

                    <p class="text-xl text-brand-100 leading-relaxed">
                        Join thousands of students already benefiting from scholarships, mentorship, and career guidance.
                    </p>

                    <div class="grid grid-cols-3 gap-6 pt-8">
                        <div>
                            <div class="text-3xl font-bold mb-1">XX</div>
                            <div class="text-sm text-brand-200">Awards Given</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold mb-1">XX</div>
                            <div class="text-sm text-brand-200">Active Users</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold mb-1">₦XX</div>
                            <div class="text-sm text-brand-200">Disbursed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>