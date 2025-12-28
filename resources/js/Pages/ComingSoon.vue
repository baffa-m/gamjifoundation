<script setup>
import { defineOptions } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, Construction } from 'lucide-vue-next';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useTheme } from '@/Composables/useTheme';

defineOptions({ layout: GuestLayout });

const { isDark } = useTheme();
</script>

<template>
    <div :class="['min-h-screen flex items-center justify-center p-4', isDark ? 'bg-slate-950' : 'bg-slate-50']">
        <div class="text-center max-w-lg mx-auto animate-fade-in-up">
            <div :class="['w-24 h-24 mx-auto mb-8 rounded-full flex items-center justify-center', isDark ? 'bg-brand-900/30 text-brand-400' : 'bg-brand-100 text-brand-600']">
                <Construction class="w-12 h-12" />
            </div>
            
            <h1 :class="['text-4xl md:text-5xl font-bold font-display mb-6', isDark ? 'text-white' : 'text-slate-900']">
                Coming Soon
            </h1>
            
            <p :class="['text-lg mb-10 leading-relaxed', isDark ? 'text-slate-400' : 'text-slate-600']">
                We're currently working hard to build this feature. Please check back later for updates.
            </p>
            
            <Link 
                href="/" 
                :class="['inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold transition-all hover:-translate-y-1 shadow-lg hover:shadow-xl', isDark ? 'bg-white text-slate-900 hover:bg-slate-200' : 'bg-brand-600 text-white hover:bg-brand-700']"
            >
                <ArrowLeft class="w-5 h-5" />
                Back to Home
            </Link>
        </div>
    </div>
</template>
