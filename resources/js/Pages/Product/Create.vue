<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import {push} from 'notivue'
import {useForm} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';
import Button from "@/Components/Button.vue";
import SubmitButton from "@/Components/SubmitButton.vue";

defineProps({
    filters: {
        type: Object
    },
    products: {
        type: Object
    },
});

const nameInput = ref(null);

const form = useForm({
    name: null,
    email: null,
    phone: null,
    shop_name: null,
    address: null,
    photo: null,
});

const createProduct = () => {
    form.post(route('products.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showToast();
        },
        onError: () => nameInput.value.focus(),
    });
};

const showToast = () => {
    if (usePage().props.flash.isSuccess) {
        push.success(usePage().props.flash.message)
    } else {
        push.error(usePage().props.flash.message)
    }
};
</script>

<template>
    <Head title="Product"/>

    <AuthenticatedLayout>
        <template #breadcrumb>
            Products > Create
        </template>

        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
                    <div class="rounded-t mb-3 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-2xl">Create Product</h4>
                                    <Button
                                        :href="route('products.index')"
                                        buttonType="link"
                                    >
                                        Go Back
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block w-full overflow-x-auto px-8 py-4">
                        <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
                            <div class="flex flex-col">
                                <label for="name" class="text-stone-600 text-sm font-medium">Name</label>
                                <input
                                    id="name"
                                    ref="nameInput"
                                    v-model="form.name"
                                    @keyup.enter="createProduct"
                                    type="text"
                                    placeholder="Enter name"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                />
                                <InputError :message="form.errors.name"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="email" class="text-stone-600 text-sm font-medium">Email</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    @keyup.enter="createProduct"
                                    type="email"
                                    placeholder="Enter email"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                />
                                <InputError :message="form.errors.email"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="phone" class="text-stone-600 text-sm font-medium">Phone</label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    @keyup.enter="createProduct"
                                    type="text"
                                    placeholder="Enter phone"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                />
                                <InputError :message="form.errors.phone"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="shop_name" class="text-stone-600 text-sm font-medium">Shop Name</label>
                                <input
                                    id="shop_name"
                                    v-model="form.shop_name"
                                    @keyup.enter="createProduct"
                                    type="text"
                                    placeholder="Enter shop name"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                />
                                <InputError :message="form.errors.shop_name"/>
                            </div>
                            <div class="flex flex-col overflow-auto">
                                <label
                                    class="w-64 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-emerald-600">
                                    <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20">
                                        <path
                                            d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z"/>
                                    </svg>
                                    <span v-if="form.photo" class="mt-2 text-base leading-normal">{{
                                            form.photo.name.replace(/(^.{17}).*(\..+$)/, "$1...$2")
                                        }}</span>
                                    <span v-else class="mt-2 text-base leading-normal">Select a photo</span>
                                    <input
                                        @input="form.photo = $event.target.files[0]"
                                        type='file'
                                        class="hidden"
                                        accept="image/png, image/jpeg, image/jpg, image/gif, image/svg"
                                    />
                                </label>
                                <InputError :message="form.errors.photo"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="address" class="text-stone-600 text-sm font-medium">Address</label>
                                <textarea
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    rows="3"
                                    placeholder="Enter address"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                ></textarea>
                                <InputError :message="form.errors.address"/>
                            </div>
                        </div>
                        <div class="my-6 flex justify-end">
                            <SubmitButton
                                :processing="form.processing"
                                @click="createProduct"
                                class="text-white bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
                            >
                                Submit
                            </SubmitButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
