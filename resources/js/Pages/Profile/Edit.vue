<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage, Head } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    phone: user.phone,
    bank: user.bank,
    ifsc: user.ifsc,
    upi: user.upi,
});

const updateUser = async () => {
    try {
        await form.patch(route('profile.update'), {
            onSuccess: (res) => {
                if (res.props.status.success) {
                    iziToast.success({
                        title: 'Success',
                        message: res.props.status.msg,
                        position: 'topRight'
                    });
                }
            }
        });
    } catch (error) {
        iziToast.error({
            title: 'Error',
            message: error,
            position: 'topRight'
        });
    }
}

</script>

<template>
    <Head title="Profile" />
    <Layout>
        <section>
            <div class="w-full bg-rk-yellow-light border border-blue-950 shadow p-5 rounded-lg text-gray-500 container mt-5">
                <form @submit.prevent="updateUser" >
                    <div class="mb-6">
                        <InputLabel for="name" value="Name" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div class="mb-6">
                        <InputLabel for="phone" value="Phone" />
                        <TextInput
                            id="phone"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.phone"
                            required
                            autofocus
                            autocomplete="phone"
                            readonly=""
                        />
                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>
                    <div class="mb-6">
                        <InputLabel for="phone" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="email"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div class="mb-6">
                        <InputLabel for="ac-number" value="A/C Number" />
                        <TextInput
                            id="ac-number"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.bank"
                            autofocus
                            autocomplete="ac-number"
                        />
                        <InputError class="mt-2" :message="form.errors.bank" />
                    </div>
                    <div class="mb-6">
                        <InputLabel for="ifsc" value="IFSC" />
                        <TextInput
                            id="ifsc"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.ifsc"
                            autofocus
                            autocomplete="ifsc"
                        />
                        <InputError class="mt-2" :message="form.errors.ifsc" />
                    </div>
                    <div class="mb-6">
                        <InputLabel for="paytm" value="PhonePe, Google Pay, Paytm Number" />
                        <TextInput
                            id="paytm"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.upi"
                            autofocus
                            autocomplete="paytm"
                        />
                        <InputError class="mt-2" :message="form.errors.upi" />
                    </div>
                    <button type="submit" class="text-white bg-rk-red hover:bg-rk-blue-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Save Changes</button>
                </form>
            </div>
        </section>
    </Layout>
</template>
