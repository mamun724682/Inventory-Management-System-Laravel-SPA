<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import { push } from 'notivue'

defineProps({
    filters: {
        type: Object
    },
    categories: {
        type: Object
    },
});

import {useForm} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';

const selectedCategory = ref(null);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const nameInput = ref(null);
const tableHeads = ref(['#', "Name", "Action"]);

const form = useForm({
    name: null,
});

const editCategoryModal = (category) => {
    selectedCategory.value = category;
    form.name = category.name
    showEditModal.value = true;

    nextTick(() => nameInput.value.focus());
};

const deleteCategoryModal = (category) => {
    selectedCategory.value = category;
    showDeleteModal.value = true;
};

const updateCategory = () => {
    form.put(route('categories.update', selectedCategory.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
        onError: () => nameInput.value.focus(),
    });
};

const deleteCategory = () => {
    form.delete(route('categories.destroy', selectedCategory.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
    });
};

const closeModal = () => {
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
                    :tableHeads="tableHeads"
                >
                    <tr v-for="(category, index) in categories.data" :key="category.id">
                        <TableData>
                            {{ (categories.current_page * categories.per_page) - (categories.per_page - (index + 1)) }}
                        </TableData>
                        <TableData>{{ category.name }}</TableData>
                        <TableData>
                            <Button @click="editCategoryModal(category)">
                                <i class="fa fa-edit"></i>
                            </Button>
                            <Button
                                @click="deleteCategoryModal(category)"
                                type="red"
                            >
                                <i class="fa fa-trash-alt"></i>
                            </Button>
                        </TableData>
                    </tr>
                </CardTable>
            </div>
        </div>

        <!--Edit data-->
        <Modal
            title="Edit"
            :show="showEditModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="updateCategory"
        >
            <div>
                <label for="name">Name</label>
                <input
                    id="name"
                    ref="nameInput"
                    v-model="form.name"
                    @keyup.enter="updateCategory"
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
            @submitAction="deleteCategory"
            maxWidth="sm"
            submitButtonText="Yes, delete it!"
        >
            Are you sure you want to delete this category?
        </Modal>
    </AuthenticatedLayout>
</template>
