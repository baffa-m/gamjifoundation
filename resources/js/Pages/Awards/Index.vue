<script setup>
import { defineOptions } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AwardCard from '@/Components/AwardCard.vue';
import { Award, Search } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';

defineOptions({ layout: GuestLayout });

const { isDark } = useTheme();

defineProps({
    awards: Array
});
</script>

<template>
    <Head title="Scholarship Opportunities - Gamji Foundation" />

    <div class="min-h-screen">
        <!-- Hero -->
        <section class="relative py-24 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-900 via-brand-800 to-slate-900" />
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-500 rounded-full blur-3xl animate-pulse" />
                <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-accent-500 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;" />
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-6xl font-bold font-display text-white mb-6 animate-fade-in-up">
                    Available <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-accent-400">Opportunities</span>
                </h1>
                <p class="text-xl text-brand-100 max-w-2xl mx-auto animate-fade-in-up md:leading-relaxed" style="animation-delay: 0.1s;">
                    Discover scholarships, grants, and funding opportunities designed to support your academic journey.
                </p>
            </div>
        </section>

        <!-- Content -->
        <section :class="['py-16', isDark ? 'bg-slate-900' : 'bg-slate-50']">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Empty State -->
                <div v-if="awards.length === 0" :class="['text-center py-20 rounded-3xl border border-dashed', isDark ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-slate-300']">
                    <Award :class="['w-20 h-20 mx-auto mb-6 opacity-50', isDark ? 'text-slate-600' : 'text-slate-300']" />
                    <h3 :class="['text-2xl font-bold mb-3', isDark ? 'text-white' : 'text-slate-900']">No Opportunities Available</h3>
                    <p :class="['max-w-md mx-auto', isDark ? 'text-slate-400' : 'text-slate-600']">We currently don't have any open awards. Please check back later or subscribe to our newsletter for updates.</p>
                </div>

                <!-- Grid -->
                <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <AwardCard 
                        v-for="award in awards" 
                        :key="award.id"
                        :award="award"
                        :is-dark="isDark"
                    />
                </div>
            </div>
        </section>
    </div>
</template>
