<template>
    <Head title="Panel Chart" />
    <Layout>
        <section>
            <div class=" bg-rk-blue-dark border border-blue-950 shadow p-5 rounded-lg text-gray-500 container mt-5">
                <h2 class="bg-text text-2xl md:text-4xl text-center font-bold">{{ market_name }} Panel Chart</h2>
                <p class="text-white text-center text-base py-5">
                    Get the most accurate {{ market_name }} DAY Panel Chart records with verified charts from {{ market_name }} DAY panel chart Experts in {{ market_name }} DAY Market.
                </p>
                <p class="text-white text-center text-sm">
                    This Page you can see {{ market_name }} DAY Panel Matka Chart (Previosly Declared results) also known as {{ market_name }} DAY Matka Panel Record, Always Updated {{ market_name }} DAY matka panel Chart. Tags {{ market_name }} DAY matka panel chart excel, {{ market_name }} DAY matka chart result, satta matka free {{ market_name }} DAY , matka chart, {{ market_name }} DAY matka panel chart today sata, aaj ka {{ market_name }} DAY matka panel chart,satta matka {{ market_name }} DAY panel chart, matka panel chart pdf, {{ market_name }} DAY matka panel chart, {{ market_name }} DAY matka panel chart.
                </p>
            </div>
        </section>
        <!--Market Chart-->
        <section>
            <div class="container py-12">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="w-full h-16 bg-rk-blue-dark flex items-center">
                        <h2 class="bg-text text-white pl-3 text-2xl font-bold">{{ market_name }}</h2>
                    </div>
                    <table class="w-full text-sm text-left text-white">
                        <thead class="text-base text-black uppercase bg-rk-gradient-yellow font-bold">
                            <tr>
                            <th scope="col" class="px-6 py-3">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Mo
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Tu
                            </th>
                            <th scope="col" class="px-6 py-3">
                                We
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Th
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Fr
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Sa
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Su
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(chart, index) in chart_data" :key="index" class="bg-rk-gradient-yellow border-b border-yellow-400 text-white">
                                <td class="px-6 py-4 text-black font-bold" v-html="formatDateRange(index)"></td>
                                <td v-for="day in chart" class="px-6 py-4 text-black font-bold">
                                <div class="flex gap-5">
                                    <div class="flex flex-col">
                                        <span v-if="day && day[0]">{{ day[0].digit1 }}</span>
                                        <span v-if="day && day[0]">{{ day[0].digit2 }}</span>
                                        <span v-if="day && day[0]">{{ day[0].digit3 }}</span>
                                    </div>
                                    <div class="flex items-center text-base">
                                        <span v-if="day && day[0]">{{ day[0].result }}</span>
                                        <span v-if="day && day[1]">{{ day[1].result }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span v-if="day && day[1]">{{ day[1].digit1 }}</span>
                                        <span v-if="day && day[1]">{{ day[1].digit2 }}</span>
                                        <span v-if="day && day[1]">{{ day[1].digit3 }}</span>
                                    </div>
                                </div>
                                <div v-if="day && !day[0] && !day[1]" class="flex gap-5">
                                    <div class="flex flex-col">
                                        <span>*</span>
                                        <span>*</span>
                                        <span>*</span>
                                    </div>
                                    <div class="flex items-center text-base">
                                        <span>*</span>
                                        <span>*</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span>*</span>
                                        <span>*</span>
                                        <span>*</span>
                                    </div>
                                </div>
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
        <!--Market Chart End-->
    </Layout>
</template>

<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import {Head} from "@inertiajs/vue3";
import {defineProps} from "vue";

const props = defineProps({
    chart_data: Object,
    market_name: String,
});

const formatDateRange = (date) => {
    let word = date.split(' ');
    return `<div class="flex flex-col justify-center items-center">
            <span>${word[0]}</span>
            <span>To</span>
            <span>${word[2]}</span>
            </div>`
}

</script>

<style scoped>

</style>
