<template>
    <form @submit.prevent="sendMessage">
        <div class="flex-auto p-5 lg:p-10">
            <h4 class="text-2xl font-semibold">
                Want to work with us?
            </h4>
            <p class="leading-relaxed mt-1 mb-4 text-blueGray-500">
                Complete this form and we will get back to you in 24 hours.
            </p>
            <div class="relative w-full mb-3 mt-8">
                <label
                    class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                    for="name"
                >
                    Full Name
                </label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                    placeholder="Full Name"
                />
                <InputError :message="form.errors.name"/>
            </div>

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
                    class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                    placeholder="Email"
                />
                <InputError :message="form.errors.email"/>
            </div>

            <div class="relative w-full mb-3">
                <label
                    class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                    for="message"
                >
                    Message
                </label>
                <textarea
                    id="message"
                    v-model="form.message"
                    required
                    rows="4"
                    cols="80"
                    class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"
                    placeholder="Type a message..."
                />
                <InputError :message="form.errors.message"/>
            </div>
            <div class="text-center mt-6">
                <SubmitButton
                    class="bg-blueGray-800 text-white active:bg-blueGray-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                    :processing="form.processing"
                >
                    Send Message
                </SubmitButton>
            </div>
        </div>
    </form>

    <Notivue v-slot="item">
        <Notification
            :item="item"
            :theme="pastelTheme"
        />
    </Notivue>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import {showToast} from "@/Utils/Helper.js";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import {Notification, Notivue, pastelTheme} from "notivue";


const form = useForm({
    name: null,
    email: null,
    message: null,
});

const sendMessage = () => {
    form.post(route('contacts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showToast();
        },
    });
};
</script>
