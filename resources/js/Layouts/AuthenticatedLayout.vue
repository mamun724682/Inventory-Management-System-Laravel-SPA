<script setup>
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import AdminNavbar from "@/Components/Navbars/AdminNavbar.vue";
import HeaderStats from "@/Components/Headers/HeaderStats.vue";
import FooterAdmin from "@/Components/Footers/FooterAdmin.vue";
</script>

<template>
    <div>
        <Sidebar/>

        <div class="relative md:ml-64 bg-blueGray-100">
            <AdminNavbar/>
            <HeaderStats/>
            <div class="px-4 md:px-10 mx-auto w-full -m-24">

                <slot/>

                <FooterAdmin/>
            </div>
        </div>
    </div>
</template>
