<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableData from "@/Components/TableData.vue";
import {ref} from 'vue';
import {truncateString} from "@/Utils/Helper.js";

defineProps({
    filters: {
        type: Object
    },
    users: {
        type: Object
    },
});

const tableHeads = ref(['#', "Name", "Email", "Email Verified At"]);
</script>

<template>
    <Head title="User"/>

    <AuthenticatedLayout>
        <template #breadcrumb>
            Users
        </template>

        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <CardTable
                    indexRoute="users.index"
                    :paginatedData="users"
                    :filters="filters"
                    :tableHeads="tableHeads"
                >
                    <template #cardHeader>
                        <div class="flex justify-between items-center">
                            <h4 class="text-2xl">Apply filters({{users.total}})</h4>
                        </div>
                    </template>

                    <tr v-for="(user, index) in users.data" :key="user.id">
                        <TableData>
                            {{ (users.current_page * users.per_page) - (users.per_page - (index + 1)) }}
                        </TableData>
                        <TableData class="text-left flex items-center" :title="user.name">
                            <img
                                :src="user.photo"
                                class="h-12 w-12 bg-white rounded-full border"
                                alt="Inventory management system"
                            />
                            <span class="ml-3 font-bold text-blueGray-600">{{ truncateString(user.name, 20) }}</span>
                        </TableData>
                        <TableData>{{ user.email }}</TableData>
                        <TableData>
                            <span v-if="user.email_verfied_at" class="text-xs font-semibold inline-block py-1 px-2 rounded text-emerald-600 bg-emerald-200">Verified</span>
                            <span v-else class="text-xs font-semibold inline-block py-1 px-2 rounded text-red-600 bg-red-200">Unverified</span>
                        </TableData>
                    </tr>
                </CardTable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
