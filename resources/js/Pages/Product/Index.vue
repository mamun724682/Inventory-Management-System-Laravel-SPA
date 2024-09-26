<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";
import Modal from "@/Components/Modal.vue";
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import {numberFormat, showToast, truncateString} from "@/Utils/Helper.js";

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
const tableHeads = ref(['#', "Name", "Product Number", "Product Code", "Category", "Supplier", "Quantity", "Status", "Action"]);

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
                            <h4 class="text-2xl">Apply filters({{products.total}})</h4>
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
                        <TableData class="text-left flex items-center" :title="product.name">
                            <img
                                :src="product.photo"
                                class="h-12 w-12 bg-white rounded-full border"
                                alt="Inventory management system"
                            />
                            <span class="ml-3 font-bold text-blueGray-600">{{ truncateString(product.name, 15) }}</span>
                        </TableData>
                        <TableData>{{ product.product_number }}</TableData>
                        <TableData>{{ product.product_code }}</TableData>
                        <TableData :title="product.category.name">{{ truncateString(product.category.name) }}</TableData>
                        <TableData :title="product.supplier?.name">{{ truncateString(product.supplier?.name ?? '-') }}</TableData>
                        <TableData>
                            {{ numberFormat(product.quantity) }} {{ product.unit_type?.symbol }}
                            <span v-if="product.quantity > 0 && product.quantity < 10" class="text-xs font-semibold inline-block py-1 px-2 rounded text-amber-600 bg-amber-200">Low Stock</span>
                            <span v-if="product.quantity < 1" class="text-xs font-semibold inline-block py-1 px-2 rounded text-red-600 bg-red-200">Stock Out</span>
                        </TableData>
                        <TableData>
                            <span v-if="product.status === 'active'" class="text-xs font-semibold inline-block py-1 px-2 rounded text-emerald-600 bg-emerald-200">Active</span>
                            <span v-else class="text-xs font-semibold inline-block py-1 px-2 rounded text-red-600 bg-red-200">Inactive</span>
                        </TableData>
                        <TableData>
                            <Button
                                :href="route('products.edit', product.id)"
                                buttonType="link"
                                preserveScroll
                            >
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
