<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import CardTable from "@/Components/Cards/CardTable.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import {push} from 'notivue'

defineProps({
    filters: {
        type: Object
    },
    salaries: {
        type: Object
    },
});

import {useForm} from '@inertiajs/vue3';
import {nextTick, ref} from 'vue';
import DashboardInputGroup from "@/Components/DashboardInputGroup.vue";

const selectedSalary = ref(null);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const nameInput = ref(null);
const tableHeads = ref(['#', "Employee", "Amount", "Salary Date", "Action"]);

const form = useForm({
    employee_id: "",
    amount: "",
    salary_date: "",
});

const editSalaryModal = (salary) => {
    selectedSalary.value = salary;

    form.employee_id = salary.employee_id;
    form.amount = salary.amount;
    form.salary_date = salary.salary_date;

    showEditModal.value = true;
    nextTick(() => nameInput.value.focus());
};

const deleteSalaryModal = (salary) => {
    selectedSalary.value = salary;
    showDeleteModal.value = true;
};

const createSalary = () => {
    form.post(route('salaries.store'), {
        preserveScroll: true,
        onSuccess: (e) => {
            closeModal();
            showToast();
        },
        onError: () => nameInput.value.focus(),
    });
};

const updateSalary = () => {
    form.put(route('salaries.update', selectedSalary.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast();
        },
        onError: () => nameInput.value.focus(),
    });
};

const deleteSalary = () => {
    form.delete(route('salaries.destroy', selectedSalary.value.id), {
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
    <Head title="Salary"/>

    <AuthenticatedLayout>
        <template #breadcrumb>
            Salary
        </template>

        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <CardTable
                    indexRoute="salaries.index"
                    :paginatedData="salaries"
                    :filters="filters"
                    :tableHeads="tableHeads"
                >
                    <template #cardHeader>
                        <div class="flex justify-between items-center">
                            <h4 class="text-2xl">Apply filters</h4>
                            <Button @click=" showCreateModal = true">Pay Salary</Button>
                        </div>
                    </template>

                    <tr v-for="(salary, index) in salaries.data" :key="salary.id">
                        <TableData>
                            {{ (salaries.current_page * salaries.per_page) - (salaries.per_page - (index + 1)) }}
                        </TableData>
                        <TableData>{{ salary.employee.name }}</TableData>
                        <TableData>{{ salary.amount }}</TableData>
                        <TableData>{{ salary.salary_date }}</TableData>
                        <TableData>
                            <Button @click="editSalaryModal(salary)">
                                <i class="fa fa-edit"></i>
                            </Button>
                            <Button
                                @click="deleteSalaryModal(salary)"
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
            title="Pay Salary"
            :show="showCreateModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="createSalary"
        >
            <div class="mt-2 grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
                <div class="flex flex-col overflow-auto">
                    <DashboardInputGroup
                        label="Employee"
                        name="employee_id"
                        v-model="form.employee_id"
                        placeholder="Select employee"
                        :errorMessage="form.errors.employee_id"
                        @keyupEnter="createSalary"
                    />
                </div>
                <div class="flex flex-col overflow-auto">
                    <DashboardInputGroup
                        label="Salary Date"
                        name="salary_date"
                        v-model="form.salary_date"
                        placeholder="Enter salary date"
                        :errorMessage="form.errors.salary_date"
                        @keyupEnter="createSalary"
                        type="date"
                    />
                </div>
            </div>
        </Modal>

        <!--Edit data-->
        <Modal
            title="Edit"
            :show="showEditModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="updateSalary"
        >
            <div>
                <DashboardInputGroup
                    label="Employee"
                    name="employee_id"
                    v-model="form.employee_id"
                    placeholder="Select employee"
                    :errorMessage="form.errors.employee_id"
                    @keyupEnter="createSalary"
                />
            </div>
            <div class="mt-2 grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
                <div class="flex flex-col overflow-auto">
                    <DashboardInputGroup
                        label="Amount"
                        name="amount"
                        v-model="form.amount"
                        placeholder="Enter amount"
                        :errorMessage="form.errors.amount"
                        @keyupEnter="createSalary"
                        type="number"
                    />
                </div>
                <div class="flex flex-col overflow-auto">
                    <DashboardInputGroup
                        label="Salary Date"
                        name="salary_date"
                        v-model="form.salary_date"
                        placeholder="Enter salary date"
                        :errorMessage="form.errors.salary_date"
                        @keyupEnter="createSalary"
                        type="date"
                    />
                </div>
            </div>
        </Modal>

        <!--Delete data-->
        <Modal
            title="Delete"
            :show="showDeleteModal"
            :formProcessing="form.processing"
            @close="closeModal"
            @submitAction="deleteSalary"
            maxWidth="sm"
            submitButtonText="Yes, delete it!"
        >
            Are you sure you want to delete this salary?
        </Modal>
    </AuthenticatedLayout>
</template>
