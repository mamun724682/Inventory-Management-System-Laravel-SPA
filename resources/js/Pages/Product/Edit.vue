<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import {useForm} from '@inertiajs/vue3';
import {ref, onMounted} from 'vue';
import Button from "@/Components/Button.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import AsyncVueSelect from "@/Components/AsyncVueSelect.vue";
import {showToast} from "@/Utils/Helper.js";
import default_image from "@/assets/img/default-image.jpg";

const isHovered = ref(false);
const fileInput = ref(null);
const previewImage = ref(null);

const props = defineProps({
    product: {
        type: Object
    },
});

onMounted(() => {
    form.category_id = props.product.category_id;
    form.supplier_id = props.product.supplier_id;
    form.name = props.product.name;
    form.description = props.product.description;
    form.product_code = props.product.product_code;
    form.root = props.product.root;
    form.buying_date = props.product.buying_date.split(" ")[0] ?? "";
    form.buying_price = props.product.buying_price;
    form.selling_price = props.product.selling_price;
    form.unit_type_id = props.product.unit_type_id;
    form.quantity = props.product.quantity;
    form.status = props.product.status;

    previewImage.value = props.product.photo;
});

const nameInput = ref(null);

const form = useForm({
    category_id: null,
    supplier_id: null,
    name: null,
    description: null,
    product_code: null,
    root: null,
    buying_date: null,
    buying_price: null,
    selling_price: null,
    unit_type_id: null,
    quantity: null,
    photo: null,
    status: null,
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        previewImage.value = URL.createObjectURL(file);
        form.photo = file;
    }
};

const updateProduct = () => {
    form.transform((data) => ({
        ...data,
        _method: "put"
    }))
        .post(route('products.update', props.product.id), {
            preserveScroll: true,
            onSuccess: () => {
                showToast();
            },
            onError: () => nameInput.value.focus(),
        });
};
</script>

<template>
    <Head title="Product"/>

    <AuthenticatedLayout>
        <template #breadcrumb>
            Products > Edit
        </template>

        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
                    <div class="rounded-t mb-3 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-2xl">Edit Product</h4>
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
                                <label for="category" class="text-stone-600 text-sm font-medium">Select Category</label>
                                <AsyncVueSelect
                                    v-model="form.category_id"
                                    resource="categories.index"
                                    placeholder="Select category"
                                    class="mt-2"
                                />
                                <InputError :message="form.errors.category_id"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="supplier" class="text-stone-600 text-sm font-medium">Select Supplier</label>
                                <AsyncVueSelect
                                    v-model="form.supplier_id"
                                    resource="suppliers.index"
                                    placeholder="Select supplier"
                                    class="mt-2"
                                />
                                <InputError :message="form.errors.supplier_id"/>
                            </div>
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
                                <label for="product_code" class="text-stone-600 text-sm font-medium">Product
                                    Code</label>
                                <input
                                    id="product_code"
                                    v-model="form.product_code"
                                    @keyup.enter="updateProduct"
                                    type="text"
                                    placeholder="Enter product code"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                />
                                <InputError :message="form.errors.product_code"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="root" class="text-stone-600 text-sm font-medium">Root</label>
                                <input
                                    id="root"
                                    v-model="form.root"
                                    @keyup.enter="updateProduct"
                                    type="text"
                                    placeholder="Enter root"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                />
                                <InputError :message="form.errors.root"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="buying_date" class="text-stone-600 text-sm font-medium">Buying Date</label>
                                <input
                                    id="buying_date"
                                    v-model="form.buying_date"
                                    @keyup.enter="updateProduct"
                                    type="date"
                                    placeholder="Enter buying date"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                />
                                <InputError :message="form.errors.buying_date"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="buying_price" class="text-stone-600 text-sm font-medium">Buying
                                    Price</label>
                                <input
                                    id="buying_price"
                                    v-model="form.buying_price"
                                    @keyup.enter="updateProduct"
                                    type="number"
                                    placeholder="Enter buying price"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                />
                                <InputError :message="form.errors.buying_price"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="selling_price" class="text-stone-600 text-sm font-medium">Selling
                                    Price</label>
                                <input
                                    id="selling_price"
                                    v-model="form.selling_price"
                                    @keyup.enter="updateProduct"
                                    type="number"
                                    placeholder="Enter selling price"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                />
                                <InputError :message="form.errors.selling_price"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="quantity" class="text-stone-600 text-sm font-medium">Quantity</label>
                                <div class="flex mt-1">
                                    <AsyncVueSelect
                                        v-model="form.unit_type_id"
                                        resource="unit-types.index"
                                        placeholder="Select unit type"
                                        class="w-1/2 rounded-l-md bg-gray-300 border-none outline-none focus:outline-none"
                                    />
                                    <input
                                        id="quantity"
                                        v-model="form.quantity"
                                        @keyup.enter="createProduct"
                                        type="number"
                                        placeholder="Enter quantity"
                                        class="w-full rounded-r-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                    />
                                </div>
                                <InputError :message="form.errors.unit_type_id"/>
                                <InputError :message="form.errors.quantity"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="status" class="text-stone-600 text-sm font-medium">Status</label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <InputError :message="form.errors.status"/>
                            </div>
                            <div class="flex flex-col">
                                <div class="relative cursor-pointer" @mouseenter="isHovered = true" @mouseleave="isHovered = false">
                                    <img
                                        @click="fileInput.click()"
                                        :alt="$page.props.auth.user.name"
                                        :src="previewImage || default_image"
                                        class="shadow-xl h-auto align-middle border-none absolute max-w-150-px"
                                        style="max-width: 400px !important; height: 150px !important;"
                                        title="Upload Photo"
                                    />
                                    <div
                                        v-if="isHovered"
                                        class="absolute flex items-center justify-center rounded-full"
                                    >
                                        <i class="fas fa-camera text-black text-2xl"></i>
                                    </div>
                                    <input type="file" class="hidden" accept="image/*" ref="fileInput" @change="handleFileChange" />
                                </div>
                                <InputError :message="form.errors.photo"/>
                            </div>
                            <div class="flex flex-col">
                                <label for="description" class="text-stone-600 text-sm font-medium">Description</label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    type="text"
                                    rows="3"
                                    placeholder="Enter description"
                                    class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                ></textarea>
                                <InputError :message="form.errors.description"/>
                            </div>
                        </div>
                        <div class="my-6 flex justify-end">
                            <SubmitButton
                                :processing="form.processing"
                                @click="updateProduct"
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
