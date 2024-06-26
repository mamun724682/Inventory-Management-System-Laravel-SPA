<template>
    <div
        class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg mt-16"
    >
        <div class="px-6">
            <div class="flex flex-wrap justify-center">
                <div class="w-full px-4 flex justify-center">
                    <div class="relative">
                        <img
                            :alt="$page.props.auth.user.name"
                            :src="avatar"
                            class="shadow-xl rounded-full h-auto align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px"
                        />
                    </div>
                </div>
            </div>
            <div class="text-center mt-24 mb-10">
                <h3 class="text-xl font-semibold leading-normal text-blueGray-700 mb-2">
                    {{ $page.props.auth.user.name }}
                </h3>
                <div class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold">
                    <a :href="'mailto:'+$page.props.auth.user.email" class="underline">
                        <i class="fas fa-envelope mr-2 text-lg text-blueGray-400"></i>
                        {{ $page.props.auth.user.email }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import avatar from "@/assets/img/avatar.jpg";
</script>
