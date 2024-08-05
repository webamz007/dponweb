<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {onMounted, ref} from "vue";

import {auth} from "@/firebase-config";
import { signInWithPhoneNumber, RecaptchaVerifier } from "firebase/auth";
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/dist/vue-tel-input.css';

defineProps({
    status: String,
});

const form = useForm({
    phone: '',
    password: '',
    password_confirmation: '',
});

const isProcessing = ref(false);
const otpCode = ref('');
const showOtp = ref(false);
const showResetPassword = ref(false);

const bindProps = ref({
    mode: "international",
        defaultCountry: "IN",
        placeholder: "Enter a phone number",
        required: true,
        enabledCountryCode: true,
        enabledFlags: true,
        onlyCountries: ['IN', 'PK'],
        autocomplete: "on",
        name: "phone",
        maxLen: 25,

    });

const preventSpace = (event) => {
    if (event.keyCode === 32) {
        event.preventDefault();
    }
}

const submit = () => {
    form.post(route('password.email'));
};

const resetPassword = () => {
    form.phone = form.phone.replace(/^\+\d+\s/g, '').replace(/\s/g, '');
    form.post(route('password.reset'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const onSendOtp = () => {
    isProcessing.value = true;
    const appVerifier = window.recaptchaVerifier
    // var number = '+1 650-555-3434';
    signInWithPhoneNumber(auth, form.phone, appVerifier).then(function (confirmationResult) {
        window.confirmationResult = confirmationResult;
        iziToast.success({
            title: 'Success',
            message: 'OTP sent successfully!',
            position: 'topRight'
        });
        isProcessing.value = false;
        showOtp.value = true;
    }).catch(function (error) {
        isProcessing.value = false;
        iziToast.error({
            title: 'Error',
            message: error.message,
            position: 'topRight'
        });
    });
}

const onOtpVerify = () => {
    let otp = otpCode.value;
    if (otp.length !== 6) {
        iziToast.error({
            title: 'Error',
            message: 'Please enter 6 digit otp code.',
            position: 'topRight'
        });
        return
    }
    // var code = 654321;
    isProcessing.value = true;
    window.confirmationResult.confirm(otp).then(async (res) => {
        iziToast.success({
            title: 'Success',
            message: 'Phone Number Verified Successfully!',
            position: 'topRight'
        });
        showOtp.value = false;
        isProcessing.value = false;
        showResetPassword.value = true;
    }).catch(function (error) {
        isProcessing.value = false;
        iziToast.error({
            title: 'Error',
            message: error.message,
            position: 'topRight'
        });
    });
}

onMounted(() => {
    window.recaptchaVerifier = new RecaptchaVerifier(auth, 'recaptcha-container', {
        'size': 'invisible',
        'callback': (response) => {},
        'expired-callback': () => {}
    });

    document.querySelector(".vti__input").required = true;
})

</script>

<template>
    <GuestLayout>
        <Head v-if="showResetPassword" title="Reset Password" />
        <Head v-else title="Forgot Password" />

        <div v-if="!(showOtp || showResetPassword)" class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your phone number and we will send you OTP that will allow you to choose a new one.
        </div>
        <h1 v-else-if="showOtp" class="text-center text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
            Verify Code
        </h1>
        <h1 v-else-if="showResetPassword" class="text-center text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
            Reset Password
        </h1>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div id="recaptcha-container"></div>

        <!-- Send OTP Form -->

        <form v-if="!(showOtp || showResetPassword)" @submit.prevent="onSendOtp">
            <div>
                <InputLabel for="phone" value="Phone" />

                <VueTelInput
                    v-model="form.phone"
                    v-bind="bindProps"
                />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-50': isProcessing }" :disabled="isProcessing">
                    <svg v-if="isProcessing" aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                    Send OTP
                </PrimaryButton>
            </div>
        </form>

        <!-- Verify OTP Form -->

        <form v-else-if="showOtp" @submit.prevent="onOtpVerify">
            <div>
                <TextInput
                    id="otpCode"
                    type="number"
                    name="otpCode"
                    v-model="otpCode"
                    required
                    autocomplete="otpCode"
                />
            </div>
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-50': isProcessing }" :disabled="isProcessing">
                    <svg v-if="isProcessing" aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                    Verify OTP
                </PrimaryButton>
            </div>
            <p class="text-sm font-light text-gray-900 mt-3">
                Didn't get code? <a @click.prevent="onSendOtp" href="#" class="font-bold text-primary-600 hover:underline dark:text-primary-500">Send Again</a>
            </p>
        </form>

        <!-- Reset Password Form -->

        <form v-else-if="showResetPassword" @submit.prevent="resetPassword">

            <div>
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    @keydown="preventSpace($event)"
                />

                <InputError class="mt-2" :message="form.errors.password" />
                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    @keydown="preventSpace($event)"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                    <svg v-if="isProcessing || form.processing" aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                    Reset Password
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
<style>
    .vti__input {
       background-color: #0B001B;
        color: #fff;
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
   }
    .vti__dropdown {
        background-color: #0B001B;
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }
</style>
