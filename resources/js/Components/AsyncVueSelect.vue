<script>
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import pickBy from "lodash/pickBy.js";

export default {
    name: 'AsyncVueSelect',
    components: {
        vSelect
    },
    data: () => ({
        observer: null,
        page: 1,
        search: '',
        paginatedData: {},
        options: [],
    }),
    computed: {
        hasNextPage() {
            return !!this.paginatedData?.next_page_url
        },
    },
    mounted() {
        this.observer = new IntersectionObserver(this.infiniteScroll);
        this.apiCall();
    },
    methods: {
        apiCall() {
            let queries = {
                page: this.page,
                name: this.search,
                inertia: "disabled",
                sort_by: "name",
                sort_order: "asc"
            };

            axios.get(route("employees.index", pickBy(queries))).then(({data}) => {
                this.paginatedData = data;
                if (this.page === 1) {
                    this.options = data.data;
                } else {
                    this.options = [
                        ...this.options,
                        ...data.data
                    ];
                }
            });
        },
        async onOpen() {
            if (this.hasNextPage) {
                await this.$nextTick()
                this.observer.observe(this.$refs.load)
            }
        },
        onClose() {
            this.observer.disconnect()
        },
        async infiniteScroll([{isIntersecting, target}]) {
            if (isIntersecting) {
                const ul = target.offsetParent;
                const scrollTop = target.offsetParent.scrollTop;
                this.page += 1;
                await this.$nextTick();
                ul.scrollTop = scrollTop;
            }
        },
    },
    watch: {
        search(newSearch) {
            this.page = 1;
            this.options = [];
            this.apiCall();
        },
        page(newPage) {
            this.apiCall();
        }
    }
}
</script>

<template>
    <v-select
        :options="options"
        :filterable="false"
        @open="onOpen"
        @close="onClose"
        @search="(query) => (search = query)"
        :reduce="option => option.id"
        label="name"
    >
        <template #list-footer>
            <li v-show="hasNextPage" ref="load" class="loader">
                Loading more options...
            </li>
        </template>
    </v-select>
</template>

<style>
.vs__search, .vs__search:focus {
    padding: 0.3rem 0;
}
</style>

<style scoped>
.loader {
    text-align: center;
    color: #bbbbbb;
}
</style>
