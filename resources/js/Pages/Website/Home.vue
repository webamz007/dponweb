<template>
    <Layout>
        <Head>
            <meta name="title" content="Play Satta Matka - Kalyan Matka - Matka Results | Satta dponweb">
            <meta name="description" content="Play Satta Matka - Play Kalyan Matka - Matka Results - Best Satta matka tips online dponweb. Check Matka Results - Kalyan Matka. Check Sattamatka chart and get Kalyanmattka tips. SATTA KALYAN MADHUR MATKA">
            <meta name="keywords" content="Satta Matka, Kalyan Matka,Matka Results,Best Satta matka tips, Sattamatka chart, dponweb,Matka Results, Kalyan Matka chart, Kalyanmattka tips, MADHUR MATKA">
            <meta name="robots" content="index, follow">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="language" content="English">
            <title>Play Satta Matka - Kalyan Matka - Matka Results | Satta dponweb.com</title>
        </Head>
        <!--Slider Revolution -->
        <div id="controls-carousel" class="relative w-full" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-52 md:h-96 lg:h-[33rem] overflow-hidden rounded-lg">
                <div v-for="slide in slides" :key="slide.id" class="duration-700 ease-in-out" data-carousel-item>
                    <img :src="'storage/'+slide.image_path" class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                </div>
                <div data-carousel-item></div>
            </div>
            <!-- Slider controls -->
            <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
            <svg aria-hidden="true" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            <span class="sr-only">Previous</span>
        </span>
            </button>
            <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
            <svg aria-hidden="true" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="sr-only">Next</span>
        </span>
            </button>
        </div>
        <!--Slider Revolution End -->
        <div class="flex justify-center flex-col md:flex-row mt-10">
            <Link :href="route('game.starline')" class="mx-4 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg mb-3 md:mb-0 md:mr-5">Starline Market</Link>
            <Link :href="route('game.delhi')" class="mx-4 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg">Delhi Market</Link>
        </div>
        <!-- Markets Cards -->
        <section class="pt-10 container">
            <div class="w-full h-14 bg-rk-blue-dark text-white text-xl text-center flex justify-center items-center">
                <h2 class="bg-text font-bold">RK Results</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 mt-5 gap-5 pb-16">
                <div v-for="market in markets" :key="market.id" class="w-full max-w-sm bg-rk-gradient-yellow border border-blue-950 rounded-lg shadow text-black font-bold">
                    <div class="flex flex-col items-center justify-center px-5 pt-3">
                        <div class="">{{ market.name }}</div>
                        <div class="text-2xl">{{ formattedResult(market.result) }}</div>
                    </div>
                    <div class="flex flex-col items-center pb-10 text-black">
                        <h5 class="mb-1 text-xl font-medium">{{ market.oet }} - {{ market.cet }}</h5>
                        <div v-if="market.status === 'true' ">
                            <span v-if="isBettingOpen(market.oet, market.cet, market.result)" class="text-sm text-green-600">Betting is Open</span>
                            <span v-else class="text-sm text-red-600">Betting is Close</span>
                        </div>
                        <span v-else class="text-sm text-red-600">Holiday</span>
                        <div class="flex mt-4 space-x-3 md:mt-6"
                             :class="
                             {
                                 'pointer-events-none': market.status !== 'true' || !isBettingOpen(market.oet, market.cet, market.result),
                               'animate-shake': market.status === 'true' && isBettingOpen(market.oet, market.cet, market.result)
                             }">
                            <Link :href="route('game', { type: 'market', market: market.id })"
                                  class="btn-gradient">Play
                                Game
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Game Time Table End-->
        <div class="flex justify-center flex-col md:flex-row">
            <a :href="$page.props.admin_details.guessing" target="_blank" class="mx-4 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg mb-3 md:mb-0">Guessing Line</a>
            <a :href="$page.props.admin_details.results_and_videos" target="_blank" class="mx-4 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg mb-3 md:mb-0">Results & Videos</a>
            <a :href="$page.props.admin_details.whatsapp_channel" target="_blank" class="mx-4 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg mb-3 md:mb-0">WhatsApp Channel</a>
            <Link :href="route('game.info')" class="mx-4 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg mb-3 md:mb-0">How To Play</Link>
        </div>
        <section>
            <div class="container py-12">
                <h2 class="bg-text font-bold text-center text-xl">Jodi Charts</h2>
                <img :src="'web-assets/img/heading-border-effect.png'" alt="" class="py-4 mx-auto">
                <!--Chart Bar-->
                <a v-for="market in markets" :key="market.id" href="#" class="border-t border-b text-black font-bold text-sm block py-2.5 px-4 my-5 shadow rounded border-yellow-400 bg-rk-gradient-yellow">{{ market.name }}</a>
                <!--Chart Bar End-->
                <h2 class="bg-text font-bold text-center text-xl">Panel Charts</h2>
                <img :src="'web-assets/img/heading-border-effect.png'" alt="" class="py-4 mx-auto">
                <!--Chart Bar-->
                <Link :href="route('chart.panel', {id: market.id})" v-for="market in markets" :key="market.id" class="border-t border-b text-black font-bold text-sm block py-2.5 px-4 my-5 shadow rounded border-yellow-400 bg-rk-gradient-yellow">{{ market.name }}</Link>
                <!--Chart Bar End-->
            </div>
        </section>
        <!--Game Time Table-->
        <section>
            <div class="container pb-12">

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="w-full h-16 bg-rk-blue-dark flex items-center">
                        <h2 class="bg-text text-white pl-3 text-2xl font-bold">RK Game Time Table</h2>
                    </div>
                    <table class="w-full text-sm text-left text-white">
                        <thead class="text-base text-black uppercase bg-rk-gradient-yellow font-bold">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Markets
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Open
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Close
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="market in markets" :key="market.id" class="bg-rk-gradient-yellow border-b border-yellow-400 text-white">
                            <td class="px-6 py-4 text-black font-bold">
                                {{ market.name }}
                            </td>
                            <td class="px-6 py-4 text-black font-bold">
                                {{ market.oet }}
                            </td>
                            <td class="px-6 py-4 text-black font-bold">
                                {{ market.cet }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </section>

        <!--Featured Games-->
        <section class="bg-[#E6BF1C] py-16">
            <div class="container">
                <h2 class="text-center text-4xl font-bold">Featured Games</h2>
                <div class="grid grid-cols-2 md:grid-cols-7 container my-5 gap-5">
                    <div class="w-full rounded-lg shadow text-gray-500 cursor-pointer">
                        <Link :href="route('game', { type: 'single_digit' })">
                            <img :src="'web-assets/img/single-ank.png'" class="w-full" alt="Single Ank">
                        </Link>
                    </div>
                    <div class="w-full rounded-lg shadow text-gray-500 cursor-pointer">
                        <Link :href="route('game', { type: 'double_digit' })">
                            <img :src="'web-assets/img/jodi.png'" class="w-full" alt="Jodi">
                        </Link>
                    </div>
                    <div class="w-full rounded-lg shadow text-gray-500 cursor-pointer">
                        <Link :href="route('game', { type: 'single_pana' })">
                            <img :src="'web-assets/img/single-pati.png'" class="w-full" alt="Single Patti">
                        </Link>
                    </div>
                    <div class="w-full rounded-lg shadow text-gray-500 cursor-pointer">
                        <Link :href="route('game', { type: 'double_pana' })">
                            <img :src="'web-assets/img/double-pati.png'" class="w-full" alt="Double Patti">
                        </Link>
                    </div>
                    <div class="w-full rounded-lg shadow text-gray-500 cursor-pointer">
                        <Link :href="route('game', { type: 'tripple_pana' })">
                            <img :src="'web-assets/img/triple-pati.png'" class="w-full" alt="Tripple Patti">
                        </Link>
                    </div>
                    <div class="w-full rounded-lg shadow text-gray-500 cursor-pointer">
                        <Link :href="route('game', { type: 'half_sangum' })">
                            <img :src="'web-assets/img/half-sangam.png'" class="w-full" alt="Half Sangum">
                        </Link>
                    </div>
                    <div class="w-full rounded-lg shadow text-gray-500 cursor-pointer">
                        <Link :href="route('game', { type: 'full_sangum' })">
                            <img :src="'web-assets/img/full-sangam.png'" class="w-full" alt="Full Sangum">
                        </Link>
                    </div>
                </div>
            </div>
        </section>
        <!--Featured Games End-->

        <section class="">
            <div class="container py-10">
                <h2 class="bg-text text-center text text-4xl font-bold">Play Online RK</h2>
                <a :href="'tel:+'+$page.props.admin_details.phone" class="bg-text pt-5 text-center text-white block text-xl font-bold">Call Us: {{ $page.props.admin_details.phone }}</a>
                <img :src="'web-assets/img/heading-border-effect.png'" alt="" class="py-4 mx-auto">
                <p class="text-white text-base">
                    Playon.net is the best preferred platforms to play online satta rk. We provide you the best interface to guess and win huge amounts through us. We Gurantee you the best online rk play with kalyan tricks and tips to play rk online. Have the best experience through our rk website and play satta with your skill to get huge rewards. Call us on the mentioned number to know more.
                </p>
            </div>
        </section>

        <!--Notice Board-->
        <section>
            <div class="grid grid-cols-1 md:grid-cols-2 container mt-5 gap-5 mb-16">
                <div class="w-full bg-rk-blue-dark border border-blue-950 rounded-lg shadow p-5 text-gray-500">
                    <h2 class="text-gray-500 md:text-5xl text-2xl uppercase"><b class="bg-text">Notice Board</b></h2>
                    <img :src="'web-assets/img/heading-border-effect.png'" alt="" class="py-4 mx-auto">
                    <p v-if="noticeBoard && noticeBoard.title" class="text-base text-white">{{ noticeBoard.title }}</p>
                    <div v-if="noticeBoard && noticeBoard.content" v-html="noticeBoard.content" class="text-base pt-3 text-white"></div>

                </div>
                <div class="w-full bg-rk-blue-dark border border-blue-950 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-5 text-gray-500">
                    <h2 class="text-gray-500 text-2xl md:text-5xl uppercase pb-3"><b class="bg-text">Game Rates</b></h2>
                    <ul>
                        <li class="flex justify-between border-t border-blue-950 py-3 text-white">
                            <span class="text-xl">Single</span>
                            <span class="text-xl">1 KA {{ setting.single_digit }}</span>
                        </li>
                        <li class="flex justify-between border-t border-blue-950 py-3 text-white">
                            <span class="text-xl">Jodi</span>
                            <span class="text-xl">1 KA {{ setting.double_digit }}</span>
                        </li>
                        <li class="flex justify-between border-t border-blue-950 py-3 text-white">
                            <span class="text-xl">Single Patti</span>
                            <span class="text-xl">1 KA {{ setting.single_pana }}</span>
                        </li>
                        <li class="flex justify-between border-t border-blue-950 py-3 text-white">
                            <span class="text-xl">Double Patti</span>
                            <span class=" text-xl">1 KA {{ setting.double_pana }}</span>
                        </li>
                        <li class="flex justify-between border-t border-blue-950 py-3 text-white">
                            <span class="text-xl">Tripple Patti</span>
                            <span class="text-xl">1 KA {{ setting.tripple_pana }}</span>
                        </li>
                        <li class="flex justify-between border-t border-blue-950 py-3 text-white">
                            <span class="text-xl">Half Sangum</span>
                            <span class="text-xl">1 KA {{ setting.half_sangum }}</span>
                        </li>
                        <li class="flex justify-between border-t border-blue-950 py-3 text-white">
                            <span class="text-xl">Full Sangum</span>
                            <span class="text-xl">1 KA {{ setting.full_sangum }}</span>
                        </li>
                    </ul>

                </div>
            </div>
        </section>
        <!--Notice Board End-->

        <!--Get Mobile App-->
        <section class="bg-rk-blue-dark">
            <div class="container py-12">
                <h2 class="bg-text text-2xl md:text-4xl text-center font-bold">Online Mobile App</h2>
                <img class="mx-auto h-20 w-48 object-cover cursor-pointer" :src="'web-assets/img/google-play.png'" alt="Mobile App">
                <p class="text-white text-center text-base">Download our attractive online satta app and play your rk game to win huge amounts! Get it on Google Now! Bonuses are additional!</p>
            </div>
        </section>
        <!--Get Mobile App End-->

        <!--Contact Us Section-->
        <section class="bg-rk-blue-dark">
            <div class="container py-12">
                <h2 class="bg-text text-2xl md:text-4xl text-center font-bold">RK Play Online</h2>
                <p class="bg-text text-white text-center text-xl font-bold pt-5">Call us to play online rk</p>
                <p class="bg-text text-white text-center text-xl font-bold pt-5">+<a :href="'tel:+'+$page.props.admin_details.phone">{{ $page.props.admin_details.phone }}</a></p>
                <img :src="'web-assets/img/heading-border-effect.png'" alt="" class="py-4 mx-auto">
                <p class="text-white text-center text-base pt-5">Get Lucky and and Play the satta rk game with the most minimum amount. We give you expert kalyan rk suggestions so that you can full fill your dreams with your own luck. Play RK Online Today!</p>
            </div>
        </section>
        <!--Contact Us Section End-->
    </Layout>
</template>

<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import {Head, Link} from "@inertiajs/vue3";
import {defineProps} from "vue";
import moment from "moment";
import 'moment-timezone';

defineProps({
    markets: Object,
    setting: Object,
    slides: Object,
    noticeBoard: Object,
});

const isBettingOpen = (openTime, closeTime, result) => {
    moment.tz.setDefault('Asia/Kolkata');
    const currentTime = moment();
    const openTimeMoment = moment(openTime, 'h:mm A');
    const closeTimeMoment = moment(closeTime, 'h:mm A');

    if (currentTime.isBefore(openTimeMoment)) {
        return true;
    } else if (currentTime.isBetween(openTimeMoment, closeTimeMoment) && result.length == "") {
        return false;
    } else if (currentTime.isBetween(openTimeMoment, closeTimeMoment) && result.length == "5") {
        return true;
    } else if (currentTime.isBetween(openTimeMoment, closeTimeMoment) && result.length == "10") {
        return false;
    } else if (currentTime.isAfter(closeTimeMoment)) {
        return false;
    }

    return false;
};

const formattedResult = (result) => {
    if (result.length === 0) {
        return '***-**-***';
    } else if (result.length === 5) {
        const prefix = result.slice(0, 3); // Get the first three characters
        const suffix = result.slice(4); // Get the last character
        console.log(suffix);
        const asterisks = "*".repeat(3); // Create a string of three asterisks
        return `${prefix}-${suffix}*-***`;
    } else {
        return result;
    }
}

</script>

<style scoped>

</style>
