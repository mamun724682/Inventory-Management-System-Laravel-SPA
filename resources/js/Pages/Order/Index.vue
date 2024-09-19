<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import {useForm} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';
import {formatDatetime, getCurrency, showToast, truncateString} from "@/Utils/Helper.js";
import TableHead from "@/Components/TableHead.vue";

defineProps({
    filters: {
        type: Object
    },
    orders: {
        type: Object
    },
});

const selectedOrder = ref(null);
const showOrderItemsModal = ref(false);
const showEditModal = ref(false);
const showSettleModal = ref(false);
const nameInput = ref(null);
const tableHeads = ref(["Order Number", "Customer", "Summary(" + getCurrency() + ")", "Paid", "Due", "Profit", "Loss", "Status", "Date", "Action"]);

const form = useForm({
    name: null,
});

const viewOrderItemsModal = (order) => {
    selectedOrder.value = order;
    showOrderItemsModal.value = true;
};

const editOrderModal = (order) => {
    selectedOrder.value = order;
    form.name = order.name
    showEditModal.value = true;

    nextTick(() => nameInput.value.focus());
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

const settleOrderModal = (order) => {
    selectedOrder.value = order;
    showSettleModal.value = true;
};
const settleDuePayment = () => {
    form.delete(route('orders.settle', selectedOrder.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
    });
};

const closeModal = () => {
    showOrderItemsModal.value = false;
    showEditModal.value = false;
    showSettleModal.value = false;
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
                            <Button
                                :href="route('carts.index')"
                                buttonType="link"
                            >Create Order</Button>
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
                        <TableData>{{ getCurrency() }}{{ order.paid }}</TableData>
                        <TableData>
                            <span :class="order.due ? 'text-red-500 text-xl font-bold' : ''">{{ getCurrency() }}{{ order.due }}</span>
                            <br>
                            <div class="flex" v-if="order.due">
                                <Button
                                    @click="viewOrderItemsModal(order)"
                                    title="Pay Due"
                                    class="px-2"
                                >
                                    <i class="fa fa-money-bill-wave"></i>
                                </Button>
                                <Button
                                    @click="settleOrderModal(order)"
                                    type="red"
                                    class="px-2"
                                    title="Settle"
                                >
                                    <i class="fa fa-handshake"></i>
                                </Button>
                            </div>
                        </TableData>
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
                            <Button @click="viewOrderItemsModal(order)" title="Order Items">
                                <i class="fa fa-list"></i>
                            </Button>
<!--                            <Button-->
<!--                                :href="route('orders.show', order.id)"-->
<!--                                buttonType="link"-->
<!--                                preserveScroll-->
<!--                            >-->
<!--                                <i class="fa fa-eye"></i>-->
<!--                            </Button>-->
                        </TableData>
                    </tr>
                </CardTable>
            </div>
        </div>

        <!--Show order items data-->
        <Modal
            :title="'Order Items(' + selectedOrder?.order_items.length + ')'"
            :show="showOrderItemsModal"
            @close="closeModal"
            maxWidth="4xl"
            :showSubmitButton="false"
        >
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 rounded bg-white">
                <div class="block w-full overflow-x-auto">
                    <!-- Projects table -->
                    <table class="items-center w-full bg-transparent border-collapse">
                        <thead>
                        <tr>
                            <TableHead>Product Name</TableHead>
                            <TableHead>Product Number</TableHead>
                            <TableHead>Product Code</TableHead>
                            <TableHead>Price</TableHead>
                            <TableHead>Quantity</TableHead>
                            <TableHead>Action</TableHead>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="(orderItem, index) in selectedOrder.order_items" :key="orderItem.id">
                            <TableData class="text-left flex items-center" :title="orderItem.product_json.name">
                                <img
                                    :src="orderItem.product_json.photo"
                                    class="h-12 w-12 bg-white rounded-full border"
                                    alt="Inventory management system"
                                />
                                <span class="ml-3 font-bold text-blueGray-600">{{ truncateString(orderItem.product_json.name, 15) }}</span>
                            </TableData>
                            <TableData>{{ orderItem.product_json.product_number }}</TableData>
                            <TableData>{{ orderItem.product_json.product_code }}</TableData>
                            <TableData>
                                Buying: <strong>{{ getCurrency() }}{{ orderItem.product_json.buying_price }}</strong>
                                <br>
                                Selling: <strong>{{getCurrency() }}{{ orderItem.product_json.selling_price }}</strong>
                            </TableData>
                            <TableData>
                                <strong>{{ orderItem.quantity }}</strong>
                            </TableData>
                            <TableData>
                                <Button
                                    v-if="orderItem.product_id"
                                    :href="route('products.edit', orderItem.product_id)"
                                    buttonType="link"
                                    preserveScroll
                                >
                                    <i class="fa fa-eye"></i>
                                </Button>
                            </TableData>
                        </tr>

                        </tbody>
                    </table>
                </div>
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

        <!--Settle Order-->
        <Modal
            title="Due Settlement"
            :show="showSettleModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="settleDuePayment"
            maxWidth="md"
            submitButtonText="Yes, settle it!"
        >
            Are you sure you want to settle this due payment?
            <br>
            <br>
            <strong>Note: </strong>The due amount will be applied as discount.
        </Modal>
    </AuthenticatedLayout>
</template>
