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
    award: Object,
    sponsors: Array
});

const form = useForm({
    title: props.award.title || '',
    description: props.award.description || '',
    amount: props.award.amount || '',
    category: props.award.category || 'general',
    sponsor_id: props.award.sponsor_id || '',
    application_start_date: props.award.application_start_date?.split('T')[0] || '',
    application_end_date: props.award.application_end_date?.split('T')[0] || '',
    announcement_date: props.award.announcement_date?.split('T')[0] || '',
    number_of_awards: props.award.number_of_awards || 1,
    status: props.award.status || 'draft',
    eligibility_criteria: props.award.eligibility_criteria?.length ? props.award.eligibility_criteria : [''],
    required_documents: props.award.required_documents || '',
    award_image: null
});

const categories = [
    { value: 'jamb', label: 'JAMB', emoji: '📚' },
    { value: 'waec', label: 'WAEC', emoji: '📝' },
    { value: 'general', label: 'General', emoji: '🎓' },
    { value: 'stem', label: 'STEM', emoji: '🔬' },
    { value: 'arts', label: 'Arts', emoji: '🎨' },
    { value: 'sports', label: 'Sports', emoji: '⚽' }
];

const statuses = [
    { value: 'draft', label: 'Draft' },
    { value: 'active', label: 'Active' },
    { value: 'closed', label: 'Closed' },
    { value: 'suspended', label: 'Suspended' }
];

const imagePreview = ref(props.award.award_image ? `/storage/${props.award.award_image}` : null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.award_image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const addCriteria = () => form.eligibility_criteria.push('');
const removeCriteria = (index) => form.eligibility_criteria.splice(index, 1);

const submit = () => {
    form.post(route('admin.awards.update', props.award.id), { _method: 'PUT' });
};
</script>

<template>
    <div class="max-w-4xl mx-auto space-y-8">
        <Link :href="route('admin.awards.index')" :class="['inline-flex items-center gap-2 text-sm font-medium', isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900']">
            <ArrowLeft class="w-4 h-4" /> Back to Awards
        </Link>

        <div>
            <h1 :class="['text-4xl font-bold font-display bg-gradient-to-r bg-clip-text text-transparent', isDark ? 'from-white to-brand-200' : 'from-slate-900 to-brand-700']">
                Edit Award
            </h1>
            <p :class="['mt-2', isDark ? 'text-slate-400' : 'text-slate-600']">Update award details (Admin)</p>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <!-- Basic Info -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Award class="w-5 h-5 text-brand-500" /> Basic Information
                </h2>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Title *</label>
                        <input v-model="form.title" type="text" class="input-premium" />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Description *</label>
                        <textarea v-model="form.description" rows="4" class="input-premium" />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Sponsor</label>
                        <select v-model="form.sponsor_id" class="input-premium">
                            <option value="">No sponsor</option>
                            <option v-for="sponsor in sponsors" :key="sponsor.id" :value="sponsor.id">{{ sponsor.user?.name }}</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Category *</label>
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                            <button v-for="cat in categories" :key="cat.value" type="button" @click="form.category = cat.value"
                                :class="['p-3 rounded-xl text-center transition-all', form.category === cat.value ? 'bg-brand-600 text-white ring-2 ring-brand-500' : isDark ? 'bg-slate-700 text-slate-300' : 'bg-slate-100 text-slate-600']">
                                <div class="text-2xl mb-1">{{ cat.emoji }}</div>
                                <div class="text-xs font-medium">{{ cat.label }}</div>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Status *</label>
                        <select v-model="form.status" class="input-premium">
                            <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <DollarSign class="w-5 h-5 text-brand-500" /> Award Details
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Amount (₦) *</label>
                        <input v-model="form.amount" type="number" class="input-premium" />
                        <InputError :message="form.errors.amount" />
                    </div>
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Number of Awards *</label>
                        <input v-model="form.number_of_awards" type="number" min="1" class="input-premium" />
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Calendar class="w-5 h-5 text-brand-500" /> Timeline
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Start *</label>
                        <input v-model="form.application_start_date" type="date" class="input-premium" />
                    </div>
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">End *</label>
                        <input v-model="form.application_end_date" type="date" class="input-premium" />
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
                    <Target class="w-5 h-5 text-brand-500" /> Eligibility & Documents
                </h2>
                <div class="space-y-6">
                    <div class="space-y-3">
                        <div v-for="(c, i) in form.eligibility_criteria" :key="i" class="flex gap-3">
                            <input v-model="form.eligibility_criteria[i]" type="text" class="input-premium flex-1" />
                            <button v-if="form.eligibility_criteria.length > 1" type="button" @click="removeCriteria(i)" class="p-3 rounded-xl bg-slate-100 hover:bg-red-50 text-red-600">
                                <X class="w-5 h-5" />
                            </button>
                        </div>
                        <button type="button" @click="addCriteria" :class="['flex items-center gap-2 text-sm font-medium', isDark ? 'text-brand-400' : 'text-brand-600']">
                            <Plus class="w-4 h-4" /> Add Criteria
                        </button>
                    </div>
                    <div class="space-y-2">
                        <label :class="['block text-sm font-semibold', isDark ? 'text-slate-300' : 'text-slate-700']">Required Documents</label>
                        <textarea v-model="form.required_documents" rows="3" class="input-premium" />
                    </div>
                </div>
            </div>

            <!-- Image -->
            <div :class="['p-8 rounded-2xl', isDark ? 'bg-slate-800/50 border border-slate-700' : 'bg-white border border-slate-200 shadow-sm']">
                <h2 :class="['text-xl font-bold mb-6 flex items-center gap-2', isDark ? 'text-white' : 'text-slate-900']">
                    <Image class="w-5 h-5 text-brand-500" /> Award Image
                </h2>
                <div class="flex items-center gap-6">
                    <div v-if="imagePreview" class="w-32 h-32 rounded-xl overflow-hidden">
                        <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                    </div>
                    <div>
                        <input type="file" accept="image/*" @change="handleImageChange" class="hidden" id="award_image" />
                        <label for="award_image" :class="['inline-flex items-center gap-2 px-4 py-2 rounded-xl cursor-pointer', isDark ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700']">
                            <Image class="w-4 h-4" /> Change Image
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-4">
                <Link :href="route('admin.awards.index')" :class="['px-6 py-3 rounded-xl font-semibold', isDark ? 'text-slate-400' : 'text-slate-600']">Cancel</Link>
                <Button type="submit" :disabled="form.processing" size="lg">
                    <Save class="w-5 h-5" /> Save Changes
                </Button>
            </div>
        </form>
    </div>
</template>
