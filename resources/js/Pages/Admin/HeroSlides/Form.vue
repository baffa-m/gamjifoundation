<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ArrowLeft, Save, Image as ImageIcon } from 'lucide-vue-next';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import Checkbox from '@/Components/Checkbox.vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    slide: {
        type: Object,
        default: null
    }
});

const form = useForm({
    title: props.slide?.title || '',
    subtitle: props.slide?.subtitle || '',
    image: null,
    cta_text: props.slide?.cta_text || '',
    cta_link: props.slide?.cta_link || '',
    is_active: props.slide?.is_active ?? true,
    _method: props.slide ? 'put' : 'post'
});

const submit = () => {
    if (props.slide) {
        form.post(route('admin.hero-slides.update', props.slide.id));
    } else {
        form.post(route('admin.hero-slides.store'));
    }
};

const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
    }
};
</script>

<template>
    <Head :title="slide ? 'Edit Slide' : 'Create Slide'" />

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Link 
                :href="route('admin.hero-slides.index')"
                class="p-2 rounded-lg hover:bg-slate-200 text-slate-500 transition"
            >
                <ArrowLeft class="w-5 h-5" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold font-display text-slate-900">
                    {{ slide ? 'Edit Hero Slide' : 'Create Hero Slide' }}
                </h1>
                <p class="text-slate-500 text-sm">Configure the homepage slider content.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 md:p-8">
            <form @submit.prevent="submit" class="space-y-6">
                
                <!-- Image Upload -->
                <div>
                    <InputLabel value="Slide Image" :required="!slide" />
                    <div class="mt-2 flex items-center gap-6">
                        <div class="w-32 h-20 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden relative">
                            <img 
                                v-if="slide?.image_url && !form.image" 
                                :src="slide.image_url" 
                                class="w-full h-full object-cover"
                            />
                            <div v-else-if="form.image" class="text-xs text-center p-2 text-brand-600 font-medium">
                                {{ form.image.name }}
                            </div>
                            <ImageIcon v-else class="w-8 h-8 text-slate-300" />
                        </div>
                        <div class="flex-1">
                            <input 
                                type="file" 
                                @change="handleImageUpload"
                                accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition"
                            />
                            <p class="mt-1 text-xs text-slate-400">Recommended size: 1920x1080px. Max 2MB.</p>
                            <InputError :message="form.errors.image" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <InputLabel for="title" value="Title" :required="true" />
                    <TextInput
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="e.g., Empowering Future Leaders"
                        required
                    />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>

                <!-- Subtitle -->
                <div>
                    <InputLabel for="subtitle" value="Subtitle" />
                    <textarea
                        id="subtitle"
                        v-model="form.subtitle"
                        class="mt-1 block w-full border-gray-300 dark:border-slate-700 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-300"
                        rows="3"
                        placeholder="Short description..."
                    ></textarea>
                    <InputError :message="form.errors.subtitle" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CTA Text -->
                    <div>
                        <InputLabel for="cta_text" value="Button Text (Optional)" />
                        <TextInput
                            id="cta_text"
                            v-model="form.cta_text"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="e.g., Apply Now"
                        />
                        <InputError :message="form.errors.cta_text" class="mt-2" />
                    </div>

                    <!-- CTA Link -->
                    <div>
                        <InputLabel for="cta_link" value="Button Link (Optional)" />
                        <TextInput
                            id="cta_link"
                            v-model="form.cta_link"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="e.g., /register"
                        />
                        <InputError :message="form.errors.cta_link" class="mt-2" />
                    </div>
                </div>

                <!-- Active Status -->
                <div class="block">
                    <label class="flex items-center">
                        <Checkbox name="is_active" v-model:checked="form.is_active" />
                        <span class="ml-2 text-sm text-slate-600">Active (Visible on homepage)</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <Link 
                        :href="route('admin.hero-slides.index')"
                        class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 font-medium"
                    >
                        Cancel
                    </Link>
                    <PrimaryButton 
                        :class="{ 'opacity-25': form.processing }" 
                        :disabled="form.processing"
                    >
                        <Save class="w-4 h-4 mr-2" />
                        {{ slide ? 'Update Slide' : 'Create Slide' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
