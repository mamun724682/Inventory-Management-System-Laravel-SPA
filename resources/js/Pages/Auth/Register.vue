<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthLayout from "@/Layouts/AuthLayout.vue";
import SubmitButton from "@/Components/SubmitButton.vue";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Register"/>

        <div class="container mx-auto px-4 h-full">
            <div class="flex content-center items-center justify-center h-full">
                <div class="w-full lg:w-4/12 px-4">
                    <div
                        class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-200 border-0"
                    >
                        <div class="rounded-t mb-0 px-6 py-6">
                            <div class="text-center">
                                <h6 class="text-blueGray-500 text-sm font-bold">
                                    Register
                                </h6>
                            </div>
                        </div>
                        <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                            <form @submit.prevent="submit">
                                <div class="relative w-full mb-3">
                                    <InputLabel for="name" value="Name"
                                                class="block uppercase text-blueGray-600 text-xs font-bold mb-2"/>

                                    <TextInput
                                        id="name"
                                        type="text"
                                        v-model="form.name"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        placeholder="Enter name"
                                    />

                                    <InputError class="mt-1" :message="form.errors.name"/>
                                </div>
                                <div class="relative w-full mb-3">
                                    <InputLabel for="email" value="Email"
                                                class="block uppercase text-blueGray-600 text-xs font-bold mb-2"/>

                                    <TextInput
                                        id="email"
                                        type="email"
                                        v-model="form.email"
                                        required
                                        autocomplete="username"
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        placeholder="Enter email"
                                    />

                                    <InputError class="mt-1" :message="form.errors.email"/>
                                </div>
                                <div class="relative w-full mb-3">
                                    <InputLabel for="password" value="Password"
                                                class="block uppercase text-blueGray-600 text-xs font-bold mb-2"/>

                                    <TextInput
                                        id="password"
                                        type="password"
                                        v-model="form.password"
                                        required
                                        autocomplete="new-password"
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        placeholder="Enter password"
                                    />

                                    <InputError class="mt-1" :message="form.errors.password"/>
                                </div>
                                <div class="relative w-full mb-3">
                                    <InputLabel for="password_confirmation" value="Confirm Password"
                                                class="block uppercase text-blueGray-600 text-xs font-bold mb-2"/>

                                    <TextInput
                                        id="password_confirmation"
                                        type="password"
                                        v-model="form.password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        placeholder="Enter confirm password"
                                    />

                                    <InputError class="mt-1" :message="form.errors.password_confirmation"/>
                                </div>

                                <div class="text-center mt-6">
                                    <SubmitButton
                                        class="bg-blueGray-800 text-white active:bg-blueGray-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150"
                                        :processing="form.processing"
                                    >
                                        Register
                                    </SubmitButton>
                                    <Link
                                        :href="route('login')"
                                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        Already registered?
                                    </Link>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
