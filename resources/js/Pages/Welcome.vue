<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('hidden');
}
</script>

<template>

    <Head title="Welcome" />
    <AuthenticationCard>


        <div
            class="min-h-screen flex items-center justify-center bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
            <div class="max-w-4xl mx-auto p-6 sm:p-8 bg-white shadow-lg rounded-lg dark:bg-gray-800">
                <header class="text-center mb-8  flex flex-col items-center mb-8">
                    <AuthenticationCardLogo />
                    <h1 class="text-4xl font-bold">Weather Notifier App</h1>
                    <p class="text-lg text-gray-500">Best Weather Notifier</p>
                </header>

                <div class="flex justify-center space-x-4">
                    <PrimaryButton class="ms-4">
                        <Link v-if="canLogin" :href="route('login')">Log in</Link>
                    </PrimaryButton>
                    <SecondaryButton class="ms-4">
                        <Link v-if="canRegister" :href="route('register')">Register</Link>
                    </SecondaryButton>
                    
                </div>
            </div>
        </div>
    </AuthenticationCard>
</template>


