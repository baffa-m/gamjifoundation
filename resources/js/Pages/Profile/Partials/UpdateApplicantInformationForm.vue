<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useTheme } from '@/Composables/useTheme';

const { isDark } = useTheme();
const props = defineProps({
    applicant: {
        type: Object,
        required: true,
    },
});

import { Plus, X } from 'lucide-vue-next';

// ...

const form = useForm({
    _method: 'PATCH',
    full_name: props.applicant.full_name,
    phone_number: props.applicant.phone_number,
    address: props.applicant.address,
    date_of_birth: props.applicant.date_of_birth,
    gender: props.applicant.gender,
    state_of_origin: props.applicant.state_of_origin,
    lga: props.applicant.lga,
    school_name: props.applicant.school_name,
    jamb_reg_number: props.applicant.jamb_reg_number,
    waec_reg_number: props.applicant.waec_reg_number,
    waec_results: props.applicant.waec_results || [],
    profile_picture: null,
});

const addSubject = () => {
    form.waec_results.push({ subject: '', grade: '' });
};

const removeSubject = (index) => {
    form.waec_results.splice(index, 1);
};

const updateApplicantInfo = () => {
    form.post(route('applicant.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
             // Optional: Show success notification
        },
    });
};

const handleImageUpload = (e) => {
    form.profile_picture = e.target.files[0];
};
</script>

<template>
    <section>
        <header>
            <h2 :class="['text-lg font-bold', isDark ? 'text-white' : 'text-slate-900']">
                Applicant Information
            </h2>
            <p :class="['mt-1 text-sm', isDark ? 'text-slate-400' : 'text-slate-500']">
                Update your personal and academic details.
            </p>
        </header>

        <form @submit.prevent="updateApplicantInfo" class="mt-6 space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                 <!-- Full Name -->
                <div class="md:col-span-2">
                    <InputLabel for="full_name" value="Full Name" />
                    <TextInput
                        id="full_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.full_name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.full_name" />
                </div>

                <!-- Phone -->
                <div>
                    <InputLabel for="phone_number" value="Phone Number" />
                    <TextInput
                        id="phone_number"
                        type="tel"
                        class="mt-1 block w-full"
                        v-model="form.phone_number"
                        required
                        autocomplete="tel"
                    />
                    <InputError class="mt-2" :message="form.errors.phone_number" />
                </div>

                <!-- DOB -->
                <div>
                    <InputLabel for="date_of_birth" value="Date of Birth" />
                    <TextInput
                        id="date_of_birth"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.date_of_birth"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.date_of_birth" />
                </div>

                 <!-- Gender -->
                 <div>
                    <InputLabel for="gender" value="Gender" />
                     <select 
                        id="gender"
                        v-model="form.gender" 
                        :class="['mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-brand-500 focus:ring-brand-500', isDark ? 'bg-slate-900 border-slate-700 text-slate-300' : '']"
                    >
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.gender" />
                </div>

                <!-- Profile Pic -->
                 <div>
                    <InputLabel for="profile_picture" value="Profile Picture" />
                    <input 
                        type="file" 
                        id="profile_picture"
                        @change="handleImageUpload" 
                        accept="image/*" 
                        class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-slate-700 dark:file:text-slate-200" 
                    />
                    <InputError :message="form.errors.profile_picture" class="mt-2" />
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <InputLabel for="address" value="Residential Address" />
                    <TextInput
                        id="address"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.address"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.address" />
                </div>

                <!-- State -->
                <div>
                    <InputLabel for="state_of_origin" value="State of Origin" />
                    <TextInput
                        id="state_of_origin"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.state_of_origin"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.state_of_origin" />
                </div>

                <!-- LGA -->
                <div>
                    <InputLabel for="lga" value="LGA" />
                    <TextInput
                        id="lga"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.lga"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.lga" />
                </div>
            </div>

            <div class="mt-6 border-t pt-6" :class="isDark ? 'border-slate-700' : 'border-slate-200'">
                <h3 :class="['text-md font-medium mb-4', isDark ? 'text-white' : 'text-slate-900']">Academic Details</h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- School -->
                    <div class="md:col-span-2">
                        <InputLabel for="school_name" value="School / Institution" />
                        <TextInput
                            id="school_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.school_name"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.school_name" />
                    </div>

                    <!-- JAMB -->
                    <div>
                        <InputLabel for="jamb_reg_number" value="JAMB Reg Number" />
                        <TextInput
                            id="jamb_reg_number"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.jamb_reg_number"
                        />
                        <InputError class="mt-2" :message="form.errors.jamb_reg_number" />
                    </div>

                    <!-- WAEC -->
                    <div>
                        <InputLabel for="waec_reg_number" value="WAEC Reg Number" />
                        <TextInput
                            id="waec_reg_number"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.waec_reg_number"
                        />
                        <InputError class="mt-2" :message="form.errors.waec_reg_number" />
                    </div>
                </div>
                </div>

                <div class="mt-6 border-t pt-6" :class="isDark ? 'border-slate-700' : 'border-slate-200'">
                    <h3 :class="['text-md font-medium mb-4', isDark ? 'text-white' : 'text-slate-900']">WAEC Results (Optional)</h3>
                    
                    <div class="space-y-4">
                        <div v-for="(result, index) in form.waec_results" :key="index" class="flex items-start gap-4 p-4 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                            <div class="flex-1">
                                <InputLabel :for="'subject_' + index" value="Subject" />
                                <TextInput
                                    :id="'subject_' + index"
                                    v-model="result.subject"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="e.g. Mathematics"
                                />
                            </div>
                            <div class="w-32">
                                <InputLabel :for="'grade_' + index" value="Grade" />
                                <select 
                                    :id="'grade_' + index"
                                    v-model="result.grade"
                                    :class="['mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-brand-500 focus:ring-brand-500', isDark ? 'bg-slate-900 border-slate-700 text-slate-300' : '']"
                                >
                                    <option value="" disabled>Select</option>
                                    <option v-for="grade in ['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9']" :key="grade" :value="grade">
                                        {{ grade }}
                                    </option>
                                </select>
                            </div>
                            <button 
                                type="button" 
                                @click="removeSubject(index)"
                                class="mt-8 text-red-500 hover:text-red-700 transition-colors p-2"
                                title="Remove Subject"
                            >
                                <X class="w-5 h-5" />
                            </button>
                        </div>

                         <button 
                            type="button" 
                            @click="addSubject"
                            :class="['flex items-center gap-2 text-sm font-medium transition-colors', isDark ? 'text-brand-400 hover:text-brand-300' : 'text-brand-600 hover:text-brand-700']"
                        >
                            <Plus class="w-4 h-4" />
                            Add Subject
                        </button>
                    </div>
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
