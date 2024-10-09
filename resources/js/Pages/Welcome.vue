<template>
    <Head :title="pageTitle"/>

    <div>
        <GuestNavbar/>

        <main>
            <div
                class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-screen-75"
            >
                <div
                    class="absolute top-0 w-full h-full bg-center bg-cover"
                    :style="{ backgroundImage: `url(${banner})` }"
                >
                    <span id="blackOverlay" class="w-full h-full absolute opacity-75 bg-black"></span>
                </div>
                <div class="container relative mx-auto">
                    <div class="items-center flex flex-wrap">
                        <div class="w-full lg:w-6/12 px-4 ml-auto mr-auto text-center">
                            <div class="pr-12">
                                <h1 class="text-white font-semibold text-5xl">
                                    Your story starts with us.
                                </h1>
                                <p class="mt-4 text-lg text-blueGray-200">
                                    Transform your business operations with custom-built web applications that
                                    streamline workflows, enhance productivity, and scale as your company grows. Get
                                    personalized solutions that meet your unique challenges and goals.
                                </p>
                                <Link
                                    :href="$page.props.auth.user ? route('dashboard') : route('login')"
                                    class="mt-4 bg-white text-blueGray-700 active:bg-blueGray-50 text-xs font-bold uppercase px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none ease-linear transition-all duration-150"
                                    type="button"
                                >
                                    Explore Inventory
                                    <i class="fas fa-arrow-alt-circle-right"></i>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px"
                    style="transform: translateZ(0);"
                >
                    <svg
                        class="absolute bottom-0 overflow-hidden"
                        xmlns="http://www.w3.org/2000/svg"
                        preserveAspectRatio="none"
                        version="1.1"
                        viewBox="0 0 2560 100"
                        x="0"
                        y="0"
                    >
                        <polygon
                            class="text-blueGray-200 fill-current"
                            points="2560 0 2560 100 0 100"
                        ></polygon>
                    </svg>
                </div>
            </div>

            <section class="pb-20 bg-blueGray-200 -mt-24">
                <div class="container mx-auto px-4">
                    <div class="flex flex-wrap">
                        <div class="lg:pt-12 pt-6 w-full md:w-4/12 px-4 text-center">
                            <div
                                class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg"
                            >
                                <div class="px-4 py-5 flex-auto">
                                    <div
                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-red-400"
                                    >
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <h6 class="text-xl font-semibold">Custom Web Apps</h6>
                                    <p class="mt-2 mb-4 text-blueGray-500">
                                        Build robust, scalable applications designed specifically for your business
                                        goals.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full md:w-4/12 px-4 text-center">
                            <div
                                class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg"
                            >
                                <div class="px-4 py-5 flex-auto">
                                    <div
                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-lightBlue-400"
                                    >
                                        <i class="fas fa-retweet"></i>
                                    </div>
                                    <h6 class="text-xl font-semibold">Seamless Integration</h6>
                                    <p class="mt-2 mb-4 text-blueGray-500">
                                        Connect your tools and systems effortlessly with smooth integration services.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 w-full md:w-4/12 px-4 text-center">
                            <div
                                class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg"
                            >
                                <div class="px-4 py-5 flex-auto">
                                    <div
                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-emerald-400"
                                    >
                                        <i class="fas fa-fingerprint"></i>
                                    </div>
                                    <h6 class="text-xl font-semibold">Scalable Solutions</h6>
                                    <p class="mt-2 mb-4 text-blueGray-500">Future-proof your business with solutions
                                        that grow alongside your operations.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center mt-32">
                        <div class="w-full md:w-5/12 px-4 mr-auto ml-auto">
                            <div
                                class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-white"
                            >
                                <i class="fas fa-user-friends text-xl"></i>
                            </div>
                            <h3 class="text-3xl mb-2 font-semibold leading-normal">
                                Working with us is a pleasure
                            </h3>
                            <p
                                class="text-lg font-light leading-relaxed mt-4 mb-4 text-blueGray-600"
                            >
                                We prioritize clear communication, transparency, and collaboration at every step of the
                                project. From concept to launch, I work closely with you to ensure your vision is
                                realized, providing regular updates and making adjustments based on your feedback.
                            </p>
                            <p
                                class="text-lg font-light leading-relaxed mt-0 mb-4 text-blueGray-600"
                            >
                                The goal is to deliver high-quality, functional applications while ensuring a smooth and
                                enjoyable experience for you.
                            </p>
                            <!--                            <a href="/" class="font-bold text-blueGray-700 mt-8">-->
                            <!--                                Check Vue Notus!-->
                            <!--                            </a>-->
                        </div>

                        <div class="w-full md:w-4/12 px-4 mr-auto ml-auto">
                            <div
                                class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg bg-emerald-500"
                            >
                                <img
                                    alt="..."
                                    :src="inventoryService"
                                    class="w-full align-middle rounded-t-lg"
                                />
                                <blockquote class="relative p-8 mb-4">
                                    <svg
                                        preserveAspectRatio="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 583 95"
                                        class="absolute left-0 w-full block h-95-px -top-94-px"
                                    >
                                        <polygon
                                            points="-30,95 583,95 583,65"
                                            class="text-emerald-500 fill-current"
                                        ></polygon>
                                    </svg>
                                    <h4 class="text-xl font-bold text-white">
                                        Top Notch Services
                                    </h4>
                                    <p class="text-md font-light mt-2 text-white">
                                        I offer reliable, efficient, and highly scalable solutions to help your business
                                        thrive and grow.
                                    </p>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="relative py-20">
                <div
                    class="bottom-auto top-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden -mt-20 h-20"
                    style="transform: translateZ(0);"
                >
                    <svg
                        class="absolute bottom-0 overflow-hidden"
                        xmlns="http://www.w3.org/2000/svg"
                        preserveAspectRatio="none"
                        version="1.1"
                        viewBox="0 0 2560 100"
                        x="0"
                        y="0"
                    >
                        <polygon
                            class="text-white fill-current"
                            points="2560 0 2560 100 0 100"
                        ></polygon>
                    </svg>
                </div>

                <div class="container mx-auto px-4">
                    <div class="items-center flex flex-wrap">
                        <div class="w-full md:w-4/12 ml-auto mr-auto px-4">
                            <img
                                alt="..."
                                class="max-w-full rounded-lg shadow-lg"
                                :src="fourthSection"
                            />
                        </div>
                        <div class="w-full md:w-5/12 ml-auto mr-auto px-4">
                            <div class="md:pr-12">
                                <div
                                    class="text-emerald-600 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-emerald-300"
                                >
                                    <i class="fas fa-rocket text-xl"></i>
                                </div>
                                <h3 class="text-3xl font-semibold">Tailored Solutions</h3>
                                <p class="mt-4 text-lg leading-relaxed text-blueGray-500">
                                    From front-end design to back-end logic, I handle all aspects of web development,
                                    ensuring high performance and usability. Let’s bring your ideas to life with clean
                                    code and a focus on scalability.
                                </p>
                                <ul class="list-none mt-6">
                                    <li class="py-2">
                                        <div class="flex items-center">
                                            <div>
                                                <span
                                                    class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-emerald-600 bg-emerald-200 mr-3">
                                                    <i class="fas fa-fingerprint"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-blueGray-500">
                                                    Responsive Design
                                                </h4>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-2">
                                        <div class="flex items-center">
                                            <div>
                                                <span
                                                    class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-emerald-600 bg-emerald-200 mr-3">
                                                    <i class="fab fa-html5"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-blueGray-500">
                                                    Secure and Reliable
                                                </h4>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-2">
                                        <div class="flex items-center">
                                            <div>
                                                <span
                                                    class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-emerald-600 bg-emerald-200 mr-3">
                                                    <i class="far fa-paper-plane"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-blueGray-500">Easy Maintenance</h4>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="pt-20 pb-48">
                <div class="container mx-auto px-4">
                    <div class="flex flex-wrap justify-center text-center mb-24">
                        <div class="w-full lg:w-6/12 px-4">
                            <h2 class="text-4xl font-semibold">What Clients Say</h2>
                            <p class="text-lg leading-relaxed m-4 text-blueGray-500">
                                Hear from clients who have trusted me to build their business solutions.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap">
                        <div class="w-full md:w-6/12 lg:w-3/12 lg:mb-0 mb-12 px-4">
                            <div class="px-6">
                                <img
                                    alt="..."
                                    :src="team1"
                                    class="shadow-lg rounded-full mx-auto max-w-120-px"
                                />
                                <div class="pt-6 text-center">
                                    <h5 class="text-xl font-bold">Ryan Tompson</h5>
                                    <p
                                        class="mt-1 text-sm text-blueGray-400 font-semibold"
                                    >
                                        The application exceeded my expectations. Delivered on time and tailored
                                        perfectly to our needs.
                                    </p>
                                    <!--                                    <div class="mt-6">-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-lightBlue-400 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-twitter"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-lightBlue-600 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-facebook-f"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-pink-500 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-dribbble"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-6/12 lg:w-3/12 lg:mb-0 mb-12 px-4">
                            <div class="px-6">
                                <img
                                    alt="..."
                                    :src="team2"
                                    class="shadow-lg rounded-full mx-auto max-w-120-px"
                                />
                                <div class="pt-6 text-center">
                                    <h5 class="text-xl font-bold">Romina Hadid</h5>
                                    <p
                                        class="mt-1 text-sm text-blueGray-400 font-semibold"
                                    >
                                        Incredible attention to detail and great communication throughout the entire
                                        process. Highly recommended!
                                    </p>
                                    <!--                                    <div class="mt-6">-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-red-600 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-google"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-lightBlue-600 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-facebook-f"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-6/12 lg:w-3/12 lg:mb-0 mb-12 px-4">
                            <div class="px-6">
                                <img
                                    alt="..."
                                    :src="team3"
                                    class="shadow-lg rounded-full mx-auto max-w-120-px"
                                />
                                <div class="pt-6 text-center">
                                    <h5 class="text-xl font-bold">Alexa Smith</h5>
                                    <p
                                        class="mt-1 text-sm text-blueGray-400 font-semibold"
                                    >
                                        Professional, efficient, and reliable. My go-to for all future web development
                                        projects.
                                    </p>
                                    <!--                                    <div class="mt-6">-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-red-600 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-google"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-lightBlue-400 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-twitter"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-blueGray-700 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-instagram"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-6/12 lg:w-3/12 lg:mb-0 mb-12 px-4">
                            <div class="px-6">
                                <img
                                    alt="..."
                                    :src="team4"
                                    class="shadow-lg rounded-full mx-auto max-w-120-px"
                                />
                                <div class="pt-6 text-center">
                                    <h5 class="text-xl font-bold">Jenna Kardi</h5>
                                    <p
                                        class="mt-1 text-sm text-blueGray-400 font-semibold"
                                    >
                                        Amazing service! The final product was exactly what I needed to streamline my
                                        business operations.
                                    </p>
                                    <!--                                    <div class="mt-6">-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-pink-500 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-dribbble"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-red-600 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-google"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-lightBlue-400 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-twitter"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                        <button-->
                                    <!--                                            class="bg-blueGray-700 text-white w-8 h-8 rounded-full outline-none focus:outline-none mr-1 mb-1"-->
                                    <!--                                            type="button"-->
                                    <!--                                        >-->
                                    <!--                                            <i class="fab fa-instagram"></i>-->
                                    <!--                                        </button>-->
                                    <!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="pb-20 relative block bg-blueGray-800">
                <div
                    class="bottom-auto top-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden -mt-20 h-20"
                    style="transform: translateZ(0);"
                >
                    <svg
                        class="absolute bottom-0 overflow-hidden"
                        xmlns="http://www.w3.org/2000/svg"
                        preserveAspectRatio="none"
                        version="1.1"
                        viewBox="0 0 2560 100"
                        x="0"
                        y="0"
                    >
                        <polygon
                            class="text-blueGray-800 fill-current"
                            points="2560 0 2560 100 0 100"
                        ></polygon>
                    </svg>
                </div>

                <div class="container mx-auto px-4 lg:pt-24 lg:pb-64">
                    <div class="flex flex-wrap text-center justify-center">
                        <div class="w-full lg:w-6/12 px-4">
                            <h2 class="text-4xl font-semibold text-white">
                                Services Offered
                            </h2>
                            <p class="text-lg leading-relaxed mt-4 mb-4 text-blueGray-400">
                                Offering a variety of development services to meet your business needs, from custom
                                solutions to seamless integrations.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap mt-12 justify-center">
                        <div class="w-full lg:w-3/12 px-4 text-center">
                            <div
                                class="text-blueGray-800 p-3 w-12 h-12 shadow-lg rounded-full bg-white inline-flex items-center justify-center"
                            >
                                <i class="fas fa-medal text-xl"></i>
                            </div>
                            <h6 class="text-xl mt-5 font-semibold text-white">
                                Frontend Development
                            </h6>
                            <p class="mt-2 mb-4 text-blueGray-400">
                                Create responsive, user-friendly interfaces that provide seamless user experiences.
                            </p>
                        </div>
                        <div class="w-full lg:w-3/12 px-4 text-center">
                            <div
                                class="text-blueGray-800 p-3 w-12 h-12 shadow-lg rounded-full bg-white inline-flex items-center justify-center"
                            >
                                <i class="fas fa-poll text-xl"></i>
                            </div>
                            <h5 class="text-xl mt-5 font-semibold text-white">
                                Backend Development
                            </h5>
                            <p class="mt-2 mb-4 text-blueGray-400">
                                Build secure, scalable, and efficient server-side applications for your business.
                            </p>
                        </div>
                        <div class="w-full lg:w-3/12 px-4 text-center">
                            <div
                                class="text-blueGray-800 p-3 w-12 h-12 shadow-lg rounded-full bg-white inline-flex items-center justify-center"
                            >
                                <i class="fas fa-lightbulb text-xl"></i>
                            </div>
                            <h5 class="text-xl mt-5 font-semibold text-white">
                                Deployment Services
                            </h5>
                            <p class="mt-2 mb-4 text-blueGray-400">
                                Ensure smooth deployment, from server setup to launching your application live.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="relative block py-24 lg:pt-0 bg-blueGray-800">
                <div class="container mx-auto px-4">
                    <div class="flex flex-wrap justify-center lg:-mt-64 -mt-48">
                        <div class="w-full lg:w-6/12 px-4">
                            <div
                                class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-200"
                            >
                                <ContactForm/>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer-component/>
    </div>
</template>
<script setup>
import GuestNavbar from "@/Components/Navbars/GuestNavbar.vue";
import FooterComponent from "@/Components/Footers/Footer.vue";
import team1 from "@/assets/img/team-1-800x800.jpg";
import team2 from "@/assets/img/team2.avif";
import team3 from "@/assets/img/team3.jpg";
import team4 from "@/assets/img/team4.jpg";
import {Head, Link} from "@inertiajs/vue3";
import ContactForm from "@/Components/ContactForm.vue";
import inventoryService from "@/assets/img/inventory-service.avif";
import banner from "@/assets/img/banner.avif";
import fourthSection from "@/assets/img/4th-section.avif";

defineProps({
    pageTitle: {
        type: String,
        required: true,
    }
});
</script>
