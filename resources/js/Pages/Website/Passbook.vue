<template>
    <Head title="Passbook" />
    <Layout>
        <section>
            <div class="container py-12">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="w-full h-16 bg-rk-blue-dark flex items-center">
                        <h2 class="bg-text text-white pl-3 text-2xl font-bold">Pass Book History</h2>
                    </div>
                    <DataTable :columns="columns" ajax="/game-passbook-data" :options="dataTableOptions" class="display w-full text-sm text-left text-white">
                        <thead class="text-base text-black uppercase bg-rk-gradient-yellow font-bold">
                        <tr>
                            <th class="!px-6 !py-3">Game</th>
                            <th class="!px-6 !py-3">Transaction Type</th>
                            <th class="!px-6 !py-3">Session</th>
                            <th class="!px-6 !py-3">Points</th>
                            <th class="!px-6 !py-3">Result</th>
                            <th class="!px-6 !py-3">Balance</th>
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

DataTable.use(DataTablesCore);

const dataTableOptions = {
    searching: false,
    ordering: false,
    language: {
        lengthMenu: ''
    }
}
const columns = [
    { data: 'market_name', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'transaction_type', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'session', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'points', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'result', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'total_points', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'passbook_date', class: '!px-6 !py-4 text-black font-bold' },
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
