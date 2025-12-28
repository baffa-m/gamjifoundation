<script setup>
import { computed } from 'vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
    headers: {
        type: Array,
        required: true
    },
    items: {
        type: Array,
        required: true
    },
    pagination: {
        type: Object,
        default: null
    },
    actions: {
        type: Boolean,
        default: false
    },
    clickable: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['page-change', 'row-click']);
</script>

<template>
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold tracking-wider">
                    <tr>
                        <th 
                            v-for="header in headers" 
                            :key="header.key"
                            class="px-6 py-4"
                        >
                            {{ header.label }}
                        </th>
                        <th v-if="actions" class="px-6 py-4 text-right">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <tr 
                        v-for="(item, index) in items" 
                        :key="item.id || index"
                        @click="clickable ? $emit('row-click', item) : null"
                        :class="['transition-colors group', clickable ? 'cursor-pointer hover:bg-brand-50/50 dark:hover:bg-slate-700/80' : 'hover:bg-slate-50/80 dark:hover:bg-slate-700/50']"
                    >
                        <td 
                            v-for="header in headers" 
                            :key="header.key"
                            class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300"
                        >
                            <slot :name="header.key" :item="item" :value="item[header.key]">
                                {{ item[header.key] }}
                            </slot>
                        </td>
                        <td v-if="actions" class="px-6 py-4 text-right">
                            <slot name="actions" :item="item" />
                        </td>
                    </tr>
                    <tr v-if="items.length === 0">
                        <td :colspan="headers.length + (actions ? 1 : 0)" class="px-6 py-12 text-center text-slate-400">
                            <slot name="empty">
                                No data available
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="pagination && pagination.links" class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
            <div class="text-sm text-slate-500">
                Showing <span class="font-medium">{{ pagination.from || 0 }}</span> to <span class="font-medium">{{ pagination.to || 0 }}</span> of <span class="font-medium">{{ pagination.total }}</span> results
            </div>
            <div class="flex gap-2">
                <button 
                    v-for="(link, i) in pagination.links" 
                    :key="i"
                    :disabled="!link.url || link.active"
                    @click="$emit('page-change', link.url)"
                    v-html="link.label"
                    :class="[
                        'px-3 py-1 rounded-md text-sm font-medium transition-colors',
                        link.active 
                            ? 'bg-brand-600 text-white shadow-sm' 
                            : !link.url 
                                ? 'text-slate-300 cursor-not-allowed' 
                                : 'text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 hover:text-brand-600 dark:hover:text-brand-400 border border-transparent hover:border-slate-200 dark:hover:border-slate-600'
                    ]"
                />
            </div>
        </div>
    </div>
</template>
