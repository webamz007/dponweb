<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const preventSpace = (event) => {
    if (event.keyCode === 32) {
        event.preventDefault();
    }
}
</script>

<template>
    <GuestLayout class="mt-10">
        <Head title="Log in" />
        <h1 class="text-center text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
            Sign in to your account
        </h1>
        <div v-if="status" class="mb-4 font-medium text-sm" :class="status.success ? 'text-green-600' : 'text-red-600'">
            {{ status.msg }}
        </div>
        <form @submit.prevent="submit" class="space-y-4 md:space-y-6">
            <div>
                <InputLabel for="email" value="Phone" />
                <TextInput
                    id="email"
                    type="text"
                    name="email"
                    placeholder="0783221112"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div>
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    @keydown="preventSpace($event)"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <Checkbox name="remember" id="remember" aria-describedby="remember" v-model:checked="form.remember" />
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="remember" class="text-gray-900 font-bold">Remember me</label>
                    </div>
                </div>
                <Link v-if="canResetPassword"
                      :href="route('password.request')"
                      class="text-sm font-bold text-gray-900 hover:underline"
                >
                    Forgot password?
                </Link>
            </div>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Sign in
            </PrimaryButton>
            <div class="flex justify-center">
                <a :href="'tel:+'+$page.props.admin_details.phone"><i class="fa-solid fa-square-phone fa-2x mr-2 text-rk-blue-dark"></i></a>
                <a :href="$page.props.admin_details.whatsapp"><i class="fa-brands fa-square-whatsapp fa-2x text-rk-blue-dark"></i></a>
            </div>
            <p class="text-sm font-light text-gray-900">
                Don’t have an account yet? <Link :href="route('register')" class="font-bold text-primary-600 hover:underline dark:text-primary-500">Sign up</Link>
            </p>
        </form>
    </GuestLayout>
</template>
