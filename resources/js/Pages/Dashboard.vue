<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CardLineChart from "@/Components/Cards/CardLineChart.vue";
import CardBarChart from "@/Components/Cards/CardBarChart.vue";
import CardPageVisits from "@/Components/Cards/CardPageVisits.vue";
import CardSocialTraffic from "@/Components/Cards/CardSocialTraffic.vue";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div>
            <div class="flex flex-wrap">
                <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4">
                    <CardLineChart />
                </div>
                <div class="w-full xl:w-4/12 px-4">
                    <CardBarChart/>
                </div>
            </div>
            <div class="flex flex-wrap mt-4">
                <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4">
                    <CardPageVisits/>
                </div>
                <div class="w-full xl:w-4/12 px-4">
                    <CardSocialTraffic/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
