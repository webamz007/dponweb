<template>
    <Head title="How To Play" />
    <Layout>
        <section>
            <div class="grid grid-cols-1 container mt-5 gap-5 mb-16">
                <div class="w-full bg-rk-blue-dark border border-blue-950 rounded-lg shadow p-5 text-gray-500">
                    <h2 class="text-gray-500 md:text-5xl text-2xl uppercase text-center"><b class="bg-text">How To Play RK Online</b></h2>
                    <img :src="'web-assets/img/heading-border-effect.png'" alt="" class="py-4 mx-auto">
                    <video class="w-full" controls>
                        <source src="path/to/video.mp4" type="video/mp4">
                        <source src="path/to/video.webm" type="video/webm">
                        Your browser does not support the video tag.
                    </video>
                    <p class="text-base text-white">Online game khelne ke liye sabse pehle aap hume paise deposit karwaye, minimum amount 500 usse kam deposit nahi liya jata, uske baad aap jitne paise hume deposit karwayenge utne point aapki user id me add kar diye jaayenge.</p>
                    <h2 class="pt-3 text-white"><b>1 Rs == 1 POINT</b></h2>
                    <p class="text-base text-white pt-3">Jo game aap khelenge agar aap ka game lagta hai to aapke points automatically badh jaayenge, and jab aapko payment chahiye ho tab aap hume call ya messege karke inform kare, jisse ki hum aapke a/c me payment tranfer kar sake.</p>
                    <p class="text-base text-white pt-3">Bet karne ka minimum amount 10 rs.usse kam se game na khele. kisi help and a/c me paise deposit karwane ke liye aap hum neeche diye gaye numbers par contact kare…</p>
                    <p class="text-base text-white pt-3">Thanks &</p>
                    <p class="text-base text-white pt-3">Regards</p>
                    <h2 class="bg-text text-center text text-4xl font-bold">Play Online RK</h2>
                    <a href="#" class="bg-text pt-5 text-center text-white block text-xl font-bold">Call Us: 8923738923</a>
                </div>
            </div>
        </section>
    </Layout>
</template>

<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import {Head} from "@inertiajs/vue3";

</script>

<style scoped>

</style>
