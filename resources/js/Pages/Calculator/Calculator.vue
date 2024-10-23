<script setup lang="ts">
import { ref, computed, Ref } from 'vue';
import Big from 'big.js';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { format } from 'date-fns';
import CurrencyPicker from '@/Components/CurrencyPicker.vue';
import Button from '@/Components/Button.vue';
import backendApi from '@/services/api/backendApi';
import { CalculationResult, CalculatorErrorResponse } from '@/types/httpResponses/backendApi';
import ItemCard from '@/Components/ItemCard.vue';
import { useTheme } from '@/services/theme';
import axios, { AxiosError, isAxiosError } from 'axios';

const askCurrencyAmount: Ref<null | string | number> = ref(null);
const currencyCode: Ref<String> = ref('');
const date: Ref<Date | null> = ref(null);
const responseData: Ref<Array<CalculationResult>> = ref([]);
const errors: Ref<CalculatorErrorResponse> = ref(<CalculatorErrorResponse>{});
const DPTextInputOptions = {
    enterSubmit: true,
    format: 'dd-MM-yyyy'
};

const isButtonDisabled = computed(() => {
    return askCurrencyAmount.value === null || date.value === null;
});

const displayKnCalculation = computed(() => {
    if (date.value === null) {
        return false;
    }

    return date.value < new Date('01-01-2023');
});

const darkColorTheme = computed(() => {
    return useTheme().theme.value != 'light';
});

const onBlur = () => {
    if (typeof askCurrencyAmount.value !== 'number') {
        return;
    }

    askCurrencyAmount.value = new Big(askCurrencyAmount.value).toFixed(2);
};

const calculateRequest = async () => {
    try {
        const response = await backendApi.post('/calculate', {
            amount: askCurrencyAmount.value,
            currencyCode: currencyCode.value,
            date: date.value === null ? '' : format(date.value, 'yyyy-MM-dd')
        });

        responseData.value = response.data;
    } catch (error: any) {
        if (!isAxiosError(error)) {
            errors.value = {
                message: 'Unexpected error occurred',
                errors: error.errors,
            };

            return;
        }

        errors.value = error.response.data;
    }
};

const formatNumber = (number: Number, round: boolean = false): string => {
    if (round) {
        return new Big(number).toFixed(2);
    }

    return new Big(number).toString();
};

</script>

<template>
    <div class="w-full flex flex-col items-center">
        <div class="w-full min-h-max flex flex-col items-stretch justify-center lg:flex-row">
            <ItemCard>
                <div class="w-full flex flex-col justify-around items-center">
                    <label>Pick a date</label>
                    <VueDatePicker
                        v-model="date"
                        :text-input="DPTextInputOptions"
                        :max-date="new Date()"
                        :enable-time-picker="false"
                        format="dd-MM-yyyy"
                        :dark="darkColorTheme"
                        auto-apply
                        vertical
                        class="w-full border-[1px] rounded-md border-white-400"
                    />
                </div>
                <CurrencyPicker @currency-change="(value: string) => currencyCode = value" />
                <div class="flex flex-col justify-around items-center w-full">
                    <label for="amount-to-eur">Amount in {{ currencyCode }}</label>
                    <input
                        id="amount-to-eur"
                        class="p-2 rounded-md border border-gray-300 bg-[color:--bg-color] w-full"
                        type="number"
                        v-model="askCurrencyAmount"
                        @blur="onBlur"
                        placeholder="Enter amount"
                    />
                </div>
            </ItemCard>
            <ItemCard>
                <div v-if="displayKnCalculation" class="flex flex-col justify-around items-start">
                    <h1>Calculation {{ currencyCode.toUpperCase() }} to KN to EUR</h1>
                    <div v-for="calculation in responseData">
                        <p>Exchange Rate - {{ formatNumber(calculation.exchangeRate) }}</p>
                        <p>Result - {{ formatNumber(calculation.amount, true) }}</p>
                    </div>
                </div>
                <div v-else class="flex flex-col justify-around items-start">
                    <h1>Calculation {{ currencyCode.toUpperCase() }} to EUR</h1>
                    <div v-for="calculation in responseData">
                        <p>Exchange Rate - {{ formatNumber(calculation.exchangeRate) }}</p>
                        <p>Result - {{ formatNumber(calculation.amount, true) }}</p>
                    </div>
                </div>
            </ItemCard>
        </div>
        <Button
            class="ms-4 w-[150px] flex flex-col items-center justify-center"
            :disabled="isButtonDisabled"
            @clicked-button="calculateRequest"
        >
            Calculate
        </Button>
    </div>
</template>

<style scoped>

</style>
