<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { useTheme } from '@/Composables/useTheme';

const { isDark } = useTheme();
const props = defineProps({
    sponsor: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    _method: 'PATCH',
    organization_name: props.sponsor.organization_name,
    sponsor_type: props.sponsor.sponsor_type,
    registration_number: props.sponsor.registration_number,
    description: props.sponsor.description,
    website: props.sponsor.website,
    address: props.sponsor.address,
});

const updateSponsorInfo = () => {
    form.post(route('sponsor.register.update'), {
        preserveScroll: true,
        onSuccess: () => {
             // Optional
        },
    });
};

const sponsorTypes = [
    { value: 'individual', label: 'Individual' },
    { value: 'corporate', label: 'Corporate' },
    { value: 'government', label: 'Government' },
    { value: 'ngo', label: 'NGO' }
];
</script>

<template>
    <section>
        <header>
            <h2 :class="['text-lg font-bold', isDark ? 'text-white' : 'text-slate-900']">
                Organization Information
            </h2>
            <p :class="['mt-1 text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                Update your organization details and contact info.
            </p>
        </header>

        <form @submit.prevent="updateSponsorInfo" class="mt-6 space-y-6">
            
            <!-- Organization Name -->
            <div>
                <InputLabel for="organization_name" value="Organization Name" />
                <TextInput
                    id="organization_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.organization_name"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.organization_name" />
            </div>

            <!-- Type -->
            <div>
                <InputLabel for="sponsor_type" value="Sponsor Type" />
                <select 
                    id="sponsor_type"
                    v-model="form.sponsor_type" 
                    :class="['mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-brand-500 focus:ring-brand-500', isDark ? 'bg-slate-900 border-slate-700 text-slate-300' : '']"
                >
                    <option v-for="type in sponsorTypes" :key="type.value" :value="type.value">
                        {{ type.label }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.sponsor_type" />
            </div>

            <!-- Reg Number -->
            <div>
                <InputLabel for="registration_number" value="Registration Number" />
                <TextInput
                    id="registration_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.registration_number"
                />
                <InputError class="mt-2" :message="form.errors.registration_number" />
            </div>

             <!-- Description -->
             <div>
                <InputLabel for="description" value="Description" />
                <textarea 
                    id="description"
                    v-model="form.description"
                    rows="3"
                    :class="['mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-brand-500 focus:ring-brand-500', isDark ? 'bg-slate-900 border-slate-700 text-slate-300' : '']"
                ></textarea>
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

             <!-- Website -->
             <div>
                <InputLabel for="website" value="Website" />
                <TextInput
                    id="website"
                    type="url"
                    class="mt-1 block w-full"
                    v-model="form.website"
                />
                <InputError class="mt-2" :message="form.errors.website" />
            </div>

             <!-- Address -->
             <div>
                <InputLabel for="address" value="Address" />
                <TextInput
                    id="address"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address"
                    required
                />
                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save Changes</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
