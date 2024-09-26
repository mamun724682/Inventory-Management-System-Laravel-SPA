<template>
    <div
        class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg mt-16"
    >
        <div class="px-6">
            <div class="flex flex-wrap justify-center">
                <div class="w-full px-4 flex justify-center">
                    <div class="relative" @mouseenter="isHovered = true" @mouseleave="isHovered = false">
                        <img
                            :alt="$page.props.auth.user.name"
                            :src="previewImage || $page.props.auth.user?.photo || avatar"
                            class="shadow-xl rounded-full h-auto align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px"
                            style="width: 150px !important; height: 150px !important;"
                        />
                        <div
                            v-if="isHovered"
                            @click="triggerFileInput"
                            class="absolute flex items-center justify-center rounded-full cursor-pointer"
                        >
                            <i class="fas fa-camera text-white text-2xl"></i>
                        </div>
                        <input type="file" class="hidden" accept="image/*" ref="fileInput" @change="handleFileChange" />
                    </div>
                </div>
            </div>
            <div class="text-center mt-24 mb-10">
                <InputError :message="form.errors.photo"/>
                <h3 class="text-xl font-semibold leading-normal text-blueGray-700 mb-2">
                    {{ $page.props.auth.user.name }}
                </h3>
                <div class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold">
                    <a :href="'mailto:'+$page.props.auth.user.email" class="underline">
                        <i class="fas fa-envelope mr-2 text-lg text-blueGray-400"></i>
                        {{ $page.props.auth.user.email }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import avatar from "@/assets/img/avatar.jpg";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import {showToast} from "@/Utils/Helper.js";

const isHovered = ref(false);
const fileInput = ref(null);
const previewImage = ref(null);

const form = useForm({
    photo: null
});

// Function to trigger the hidden file input when the upload icon is clicked
const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Create a temporary URL for the selected file
        previewImage.value = URL.createObjectURL(file);

        form.photo = file;

        form.post(route('profile.image'), {
            preserveScroll: true,
            onSuccess: () => {
                showToast();
            },
        });
    }
};
</script>
