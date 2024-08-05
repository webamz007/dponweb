<template>
    <!-- Footer -->
    <footer class="w-full p-4 bg-rk-blue-dark border-t border-[#290a59] shadow md:flex md:items-center md:justify-between md:p-6 mt-5">
    <span class="text-base text-white sm:text-center">© 2023 <a href="#" class="hover:underline">Rk Online™</a>. All Rights Reserved.
    </span>
        <div class="flex justify-center">
            <a :href="'tel:+'+$page.props.admin_details.phone"><i class="fa-solid fa-square-phone fa-2x mr-2 text-rk-yellow-light"></i></a>
            <a :href="$page.props.admin_details.whatsapp"><i class="fa-brands fa-square-whatsapp fa-2x text-rk-yellow-light"></i></a>
        </div>
        <ul class="flex flex-wrap items-center mt-3 text-base font-medium text-white sm:mt-0">
            <li>
                <a :href="$page.props.admin_details.guessing" target="_blank" class="mr-4 hover:underline md:mr-6">Guessing Line</a>
            </li>
            <li>
                <a :href="$page.props.admin_details.results_and_videos" target="_blank" class="mr-4 hover:underline md:mr-6">Results & Videos</a>
            </li>
            <li>
                <a :href="$page.props.admin_details.whatsapp_channel" target="_blank" class="mr-4 hover:underline md:mr-6">WhatsApp Channel</a>
            </li>
            <li>
                <Link :href="route('game.info')"
                      :class="{'text-rk-yellow-light': $page.url === '/how-to-play'}"
                      class="mr-4 hover:underline md:mr-6">How to Play
                </Link>
            </li>
        </ul>
    </footer>
    <!-- Footer End -->
</template>

<script setup>
import {Link} from "@inertiajs/vue3";

</script>

<style scoped>

</style>
