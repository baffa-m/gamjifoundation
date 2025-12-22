<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';

defineProps({
  award: {
    type: Object,
    required: true
  },
  isDark: Boolean
});
</script>

<template>
  <Link 
    :href="`/awards/${award.slug || award.id}`"
    :class="[
      'relative rounded-2xl overflow-hidden group cursor-pointer block transition-all duration-500 hover:-translate-y-1', 
      isDark ? 'glass-dark hover:shadow-[0_0_30px_rgba(245,158,11,0.1)]' : 'glass hover:shadow-[0_0_30px_rgba(245,158,11,0.15)] border-white/50'
    ]"
  >
    <!-- Top Decoration -->
    <div class="h-1.5 w-full bg-gradient-to-r from-brand-600 via-brand-400 to-accent-400" />
    
    <div class="p-6">
      <div class="flex justify-between items-start mb-4">
        <span class="px-3 py-1 text-xs font-bold rounded-full bg-brand-50 text-brand-700 border border-brand-100">
          {{ award.type }}
        </span>
        <span :class="['text-xs font-medium', isDark ? 'text-slate-400' : 'text-slate-500']">
          Ends {{ award.deadline }}
        </span>
      </div>
      
      <h3 :class="['text-xl font-bold mb-1 font-display group-hover:text-brand-600 transition-colors', isDark ? 'text-white' : 'text-slate-900']">
        {{ award.title }}
      </h3>
      <p class="text-sm text-slate-500 mb-6">
        Sponsored by {{ award.sponsor }}
      </p>
      
      <div :class="['flex items-center justify-between pt-4 border-t', isDark ? 'border-white/10' : 'border-slate-100']">
        <div>
          <span class="block text-xs text-slate-500 uppercase tracking-wider">Value</span>
          <span :class="['font-bold text-lg font-display', isDark ? 'text-accent-400' : 'text-brand-700']">
            {{ award.amount }}
          </span>
        </div>
        <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:bg-brand-600 group-hover:border-brand-600 group-hover:text-white transition-all duration-300 shadow-sm">
          <ArrowRight class="w-5 h-5" />
        </div>
      </div>
    </div>
  </Link>
</template>