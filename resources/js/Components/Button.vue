<script setup>
defineProps({
    type: {
        type: String,
        default: 'default'
    },
});
</script>

<template>
    <button
        class="text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
        :class="[
            type === 'red'
            ? 'bg-red-500'
            : type === 'gray'
            ? 'bg-gray-500'
            : 'bg-emerald-500'
        ]"
    >
        <slot/>
    </button>
</template>
