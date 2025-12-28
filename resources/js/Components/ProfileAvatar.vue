<script setup>
import { computed } from 'vue';

const props = defineProps({
  name: {
    type: String,
    required: true
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg', 'xl', '2xl'].includes(value)
  },
  showStatus: {
    type: Boolean,
    default: false
  },
  isOnline: {
    type: Boolean,
    default: false
  }
});

// Generate initials from name
const initials = computed(() => {
  const names = props.name.trim().split(' ');
  if (names.length >= 2) {
    return (names[0][0] + names[names.length - 1][0]).toUpperCase();
  }
  return props.name.substring(0, 2).toUpperCase();
});

// Generate gradient based on name hash
const avatarGradient = computed(() => {
  let hash = 0;
  for (let i = 0; i < props.name.length; i++) {
    hash = props.name.charCodeAt(i) + ((hash << 5) - hash);
  }
  
  const gradients = [
    'from-brand-500 to-emerald-400',
    'from-blue-500 to-cyan-400',
    'from-purple-500 to-pink-400',
    'from-orange-500 to-amber-400',
    'from-rose-500 to-pink-400',
    'from-teal-500 to-green-400',
    'from-indigo-500 to-purple-400',
    'from-emerald-500 to-teal-400',
  ];
  
  return gradients[Math.abs(hash) % gradients.length];
});

const sizeClasses = computed(() => {
  const sizes = {
    sm: 'w-8 h-8 text-xs',
    md: 'w-12 h-12 text-sm',
    lg: 'w-16 h-16 text-lg',
    xl: 'w-24 h-24 text-2xl',
    '2xl': 'w-32 h-32 text-4xl'
  };
  return sizes[props.size];
});

const statusSizeClasses = computed(() => {
  const sizes = {
    sm: 'w-2 h-2',
    md: 'w-3 h-3',
    lg: 'w-4 h-4',
    xl: 'w-5 h-5',
    '2xl': 'w-6 h-6'
  };
  return sizes[props.size];
});
</script>

<template>
  <div class="relative inline-block group">
    <div 
      :class="[
        'rounded-full flex items-center justify-center font-bold text-white shadow-lg',
        'bg-gradient-to-br transition-all duration-300 group-hover:scale-105 group-hover:shadow-xl',
        avatarGradient,
        sizeClasses
      ]"
    >
      {{ initials }}
    </div>
    
    <!-- Status Indicator -->
    <div 
      v-if="showStatus"
      :class="[
        'absolute bottom-0 right-0 rounded-full border-2 border-white dark:border-slate-900',
        isOnline ? 'bg-emerald-500' : 'bg-slate-400',
        statusSizeClasses
      ]"
    />
  </div>
</template>
