<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import { Lock, AlertTriangle, Eye, EyeOff } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';

const { isDark } = useTheme();

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const showPassword = ref(false);
const confirmationChecked = ref(false);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    confirmationChecked.value = false;
    nextTick(() => passwordInput.value?.focus?.());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    confirmationChecked.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 :class="['text-lg font-bold', isDark ? 'text-white' : 'text-slate-900']">
                Delete Account
            </h2>
            <p :class="['mt-1 text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                Once your account is deleted, all of its resources and data will be permanently deleted.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion" class="w-full sm:w-auto">
            <AlertTriangle class="w-4 h-4 mr-2" />
            Delete Account
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6 sm:p-8">
                <!-- Header -->
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-14 h-14 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                        <AlertTriangle class="w-7 h-7 text-red-600 dark:text-red-400" />
                    </div>
                    <div class="flex-grow">
                        <h2 :class="['text-2xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                            Delete Account?
                        </h2>
                        <p :class="['mt-1 text-sm', isDark ? 'text-slate-400' : 'text-slate-600']">
                            This action cannot be undone
                        </p>
                    </div>
                </div>

                <!-- Warning Message -->
                <div :class="['p-4 rounded-lg border-2 mb-6', isDark ? 'bg-red-950/30 border-red-900/50' : 'bg-red-50 border-red-200']">
                    <p :class="['text-sm leading-relaxed', isDark ? 'text-red-200' : 'text-red-800']">
                        Once your account is deleted, all of its resources and data
                        will be <strong>permanently deleted</strong>. Please enter your password to
                        confirm you would like to permanently delete your account.
                    </p>
                </div>

                <!-- Confirmation Checkbox -->
                <div class="mb-6">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input 
                            type="checkbox" 
                            v-model="confirmationChecked"
                            class="w-5 h-5 mt-0.5 text-red-600 border-slate-300 dark:border-slate-600 rounded focus:ring-red-500 focus:ring-offset-0 transition-all"
                        />
                        <span :class="['text-sm select-none', isDark ? 'text-slate-300' : 'text-slate-700']">
                            I understand this action is <strong class="font-bold text-red-600 dark:text-red-400">permanent and irreversible</strong>
                        </span>
                    </label>
                </div>

                <!-- Password Input -->
                <div class="space-y-2 mb-6">
                    <label for="password" :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">
                        Confirm with Password
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <Lock class="h-5 w-5 text-slate-400 group-focus-within:text-red-500 transition-colors" />
                        </div>
                        <input
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            class="input-premium pl-12 pr-12"
                            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-100': form.errors.password }"
                            placeholder="Enter your password"
                            @keyup.enter="confirmationChecked && deleteUser()"
                            :disabled="!confirmationChecked"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                            :disabled="!confirmationChecked"
                        >
                            <Eye v-if="!showPassword" class="h-5 w-5" />
                            <EyeOff v-else class="h-5 w-5" />
                        </button>
                    </div>
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <SecondaryButton @click="closeModal" class="w-full sm:w-auto justify-center">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        :class="{ 'opacity-50 cursor-not-allowed': form.processing || !confirmationChecked }"
                        :disabled="form.processing || !confirmationChecked"
                        @click="deleteUser"
                        class="w-full sm:w-auto justify-center"
                    >
                        <span v-if="!form.processing" class="flex items-center gap-2">
                            <AlertTriangle class="w-4 h-4" />
                            Yes, Delete Account
                        </span>
                        <span v-else class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Deleting...
                        </span>
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
