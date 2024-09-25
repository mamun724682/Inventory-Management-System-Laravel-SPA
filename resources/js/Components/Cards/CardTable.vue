<template>
    <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
        <div class="rounded-t mb-3 px-4 py-3 border-0">
            <div class="flex flex-wrap items-center">
                <div class="relative w-full px-4 max-w-full flex-grow flex-1">

                    <slot name="cardHeader"/>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                        <div
                            v-for="(filter, key, index) in filters" :key="index"
                            class="flex flex-col"
                        >
                            <label :for="key" class="text-stone-600 text-sm font-medium">{{ filter.label }}</label>
                            <input
                                v-if="filter.type === 'string'"
                                :id="key"
                                :placeholder="filter.placeholder"
                                v-model="form[key]"
                                type="text"
                                class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                            />
                            <input
                                v-if="filter.type === 'date'"
                                :id="key"
                                :placeholder="filter.placeholder"
                                v-model="form[key]"
                                type="date"
                                class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                            />
                            <input
                                v-if="filter.type === 'month'"
                                :id="key"
                                :placeholder="filter.placeholder"
                                v-model="form[key]"
                                type="month"
                                class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                            />
                            <select
                                v-else-if="filter.type === 'select_static'"
                                :id="key"
                                v-model="form[key]"
                                class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                                <option value="">Select option</option>
                                <option
                                    v-for="(option, staticSelectIndex) in filter.options"
                                    :key="staticSelectIndex"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>

                            <AsyncVueSelect
                                v-if="filter.type === 'select'"
                                v-model="form[key]"
                                class="mt-2"
                                :resource="filter.resource"
                                :resourceLabel="filter.resourceLabel"
                                :placeholder="filter.placeholder"
                            />

                            <input
                                v-if="filter.type === 'number_range'"
                                :id="key"
                                placeholder="Sample range: 10-100"
                                v-model="form[key]"
                                @input="form[key] = $event.target.value.replace(/[^0-9-]/g, '')"
                                type="text"
                                class="mt-2 block w-full rounded-md border border-gray-200 px-2 py-2 shadow-sm outline-none focus:outline-none focus:shadow-outline"
                            />

                            <Datepicker
                                v-if="filter.type === 'date_range'"
                                v-model="form[key]"
                                model-type="yyyy-MM-dd"
                                format="yyyy-MM-dd"
                                range
                                auto-apply
                                :enable-time-picker="false"
                                class="mt-2"
                                :placeholder="filter.placeholder"
                            />

                            <Datepicker
                                v-if="filter.type === 'datetime_range'"
                                v-model="form[key]"
                                model-type="yyyy-MM-dd HH:mm:ss"
                                range
                                auto-apply
                                class="mt-2"
                                :placeholder="filter.placeholder"
                            />
                        </div>

                        <div class="flex flex-col justify-end">
                            <div>
                                <button
                                    @click="reset"
                                    class="active:scale-95 rounded-lg bg-gray-200 px-8 py-2 font-medium text-gray-600 outline-none focus:ring hover:opacity-90">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block w-full overflow-x-auto">
            <!-- Projects table -->
            <table class="items-center w-full bg-transparent border-collapse">
                <thead>
                <tr>
                    <TableHead v-for="(tableHead, index) in tableHeads" :key="index" v-html="tableHead"></TableHead>
                </tr>
                </thead>
                <tbody>

                <slot/>

                <tr v-if="paginatedData.data.length === 0">
                    <td :colspan="tableHeads.length" class="py-4">
                        <div class="flex justify-center">
                            <img
                                alt="Inventory management system"
                                :src="emptyData"
                            />
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex justify-end">
        <Pagination :links="paginatedData.links"/>
    </div>
</template>
<script>
import TableDropdown from "@/Components/Dropdowns/TableDropdown.vue";
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Pagination from "@/Components/Pagination.vue";
import TableHead from "@/Components/TableHead.vue";
import TableData from "@/Components/TableData.vue";
import Button from "@/Components/Button.vue";
import emptyData from "@/assets/img/emptyData.png"
import {usePage} from "@inertiajs/vue3";
import {push} from "notivue";
import AsyncVueSelect from "@/Components/AsyncVueSelect.vue";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

export default {
    components: {
        AsyncVueSelect,
        Button,
        TableData,
        TableHead,
        Pagination,
        TableDropdown,
        Datepicker,
    },
    props: {
        indexRoute: String,
        paginatedData: Object,
        filters: Object,
        tableHeads: Array
    },
    data() {
        return {
            form: {},
            emptyData: emptyData,
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                // Check the form is changed
                let isFormChanged = false;
                for (const key in this.filters) {
                    let formKeyValue = this.form[key] ?? "";
                    if (formKeyValue !== this.filters[key].value) {
                        isFormChanged = true;
                    }
                }

                // if Changed than send request
                if (isFormChanged) {
                    this.$inertia.get(route(this.indexRoute), pickBy(this.form), {
                        preserveState: true,
                        onError: (e) => {
                            console.log(Object.values(e)[0])
                            this.showErrorToast(Object.values(e)[0])
                        }
                    });
                }
            }, 150),
        },
    },
    created() {
        this.initializeForm();
    },
    methods: {
        initializeForm() {
            for (const key in this.filters) {
                this.form[key] = this.filters[key].value
            }
        },
        reset() {
            this.form = mapValues(this.form, () => "")
        },
        showToast() {
            if (usePage().props.flash.isSuccess) {
                push.success(usePage().props.flash.message)
            } else {
                push.error(usePage().props.flash.message)
            }
        },
        showErrorToast(message) {
            push.error(message)
        },
    }
}
</script>

<style>
:root {
    --dp-input-padding: 10px 30px 6px 12px !important;
    --dp-border-radius: 6px !important;
}
</style>
