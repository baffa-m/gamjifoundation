<script setup>
import { defineOptions, ref, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { User, MapPin, School, FileText, Save, Calendar, Phone } from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    locations: Array
});

// Transform locations for SearchableSelect
const stateOptions = computed(() => {
    return props.locations.map(state => ({
        id: state.id,
        name: state.name,
        // Keep original object for easier LGA lookup
        original: state
    }))
});

const { isDark } = useTheme();

const form = useForm({
    full_name: '',
    phone_number: '',
    address: '',
    date_of_birth: '',
    gender: 'male',
    state_of_origin: '',
    lga: '',
    school_name: '',
    jamb_reg_number: '',
    waec_reg_number: '',
    profile_picture: null
});

const handleImageUpload = (e) => {
    form.profile_picture = e.target.files[0];
};

const lgas = ref([]);



const lgaOptions = computed(() => {
    return lgas.value.map(lga => ({
        id: lga.id,
        name: lga.name
    }))
});

const onStateChange = (newStateName) => {
    form.state_of_origin = newStateName;
    form.lga = ''; // Reset LGA
    
    const selectedState = props.locations.find(state => state.name === newStateName);
    lgas.value = selectedState ? selectedState.lgas : [];
};

const onLgaChange = (newLgaName) => {
    form.lga = newLgaName;
};

const submit = () => {
    form.post(route('applicant.profile.store'));
};
</script>

<template>
    <Head title="Complete Your Profile" />

    <div class="max-w-4xl mx-auto space-y-8 pb-12">
        <!-- Header -->
        <div class="text-center animate-fade-in-up">
            <h1 :class="['text-3xl font-bold font-display', isDark ? 'text-white' : 'text-slate-900']">
                Complete Your Profile
            </h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">
                Please provide your details to start applying for scholarships.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <!-- Personal Info -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <User class="w-5 h-5 text-brand-500" /> Personal Information
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <InputLabel value="Full Name" :required="true" />
                        <TextInput v-model="form.full_name" type="text" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.full_name" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel value="Phone Number" :required="true" />
                        <TextInput v-model="form.phone_number" type="tel" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.phone_number" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel value="Date of Birth" :required="true" />
                        <TextInput v-model="form.date_of_birth" type="date" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.date_of_birth" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel value="Gender" :required="true" />
                        <SelectInput 
                            v-model="form.gender" 
                            :class="[isDark ? 'bg-slate-900 border-slate-700 text-slate-300' : '']"
                        >
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </SelectInput>
                        <InputError :message="form.errors.gender" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel value="Profile Picture (Optional)" />
                        <input type="file" @change="handleImageUpload" accept="image/*" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />
                        <InputError :message="form.errors.profile_picture" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <MapPin class="w-5 h-5 text-brand-500" /> Location
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <InputLabel value="State of Origin" :required="true" />
                        <SearchableSelect 
                            :modelValue="form.state_of_origin" 
                            @update:modelValue="onStateChange"
                            :options="stateOptions"
                            placeholder="Select State"
                        />
                        <InputError :message="form.errors.state_of_origin" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel value="LGA" :required="true" />
                        <SearchableSelect 
                            :modelValue="form.lga" 
                            @update:modelValue="onLgaChange"
                            :options="lgaOptions"
                            placeholder="Select LGA"
                            :disabled="!form.state_of_origin"
                        />
                        <InputError :message="form.errors.lga" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <InputLabel value="Residential Address" :required="true" />
                        <TextInput v-model="form.address" type="text" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.address" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Academic Info -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <School class="w-5 h-5 text-brand-500" /> Academic Information
                </h2>

                <div class="space-y-6">
                    <div>
                        <InputLabel value="Current School / Institution" :required="true" />
                        <TextInput v-model="form.school_name" type="text" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.school_name" class="mt-2" />
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel value="JAMB Registration Number (Optional)" />
                            <TextInput v-model="form.jamb_reg_number" type="text" class="mt-1 block w-full" />
                            <InputError :message="form.errors.jamb_reg_number" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel value="WAEC Registration Number (Optional)" />
                            <TextInput v-model="form.waec_reg_number" type="text" class="mt-1 block w-full" />
                            <InputError :message="form.errors.waec_reg_number" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="px-8 py-3 text-base">
                    <Save class="w-5 h-5 mr-2" /> Save Profile
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
