<script setup>
const props = defineProps({
    processing: {
        type: Boolean,
        default: false,
    },
});
</script>
<template>
    <button
        type="submit"
        :class="{ 'opacity-25': props.processing }"
        :disabled="props.processing"
    >
        <slot/>
        <i v-if="props.processing" class="fas fa-spinner fa-spin"></i>
    </button>
</template>
