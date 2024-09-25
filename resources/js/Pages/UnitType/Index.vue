<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";

defineProps({
    filters: {
        type: Object
    },
    unitTypes: {
        type: Object
    },
});

import {useForm} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';
import {showToast} from "@/Utils/Helper.js";

const selectedUnitType = ref(null);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const nameInput = ref(null);
const tableHeads = ref(['#', "Name", "Symbol", "Action"]);

const form = useForm({
    name: null,
    symbol: null,
});

const createUnitTypeModal = () => {
    showCreateModal.value = true;

    nextTick(() => nameInput.value.focus());
};

const editUnitTypeModal = (unitType) => {
    selectedUnitType.value = unitType;
    form.name = unitType.name
    form.symbol = unitType.symbol
    showEditModal.value = true;

    nextTick(() => nameInput.value.focus());
};

const deleteUnitTypeModal = (unitType) => {
    selectedUnitType.value = unitType;
    showDeleteModal.value = true;
};

const createUnitType = () => {
    form.post(route('unit-types.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
        onError: () => nameInput.value.focus(),
    });
};

const updateUnitType = () => {
    form.put(route('unit-types.update', selectedUnitType.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
        onError: () => nameInput.value.focus(),
    });
};

const deleteUnitType = () => {
    form.delete(route('unit-types.destroy', selectedUnitType.value.id), {
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
    <Head title="UnitType"/>

    <AuthenticatedLayout>
        <template #breadcrumb>
            Unit Types
        </template>

        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <CardTable
                    indexRoute="unit-types.index"
                    :paginatedData="unitTypes"
                    :filters="filters"
                    :tableHeads="tableHeads"
                >
                    <template #cardHeader>
                        <div class="flex justify-between items-center">
                            <h4 class="text-2xl">Apply filters({{unitTypes.total}})</h4>
                            <Button @click="createUnitTypeModal">Create UnitType</Button>
                        </div>
                    </template>

                    <tr v-for="(unitType, index) in unitTypes.data" :key="unitType.id">
                        <TableData>
                            {{ (unitTypes.current_page * unitTypes.per_page) - (unitTypes.per_page - (index + 1)) }}
                        </TableData>
                        <TableData>{{ unitType.name }}</TableData>
                        <TableData>{{ unitType.symbol }}</TableData>
                        <TableData>
                            <Button @click="editUnitTypeModal(unitType)">
                                <i class="fa fa-edit"></i>
                            </Button>
                            <Button
                                @click="deleteUnitTypeModal(unitType)"
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
            @submitAction="createUnitType"
        >
            <div>
                <label for="name">Name</label>
                <input
                    id="name"
                    ref="nameInput"
                    v-model="form.name"
                    @keyup.enter="createUnitType"
                    type="text"
                    placeholder="Enter name"
                    class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-white rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"
                />
                <InputError :message="form.errors.name"/>
            </div>
            <div class="mt-2">
                <label for="symbol">Symbol</label>
                <input
                    id="symbol"
                    v-model="form.symbol"
                    @keyup.enter="createUnitType"
                    type="text"
                    placeholder="Enter symbol"
                    class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-white rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"
                />
                <InputError :message="form.errors.symbol"/>
            </div>
        </Modal>

        <!--Edit data-->
        <Modal
            title="Edit"
            :show="showEditModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="updateUnitType"
        >
            <div>
                <label for="name">Name</label>
                <input
                    id="name"
                    ref="nameInput"
                    v-model="form.name"
                    @keyup.enter="updateUnitType"
                    type="text"
                    placeholder="Enter name"
                    class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-white rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"
                />
                <InputError :message="form.errors.name"/>
            </div>
            <div class="mt-2">
                <label for="symbol">Symbol</label>
                <input
                    id="symbol"
                    v-model="form.symbol"
                    @keyup.enter="updateUnitType"
                    type="text"
                    placeholder="Enter symbol"
                    class="px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-white rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"
                />
                <InputError :message="form.errors.symbol"/>
            </div>
        </Modal>

        <!--Delete data-->
        <Modal
            title="Delete"
            :show="showDeleteModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="deleteUnitType"
            maxWidth="sm"
            submitButtonText="Yes, delete it!"
        >
            Are you sure you want to delete this unit type?
        </Modal>
    </AuthenticatedLayout>
</template>
