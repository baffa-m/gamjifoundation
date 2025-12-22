<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Mail, Lock, Eye, EyeOff, ArrowRight, CheckCircle, Shield, Zap } from 'lucide-vue-next';

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
    { icon: Shield, text: 'Bank-level security' },
    { icon: Zap, text: 'Lightning fast' },
    { icon: CheckCircle, text: 'Trusted by 12K+ students' }
];
</script>

<template>
    <Head title="Sign In - GAMJI Foundation" />

    <div class="min-h-screen auth-page-bg flex">
        <!-- Left Side - Form -->
        <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <Link href="/" class="inline-flex items-center gap-3 mb-8 group animate-fade-in">
                    <div class="relative">
                        <div class="absolute inset-0 bg-brand-500 rounded-2xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                        <div class="relative w-14 h-14 bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl shadow-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold font-display text-slate-900">GAMJI</h1>
                        <p class="text-xs text-slate-500 font-medium">Foundation</p>
                    </div>
                </Link>

                <!-- Auth Card -->
                <div class="auth-card animate-fade-in-up" style="animation-delay: 0.1s;">
                    <!-- Header -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold font-display text-slate-900 mb-2">
                            Welcome back! 👋
                        </h2>
                        <p class="text-slate-600">
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
                            <label for="email" class="block text-sm font-semibold text-slate-700">
                                Email address
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <Mail class="h-5 w-5 text-slate-400 group-focus-within:text-brand-500 transition-colors" />
                                </div>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="input-premium pl-12"
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
                            <label for="password" class="block text-sm font-semibold text-slate-700">
                                Password
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <Lock class="h-5 w-5 text-slate-400 group-focus-within:text-brand-500 transition-colors" />
                                </div>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    class="input-premium pl-12 pr-12"
                                    :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.password }"
                                    placeholder="Enter your password"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
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
                                <input
                                    v-model="form.remember"
                                    type="checkbox"
                                    class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-slate-300 rounded cursor-pointer transition-all"
                                />
                                <span class="ml-2 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">
                                    Remember me
                                </span>
                            </label>

                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors"
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
                    <div class="divider my-8">
                        <span>New to GAMJI?</span>
                    </div>

                    <!-- Register Link -->
                    <Link
                        :href="route('register')"
                        class="block w-full text-center py-3.5 px-6 border-2 border-slate-200 text-slate-700 font-semibold rounded-xl hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700 transition-all duration-300"
                    >
                        Create a free account
                    </Link>

                    <!-- Account Types Info -->
                    <div class="mt-8 pt-8 border-t border-slate-200">
                        <p class="text-xs text-center text-slate-500 font-medium mb-4 uppercase tracking-wider">
                            Account Types Available
                        </p>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="text-center p-3 bg-blue-50 rounded-xl border border-blue-100 hover:border-blue-300 transition-colors">
                                <div class="text-2xl mb-1">🎓</div>
                                <p class="text-xs font-bold text-blue-900">Applicant</p>
                                <p class="text-[10px] text-blue-600 mt-0.5">Apply for awards</p>
                            </div>
                            <div class="text-center p-3 bg-emerald-50 rounded-xl border border-emerald-100 hover:border-emerald-300 transition-colors">
                                <div class="text-2xl mb-1">💼</div>
                                <p class="text-xs font-bold text-emerald-900">Sponsor</p>
                                <p class="text-[10px] text-emerald-600 mt-0.5">Fund programs</p>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-xl border border-purple-100 hover:border-purple-300 transition-colors">
                                <div class="text-2xl mb-1">⚡</div>
                                <p class="text-xs font-bold text-purple-900">Admin</p>
                                <p class="text-[10px] text-purple-600 mt-0.5">Manage all</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="mt-8 flex items-center justify-center gap-6 text-xs text-slate-600 animate-fade-in" style="animation-delay: 0.3s;">
                    <div v-for="feature in features" :key="feature.text" class="flex items-center gap-1.5">
                        <component :is="feature.icon" class="w-4 h-4 text-brand-600" />
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
                    <div class="w-20 h-20 bg-white/10 backdrop-blur-lg rounded-2xl flex items-center justify-center mb-8">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>

                    <h2 class="text-4xl font-bold font-display leading-tight">
                        Empowering Nigerian Students to Achieve Excellence
                    </h2>

                    <p class="text-xl text-brand-100 leading-relaxed">
                        Join thousands of students already benefiting from scholarships, mentorship, and career guidance.
                    </p>

                    <div class="grid grid-cols-3 gap-6 pt-8">
                        <div>
                            <div class="text-3xl font-bold mb-1">500+</div>
                            <div class="text-sm text-brand-200">Awards Given</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold mb-1">12K+</div>
                            <div class="text-sm text-brand-200">Active Users</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold mb-1">₦45M</div>
                            <div class="text-sm text-brand-200">Disbursed</div>
                        </div>
                    </div>

                    <!-- Testimonial -->
                    <div class="mt-12 p-6 bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-full bg-brand-400 flex items-center justify-center text-white font-bold">
                                AO
                            </div>
                            <div>
                                <div class="font-semibold">Aisha Okonkwo</div>
                                <div class="text-sm text-brand-200">JAMB Scholar 2024</div>
                            </div>
                        </div>
                        <p class="text-brand-50 italic">
                            "GAMJI changed my life. I received a full scholarship and mentorship that helped me get into my dream university."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>