<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import SubmitButton from "@/Components/SubmitButton.vue";

defineProps({
    pageTitle: {
        type: String,
    },
    canResetPassword: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head :title="pageTitle"/>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div class="container mx-auto px-4 h-full">
            <div class="flex content-center items-center justify-center h-full">
                <div class="w-full lg:w-4/12 px-4">
                    <div
                        class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-200 border-0"
                    >
                        <div class="rounded-t mb-0 px-6 py-6">
                            <div class="text-center mb-3">
                                <h6 class="text-blueGray-500 text-sm font-bold">
                                    Sign in with credentials
                                </h6>
                            </div>
                            <hr class="mt-6 border-b-1 border-blueGray-300"/>
                        </div>
                        <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                            <form @submit.prevent="submit">
                                <div class="relative w-full mb-3">
                                    <label
                                        class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                        for="email"
                                    >
                                        Email
                                    </label>
                                    <input
                                        id="email"
                                        type="email"
                                        v-model="form.email"
                                        required
                                        autofocus
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        placeholder="Email"
                                    />
                                    <InputError :message="form.errors.email"/>
                                </div>

                                <div class="relative w-full mb-3">
                                    <label
                                        class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                        for="password"
                                    >
                                        Password
                                    </label>
                                    <input
                                        id="password"
                                        type="password"
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        placeholder="Password"
                                        v-model="form.password"
                                        required
                                        autocomplete="current-password"
                                    />
                                    <InputError :message="form.errors.password"/>
                                </div>
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <Checkbox
                                            name="remember"
                                            v-model:checked="form.remember"
                                            class="form-checkbox border-0 rounded text-blueGray-700 ml-1 w-5 h-5 ease-linear transition-all duration-150"
                                        />
                                        <span class="ml-2 text-sm font-semibold text-blueGray-600">
                                            Remember me
                                        </span>
                                    </label>
                                </div>

                                <div class="text-center mt-6">
                                    <SubmitButton
                                        class="bg-blueGray-800 text-white active:bg-blueGray-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150"
                                        :processing="form.processing"
                                    >
                                        Sign In
                                    </SubmitButton>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="flex flex-wrap mt-6 relative">
                        <div class="w-1/2">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-blueGray-200"
                            >
                                <small>Forgot password?</small>
                            </Link>
                        </div>
                        <div class="w-1/2 text-right">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="text-blueGray-200"
                            >
                                <small>Create new account</small>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
