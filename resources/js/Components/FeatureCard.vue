<script setup>
import { GraduationCap, ClipboardList, BookOpen, Users } from 'lucide-vue-next';

defineProps({
  feature: {
    type: Object,
    required: true
  },
  isDark: Boolean
});

const iconComponents = {
  'GraduationCap': GraduationCap,
  'ClipboardList': ClipboardList,
  'BookOpen': BookOpen,
  'Users': Users
};
</script>

<template>
  <div 
    :class="[
      'group p-8 rounded-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden', 
      isDark ? 'glass-dark hover:shadow-[0_0_40px_rgba(34,197,94,0.15)]' : 'glass hover:shadow-[0_0_40px_rgba(34,197,94,0.1)] border-white/40'
    ]"
  >
    <div class="absolute top-0 right-0 w-40 h-40 bg-brand-500/10 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-brand-500/20 transition-colors duration-500" />
    
    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-50 to-brand-100 text-brand-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-sm border border-brand-200/50">
      <component :is="iconComponents[feature.icon]" class="w-8 h-8" />
    </div>
    
    <h3 :class="['text-xl font-bold mb-3 font-display', isDark ? 'text-white' : 'text-slate-900']">
      {{ feature.title }}
    </h3>
    <p :class="['leading-relaxed text-sm', isDark ? 'text-slate-400' : 'text-slate-600']">
      {{ feature.desc }}
    </p>
  </div>
</template>