<script setup>
defineProps({
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'success', 'warning', 'danger', 'info', 'brand', 'purple'].includes(value)
    },
    icon: {
        type: Object,
        default: null
    },
    pulse: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <span
        :class="[
            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border transition-all duration-300',
            {
                'bg-slate-100 text-slate-700 border-slate-200': variant === 'default',
                'bg-emerald-100 text-emerald-700 border-emerald-200': variant === 'success',
                'bg-amber-100 text-amber-700 border-amber-200': variant === 'warning',
                'bg-rose-100 text-rose-700 border-rose-200': variant === 'danger',
                'bg-blue-100 text-blue-700 border-blue-200': variant === 'info',
                'bg-brand-100 text-brand-700 border-brand-200': variant === 'brand',
                'bg-purple-100 text-purple-700 border-purple-200': variant === 'purple',
                'animate-pulse-slow': pulse
            }
        ]"
    >
        <component v-if="icon" :is="icon" class="w-3 h-3 mr-1" />
        <slot />
    </span>
</template>