<template>
    <Head title="Withdraw Funds" />
    <Layout>
        <div class="container mt-5 pb-48">
            <div class="w-full bg-[#E6BF1C] border border-blue-950 shadow p-5 rounded-lg text-gray-700 container mt-5">
                <h2 class="text-center font-bold">Fund Withdraw Request</h2>
                <p class="text-center text-gray-700 text-sm font-bold">{{ withdrawal_settings.start_time }} to {{ withdrawal_settings.end_time }} withdraw timing payment will be done in Morning.</p>
                <p v-if="withdrawal_settings.days !== ''" class="text-center text-gray-700 text-sm font-bold">Withdraw will be closed by {{ withdrawal_settings.days }}.</p>
                <p class="text-center text-gray-700 text-sm font-bold">You can withdraw your funds upto ₹{{ $page.props.admin_details.withdraw_limit.toLocaleString() }}.</p>
                <hr class="my-5 border-rk-blue-dark">
                <h2 class="text-center font-bold">For Withdraw related queries Call or WhatsApp</h2>
                <a :href="'tel:+'+$page.props.admin_details.phone">
                    <strong class="text-center block pt-3"> <i class="fa-brands fa-whatsapp"></i> {{ $page.props.admin_details.phone }}</strong>
                </a>
                <hr class="my-5 border-rk-blue-dark">
                <strong class="text-center block pt-3">
                    <Link :href="route('add.funds')">
                        <i class="fa-solid fa-wallet"></i> <span class="wallet-points">{{ $page.props.auth.user.points }}</span>
                    </Link>
                </strong>
                <hr class="my-5 border-rk-blue-dark">
                <div class="mb-6">
                    <input v-model="amount" type="text" id="amount" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-white" placeholder="Enter Amount">
                </div>
                <button @click="withRequest" :class="{ 'opacity-50': isProcessing }" :disabled="isProcessing" type="button" class="text-white bg-rk-red hover:bg-rk-blue-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <svg v-if="isProcessing" aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                    Send Withdraw Request
                </button>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import {Head, usePage, Link} from "@inertiajs/vue3";
import axios from "axios";
import {defineProps, ref} from "vue";

const props = defineProps({
    withdrawal_settings: Object,
})

const page = usePage();
let points = page.props.auth.user.points;
let amount = ref('');
let isProcessing = ref(false);
let withdraw_limit = page.props.admin_details.withdraw_limit;

const withRequest = async () => {
    if (amount.value.length === 0 || amount.value < 1) {
        iziToast.error({
            title: 'Error',
            message: 'Please insert the amount.',
            position: 'topRight'
        });

        return;
    }

    if ( amount.value < 500) {
        iziToast.error({
            title: 'Error',
            message: 'You Can Withdraw Minimum Amount 500 ',
            position: 'topRight'
        });
        return
    }

    if ( amount.value > withdraw_limit) {
        iziToast.error({
            title: 'Error',
            message: 'You Can Withdraw Amount Upto '+withdraw_limit.toLocaleString(),
            position: 'topRight'
        });
        return
    }

    try {
        isProcessing.value = true;
        const response = await axios.post('withdraw-request', { amount: amount.value });
        if (response.data.success) {
            points = await getUserPoints(points);
            Array.from(document.querySelectorAll('.wallet-points')).forEach(element => element.textContent = points);
            amount.value = '';
            iziToast.success({
                title: 'Success',
                message: response.data.msg,
                position: 'topRight'
            });
        } else {
            iziToast.error({
                title: 'Error',
                message: response.data.msg,
                position: 'topRight'
            });
        }
        isProcessing.value = false;
    } catch (error) {
        isProcessing.value = false;
        iziToast.error({
            title: 'Error',
            message: error.message,
            position: 'topRight'
        });
    }
}


const getUserPoints = async (calPoints) => {
    try {
        const response = await axios.post(route('user-points'));
        return response.data.points;
    } catch (error) {
        return calPoints;
    }
}

</script>

<style scoped>

</style>
