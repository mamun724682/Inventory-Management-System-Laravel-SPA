<script setup>
import GuestNavbar from "@/Components/Navbars/GuestNavbar.vue";
import FooterSmall from "@/Components/Footers/FooterSmall.vue";
import registerBg2 from "@/assets/img/register_bg_2.png";
</script>

<template>
    <div>
        <GuestNavbar/>

        <main>
            <section class="relative w-full h-full py-40 min-h-screen">
                <div
                    class="absolute top-0 w-full h-full bg-blueGray-800 bg-no-repeat bg-full"
                    :style="`background-image: url('${registerBg2}');`"
                ></div>

                <slot/>

                <FooterSmall absolute/>
            </section>
        </main>
    </div>
</template>
