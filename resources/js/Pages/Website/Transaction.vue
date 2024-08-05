<template>
    <Head title="Transaction" />
    <Layout>
        <section>
            <div class="container py-12">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="w-full h-16 bg-rk-blue-dark flex items-center">
                        <h2 class="bg-text text-white pl-3 text-2xl font-bold">Transactions History</h2>
                    </div>
                    <DataTable :columns="columns" ajax="/game-transaction-data" :options="dataTableOptions" class="display w-full text-sm text-left text-white">
                        <thead class="text-base text-black uppercase bg-rk-gradient-yellow font-bold">
                        <tr>
                            <th class="!px-6 !py-3">Type</th>
                            <th class="!px-6 !py-3">Create By</th>
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

DataTable.use(DataTablesCore);

const dataTableOptions = {
    searching: false,
    ordering: false,
    language: {
        lengthMenu: ''
    }
}
const columns = [
    { data: 'transaction_type', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'transaction_creator', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'points', class: '!px-6 !py-4 text-black font-bold' },
    { data: 'transaction_date', class: '!px-6 !py-4 text-black font-bold' },
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
