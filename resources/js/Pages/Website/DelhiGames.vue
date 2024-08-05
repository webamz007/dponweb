<template>
    <Head title="Starline Games" />
    <Layout>
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
        <!-- Market Cards -->
        <section class="container mt-10">
            <div class="w-full h-14 bg-rk-blue-dark text-white text-xl text-center flex justify-center items-center">
                <h2 class="bg-text font-bold">Delhi Games</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 mt-5 gap-5 pb-16">
                <div v-for="market in markets" :key="market.id" class="w-full max-w-sm bg-rk-gradient-yellow border border-blue-950 rounded-lg shadow text-black font-bold">
                    <div class="flex flex-col items-center justify-center px-5 pt-3">
                        <div class="">{{ market.name }}</div>
                        <div class="text-5xl">{{ formattedResult(market.result) }}</div>
                    </div>
                    <div class="flex flex-col items-center pb-10 text-black">
                        <h5 class="mb-1 text-xl font-medium">{{ market.oet }}</h5>
                        <div v-if="market.status === 'true' ">
                            <span v-if="isBettingOpen(market.oet, market.ort, market.result)" class="text-sm text-green-600">Betting is Open</span>
                            <span v-else class="text-sm text-red-600">Betting is Close</span>
                        </div>
                        <span v-else class="text-sm text-red-600">Holiday</span>
                        <div class="flex mt-4 space-x-3 md:mt-6"
                             :class="
                             {
                               'pointer-events-none': market.status !== 'true' || !isBettingOpen(market.oet, market.ort, market.result),
                               'animate-shake': market.status === 'true' && isBettingOpen(market.oet, market.ort, market.result)
                             }">
                            <Link :href="route('game', { type: 'delhi', market: market.id })"
                                  class="btn-gradient cursor-pointer">Play
                                Game
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Market Cards End -->
        <!--Notice Board-->
        <section>
            <div class="grid grid-cols-1 md:grid-cols-2 container gap-5 mb-10">
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
                            <span class="text-xl">Ander</span>
                            <span class="text-xl">1 KA {{ setting.ander }}</span>
                        </li>
                        <li class="flex justify-between border-t border-blue-950 py-3 text-white">
                            <span class="text-xl">Baher</span>
                            <span class="text-xl">1 KA {{ setting.baher }}</span>
                        </li>
                    </ul>

                </div>
            </div>
        </section>
        <!--Notice Board End-->
    </Layout>
</template>

<script setup>
import {Head, Link} from "@inertiajs/vue3";
import Layout from "@/Pages/Shared/Layout.vue";
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

    return currentTime.isBefore(openTimeMoment);
};
const formattedResult = (result) => {
    if (result.length === 0) {
        return '**';
    } else {
        return result;
    }
}
</script>

<style scoped>

</style>
