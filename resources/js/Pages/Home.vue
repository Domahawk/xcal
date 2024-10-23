<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import Calculator from "@/Pages/Calculator/Calculator.vue";
import ThemePicker from '@/Components/ThemePicker.vue';
</script>

<template>
    <Head title="Home"/>
    <section class="w-full flex flex-col items-center">
        <div class="w-full flex min-h-screen flex-col items-center">
            <header
                class="w-full flex flex-col items-center justify-center bg-[--accent-color] pt-[20px] pb-[20px]">
                <h2>Dates before year 2023 will calculate the amount from asked currency to KN then from KN to EUR</h2>
            </header>
            <ThemePicker/>
            <main class="w-full">
                <Calculator/>
            </main>
        </div>
    </section>
</template>

<style>
* {
    transition: background-color 0.3s ease;
}
</style>
