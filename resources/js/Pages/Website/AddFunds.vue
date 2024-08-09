<template>
    <Head title="Add Funds" />
    <Layout>
        <div class="container mt-5 pb-48">
            <div v-if="selected_payment_method !== 'app'" class="mb-6">
                <input v-model="amount" type="text" id="amount" class="bg-rk-yellow-light text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-700" placeholder="Enter Amount">
            </div>
            <div class="w-full bg-[#E6BF1C] border border-blue-950 shadow p-5 rounded-lg text-gray-700 container mt-5">
                <h2 class="text-center font-bold">Add Your Fund</h2>
                <p class="text-center text-gray-700 text-sm font-bold">You can add unlimited funds.</p>
                <hr class="my-5 border-rk-blue-dark">
                <h2 class="text-center font-bold">For any query Call or WhatsApp</h2>
                <a :href="'tel:+'+$page.props.admin_details.phone">
                    <strong class="text-center block pt-3"> <i class="fa-brands fa-whatsapp"></i> {{ $page.props.admin_details.phone }}</strong>
                </a>
                <hr class="my-5 border-rk-blue-dark">
                <strong class="text-center block pt-3"><i class="fa-solid fa-wallet"></i> <span class="wallet-points">{{ $page.props.auth.user.points }}</span></strong>
                <hr class="my-5 border-rk-blue-dark">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <select v-model="selected_payment_method" name="selected_payment_method" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="default">-- Select Payment Method --</option>
                            <option v-if="$page.props.settings.isPhonePe" value="phonepe">Pay Through PhonePe</option>
                            <option v-if="$page.props.settings.isUMoney" value="payu">Pay Through UMoney</option>
                            <option v-if="$page.props.settings.isRazorPay" value="razorpay">Pay Through RazorPay</option>
                            <option v-if="$page.props.settings.isFastUPI1" value="fastUPI1">Pay Through SBI Fast UPI 1</option>
                            <option v-if="$page.props.settings.isFastUPI2" value="fastUPI2">Pay Through SBI Fast UPI 2</option>
                        </select>
                    </div>
                </div>
                <button @click="requestInitiate" type="button" class="text-white bg-rk-red hover:bg-rk-blue-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Pay Now</button>
            </div>
            <form method="GET" id="payment-form">
                <input type="hidden" name="amount" :value="amount">
            </form>
            <form :action="payUDetail.action" method="post" name="payuForm">
                <input type="hidden" name="key" :value="payUDetail.key"/>
                <input type="hidden" name="hash" :value="payUDetail.hash"/>
                <input type="hidden" name="txnid" :value="payUDetail.txnid"/>
                <input type="hidden" name="amount" id="amountInput" :value="payUDetail.amount"/>
                <input type="hidden" name="firstname" id="firstname" :value="payUDetail.firstname"/>
                <input type="hidden" name="email" id="email" :value="payUDetail.email"/>
                <input type="hidden" name="phone" id="phone" :value="payUDetail.phone"/>
                <input type="hidden" name="productinfo" :value="payUDetail.productinfo">
                <input type="hidden" name="surl" :value="payUDetail.surl"/>
                <input type="hidden" name="furl" :value="payUDetail.furl"/>
                <input type="hidden" name="service_provider" :value="payUDetail.service_provider"/>
                <input type="hidden" name="udf1" :value="payUDetail.user_id"/>
            </form>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import {Head, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {defineProps, ref} from "vue";

const props = defineProps({
    settings: Object,
})
const page = usePage();
let amount = ref('');
let selected_payment_method = ref('default');
let payUDetail = ref({
    action: '',
    key: '',
    hash: '',
    txnid: '',
    firstname: '',
    email: '',
    phone: '',
    productinfo: '',
    surl: '',
    furl: '',
    amount: 0,
    service_provider: 'payu_paisa',
    user_id: page.props.auth.user.id
});

const requestInitiate = () => {
    const { value: paymentMethod } = selected_payment_method;
    const { value: amt } = amount;

    if (!amt || amt < 1) {
        return showToast('Error', "Please Insert Amount");
    }
    if (amt < 100) {
        return showToast('Error', "Amount Can't Be Less Than 100");
    }

    switch (paymentMethod) {
        case 'payu':
            initiatePayU(amt);
            break;
        case 'phonepe':
            submitForm(route('phonePe'));
            break;
        case 'fastUPI1':
            submitForm(route('fastUPI.payment.createOrder', { upi: 'UPI1' }));
            break;
        case 'fastUPI2':
            submitForm(route('fastUPI.payment.createOrder', { upi: 'UPI2' }));
            break;
        case 'razorpay':
            payWithRazorpay();
            break;
        default:
            showToast('Error', "Select Payment Method");
    }
};

const submitForm = (action) => {
    const form = document.getElementById('payment-form');
    form.action = action;
    form.submit();
};

const initiatePayU = (amt) => {
    axios.post('payUMoney', { amount: parseInt(amt) })
        .then(({ data }) => {
            Object.assign(payUDetail.value, {
                action: data.action,
                key: data.merchant_key,
                hash: data.hash,
                txnid: data.txnid,
                firstname: data.name,
                email: data.email,
                phone: data.phone,
                productinfo: data.product_info,
                surl: data.successURL,
                furl: data.failURL,
                service_provider: data.service_provider,
                amount: data.amount,
                user_id: data.user_id,
            });
            setTimeout(() => document.forms.payuForm.submit(), 1000);
        })
        .catch(error => showToast('Error', error.message || 'Error processing PayU'));
};

const payWithRazorpay = async () => {
    try {
        const { data } = await axios.post('create-order', { amount: amount.value });
        const { order_id } = data;

        const options = {
            key: props.settings.razorpay_key_id,
            amount: amount.value * 100,
            currency: 'INR',
            name: 'DP ON WEB',
            description: 'Test Transaction',
            order_id,
            handler: async (response) => handlePayment(response),
            prefill: {
                name: page.props.auth.name,
                email: page.props.auth.email,
                contact: page.props.auth.phone,
            },
            theme: { color: '#3399cc' },
        };

        new Razorpay(options).open();
    } catch (error) {
        showToast('Error', 'Error in creating order');
    }
};

const handlePayment = async (response) => {
    try {
        const { data } = await axios.post('verify-payment', {
            razorpay_order_id: response.razorpay_order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_signature: response.razorpay_signature,
            amount: amount.value * 100,
        });

        updateWalletPoints(data.points);
        amount.value = '';
        showToast('Success', data.msg, 'success');
    } catch (error) {
        showToast('Error', 'Payment verification failed');
    }
};

const updateWalletPoints = (points) => {
    document.querySelectorAll('.wallet-points').forEach(element => element.textContent = points);
};

const showToast = (title, message, type = 'error') => {
    iziToast[type]({ title, message, position: 'topRight' });
};

</script>

<style scoped>

</style>
