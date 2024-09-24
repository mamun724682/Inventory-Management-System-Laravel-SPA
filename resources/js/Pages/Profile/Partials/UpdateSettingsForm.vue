<script setup>
import InputError from '@/Components/InputError.vue';
import {useForm} from '@inertiajs/vue3';
import SubmitButton from "@/Components/SubmitButton.vue";
import {onMounted, ref} from "vue";

const props = defineProps({
    settings: {
        type: Object,
    },
});

const form = ref({});

onMounted(() => {
    let formData = {};
    props.settings.fields.forEach(field => {
        formData[field.value] = props.settings.data?.hasOwnProperty(field.value) ? props.settings.data[field.value] : null;
    });
    form.value = formData;
});
</script>

<template>
    <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
        System Settings
    </h6>
    <form @submit.prevent="$inertia.put(route('settings.update'), form, {preserveScroll: true})">
        <div class="flex flex-wrap">

            <div
                v-for="(settingField, index) in settings.fields"
                :key="index"
                class="w-full lg:w-6/12 px-4"
            >
                <div class="relative w-full mb-3">
                    <label
                        class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                        :for="settingField.value"
                    >
                        {{ settingField.label }}
                    </label>
                    <input
                        :id="settingField.value"
                        type="text"
                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                        v-model="form[settingField.value]"
                    />
<!--                    <InputError :message="form.errors[settingField.value]"/>-->
                </div>
            </div>

            <div class="w-full px-4 flex justify-end">
                <SubmitButton
                    :processing="form.processing"
                    class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
                >
                    Save
                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <i v-if="form.recentlySuccessful" class="fas fa-check-circle"></i>
                    </Transition>
                </SubmitButton>
            </div>
        </div>
    </form>
</template>
