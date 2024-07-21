<script setup>
import InputError from "@/Components/InputError.vue";

const model = defineModel({
    type: [String, Number],
    required: true,
});

defineProps({
    label: {
        type: String,
    },
    name: {
        type: String,
    },
    type: {
        type: String,
        default: 'text',
    },
    placeholder: {
        type: String,
    },
    errorMessage: {
        type: String,
    },
});

const emit = defineEmits(['keyupEnter']);
const keyupEnter = () => {
    emit('keyupEnter');
};
</script>

<template>
    <label :for="name" class="text-stone-600 text-sm font-medium" v-html="label"></label>
    <input
        :id="name"
        v-model="model"
        @keyup.enter="keyupEnter"
        :type="type"
        :placeholder="placeholder"
        class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
    />
    <InputError :message="errorMessage"/>
</template>
