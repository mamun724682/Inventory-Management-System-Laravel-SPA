<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import {useForm} from '@inertiajs/vue3';
import {watch} from 'vue';
import AsyncVueSelect from "@/Components/AsyncVueSelect.vue";
import {getCurrency, numberFormat, showToast, truncateString} from "@/Utils/Helper.js";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";

const props = defineProps({
    products: Object,
    carts: Object,
    cartSubtotal: Number,
    discountType: String,
    discount: Number,
    totalDiscount: Number,
    tax: Number,
    totalTax: Number,
    total: Number,
    orderPaidByTypes: Object,
});

const form = useForm({
    customer_id: null,
    total: null,
    paid: null,
    paid_through: "cash",
    custom_discount: {
        discount: 0,
        discount_type: "fixed"
    },
});

// Watch props and update the form fields reactively
watch(props, (newProps) => {
    if (form.custom_discount.discount_type === "fixed") {
        form.total = newProps.total - form.custom_discount.discount;
        form.paid = newProps.total - form.custom_discount.discount;
    } else {
        form.total = numberFormat(newProps.total - (newProps.cartSubtotal * (form.custom_discount.discount / 100)));
        form.paid = numberFormat(newProps.total - (newProps.cartSubtotal * (form.custom_discount.discount / 100)));
    }
}, { immediate: true });

// watch form
watch(() => form.custom_discount, async (newForm, oldForm) => {
    if (form.custom_discount.discount_type === "fixed") {
        form.total = props.total - form.custom_discount.discount;
        form.paid = props.total - form.custom_discount.discount;
    } else {
        form.total = numberFormat(props.total - (props.cartSubtotal * (form.custom_discount.discount / 100)));
        form.paid = numberFormat(props.total - (props.cartSubtotal * (form.custom_discount.discount / 100)));
    }
}, {
    immediate: true,
    deep: true
})

const addToCart = (product) => {
    router.post(route('carts.store', product.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            showToast();
        }
    });
};

const incrementCartQuantity = (cart) => {
    router.put(route('carts.increment', cart.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            showToast();
        }
    });
};

const decrementCartQuantity = (cart) => {
    router.put(route('carts.decrement', cart.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            showToast();
        }
    });
};

const insertCartQuantity = (cart, quantity) => {
    if (cart.quantity == quantity) {
        return;
    }
    router.put(route('carts.update', cart.id), {
        quantity: quantity,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showToast();
        }
    });
};

const deleteCart = (cart) => {
    router.delete(route('carts.delete', cart.id), {
        preserveScroll: true,
        onSuccess: () => {
            showToast();
        }
    });
};

const deleteCartAllItems = () => {
    router.delete(route('carts.delete.all'), {
        onSuccess: () => {
            showToast();
        }
    });
};

const createOrder = () => {
    if (!props.carts.total) {
        return;
    }
    form.post(route('orders.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showToast();
            form.reset()
        },
    });
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
                                    <div class="font-bold text-xl">Products</div>
<!--                                    <span class="text-xs">Location ID#SIMON123</span>-->
                                </div>
<!--                                <div class="flex items-center">-->
<!--                                    <div class="text-sm text-center mr-4">-->
<!--                                        <div class="font-light text-gray-500">last synced</div>-->
<!--                                        <span class="font-semibold">3 mins ago</span>-->
<!--                                    </div>-->
<!--                                    <div>-->
<!--                                        <span-->
<!--                                            class="px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded">Help</span>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                            <!-- end header -->

                            <!-- products -->
                            <div class="grid grid-cols-3 gap-4 px-5 mt-5 overflow-y-auto h-4/6">

                                <div
                                    v-for="(product, index) in products.data" :key="product.id"
                                    role="button"
                                    class="select-none cursor-pointer transition-shadow rounded-md bg-white shadow hover:shadow-lg border border-gray-200 flex flex-col justify-between max-h-56"
                                    :title="product.name"
                                    @click="addToCart(product)"
                                >
                                    <div class="flex justify-center items-center md:p-3">
                                        <img :src="product.photo" class="max-h-40 object-cover rounded-md" :alt="product.name">
                                    </div>
                                    <div class="flex pb-3 px-3 text-sm">
                                        <p class="flex-grow truncate mr-1">
                                            <span v-if="product.quantity > 0" class="text-xs font-semibold inline-block py-1 px-2 rounded text-emerald-600 bg-emerald-200">{{ product.quantity }}{{ product.unit_type?.symbol }}</span>
                                            <span v-if="product.quantity < 1" class="text-xs font-semibold inline-block py-1 px-2 rounded text-red-600 bg-red-200">0</span>
                                            {{ truncateString(product.name, 11) }}
                                        </p>
                                        <p class="nowrap font-semibold">
                                            {{ getCurrency() }}{{ product.selling_price }}
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
                                    <span
                                        @click="carts.total > 0 ? deleteCartAllItems() : null"
                                        :role="carts.total > 0 ? 'button' : null"
                                        class="px-4 py-2 rounded-md bg-red-100 text-red-500"
                                    >Clear({{ carts.total }})</span>
                                </div>
                            </div>
                            <!-- end header -->
                            <!-- order list -->
                            <div class="px-5 py-2 overflow-y-auto h-64">

                                <div
                                    v-for="cart in carts.data"
                                    :key="cart.id"
                                    class="flex flex-row justify-between items-center mb-4"
                                    :class="cart.quantity > cart.product.quantity ? 'bg-red-200' : ''"
                                >
                                    <div class="flex flex-row items-center w-2/5" :title="cart.product.name">
                                        <img :src="cart.product.photo"
                                             class="w-10 h-10 object-cover rounded-md" :alt="cart.product.name">
                                        <span class="ml-1 font-semibold text-sm">
                                            {{ truncateString(cart.product.name) }}
                                            <br>
                                            Q: {{ numberFormat(cart.product.quantity) }} {{ cart.product.unit_type?.symbol }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span
                                            role="button"
                                            @click="decrementCartQuantity(cart)"
                                            class="px-3 py-1 rounded-l-md bg-gray-300 "
                                        >-</span>
                                        <input
                                            @keyup.enter="insertCartQuantity(cart, $event.target.value)"
                                            type="number"
                                            class="font-semibold border-gray-300 px-0.5 py-1 w-10 text-center"
                                            :value="cart.quantity"
                                        >
                                        <span
                                            @click="incrementCartQuantity(cart)"
                                            role="button"
                                            class="px-3 py-1 rounded-r-md bg-gray-300 "
                                        >+</span>
                                    </div>
                                    <div class="font-semibold text-lg w-16 text-center">
                                        {{ getCurrency() }}{{ numberFormat(cart.quantity * cart.product.selling_price) }}
                                    </div>
                                    <i
                                        @click="deleteCart(cart)"
                                        role="button"
                                        class="fa fa-trash-alt text-red-500"
                                    ></i>
                                </div>

                            </div>
                            <!-- end order list -->
                            <!-- totalItems -->
                            <div class="px-5 mt-1">
                                <div class="pt-2 rounded-md shadow-lg">
                                    <div class=" px-4 flex justify-between ">
                                        <span class="font-semibold text-sm">Subtotal</span>
                                        <span class="font-bold">{{ getCurrency() }}{{ cartSubtotal }}</span>
                                    </div>
                                    <div class=" px-4 flex justify-between ">
                                        <span class="font-semibold text-sm">Sales Tax({{ tax }}%)</span>
                                        <span class="font-bold">{{ getCurrency() }}{{ totalTax }}</span>
                                    </div>
                                    <div class=" px-4 flex justify-between ">
                                        <span v-if="discountType === 'fixed'" class="font-semibold text-sm">Discount({{ getCurrency()}}{{ discount }})</span>
                                        <span v-else class="font-semibold text-sm">Discount({{ discount }}%)</span>
                                        <span class="font-bold">- {{ getCurrency() }}{{ totalDiscount }}</span>
                                    </div>
                                    <div class=" px-4 flex justify-between items-center">
                                        <div class="text-sm flex items-center flex-wrap">
                                            <span class="font-semibold text-sm mr-1">Custom Discount - </span>
                                            <div class="flex">
                                                <select
                                                    v-model="form.custom_discount.discount_type"
                                                    class="px-3 py-1 w-14 rounded-l-md bg-gray-300 border-none"
                                                >
                                                    <option value="fixed">=</option>
                                                    <option value="percentage">%</option>
                                                </select>
                                                <input
                                                    v-model="form.custom_discount.discount"
                                                    type="number"
                                                    class="font-semibold border-gray-300 px-0.5 py-1 w-10 text-center"
                                                >
                                            </div>
                                        </div>
                                        <span v-if="form.custom_discount.discount_type === 'fixed'" class="font-bold">- {{ getCurrency() }}{{ form.custom_discount.discount }}</span>
                                        <span v-else class="font-bold">- {{ getCurrency() }}{{ numberFormat(cartSubtotal * (form.custom_discount.discount / 100)) }}</span>
                                    </div>
                                    <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                                        <span class="font-semibold text-2xl">Total</span>
                                        <span class="font-bold text-2xl">{{ getCurrency() }}{{ form.total }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- end total -->
                            <!-- cash -->
                            <div class="px-5 mt-1">
                                <div class="rounded-md shadow-lg px-4 py-4">
                                    <div>
                                        <label for="customer" class="text-stone-600 text-sm font-medium">Customer</label>
                                        <AsyncVueSelect
                                            v-model="form.customer_id"
                                            class="my-1"
                                            resource="customers.index"
                                            placeholder="Select Customer"
                                        />
                                        <InputError :message="form.errors.customer_id"/>
                                    </div>

                                    <div>
                                        <label for="paid" class="text-stone-600 text-sm font-medium">Pay</label>
                                        <div class="flex mt-1">
                                            <select
                                                id="paid_through"
                                                v-model="form.paid_through"
                                                class="w-1/2 rounded-l-md bg-gray-300 border-none px-2 py-2 outline-none focus:outline-none"
                                            >
                                                <option
                                                    v-for="(orderPaidByType, index) in orderPaidByTypes"
                                                    :key="index"
                                                    :value="orderPaidByType.value"
                                                >
                                                    {{ orderPaidByType.label }}
                                                </option>
                                            </select>
                                            <input
                                                id="paid"
                                                placeholder="Enter paid amount"
                                                v-model="form.paid"
                                                type="text"
                                                class="w-full rounded-r-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                                            />
                                        </div>
                                        <InputError :message="form.errors.paid"/>
                                    </div>
                                </div>
                            </div>
                            <!-- end cash -->
                            <!-- button pay-->
                            <div class="px-5 mt-3 mb-4">
                                <SubmitButton
                                    @click="createOrder"
                                    :processing="form.processing"
                                    class="w-full px-4 py-4 rounded-md shadow-lg text-center bg-emerald-500 text-white font-semibold focus:outline-none"
                                    :class="!carts.total ? 'cursor-not-allowed' : ''"
                                >Pay & Print</SubmitButton>
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

<style scoped>
/* Custom CSS to hide the spinner */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>
