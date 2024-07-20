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
                        <TableData>{{ product.product_code }}</TableData>
                        <TableData>{{ product.category.name }}</TableData>
                        <TableData>{{ product.supplier?.name ?? '-' }}</TableData>
                        <TableData>{{ product.quantity }}</TableData>
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
