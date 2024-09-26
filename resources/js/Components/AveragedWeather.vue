<template>
    <div class="p-4 bg-white shadow rounded-lg dark:bg-gray-800">
        <h2 class="text-xl font-bold mb-4">Weather Averages for {{ city }}</h2>
        <ul v-if="weatherData">
            <li>Temperature: {{ weatherData.temp || 'N/A' }}°C</li>
            <li>Precipitation: {{ weatherData.precipitation || 'N/A' }} mm</li>
            <li>UV Index: {{ weatherData.uv_index || 'N/A' }}</li>
        </ul>
        <p v-else>
            Weather data is currently unavailable.
        </p>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    city: String,
    weatherData: {
        type: Object,
        default: () => ({}), // Default to an empty object if not provided
    },
});
</script>
