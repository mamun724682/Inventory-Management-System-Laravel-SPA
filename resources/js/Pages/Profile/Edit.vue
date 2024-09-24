<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import CardProfile from "@/Components/Cards/CardProfile.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateSettingsForm from "@/Pages/Profile/Partials/UpdateSettingsForm.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    settings: {
        type: Object,
    },
});
</script>

<template>
    <Head title="Profile"/>

    <AuthenticatedLayout>
        <template #breadcrumb>
            Profile
        </template>

        <div class="flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0"
                >
                    <div class="rounded-t bg-white mb-0 px-6 py-6">
                        <div class="text-center flex justify-between">
                            <h6 class="text-blueGray-700 text-xl font-bold">My account</h6>
                        </div>
                    </div>
                    <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                        <UpdateSettingsForm
                            :settings="settings"
                        />

                        <hr class="mt-6 border-b-1 border-blueGray-300"/>

                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />

                        <hr class="mt-6 border-b-1 border-blueGray-300"/>

                        <UpdatePasswordForm/>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-4/12 px-4">
                <CardProfile/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
