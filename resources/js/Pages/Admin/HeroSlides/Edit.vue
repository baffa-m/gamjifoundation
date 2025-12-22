<script setup>
import Form from './Form.vue';

defineProps({
    slide: Object
});
</script>

<template>
    <Form :slide="slide" />
</template>
