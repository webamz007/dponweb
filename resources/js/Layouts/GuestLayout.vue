<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section class="container flex justify-center items-center">
        <div class="w-full">
            <ApplicationLogo />
            <div class="w-full bg-rk-yellow-light rounded-lg shadow dark:border md:mt-0 xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <slot />
                </div>
            </div>
        </div>
    </section>
</template>
