<script setup>
import { computed, ref, watch } from 'vue'
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from '@headlessui/vue'
import { Check, ChevronsUpDown } from 'lucide-vue-next'
import { useTheme } from '@/Composables/useTheme';

const { isDark } = useTheme();

const props = defineProps({
    modelValue: [String, Number, Object],
    options: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Select option'
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const query = ref('')

// Normalize options to handle both simple strings and objects
const normalizedOptions = computed(() => {
    return props.options.map(option => {
        if (typeof option === 'object' && option !== null) {
            return option
        }
        return { id: option, name: option }
    })
})

const filteredOptions = computed(() =>
    query.value === ''
        ? normalizedOptions.value
        : normalizedOptions.value.filter((option) =>
            option.name
                .toLowerCase()
                .replace(/\s+/g, '')
                .includes(query.value.toLowerCase().replace(/\s+/g, ''))
        )
)

const selected = ref(props.modelValue ? normalizedOptions.value.find(o => o.name === props.modelValue) : null)

watch(() => props.modelValue, (newValue) => {
    selected.value = normalizedOptions.value.find(o => o.name === newValue) || null
})

watch(selected, (newValue) => {
    const value = newValue ? newValue.name : ''
    emit('update:modelValue', value)
    emit('change', value)
})
</script>

<template>
    <div class="relative w-full">
        <Combobox v-model="selected" :disabled="disabled" nullable v-slot="{ open }">
            <div class="relative w-full cursor-default overflow-hidden rounded-xl bg-white dark:bg-slate-800 text-left border-2 border-slate-200 dark:border-slate-700 focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-500/10 transition-all duration-300 sm:text-sm">
                <ComboboxInput
                    class="w-full border-none py-3 pl-4 pr-10 text-sm leading-5 text-gray-900 dark:text-gray-100 bg-transparent focus:ring-0 placeholder-slate-400"
                    :displayValue="(option) => option?.name"
                    @change="query = $event.target.value"
                    :placeholder="placeholder"
                />
                <ComboboxButton 
                    v-if="!open"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-text"
                />
                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                    <ChevronsUpDown class="h-5 w-5 text-gray-400" aria-hidden="true" />
                </ComboboxButton>
            </div>
            <TransitionRoot
                leave="transition ease-in duration-100"
                leaveFrom="opacity-100"
                leaveTo="opacity-0"
                @after-leave="query = ''"
            >
                <ComboboxOptions class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-800 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm z-50">
                    <div
                        v-if="filteredOptions.length === 0 && query !== ''"
                        class="relative cursor-default select-none py-2 px-4 text-gray-700 dark:text-gray-300"
                    >
                        Nothing found.
                    </div>

                    <ComboboxOption
                        v-for="option in filteredOptions"
                        as="template"
                        :key="option.id"
                        :value="option"
                        v-slot="{ selected, active }"
                    >
                        <li
                            class="relative cursor-default select-none py-2 pl-10 pr-4"
                            :class="{
                                'bg-brand-600 text-white': active,
                                'text-gray-900 dark:text-gray-100': !active,
                            }"
                        >
                            <span
                                class="block truncate"
                                :class="{ 'font-medium': selected, 'font-normal': !selected }"
                            >
                                {{ option.name }}
                            </span>
                            <span
                                v-if="selected"
                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                                :class="{ 'text-white': active, 'text-brand-600': !active }"
                            >
                                <Check class="h-5 w-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </TransitionRoot>
        </Combobox>
    </div>
</template>
