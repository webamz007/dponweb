<template>
    <Head title="History" />
    <Layout>
        <section>
            <div class="container py-12">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="w-full h-16 bg-rk-blue-dark flex items-center">
                        <h2 class="bg-text text-white pl-3 text-2xl font-bold">Game History</h2>
                    </div>
                    <DataTable :columns="columns" :ajax="ajaxUrl" :options="dataTableOptions" class="display w-full text-sm text-left text-white">
                        <thead class="text-base text-black uppercase bg-rk-gradient-yellow font-bold">
                        <tr>
                            <th class="!px-6 !py-3">Game</th>
                            <th class="!px-6 !py-3">Bazar</th>
                            <th class="!px-6 !py-3">Digit</th>
                            <th class="!px-6 !py-3">Amount</th>
                            <th class="!px-6 !py-3">Date</th>
                        </tr>
                        </thead>
                    </DataTable>
                </div>

            </div>
        </section>
    </Layout>
</template>

<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import {Head} from "@inertiajs/vue3";
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
import {defineProps} from "vue";
DataTable.use(DataTablesCore);

const props = defineProps({
    market_type: String,
})

const ajaxUrl = `/game-history-data?type=${props.market_type}`;

const dataTableOptions = {
    searching: false,
    ordering: false,
    language: {
        lengthMenu: ''
    }
}
const columns = [
    { data: 'market_name', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'session', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'number', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'amount', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'bid_date', class: '!px-6 !py-4 text-black font-bold' },
];

</script>

<style>
@import 'datatables.net-dt';
.dataTables_info {
    color: #fff !important;
}
.dataTables_paginate {
    color: #fff !important;
}
</style>
