<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";
import Modal from "@/Components/Modal.vue";
import {push} from 'notivue'
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import Datepicker from "@vuepic/vue-datepicker";
import TableHead from "@/Components/TableHead.vue";
import AsyncVueSelect from "@/Components/AsyncVueSelect.vue";
import {truncateString} from "../../Utils/Helper.js";

defineProps({
    products: {
        type: Object
    },
});

const selectedProduct = ref(null);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const tableHeads = ref(['#', "Name", "Product Code", "Category", "Supplier", "Quantity", "Action"]);

const form = useForm({
    name: null,
    email: null,
    phone: null,
    shop_name: null,
    address: null,
    photo: null,
});

const deleteProductModal = (product) => {
    selectedProduct.value = product;
    showDeleteModal.value = true;
};

const deleteProduct = () => {
    form.delete(route('products.destroy', selectedProduct.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
    });
};

const closeModal = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    showDeleteModal.value = false;
    form.reset();
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
            POS
        </template>
        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <div class="relative -mt-16 flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
                    <div class="flex lg:flex-row flex-col-reverse shadow-lg">
                        <!-- left section -->
                        <div class="lg:w-4/5 min-h-screen shadow-lg overflow-auto max-h-screen">
                            <!-- header -->
                            <div class="flex flex-row justify-between items-center px-5 mt-5">
                                <div class="text-gray-800">
                                    <div class="font-bold text-xl">Simons's BQQ Team</div>
                                    <span class="text-xs">Location ID#SIMON123</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm text-center mr-4">
                                        <div class="font-light text-gray-500">last synced</div>
                                        <span class="font-semibold">3 mins ago</span>
                                    </div>
                                    <div>
                                        <span
                                            class="px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded">Help</span>
                                    </div>
                                </div>
                            </div>
                            <!-- end header -->

                            <!-- products -->
                            <div class="grid grid-cols-3 gap-4 px-5 mt-5 overflow-y-auto h-4/6">

                                <div
                                    v-for="(product, index) in products.data" :key="product.id"
                                    role="button"
                                    class="select-none cursor-pointer transition-shadow rounded-md bg-white shadow hover:shadow-lg border border-gray-200 flex flex-col justify-between max-h-56"
                                    :title="product.name"
                                >
                                    <div class="flex justify-center items-center md:p-3">
                                        <img :src="product.photo" class="max-h-40 object-cover rounded-md" :alt="product.name">
                                    </div>
                                    <div class="flex pb-3 px-3 text-sm">
                                        <p class="flex-grow truncate mr-1">
                                            <span v-if="product.quantity > 0" class="text-xs font-semibold inline-block py-1 px-2 rounded text-emerald-600 bg-emerald-200">{{ product.quantity }}</span>
                                            <span v-if="product.quantity < 1" class="text-xs font-semibold inline-block py-1 px-2 rounded text-red-600 bg-red-200">0</span>
                                            {{ truncateString(product.name, 11) }}
                                        </p>
                                        <p class="nowrap font-semibold">
                                            {{ product.selling_price }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                            <!-- end products -->
                        </div>
                        <!-- end left section -->

                        <!-- right section -->
                        <div class="lg:w-2/5">
                            <!-- header -->
                            <div class="flex flex-row items-center justify-between px-5 mt-5">
                                <div class="font-bold text-xl">Cart</div>
                                <div class="font-semibold">
                                    <span class="px-4 py-2 rounded-md bg-red-100 text-red-500">Clear(6)</span>
                                </div>
                            </div>
                            <!-- end header -->
                            <!-- order list -->
                            <div class="px-5 py-4 mt-5 overflow-y-auto h-64">
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Grilled Corn</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">-</span>
                                        <span class="font-semibold mx-4">10</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $3.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Grilled Corn</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">-</span>
                                        <span class="font-semibold mx-4">10</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $3.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Grilled Corn</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">-</span>
                                        <span class="font-semibold mx-4">10</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $3.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center mb-4">
                                    <div class="flex flex-row items-center w-2/5">
                                        <img src="https://www.emsifa.com/tailwind-pos/img/sawarma.png"
                                             class="w-10 h-10 object-cover rounded-md" alt="">
                                        <span class="ml-4 font-semibold text-sm">Ranch Burger</span>
                                    </div>
                                    <div class="w-32 flex justify-between">
                                        <span class="px-3 py-1 rounded-md bg-red-300 text-white">x</span>
                                        <span class="font-semibold mx-4">1</span>
                                        <span class="px-3 py-1 rounded-md bg-gray-300 ">+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        $2.50
                                    </div>
                                </div>
                            </div>
                            <!-- end order list -->
                            <!-- totalItems -->
                            <div class="px-5 mt-1">
                                <div class="py-4 rounded-md shadow-lg">
                                    <div class=" px-4 flex justify-between ">
                                        <span class="font-semibold text-sm">Subtotal</span>
                                        <span class="font-bold">$35.25</span>
                                    </div>
                                    <div class=" px-4 flex justify-between ">
                                        <span class="font-semibold text-sm">Discount</span>
                                        <span class="font-bold">- $5.00</span>
                                    </div>
                                    <div class=" px-4 flex justify-between ">
                                        <span class="font-semibold text-sm">Sales Tax</span>
                                        <span class="font-bold">$2.25</span>
                                    </div>
                                    <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                                        <span class="font-semibold text-2xl">Total</span>
                                        <span class="font-bold text-2xl">$37.50</span>
                                    </div>
                                </div>
                            </div>
                            <!-- end total -->
                            <!-- cash -->
                            <div class="px-5 mt-3">
                                <div class="rounded-md shadow-lg px-4 py-4">
                                    <div class="flex flex-row justify-between items-center">
                                        <div class="flex flex-col">
                                            <span class="uppercase text-xs font-semibold">cashless credit</span>
                                            <span class="text-xl font-bold text-emerald-500">$32.50</span>
                                            <span class=" text-xs text-gray-400">Available</span>
                                        </div>
                                        <div class="px-4 py-3 bg-gray-300 text-gray-800 rounded-md font-bold"> Cancel
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end cash -->
                            <!-- button pay-->
                            <div class="px-5 mt-3">
                                <div
                                    class="px-4 py-4 rounded-md shadow-lg text-center bg-emerald-500 text-white font-semibold">
                                    Pay With Cashless Credit
                                </div>
                            </div>
                            <!-- end button pay -->
                        </div>
                        <!-- end right section -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
