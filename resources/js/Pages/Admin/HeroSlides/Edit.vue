<script setup>
import Form from './Form.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


defineOptions({ layout: AuthenticatedLayout });

defineProps({
    slide: Object
});
</script>

<template>
    <Form :slide="slide" />
</template>
