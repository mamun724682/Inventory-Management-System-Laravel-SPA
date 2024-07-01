<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableDropdown from "@/Components/Dropdowns/TableDropdown.vue";
import TableHead from "@/Components/TableHead.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";

defineProps({
    filters: {
        type: Object
    },
    categories: {
        type: Object
    },
});
</script>

<template>
    <Head title="Category"/>

    <AuthenticatedLayout>
        <template #breadcrumb>
            Categories
        </template>

        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <CardTable
                    indexRoute="categories.index"
                    :paginatedData="categories"
                    :filters="filters"
                >
                    <thead>
                    <tr>
                        <TableHead>#</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Action</TableHead>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(category, index) in categories.data" :key="category.id">
                        <TableData>{{ (categories.current_page * categories.per_page) - (categories.per_page - (index + 1)) }}</TableData>
                        <TableData>{{ category.name }}</TableData>
                        <TableData>
                            <Button>
                                <i class="fa fa-edit"></i>
                            </Button>
                            <Button type="red">
                                <i class="fa fa-trash-alt"></i>
                            </Button>
                        </TableData>
                    </tr>
                    <tr v-if="categories.data.length === 0">
                        <td colspan="4" class="text-center py-4">
                            No data found
                        </td>
                    </tr>
                    </tbody>
                </CardTable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
