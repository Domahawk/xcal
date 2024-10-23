<script setup lang="ts">
import { Ref, ref } from "vue";

const currencies: Ref<object> = ref({
  aud: "Australian Dollar",
  cad: "Canadian Dollar",
  czk: "Czech Koruna",
  dkk: "Danish Krone",
  huf: "Hungarian Forint",
  jpy: "Japanese Yen",
  nok: "Norwegian Krone",
  sek: "Swedish Krona",
  chf: "Swiss Franc",
  gbp: "British Pound Sterling",
  usd: "US Dollar",
  bam: "Bosnia And Herzegovina Convertible Mark",
  pln: "Polish Zloty"
});
const selectedCurrency: Ref<String> = ref("");

const emit = defineEmits<{
  (e: 'currencyChange', value: String): void
}>()

const handleCurrencyChange = () => {
  emit('currencyChange', selectedCurrency.value)
}

</script>

<template>
    <div class="w-full flex flex-col items-center justify-around">
      <label for="currency-picker">Pick a currency</label>
      <select
          id="currency-picker"
          v-model="selectedCurrency"
          @change="handleCurrencyChange"
          class="p-2 rounded-md border border-gray-300 bg-[color:--bg-color] w-full"
      >
        <option v-for="(currencyName, currencyCode) in currencies" :value="currencyCode"> {{ currencyName }}</option>
      </select>
    </div>
</template>

<style scoped>

</style>
