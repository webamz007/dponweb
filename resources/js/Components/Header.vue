<template>
    <!-- Header -->
    <!--TopBar Start-->

    <nav v-if="$page.props.auth.user" class="border-gray-200 bg-rk-blue-light">
        <div class="container flex flex-wrap items-center justify-between mx-auto p-4">
            <div class="flex justify-center">
                <a :href="'tel:+'+$page.props.admin_details.phone"><i class="fa-solid fa-square-phone fa-2x mr-2 text-rk-yellow-light"></i></a>
                <a :href="$page.props.admin_details.whatsapp"><i class="fa-brands fa-square-whatsapp fa-2x text-rk-yellow-light"></i></a>
            </div>
            <div class="text-rk-yellow-light md:hidden">
                <Link :href="route('add.funds')">
                    <i class="fa-solid fa-wallet mr-1"></i> ₹<span class="wallet-points">{{ $page.props.auth.user.points !== '' ? $page.props.auth.user.points : 0 }}</span>
                </Link>
            </div>
            <button data-collapse-toggle="navbar-solid-bg" type="button" class="inline-flex items-center p-2 ml-3 mt-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-rk-yellow-light hover:text-black focus:outline-none focus:ring-0 focus:ring-gray-200" aria-controls="navbar-solid-bg" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
            </button>
            <div class="hidden w-full md:block md:w-auto self-center" id="navbar-solid-bg">
                <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-transparent md:flex-row md:space-x-8 md:mt-0 md:border-0">
                    <li>
                        <Link :href="route('game.transaction')"
                              class="block py-2 pl-3 pr-4 text-rk-yellow-light rounded md:p-0"
                              aria-current="page">Transactions</Link>
                    </li>
                    <li>
                        <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="flex items-center justify-between w-full py-2 pl-3 pr-4 text-rk-yellow-light rounded hover:text-black hover:bg-rk-yellow-light md:hover:bg-transparent md:border-0 md:hover:text-rk-yellow-light md:p-0 md:w-auto">My History <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>
                        <!-- Dropdown menu -->
                        <div id="dropdownNavbar" class="z-10 hidden font-normal bg-rk-yellow-light divide-y divide-gray-100 rounded-lg shadow w-72 md:w-44 z-50">
                            <ul class="py-2 text-sm text-black" aria-labelledby="dropdownLargeButton">
                                <li>
                                    <Link :href="route('game.history', {type: 'other'})" class="block px-4 py-2 hover:bg-rk-red hover:text-white">Bid History</Link>
                                </li>
                                <li>
                                    <Link :href="route('game.history', {type: 'starline'})" class="block px-4 py-2 hover:bg-rk-red hover:text-white">Starline Bid History</Link>
                                </li>
                                <li>
                                    <Link :href="route('game.history', {type: 'delhi'})" class="block px-4 py-2 hover:bg-rk-red hover:text-white">Delhi Bid History</Link>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <Link :href="route('game.passbook')"
                              class="block py-2 pl-3 pr-4 text-rk-yellow-light rounded md:p-0"
                              aria-current="page">Passbook</Link>
                    </li>
                    <li>
                        <Link :href="route('add.funds')"
                              class="block py-2 pl-3 pr-4 text-rk-yellow-light rounded md:p-0"
                              aria-current="page">Add Funds</Link>
                    </li>
                    <li>
                        <Link :href="route('withdraw-funds')"
                              class="block py-2 pl-3 pr-4 text-rk-yellow-light rounded md:p-0"
                              aria-current="page">Withdraw Funds</Link>
                    </li>
                </ul>
            </div>
            <div class="text-rk-yellow-light hidden md:block">
                <Link :href="route('add.funds')">
                    <i class="fa-solid fa-wallet mr-1"></i> ₹<span class="wallet-points" id="wallet-points">{{ $page.props.auth.user.points !== '' ? $page.props.auth.user.points : 0 }}</span>
                </Link>
            </div>
        </div>
    </nav>
    <!--TopBar End-->

    <header class="bg-transparent bg-nav">
        <nav class="bg-transparent border-gray-200 dark:bg-gray-900">
            <div class="container flex flex-wrap items-center justify-between mx-auto p-4">
                <Link :href="route('home')" class="flex items-center">
                    <img :src="'web-assets/img/rk-logo.png'" class="w-20 h-auto mr-3" alt="DP WEB Logo"/>
                    <span class="self-center text-2xl font-semibold whitespace-nowrap text-rk-yellow-light">DP WEB</span>
                </Link>
                <div class="flex items-center md:order-2">
                    <button type="button"
                            class="flex mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-yellow-400"
                            id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                            data-dropdown-placement="bottom">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" :src="'web-assets/img/avatar.png'" alt="user photo">
                    </button>
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-black rounded-lg shadow bg-transparent bg-rk-gradient-yellow"
                         id="user-dropdown">
                        <div class="px-4 py-3" v-if="$page.props.auth.user">
                            <span class="block text-sm text-black">{{ $page.props.auth.user.name }}</span>
                            <span class="block text-sm  text-gray-900 truncate">{{ $page.props.auth.user.email }}</span>
                        </div>
                        <ul v-if="$page.props.auth.user" class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <Link :href="route('profile.edit')"
                                   class="block px-4 py-2 text-sm text-black hover:text-white hover:bg-rk-red">Profile</Link>
                            </li>
                            <li>
                                <Link :href="route('logout')" method="post"
                                   class="block px-4 py-2 text-sm text-black hover:text-white hover:bg-rk-red">
                                    Sign out
                                </Link>
                            </li>
                        </ul>
                        <ul v-else class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <Link :href="route('login')"
                                      class="block px-4 py-2 text-sm text-black hover:bg-rk-red">
                                    Sign In
                                </Link>
                                <Link :href="route('register')"
                                      class="block px-4 py-2 text-sm text-black hover:bg-rk-red">
                                    Register
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <button data-collapse-toggle="mobile-menu-2" type="button"
                            class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-rk-yellow-light hover:text-black focus:outline-none focus:ring-0 focus:ring-gray-200"
                            aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="mobile-menu-2">
                    <ul class="flex flex-col font-medium text-white p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-transparent md:flex-row md:space-x-8 md:mt-0 md:border-0">
                        <li>
                            <Link :href="route('game', { type: 'default' })" :class="{'!bg-rk-red !text-white': $page.url === '/game?type=default'}"
                                  class="block mb-2 md:mb-0 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg">Default</Link>
                        </li>
<!--                        <li>-->
<!--                            <Link :href="route('game', { type: 'single_pana' })" :class="{'!bg-rk-red !text-white': $page.url === '/game?type=single_pana'}"-->
<!--                               class="block mb-2 md:mb-0 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg">S P</Link>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <Link :href="route('game', { type: 'double_pana' })" :class="{'!bg-rk-red !text-white': $page.url === '/game?type=double_pana'}"-->
<!--                               class="block mb-2 md:mb-0 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg">D P</Link>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <Link :href="route('game', { type: 'tripple_pana' })" :class="{'!bg-rk-red !text-white': $page.url === '/game?type=tripple_pana'}"-->
<!--                               class="block mb-2 md:mb-0 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg">T P</Link>-->
<!--                        </li>-->
                        <li>
                            <Link :href="route('game', { type: 'half_sangum' })" :class="{'!bg-rk-red !text-white': $page.url === '/game?type=half_sangum'}"
                               class="block mb-2 md:mb-0 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg">Half Sangum</Link>
                        </li>
                        <li>
                            <Link :href="route('game', { type: 'full_sangum' })" :class="{'!bg-rk-red !text-white': $page.url === '/game?type=full_sangum'}"
                               class="block mb-2 md:mb-0 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg">Full Sangum</Link>
                        </li>
                        <li>
                            <Link :href="route('game', { type: 'sp_motors' })" :class="{'!bg-rk-red !text-white': $page.url === '/game?type=sp_motors'}"
                                  class="block mb-2 md:mb-0 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg">SP Motors</Link>
                        </li>
                        <li>
                            <Link :href="route('game', { type: 'dp_motors' })" :class="{'!bg-rk-red !text-white': $page.url === '/game?type=dp_motors'}"
                                  class="block mb-2 md:mb-0 animate-shake bg-rk-yellow-light px-5 py-3 text-black rounded-lg">DP Motors</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- Header End -->
</template>

<script setup>
import { initFlowbite } from 'flowbite';
import {Link} from "@inertiajs/vue3";
import {onMounted} from "vue";

onMounted(() => {
    initFlowbite();
});


</script>

<style scoped>

.bg-nav {
    background-image: url('web-assets/img/other-nav-bg.png');
}

</style>
