<script setup>
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import AdminNavbar from "@/Components/Navbars/AdminNavbar.vue";
import FooterAdmin from "@/Components/Footers/FooterAdmin.vue";
</script>

<template>
    <div>
        <Sidebar/>

        <div class="relative md:ml-64 bg-blueGray-100">
            <AdminNavbar>
                <template #breadcrumb>
                    <slot name="breadcrumb"/>
                </template>
            </AdminNavbar>

            <!-- Header -->
            <div class="relative bg-emerald-600 md:pt-32 pb-32 pt-12">
                <div class="px-4 md:px-10 mx-auto w-full">
                    <div>
                        <!-- Card stats -->
                        <slot name="headerState" />
                    </div>
                </div>
            </div>

            <div class="px-4 md:px-10 mx-auto w-full -m-24">

                <slot/>

                <FooterAdmin/>
            </div>
        </div>
    </div>
</template>
