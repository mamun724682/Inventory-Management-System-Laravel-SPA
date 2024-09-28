<template>
    <img :src="logo" alt="LaraTory">
</template>

<script setup>
import LaraToryWhite from "@/assets/img/laratory-white.png";
import LaraToryBlack from "@/assets/img/laratory-black.png";
import LaraToryFavicon from "@/assets/img/laratory-favicon.png";
import {computed} from "vue";

const props = defineProps({
    type: {
        type: String,
        default: "full"
    },
    color: {
        type: String,
        default: "white"
    },
})

const logo = computed(() => {
    if (props.type === "full") {
        return props.color === 'white' ? LaraToryWhite : LaraToryBlack;
    }

    return LaraToryFavicon;
});
</script>
