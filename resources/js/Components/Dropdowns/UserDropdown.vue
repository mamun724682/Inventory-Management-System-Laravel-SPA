<template>
    <div>
        <a
            class="text-blueGray-500 block"
            href="#pablo"
            ref="btnDropdownRef"
            v-on:click="toggleDropdown($event)"
        >
            <div class="items-center flex">
        <span
            class="w-12 h-12 text-sm text-white bg-blueGray-200 inline-flex items-center justify-center rounded-full"
        >
          <img
              alt="..."
              class="w-full rounded-full align-middle border-none shadow-lg"
              :src="$page.props.auth.user.photo"
          />
        </span>
            </div>
        </a>
        <div
            ref="popoverDropdownRef"
            class="bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48"
            v-bind:class="{
        hidden: !dropdownPopoverShow,
        block: dropdownPopoverShow,
      }"
        >
            <Link
                :href="route('profile.edit')"
                class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700"
            >
                Profile
            </Link>
            <Link
                :href="route('logout')"
                method="post"
                class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700"
            >
                Logout
            </Link>
        </div>
    </div>
</template>

<script>
import {createPopper} from "@popperjs/core";

import avatar from "@/assets/img/avatar.jpg";
import {Link} from "@inertiajs/vue3";

export default {
    data() {
        return {
            dropdownPopoverShow: false,
            avatar: avatar,
        };
    },
    components: {
        Link
    },
    methods: {
        toggleDropdown: function (event) {
            event.preventDefault();
            if (this.dropdownPopoverShow) {
                this.dropdownPopoverShow = false;
            } else {
                this.dropdownPopoverShow = true;
                createPopper(this.$refs.btnDropdownRef, this.$refs.popoverDropdownRef, {
                    placement: "bottom-start",
                });
            }
        },
    },
};
</script>
