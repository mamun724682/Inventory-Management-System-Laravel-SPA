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
                    <td colspan="4" class="text-center py-4">
                        No data found
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

export default {
    components: {
        Button, TableData,
        TableHead,
        Pagination,
        TableDropdown,
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
                    this.$inertia.get(route(this.indexRoute), pickBy(this.form), {preserveState: true});
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
    }
}
</script>
