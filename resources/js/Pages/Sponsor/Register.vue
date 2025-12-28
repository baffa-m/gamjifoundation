<script setup>
import { defineOptions } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Building2, Globe, MapPin, FileText, Save, Users, Briefcase, Heart, Building } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import InputError from '@/Components/InputError.vue';
import Button from '@/Components/UI/Button.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const form = useForm({
    organization_name: '',
    sponsor_type: 'corporate',
    registration_number: '',
    description: '',
    website: '',
    address: ''
});

const sponsorTypes = [
    { value: 'individual', label: 'Individual', icon: Users, desc: 'Personal sponsorship' },
    { value: 'corporate', label: 'Corporate', icon: Briefcase, desc: 'Company or business' },
    { value: 'government', label: 'Government', icon: Building, desc: 'Government agency' },
    { value: 'ngo', label: 'NGO', icon: Heart, desc: 'Non-profit organization' }
];

const submit = () => {
    form.post(route('sponsor.register.store'));
};
</script>

<template>
    <div class="max-w-3xl mx-auto space-y-8">
        <!-- Header -->
        <div class="text-center animate-fade-in-up">
            <div :class="['w-20 h-20 mx-auto mb-6 rounded-2xl flex items-center justify-center', isDark ? 'bg-brand-600/20' : 'bg-brand-100']">
                <Building2 :class="['w-10 h-10', isDark ? 'text-brand-400' : 'text-brand-600']" />
            </div>
            <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                Become a Sponsor
            </h1>
            <p :class="['mt-3 text-lg', isDark ? 'text-slate-400' : 'text-slate-600']">
                Support students and make a difference in their education
            </p>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="space-y-8">
            <!-- Sponsor Type -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6', isDark ? 'text-white' : 'text-slate-900']">
                    Sponsor Type
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button
                        v-for="type in sponsorTypes"
                        :key="type.value"
                        type="button"
                        @click="form.sponsor_type = type.value"
                        :class="[
                            'p-4 rounded-xl text-center transition-all',
                            form.sponsor_type === type.value
                                ? 'bg-brand-600 text-white ring-2 ring-brand-500 ring-offset-2' + (isDark ? ' ring-offset-slate-900' : ' ring-offset-white')
                                : isDark ? 'bg-slate-700 text-slate-300 hover:bg-slate-600' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                        ]"
                    >
                        <component :is="type.icon" class="w-8 h-8 mx-auto mb-2" />
                        <div class="font-semibold">{{ type.label }}</div>
                        <div :class="['text-xs mt-1', form.sponsor_type === type.value ? 'text-brand-100' : isDark ? 'text-slate-400' : 'text-slate-500']">
                            {{ type.desc }}
                        </div>
                    </button>
                </div>
            </div>

            <!-- Organization Details -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Building2 class="w-5 h-5 text-brand-500" />
                    Organization Details
                </h2>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                            Organization / Your Name *
                        </label>
                        <input v-model="form.organization_name" type="text" class="input-premium" 
                            placeholder="e.g., ABC Foundation or John Doe" />
                        <InputError :message="form.errors.organization_name" />
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                            Registration Number (Optional)
                        </label>
                        <input v-model="form.registration_number" type="text" class="input-premium" 
                            placeholder="CAC/NGO Registration Number" />
                        <InputError :message="form.errors.registration_number" />
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                            About You / Organization
                        </label>
                        <textarea v-model="form.description" rows="3" class="input-premium" 
                            placeholder="Brief description of your mission..." />
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <MapPin class="w-5 h-5 text-brand-500" />
                    Contact Information
                </h2>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                            Address *
                        </label>
                        <input v-model="form.address" type="text" class="input-premium" 
                            placeholder="Office or Contact Address" />
                        <InputError :message="form.errors.address" />
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                            Website (Optional)
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <Globe :class="['h-5 w-5', isDark ? 'text-slate-500' : 'text-slate-400']" />
                            </div>
                            <input v-model="form.website" type="url" class="input-premium pl-12" 
                                placeholder="https://yourwebsite.com" />
                        </div>
                        <InputError :message="form.errors.website" />
                    </div>
                </div>
            </div>

            <!-- Notice -->
            <div :class="['p-6 rounded-2xl', isDark ? 'bg-brand-900/30 border border-brand-800' : 'bg-brand-50 border border-brand-200']">
                <p :class="['text-sm', isDark ? 'text-brand-200' : 'text-brand-700']">
                    <strong>Note:</strong> Your sponsor profile will be reviewed by our admin team. 
                    Once approved, you'll be able to create and manage awards for applicants.
                </p>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-center">
                <Button type="submit" :disabled="form.processing" size="lg" class="px-12">
                    <Save class="w-5 h-5" /> Submit for Review
                </Button>
            </div>
        </form>
    </div>
</template>
