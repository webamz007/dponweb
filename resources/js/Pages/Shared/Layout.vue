<template>
    <Toaster />
    <div class="min-h-screen flex flex-col">
        <Header />
        <main class="flex-1">
            <slot></slot>
        </main>
        <Footer/>
    </div>
</template>

<script setup>
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import Toaster from "@/Components/Toaster.vue";
</script>

<style scoped>

</style>
