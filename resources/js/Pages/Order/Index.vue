<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import { push } from 'notivue'
import {useForm} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';
import {formatDatetime, getCurrency} from "@/Utils/Helper.js";

defineProps({
    filters: {
        type: Object
    },
    orders: {
        type: Object
    },
});

const selectedOrder = ref(null);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const nameInput = ref(null);
const tableHeads = ref(["Order Number", "Customer", "Summary(" + getCurrency() + ")", "Paid", "Due", "Profit", "Loss", "Status", "Date", "Action"]);

const form = useForm({
    name: null,
});

const createOrderModal = () => {
    showCreateModal.value = true;

    nextTick(() => nameInput.value.focus());
};

const editOrderModal = (order) => {
    selectedOrder.value = order;
    form.name = order.name
    showEditModal.value = true;

    nextTick(() => nameInput.value.focus());
};

const deleteOrderModal = (order) => {
    selectedOrder.value = order;
    showDeleteModal.value = true;
};

const createOrder = () => {
    form.post(route('orders.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
        onError: () => nameInput.value.focus(),
    });
};

const updateOrder = () => {
    form.put(route('orders.update', selectedOrder.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
        onError: () => nameInput.value.focus(),
    });
};

const deleteOrder = () => {
    form.delete(route('orders.destroy', selectedOrder.value.id), {
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
    <Head title="Order"/>

    <AuthenticatedLayout>
        <template #breadcrumb>
            Orders
        </template>

        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <CardTable
                    indexRoute="orders.index"
                    :paginatedData="orders"
                    :filters="filters"
                    :tableHeads="tableHeads"
                >
                    <template #cardHeader>
                        <div class="flex justify-between items-center">
                            <h4 class="text-2xl">Apply filters</h4>
                            <Button @click="createOrderModal">Create Order</Button>
                        </div>
                    </template>

                    <tr v-for="(order, index) in orders.data" :key="order.id">
                        <TableData>
                            <strong>#{{ order.order_number }}</strong>
                        </TableData>
                        <TableData>{{ order.customer ? order.customer.name : 'Unknown' }}</TableData>
                        <TableData class="text-start">
                            <span>Sub Total: {{ order.sub_total }}</span><br>
                            <span>Tax: {{ order.tax_total }}</span><br>
                            <span>Discount: {{ order.discount_total }}</span><br>
                            <span>Total: {{ order.total }}</span><br>
                        </TableData>
                        <TableData>
                            <span>{{ getCurrency() }}{{ order.paid }}</span><br>
                            <span>by {{ order.paid_by }}</span><br>
                        </TableData>
                        <TableData :class="order.due ? 'text-red-500 text-xl' : ''">{{ getCurrency() }}{{ order.due }}</TableData>
                        <TableData>{{ getCurrency() }}{{ order.profit }}</TableData>
                        <TableData>{{ getCurrency() }}{{ order.loss }}</TableData>
                        <TableData>
                            <span v-if="order.status === 'paid'" class="text-xs font-semibold inline-block py-1 px-2 rounded text-emerald-600 bg-emerald-200">Paid</span>
                            <span v-else-if="order.status === 'partial_paid'" class="text-xs font-semibold inline-block py-1 px-2 rounded text-amber-600 bg-amber-200">Partial Paid</span>
                            <span v-else-if="order.status === 'unpaid'" class="text-xs font-semibold inline-block py-1 px-2 rounded text-red-600 bg-red-200">Unpaid</span>
                            <span v-else class="text-xs font-semibold inline-block py-1 px-2 rounded text-blue-600 bg-blue-200">Settled</span>
                        </TableData>
                        <TableData>{{ formatDatetime(order.created_at) }}</TableData>
                        <TableData>
                            <Button @click="editOrderModal(order)">
                                <i class="fa fa-edit"></i>
                            </Button>
                            <Button
                                @click="deleteOrderModal(order)"
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
            @submitAction="createOrder"
        >
            <div>
                <label for="name">Name</label>
                <input
                    id="name"
                    ref="nameInput"
                    v-model="form.name"
                    @keyup.enter="createOrder"
                    type="text"
                    placeholder="Enter name"
                    class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-white rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"
                />
                <InputError :message="form.errors.name"/>
            </div>
        </Modal>

        <!--Edit data-->
        <Modal
            title="Edit"
            :show="showEditModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="updateOrder"
        >
            <div>
                <label for="name">Name</label>
                <input
                    id="name"
                    ref="nameInput"
                    v-model="form.name"
                    @keyup.enter="updateOrder"
                    type="text"
                    placeholder="Enter name"
                    class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-white rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"
                />
                <InputError :message="form.errors.name"/>
            </div>
        </Modal>

        <!--Delete data-->
        <Modal
            title="Delete"
            :show="showDeleteModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="deleteOrder"
            maxWidth="sm"
            submitButtonText="Yes, delete it!"
        >
            Are you sure you want to delete this order?
        </Modal>
    </AuthenticatedLayout>
</template>
