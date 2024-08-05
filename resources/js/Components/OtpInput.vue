<template>
    <div ref="container" class="flex gap-4 items-center">
        <input v-for="n in length" :key="n"
               @keyup="(e) => handleEnter(e, n-1)"
               v-model="otpArray[n-1]" type="text" maxlength="1" class="border rounded-md w-10 p-2 text-center">
    </div>
</template>

<script setup>
import {ref, defineProps, onMounted} from "vue";
let otpProps = defineProps({
    length: {
        type: Number,
        default: 6
    }
});
const otpArray = ref([]);
const container = ref();
const otpEmit = defineEmits('entered');

onMounted(() => {
    for (let i = 0; i < otpProps.length; i++) {
        otpArray.value[i] = null
    }
})

const handleEnter = (e, i) => {
    const children = container.value.children;
    const keyPressed = e.key;

    if (i > 0 && (keyPressed === 'Backspace') || keyPressed === 'Delete') {
        otpArray.value[i] = null;
        setTimeout(() => {
            children[i-1].focus()
        }, 100)
    }
    else {
        const matched = keyPressed.match(/^[0-9]$/)
        if (!matched) {
            otpArray.value[i] = null;
            return
        }
        else if (i < otpProps.length - 1) {
            setTimeout(() => {
                children[i+1].focus()
            }, 100)
        }
        checkOtp();
    }
}

const checkOtp = () => {
    const children = container.value.children;

    let flag = true;

    for (let i = 0; i < otpProps.length -1; i++) {
        if (otpArray.value[i] === null) {
            children[i].classList.add('border-red-500')
            flag = false;
        }
        else {
            children[i].classList.remove('border-red-500')
        }
    }
     if (flag) otpEmit('entered', otpArray.value.join(''));
}
</script>
