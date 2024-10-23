<script setup lang="ts">
import {computed} from "vue";

const props = defineProps<{
    disabled: boolean,
}>()

const emits = defineEmits<{
    (e: 'clickedButton'): void
}>();

const handleButtonClick = () => {
    emits('clickedButton')
}

const buttonStyle = computed(() => {
    return {
        'bg-[color:--button-bg] text-[--button-text]': !props.disabled,
        'bg-[color:--button-disabled-bg] text-[--button-disabled-text]': props.disabled,
    }
})

</script>

<template>
    <button
        class="inline-flex
            items-center
            rounded-md
            py-2
            text-xs
            font-semibold
            uppercase
            tracking-widest
            focus:bg-[--light-bg-color]
            focus:text-[--button-focus-text]
            hover:bg-[--button-hover-bg]
            hover:text-[--button-hover-text]"
        :disabled="props.disabled"
        @click="handleButtonClick"
        :class="buttonStyle"
    >Calculate
    </button>
</template>

<style scoped>

</style>
