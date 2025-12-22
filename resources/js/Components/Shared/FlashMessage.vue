<script setup>
import { X, CheckCircle, AlertCircle, Info } from 'lucide-vue-next';
import { useFlash } from '@/Composables/useFlash';

const { show, message, type, close } = useFlash();

const icons = {
    success: CheckCircle,
    error: AlertCircle,
    info: Info
};

const colors = {
    success: 'bg-green-50 border-green-200 text-green-800',
    error: 'bg-red-50 border-red-200 text-red-800',
    info: 'bg-blue-50 border-blue-200 text-blue-800'
};
</script>

<template>
    <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show && message"
            :class="colors[type]"
            class="fixed top-4 right-4 z-50 max-w-md rounded-lg border p-4 shadow-lg"
        >
            <div class="flex items-start">
                <component :is="icons[type]" class="h-5 w-5 mr-3 flex-shrink-0" />
                <p class="flex-1 text-sm font-medium">{{ message }}</p>
                <button
                    @click="close"
                    class="ml-3 flex-shrink-0 hover:opacity-75 transition"
                >
                    <X class="h-5 w-5" />
                </button>
            </div>
        </div>
    </transition>
</template>