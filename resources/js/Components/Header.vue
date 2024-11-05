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
                    <!-- Notification Bell and Dropdown -->
                    <div v-if="$page.props.auth.user" class="relative mr-3">
                        <button type="button"
                                class="flex text-sm rounded-full focus:ring-4 focus:ring-yellow-400 notification-bell"
                                id="notification-menu-button"
                                @click="fetchNotifications"
                                aria-expanded="false"
                                data-dropdown-toggle="notification-dropdown"
                                data-dropdown-placement="bottom">
                            <span class="sr-only">View notifications</span>
                            <div class="relative">
                                <i class="fas fa-bell text-2xl text-rk-yellow-light"></i>
                                <div v-if="unreadCount > 0"
                                     class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs animate-pulse">
                                    {{ unreadCount }}
                                </div>
                            </div>
                        </button>

                        <!-- Notification Dropdown menu -->
                        <div class="z-50 hidden text-base list-none rounded-lg shadow-lg"
                             id="notification-dropdown">
                            <div class="notification-container bg-gradient-to-b from-rk-blue-dark via-rk-blue to-rk-blue-dark rounded-lg overflow-hidden">
                                <!-- Header -->
                                <div class="px-4 py-3 bg-gradient-to-r from-rk-blue-dark to-rk-blue border-b border-rk-blue-light">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-semibold text-rk-yellow-light">Notifications</h3>
                                        <button v-if="unreadCount > 0"
                                                @click="markAllAsRead"
                                                class="text-sm bg-rk-red hover:bg-red-600 text-white rounded-md px-3 py-1 transition duration-300 ease-in-out">
                                            Mark all as read
                                        </button>
                                    </div>
                                </div>

                                <!-- Loading State -->
                                <div v-if="isLoading" class="p-4 text-center text-gray-400">
                                    Loading notifications...
                                </div>

                                <!-- Error State -->
                                <div v-else-if="error" class="p-4 text-center text-red-400">
                                    {{ error }}
                                </div>

                                <!-- Empty State -->
                                <div v-else-if="notifications.length === 0" class="p-4 text-center text-gray-400">
                                    No notifications yet
                                </div>

                                <!-- Notifications List -->
                                <div v-else class="divide-y divide-rk-blue-light max-h-[60vh] overflow-y-auto custom-scrollbar">
                                    <div v-for="notification in notifications"
                                         :key="notification.id"
                                         @click="markAsRead(notification.id)"
                                         class="flex px-4 py-3 hover:bg-rk-blue-dark/70 transition duration-300 ease-in-out cursor-pointer"
                                         :class="{'bg-gradient-to-r from-rk-blue to-rk-blue-light/20': !notification.is_read,
                                 'bg-gradient-to-r from-rk-blue-dark to-rk-blue': notification.is_read}">
                                        <div class="flex-shrink-0 w-3">
                                            <div class="h-2 w-2 rounded-full mt-2"
                                                 :class="{'bg-rk-yellow-light': !notification.is_read,
                                         'bg-gray-600': notification.is_read}">
                                            </div>
                                        </div>
                                        <div class="w-full pl-3">
                                            <div class="text-sm mb-1"
                                                 :class="{'text-white font-semibold': !notification.is_read,
                                         'text-gray-300': notification.is_read}">
                                                {{ notification.title }}
                                            </div>
                                            <div class="text-xs text-gray-300 mb-1.5">
                                                {{ notification.message }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ formatTimestamp(notification.created_at) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
import {Link, usePage} from "@inertiajs/vue3";
import {ref, onMounted} from "vue";
import axios from 'axios';

// Reactive references
const notifications = ref([]);
const unreadCount = ref(0);
const isLoading = ref(false);
const error = ref(null);
const user = usePage().props.auth.user;
const user_id = user ? user.id : null;

onMounted(() => {
    initFlowbite();
    if (user_id) {
        fetchUnreadCount();
    }
});

// Function to fetch notifications
const fetchNotifications = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(`/api/notifications?user_id=${user_id}`);
        notifications.value = response.data.notifications;
    } catch (err) {
        error.value = 'Failed to load notifications';
        console.error('Error fetching notifications:', err);
    } finally {
        isLoading.value = false;
    }
};

// Function to fetch unread count
const fetchUnreadCount = async () => {
    try {
        const response = await axios.get(`/api/notifications/unread-count?user_id=${user_id}`);
        unreadCount.value = response.data.count;
    } catch (err) {
        console.error('Error fetching unread count:', err);
    }
};

// Function to mark all notifications as read
const markAllAsRead = async () => {
    try {
        await axios.post('/api/notifications/mark-all-read', { user_id });
        // Update the notifications to show they're read
        notifications.value = notifications.value.map(notification => ({
            ...notification,
            is_read: 1
        }));
        unreadCount.value = 0;
    } catch (err) {
        console.error('Error marking notifications as read:', err);
    }
};

// Function to mark single notification as read
const markAsRead = async (notificationId) => {
    try {
        await axios.post(`/api/notifications/${notificationId}/mark-read`, { user_id });
        // Update the specific notification
        const notification = notifications.value.find(n => n.id === notificationId);
        if (notification && !notification.is_read) {
            notification.is_read = 1;
            unreadCount.value--;
        }
    } catch (err) {
        console.error('Error marking notification as read:', err);
    }
};

// Format timestamp
const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000); // difference in seconds

    if (diff < 60) return 'Just now';
    if (diff < 3600) return `${Math.floor(diff / 60)} minutes ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;
    if (diff < 2592000) return `${Math.floor(diff / 86400)} days ago`;

    return date.toLocaleDateString();
};


</script>

<style scoped>

.bg-nav {
    background-image: url('web-assets/img/other-nav-bg.png');
}
.notification-bell {
    transition: transform 0.2s ease-in-out;
}

.notification-bell:hover {
    transform: scale(1.1);
}

#notification-dropdown {
    position: absolute;
    right: 0;
    min-width: 320px;
    max-width: 400px;
    margin-top: 0.5rem;
}

.notification-container {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

@media (max-width: 768px) {
    #notification-dropdown {
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        width: 100%;
        max-width: 100%;
        margin: 0;
        height: calc(100vh - 60px);
        border-radius: 0;
    }

    .notification-container {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .notification-container > div:nth-child(2) {
        flex-grow: 1;
        max-height: none;
    }
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.animate-pulse {
    animation: pulse 2s infinite;
}

.custom-scrollbar {
    /* Firefox */
    scrollbar-width: thin;
    scrollbar-color: #fbbf24 rgba(30, 41, 59, 0.5);

    /* Enable smooth scrolling */
    scroll-behavior: smooth;
}

/* Webkit browsers */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(30, 41, 59, 0.5);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #fbbf24;
    border-radius: 3px;
    transition: background 0.2s ease;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #f59e0b;
}
</style>
