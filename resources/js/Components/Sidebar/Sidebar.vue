<template>
    <nav
        class="md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-nowrap md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative md:w-64 z-10 py-4 px-6"
    >
        <div
            class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto"
        >
            <!-- Toggler -->
            <button
                class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                type="button"
                v-on:click="toggleCollapseShow('bg-white m-2 py-3 px-6')"
            >
                <i class="fas fa-bars"></i>
            </button>
            <!-- Brand -->
            <Link
                class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
                href="/"
            >
                <ApplicationLogo color="black" class="hidden sm:block" />
            </Link>
            <!-- User -->
            <ul class="md:hidden items-center flex flex-wrap list-none">
                <li class="inline-block relative">
                    <notification-dropdown/>
                </li>
                <li class="inline-block relative">
                    <user-dropdown/>
                </li>
            </ul>
            <!-- Collapse -->
            <div
                class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded"
                v-bind:class="collapseShow"
            >
                <!-- Collapse header -->
                <div
                    class="md:min-w-full md:hidden block pb-4 mb-4 border-b border-solid border-blueGray-200"
                >
                    <div class="flex flex-wrap">
                        <div class="w-6/12">
                            <Link
                                class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
                                href="/"
                            >
                                <ApplicationLogo type="short" class="h-10" />
                            </Link>
                        </div>
                        <div class="w-6/12 flex justify-end">
                            <button
                                type="button"
                                class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                                v-on:click="toggleCollapseShow('hidden')"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Form -->
                <form class="mt-6 mb-4 md:hidden" @submit.prevent="searchProduct">
                    <div class="mb-3 pt-0">
                        <input
                            type="text"
                            v-model="form.keyword"
                            placeholder="Search product..."
                            class="border-0 px-3 py-2 h-12 border border-solid border-blueGray-500 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-base leading-snug shadow-none outline-none focus:outline-none w-full font-normal"
                        />
                    </div>
                </form>

                <!-- Divider -->
                <hr class="my-4 md:min-w-full"/>
                <!-- Navigation -->
                <ul class="md:flex-col md:min-w-full flex flex-col list-none">
                    <SidebarItem
                        name="Dashboard"
                        routeName="dashboard"
                        icon="fas fa-tv"
                    />

                    <SidebarItem
                        name="POS"
                        routeName="carts.index"
                        icon="fas fa-shopping-cart"
                    />

                    <SidebarItem
                        name="Orders"
                        routeName="orders.index"
                        icon="fas fa-database"
                    />

                    <SidebarItem
                        name="Transactions"
                        routeName="transactions.index"
                        icon="fas fa-dollar-sign"
                    />

                    <SidebarItem
                        name="Categories"
                        routeName="categories.index"
                        icon="fas fa-list"
                    />

                    <SidebarItem
                        name="Unit Types"
                        routeName="unit-types.index"
                        icon="fa fa-balance-scale"
                    />

                    <SidebarItem
                        name="Suppliers"
                        routeName="suppliers.index"
                        icon="fas fa-users-cog"
                    />

                    <SidebarItem
                        name="Products"
                        routeName="products.index"
                        icon="fas fa-shopping-bag"
                    />

                    <SidebarItem
                        name="Employee"
                        routeName="employees.index"
                        icon="fas fa-house-user"
                    />

                    <SidebarItem
                        name="Salary"
                        routeName="salaries.index"
                        icon="fas fa-money-bill"
                    />

                    <SidebarItem
                        name="Customer"
                        routeName="customers.index"
                        icon="fas fa-users"
                    />

                    <SidebarItem
                        name="Expenses"
                        routeName="expenses.index"
                        icon="fas fa-book"
                    />

                    <SidebarItem
                        name="Settings"
                        routeName="profile.edit"
                        icon="fas fa-tools"
                    />
                </ul>

                <!-- Divider -->
                <hr class="my-4 md:min-w-full"/>

            </div>
        </div>
    </nav>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import {showToast} from "@/Utils/Helper.js";

const form = useForm({
    keyword: null,
});

const searchProduct = () => {
    form.get(route('carts.index'), {
        preserveScroll: true,
    });
};
</script>

<script>
import NotificationDropdown from "@/Components/Dropdowns/NotificationDropdown.vue";
import UserDropdown from "@/Components/Dropdowns/UserDropdown.vue";
import {Link} from "@inertiajs/vue3";
import SidebarItem from "@/Components/Sidebar/SidebarItem.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

export default {
    data() {
        return {
            collapseShow: "hidden",
        };
    },
    methods: {
        toggleCollapseShow: function (classes) {
            this.collapseShow = classes;
        },
    },
    components: {
        ApplicationLogo,
        SidebarItem,
        NotificationDropdown,
        UserDropdown,
        Link,
    },
};
</script>
