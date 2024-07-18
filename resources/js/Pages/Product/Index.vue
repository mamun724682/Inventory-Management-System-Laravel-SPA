<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import {push} from 'notivue'
import {useForm, Link} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';

defineProps({
    filters: {
        type: Object
    },
    products: {
        type: Object
    },
});

const selectedProduct = ref(null);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const nameInput = ref(null);
const tableHeads = ref(['#', "Name", "Email", "Phone", "Shop Name", "Action"]);

const form = useForm({
    name: null,
    email: null,
    phone: null,
    shop_name: null,
    address: null,
    photo: null,
});

const createProductModal = () => {
    showCreateModal.value = true;

    nextTick(() => nameInput.value.focus());
};

const editProductModal = (product) => {
    selectedProduct.value = product;

    form.name = product.name;
    form.email = product.email;
    form.phone = product.phone;
    form.shop_name = product.shop_name;
    form.address = product.address;
    form.photo = null;

    showEditModal.value = true;

    nextTick(() => nameInput.value.focus());
};

const deleteProductModal = (product) => {
    selectedProduct.value = product;
    showDeleteModal.value = true;
};

const createProduct = () => {
    form.post(route('products.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
        onError: () => nameInput.value.focus(),
    });
};

const updateProduct = () => {
    form.transform((data) => ({
        ...data,
        _method: "put"
    }))
        .post(route('products.update', selectedProduct.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                showToast();
            },
            onError: () => nameInput.value.focus(),
        });
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
            Products
        </template>

        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <CardTable
                    indexRoute="products.index"
                    :paginatedData="products"
                    :filters="filters"
                    :tableHeads="tableHeads"
                >
                    <template #cardHeader>
                        <div class="flex justify-between items-center">
                            <h4 class="text-2xl">Apply filters</h4>
                            <Button
                                :href="route('products.create')"
                                buttonType="link"
                            >
                                Create Product
                            </Button>
                        </div>
                    </template>

                    <tr v-for="(product, index) in products.data" :key="product.id">
                        <TableData>
                            {{ (products.current_page * products.per_page) - (products.per_page - (index + 1)) }}
                        </TableData>
                        <TableData class="text-left flex items-center">
                            <img
                                :src="product.photo"
                                class="h-12 w-12 bg-white rounded-full border"
                                alt="Inventory management system"
                            />
                            <span class="ml-3 font-bold text-blueGray-600">{{ product.name }}</span>
                        </TableData>
                        <TableData>{{ product.email }}</TableData>
                        <TableData>{{ product.phone }}</TableData>
                        <TableData>{{ product.shop_name }}</TableData>
                        <TableData>
                            <Button @click="editProductModal(product)">
                                <i class="fa fa-edit"></i>
                            </Button>
                            <Button
                                @click="deleteProductModal(product)"
                                type="red"
                            >
                                <i class="fa fa-trash-alt"></i>
                            </Button>
                        </TableData>
                    </tr>
                </CardTable>
            </div>
        </div>

        <!--Create data-->
        <Modal
            title="Create"
            :show="showCreateModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="createProduct"
        >
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
                <div class="flex flex-col">
                    <label
                        class="w-64 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-emerald-600">
                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
        </Modal>

        <!--Edit data-->
        <Modal
            title="Edit"
            :show="showEditModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="updateProduct"
        >
            <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
                <div class="flex flex-col">
                    <label for="name" class="text-stone-600 text-sm font-medium">Name</label>
                    <input
                        id="name"
                        ref="nameInput"
                        v-model="form.name"
                        @keyup.enter="updateProduct"
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
                        @keyup.enter="updateProduct"
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
                        @keyup.enter="updateProduct"
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
                        @keyup.enter="updateProduct"
                        type="text"
                        placeholder="Enter shop name"
                        class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                    />
                    <InputError :message="form.errors.shop_name"/>
                </div>
                <div class="flex flex-col">
                    <label
                        class="w-64 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-emerald-600">
                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
        </Modal>

        <!--Delete data-->
        <Modal
            title="Delete"
            :show="showDeleteModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="deleteProduct"
            maxWidth="sm"
            submitButtonText="Yes, delete it!"
        >
            Are you sure you want to delete this product?
        </Modal>
    </AuthenticatedLayout>
</template>
