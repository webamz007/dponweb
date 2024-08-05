<template>

    <TransitionGroup
        tag="div"
        enter-from-class="translate-x-full opacity-0"
        enter-active-class="duration-500"
        leave-active-class="duration-500"
        leave-to-class="translate-x-full opacity-0"
        class="fixed top-4 right-4 z-50 w-full max-w-xs">
        <ToastListItem
            v-for="(item, index) in toast.items"
            :key="item.key"
            :message="item.message"
            :type="item.type"
            :duration="2000"
            @remove="remove(index)"
        />
    </TransitionGroup>

</template>

<script setup>
import ToastListItem from "@/Components/ToastListItem.vue";
import {onUnmounted, ref} from "vue";
import {usePage, router} from "@inertiajs/vue3";
import toast from "@/Stores/toaster";


const page = usePage();

const removeFinishEventListener = router.on('finish', () => {
    if (page.props.value.toast) {
        toast.add({message: page.props.value.toast})
    }
})
onUnmounted(() =>removeFinishEventListener())

const remove = (index) => {
    toast.remove(index);
}

</script>

<style scoped>

</style>
