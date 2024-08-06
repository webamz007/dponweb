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
                            <option value="phonepe">Pay Through PhonePe</option>
                            <option value="payu">Pay Through UMoney</option>
                            <option value="razorpay">Pay Through RazorPay</option>
<!--                            <option value="app">Pay Through App</option>-->
                            <option value="fastUPI">Pay Through SBI Fast UPI</option>
                        </select>
                    </div>
                </div>
                <button @click="requestInitiate" type="button" class="text-white bg-rk-red hover:bg-rk-blue-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">{{ btnText }}</button>
            </div>
            <form :action="route('phonePe')" id="phonepe-form">
                <input type="hidden" name="amount" :value="amount">
            </form>
            <form :action="route('fastUPI.payment.createOrder')" method="GET" id="fastUPI-form">
                <input type="hidden" name="amount" :value="amount">
                <input type="hidden" name="_token" :value="csrfToken">
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
import {computed, defineProps, ref} from "vue";

const props = defineProps({
    settings: Object,
})
const page = usePage();
let amount = ref('');
let csrfToken = ref('');
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

const btnText = computed(() => {
    return selected_payment_method.value === 'app' ? 'Download App' : 'Pay Now';
})

const requestInitiate = () => {

    if ((amount.value.length === 0 || amount.value < 1) && selected_payment_method.value !== 'app') {
        iziToast.error({
            title: 'Error',
            message: 'Please Insert Amount',
            position: 'topRight'
        });
        return
    }

    if (( amount.value < 100) && selected_payment_method.value !== 'app') {
        iziToast.error({
            title: 'Error',
            message: "Amount Can't Less Than 100",
            position: 'topRight'
        });
        return
    }

    if (selected_payment_method.value === 'payu') {

        const formData = {
            amount: parseInt(amount.value),
        };
        axios.post('payUMoney', formData)
            .then(response => {
                payUDetail.value = {
                    action: response.data.action,
                    key: response.data.merchant_key,
                    hash: response.data.hash,
                    txnid: response.data.txnid,
                    firstname: response.data.name,
                    email: response.data.email,
                    phone: response.data.phone,
                    productinfo: response.data.product_info,
                    surl: response.data.successURL,
                    furl: response.data.failURL,
                    service_provider: response.data.service_provider,
                    amount: response.data.amount,
                    user_id: response.data.user_id,
                };
            })
            .catch(error => {
                iziToast.error({
                    title: 'Error',
                    message: error,
                    position: 'topRight'
                });
            });
        // document.getElementById('amountInput').value = amount.value;
        setTimeout(function(){
            document.forms.payuForm.submit();
        }, 1000);

    } else if(selected_payment_method.value === 'phonepe') {
        document.getElementById("phonepe-form").submit();
    } else if(selected_payment_method.value === 'fastUPI') {
        let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        csrfToken.value = csrf;
        document.getElementById("fastUPI-form").submit();
    } else if(selected_payment_method.value === 'razorpay') {
        payWithRazorpay();
    } else if(selected_payment_method.value === 'app') {
        handleDownload();
    } else {
        iziToast.error({
            title: 'Error',
            message: 'Select Payment Method',
            position: 'topRight'
        });
    }
}

const phonePe = () => {
    axios.post('phonepe', {amount: amount.value})
        .then(response => {

        })
        .catch(error => {
            iziToast.error({
                title: 'Error',
                message: error,
                position: 'topRight'
            });
        });
}

const payWithRazorpay = async () => {
    try {
        const response = await axios.post('create-order', { amount: amount.value });
        const { order_id } = response.data;

        const options = {
            key: props.settings.razorpay_key_id,
            amount: amount.value * 100,
            currency: 'INR',
            name: 'DP ON WEB',
            description: 'Test Transaction',
            order_id: order_id,
            handler: async (response) => {
                try {
                    const res = await axios.post('verify-payment', {
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature,
                        amount: amount.value * 100,
                    });
                    amount.value = '';
                    Array.from(document.querySelectorAll('.wallet-points')).forEach(element => element.textContent = res.data.points);
                    iziToast.success({
                        title: 'Success',
                        message: res.data.msg,
                        position: 'topRight'
                    });
                } catch (error) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Payment verification failed',
                        position: 'topRight'
                    });
                }
            },
            prefill: {
                name: page.props.auth.name,
                email: page.props.auth.email,
                contact: page.props.auth.phone,
            },
            theme: {
                color: '#3399cc',
            },
        };

        const rzp = new Razorpay(options);
        rzp.open();
    } catch (error) {
        iziToast.error({
            title: 'Error',
            message: 'Error in creating order',
            position: 'topRight'
        });
    }
}

const handleDownload = () => {
    if (isMobile()) {
        checkAndOpenApp();
    } else {
        downloadApk();
    }
}

const isMobile = () => {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

const checkAndOpenApp = () => {
    window.location.href = 'mrsaifnetapp://'; // Replace with your app's scheme

    // Set a timeout to check if the app was opened successfully.
    setTimeout(() => {
        if (!document.hidden) {
            // App was not opened, trigger APK download.
            downloadApk();
        }
    }, 2000); // 2 seconds
}

const downloadApk = () => {
    window.location.href = '/download-apk';
}

</script>

<style scoped>

</style>
