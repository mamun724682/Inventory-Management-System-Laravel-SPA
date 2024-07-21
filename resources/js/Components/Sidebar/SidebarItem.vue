<template>
    <li class="items-center">
        <Link
            :href="route(routeName)"
            class="text-xs uppercase py-3 font-bold block"
            :class="[
                  route().current(routeName)
                    ? 'text-emerald-500 hover:text-emerald-600'
                    : 'text-blueGray-700 hover:text-blueGray-500',
                ]"
        >
            <i
                v-if="route().current(routeName)"
                class="mr-2 text-sm opacity-75"
                :class="icon"
            ></i>
            <i v-else
                class="mr-2 text-sm text-blueGray-300"
                :class="icon"
            ></i>

            {{ name }}
        </Link>
    </li>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    name: {
        type: String,
    },
    routeName: {
        type: [String, Array],
    },
    icon: {
        type: String,
    },
});
</script>
