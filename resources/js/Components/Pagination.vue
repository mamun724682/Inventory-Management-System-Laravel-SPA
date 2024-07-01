<script setup>
import {Link} from '@inertiajs/vue3'

defineProps({
    links: {
        type: Array,
    },
});
</script>

<template>
    <div v-if="links.length > 3">
        <nav class="block">
            <ul class="flex pl-0 rounded list-none flex-wrap">
                <li v-for="(link, key) in links">
                    <p
                        v-if="link.url === null" :key="key"
                        class="first:ml-0 text-xs font-semibold flex w-8 h-8 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-emerald-200 text-white bg-emerald-200">
                        <i v-if="link.label.includes('Previous')" class="fas fa-chevron-left -ml-px"></i>
                        <i v-else-if="link.label.includes('Next')" class="fas fa-chevron-right -mr-px"></i>
                        <span v-else>{{ link.label }}</span>
                    </p>
                    <Link
                        v-else :key="`link-${key}`"
                        class="first:ml-0 text-xs font-semibold flex w-8 h-8 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-emerald-200"
                        :class="[
                            link.active ? 'text-white bg-emerald-500' : 'bg-white text-emerald-500 hover:bg-emerald-200'
                            ]"
                        :href="link.url"
                    >
                        <i v-if="link.label.includes('Previous')" class="fas fa-chevron-left -ml-px"></i>
                        <i v-else-if="link.label.includes('Next')" class="fas fa-chevron-right -mr-px"></i>
                        <p v-else>{{ link.label }}</p>
                    </Link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<style scoped>

</style>
