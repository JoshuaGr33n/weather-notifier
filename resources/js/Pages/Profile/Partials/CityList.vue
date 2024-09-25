<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Accepting userCities as a prop
const props = defineProps({
    userCities: Array,
});

// Cities form
const form = useForm({
    city_name: '',
});

const addCity = () => {
    form.post(route('user-cities.store'), {
        onSuccess: () => {
            form.reset('city_name');
        },
    });
};
</script>

<template>
    <AppLayout title="Cities">
        <template #header>
            Add your preferred cities for weather notifications.
        </template>
        <FormSection @submitted="addCity" class="mt-4">
            <template #form>
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="city_name" value="City Name" />
                    <TextInput id="city_name" v-model="form.city_name" type="text" class="mt-1 block w-full" />
                    <InputError :message="form.errors.city_name" class="mt-2" />
                </div>
            </template>

            <template #actions>
                <ActionMessage :on="form.recentlySuccessful" class="me-3">
                    City added.
                </ActionMessage>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Add City
                </PrimaryButton>
            </template>

            <!-- Listing the added cities -->
            <template #list>
                <h3 class="mt-6 text-lg font-medium">Your Cities</h3>
                <ul class="mt-4 bg-white shadow-md rounded-lg p-4">
                    <li v-for="city in props.userCities" :key="city.id">
                        {{ city.city_name }}
                    </li>
                </ul>
            </template>
        </FormSection>
    </AppLayout>
</template>
