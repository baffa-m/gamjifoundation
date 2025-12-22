<script setup>
import { useTheme } from '@/Composables/useTheme';

const { isDark } = useTheme();

defineProps({
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'premium', 'glass'].includes(value)
    },
    hoverable: {
        type: Boolean,
        default: true
    },
    padding: {
        type: String,
        default: 'md',
        validator: (value) => ['none', 'sm', 'md', 'lg'].includes(value)
    }
});
</script>

<template>
    <div
        :class="[
            'rounded-2xl transition-all duration-300',
            {
                // Default variant
                'bg-white border border-slate-200 shadow-sm': variant === 'default' && !isDark,
                'bg-slate-800 border border-slate-700 shadow-none': variant === 'default' && isDark,
                // Premium variant
                'card-premium': variant === 'premium' && !isDark,
                'bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 rounded-2xl shadow-none hover:shadow-glow-lg hover:-translate-y-1 transition-all duration-500': variant === 'premium' && isDark,
                // Glass variant
                'card-glass': variant === 'glass' && !isDark,
                'bg-slate-900/70 backdrop-blur-xl border border-white/10 rounded-2xl shadow-none': variant === 'glass' && isDark,
                // Hover effect
                'hover-lift': hoverable
            }
        ]"
    >
        <div
            v-if="$slots.header"
            :class="['px-6 py-4 border-b', isDark ? 'border-slate-700' : 'border-slate-200']"
        >
            <slot name="header" />
        </div>
        
        <div
            :class="[
                {
                    'p-0': padding === 'none',
                    'p-4': padding === 'sm',
                    'p-6': padding === 'md',
                    'p-8': padding === 'lg',
                }
            ]"
        >
            <slot />
        </div>
        
        <div
            v-if="$slots.footer"
            :class="['px-6 py-4 border-t rounded-b-2xl', isDark ? 'border-slate-700 bg-slate-800/50' : 'border-slate-200 bg-slate-50']"
        >
            <slot name="footer" />
        </div>
    </div>
</template>