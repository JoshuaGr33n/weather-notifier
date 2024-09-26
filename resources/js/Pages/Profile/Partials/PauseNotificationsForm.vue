<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { formatDistanceToNow } from 'date-fns'; 
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    notification_paused_until: String, // Receive the notification_paused_until from the backend
});

// Reactive form data
const form = useForm({
    pause_duration: 1, // Default value for pause duration
});

// Function to handle pause notifications
const pauseNotifications = () => {
    const duration = Number(form.pause_duration); // Convert to integer
    if (duration >= 1) {
        form.post(route('pauseNotifications'), {
            data: { pause_duration: duration },
            onSuccess: () => {
                form.reset('pause_duration'); // Reset the input field after success
            },
            onError: () => {
                console.log("Error occurred while pausing notifications.");
            }
        });
    } else {
        console.log("Pause duration must be at least 1 hour.");
    }
};

// Function to handle unpausing notifications
const restoreNotifications = () => {
    form.post(route('restoreNotifications'), {
        onSuccess: () => {
            console.log("Notifications restored successfully.");
        },
        onError: () => {
            console.log("Error occurred while unpausing notifications.");
        }
    });
};

// Computed property to calculate the time until pause expiration
const timeUntilExpiration = computed(() => {
    if (props.notification_paused_until) {
        const pausedUntil = new Date(props.notification_paused_until);
        if (pausedUntil > new Date()) {
            return formatDistanceToNow(pausedUntil, { addSuffix: true });
        }
    }
    return null;
});
</script>



<template>
    <AppLayout title="Notifications">
        <template #header>
            <h2 class="text-lg font-medium text-gray-900">Pause Notifications</h2>

            <!-- Display current time and expiration time if pause is active -->
            <div v-if="timeUntilExpiration">
                <p class="text-sm text-gray-600">
                    Notifications are currently paused until {{ new Date(props.notification_paused_until).toLocaleString() }} ({{ timeUntilExpiration }}).
                </p>

                <!-- Button to restore notifications -->
                <PrimaryButton @click="restoreNotifications" class="mt-3">
                    Restore Notifications
                </PrimaryButton>
            </div>

            <div v-else>
                <p class="text-sm text-gray-600">
                   No paused notification
                </p>
            </div>
        </template>

        <div>
            <FormSection @submitted="pauseNotifications" class="mt-4 mr-20">
                <template #form>
                    <div class="col-span-6 sm:col-span-4">
                        <InputLabel for="pause_duration" value="Pause for (hours):" />
                        <TextInput id="pause_duration" v-model="form.pause_duration" type="number" min="1" class="mt-1 block w-full" />
                        <InputError :message="form.errors.pause_duration" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <ActionMessage :on="form.recentlySuccessful" class="me-3">
                        Notifications paused successfully.
                    </ActionMessage>

                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Pause Notifications
                    </PrimaryButton>
                </template>
            </FormSection>
        </div>
    </AppLayout>
</template>
