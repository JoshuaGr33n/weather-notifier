<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    precipitation_threshold: 10,
    uv_index_threshold: 5,
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput v-model="form.name" type="text" class="mt-1 block w-full" required autofocus />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput v-model="form.email" type="email" class="mt-1 block w-full" required />
                <InputError :message="form.errors.email" class="mt-2" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput v-model="form.password" type="password" class="mt-1 block w-full" required />
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required />
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <!-- Custom Fields -->
            <div class="mt-4">
                <InputLabel for="precipitation_threshold" value="Precipitation Threshold" />
                <TextInput v-model="form.precipitation_threshold" type="number" class="mt-1 block w-full" />
                <InputError :message="form.errors.precipitation_threshold" class="mt-2" />
            </div>

            <div class="mt-4">
                <InputLabel for="uv_index_threshold" value="UV Index Threshold" />
                <TextInput v-model="form.uv_index_threshold" type="number" class="mt-1 block w-full" />
                <InputError :message="form.errors.uv_index_threshold" class="mt-2" />
            </div>

            
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
