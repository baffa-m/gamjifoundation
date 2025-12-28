<script setup>
import { defineOptions, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    ArrowLeft, Award, FileText, Calendar, DollarSign, 
    Users, Image, Save, Plus, X, Target, Building2
} from 'lucide-vue-next';
import { useTheme } from '@/Composables/useTheme';
import InputError from '@/Components/InputError.vue';
import Button from '@/Components/UI/Button.vue';

defineOptions({ layout: AuthenticatedLayout });

const { isDark } = useTheme();

const props = defineProps({
    sponsors: Array
});

const form = useForm({
    title: '',
    description: '',
    amount: '',
    category: 'general',
    sponsor_id: '',
    application_start_date: '',
    application_end_date: '',
    announcement_date: '',
    number_of_awards: 1,
    status: 'draft',
    eligibility_criteria: [''],
    required_documents: '',
    award_image: null
});

const categories = [
    { value: 'jamb', label: 'JAMB', emoji: '📚' },
    { value: 'waec', label: 'WAEC', emoji: '📝' },
    { value: 'general', label: 'General', emoji: '🎓' }
];

const statuses = [
    { value: 'draft', label: 'Draft', color: 'amber' },
    { value: 'active', label: 'Active', color: 'emerald' },
    { value: 'closed', label: 'Closed', color: 'slate' },
    { value: 'suspended', label: 'Suspended', color: 'red' }
];

const imagePreview = ref(null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.award_image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const addCriteria = () => {
    form.eligibility_criteria.push('');
};

const removeCriteria = (index) => {
    form.eligibility_criteria.splice(index, 1);
};

const submit = () => {
    form.post(route('admin.awards.store'));
};
</script>

<template>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Back Button -->
        <Link 
            :href="route('admin.awards.index')" 
            :class="['inline-flex items-center gap-2 text-sm font-medium transition-colors', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']"
        >
            <ArrowLeft class="w-4 h-4" />
            Back to Awards
        </Link>

        <!-- Header -->
        <div class="animate-fade-in-up">
            <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                Create New Award
            </h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">
                Create a new scholarship program (Admin)
            </p>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="space-y-8">
            <!-- Basic Information -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Award class="w-5 h-5 text-brand-500" />
                    Basic Information
                </h2>

                <div class="space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Award Title *</label>
                        <input v-model="form.title" type="text" class="input-premium" placeholder="e.g., JAMB Excellence Award 2024" />
                        <InputError :message="form.errors.title" />
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Description *</label>
                        <textarea v-model="form.description" rows="4" class="input-premium" placeholder="Describe the award..." />
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Sponsor -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Sponsor</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <Building2 :class="['h-5 w-5', isDark ? 'text-slate-500' : 'text-slate-400']" />
                            </div>
                            <select v-model="form.sponsor_id" class="input-premium pl-12">
                                <option value="">No sponsor (Foundation Award)</option>
                                <option v-for="sponsor in sponsors" :key="sponsor.id" :value="sponsor.id">
                                    {{ sponsor.user?.name }}
                                </option>
                            </select>
                        </div>
                        <InputError :message="form.errors.sponsor_id" />
                    </div>

                    <!-- Category -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Category *</label>
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                            <button
                                v-for="cat in categories"
                                :key="cat.value"
                                type="button"
                                @click="form.category = cat.value"
                                :class="[
                                    'p-3 rounded-xl text-center transition-all duration-300',
                                    form.category === cat.value
                                        ? 'bg-brand-600 text-white ring-2 ring-brand-500 ring-offset-2' + (isDark ? ' ring-offset-slate-900' : ' ring-offset-white')
                                        : isDark ? 'bg-slate-700 text-slate-300 hover:bg-slate-600' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                                ]"
                            >
                                <div class="text-2xl mb-1">{{ cat.emoji }}</div>
                                <div class="text-xs font-medium">{{ cat.label }}</div>
                            </button>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Status *</label>
                        <div class="flex flex-wrap gap-3">
                            <button
                                v-for="status in statuses"
                                :key="status.value"
                                type="button"
                                @click="form.status = status.value"
                                :class="[
                                    'px-4 py-2 rounded-xl text-sm font-medium transition-all',
                                    form.status === status.value
                                        ? `bg-${status.color}-600 text-white`
                                        : isDark ? 'bg-slate-700 text-slate-300 hover:bg-slate-600' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                                ]"
                            >
                                {{ status.label }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Award Details -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <DollarSign class="w-5 h-5 text-brand-500" />
                    Award Details
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Amount (₦) *</label>
                        <input v-model="form.amount" type="number" min="0" class="input-premium" placeholder="50000" />
                        <InputError :message="form.errors.amount" />
                    </div>
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Number of Awards *</label>
                        <input v-model="form.number_of_awards" type="number" min="1" class="input-premium" placeholder="1" />
                        <InputError :message="form.errors.number_of_awards" />
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Calendar class="w-5 h-5 text-brand-500" />
                    Timeline
                </h2>

                <div class="grid md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Start Date *</label>
                        <input v-model="form.application_start_date" type="date" class="input-premium" />
                        <InputError :message="form.errors.application_start_date" />
                    </div>
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">End Date *</label>
                        <input v-model="form.application_end_date" type="date" class="input-premium" />
                        <InputError :message="form.errors.application_end_date" />
                    </div>
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Announcement</label>
                        <input v-model="form.announcement_date" type="date" class="input-premium" />
                    </div>
                </div>
            </div>

            <!-- Eligibility -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Target class="w-5 h-5 text-brand-500" />
                    Eligibility & Documents
                </h2>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Eligibility Criteria</label>
                        <div v-for="(criteria, index) in form.eligibility_criteria" :key="index" class="flex gap-3">
                            <input v-model="form.eligibility_criteria[index]" type="text" class="input-premium flex-1" :placeholder="`Criteria ${index + 1}`" />
                            <button v-if="form.eligibility_criteria.length > 1" type="button" @click="removeCriteria(index)" :class="['p-3 rounded-xl', isDark ? 'bg-slate-700 hover:bg-red-900/30 text-red-400' : 'bg-slate-100 hover:bg-red-50 text-red-600']">
                                <X class="w-5 h-5" />
                            </button>
                        </div>
                        <button type="button" @click="addCriteria" :class="['flex items-center gap-2 text-sm font-medium', isDark ? 'text-brand-400' : 'text-brand-600']">
                            <Plus class="w-4 h-4" /> Add Criteria
                        </button>
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Required Documents</label>
                        <textarea v-model="form.required_documents" rows="3" class="input-premium" placeholder="List required documents..." />
                    </div>
                </div>
            </div>

            <!-- Image -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Image class="w-5 h-5 text-brand-500" />
                    Award Image
                </h2>
                <div class="flex items-center gap-6">
                    <div v-if="imagePreview" class="w-32 h-32 rounded-xl overflow-hidden">
                        <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                    </div>
                    <div>
                        <input type="file" accept="image/*" @change="handleImageChange" class="hidden" id="award_image" />
                        <label for="award_image" :class="['inline-flex items-center gap-2 px-4 py-2 rounded-xl cursor-pointer', isDark ? 'bg-slate-700 hover:bg-slate-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700']">
                            <Image class="w-4 h-4" /> Upload Image
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-4">
                <Link :href="route('admin.awards.index')" :class="['px-6 py-3 rounded-xl font-semibold', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']">
                    Cancel
                </Link>
                <Button type="submit" :disabled="form.processing" size="lg">
                    <Save class="w-5 h-5" /> Create Award
                </Button>
            </div>
        </form>
    </div>
</template>
