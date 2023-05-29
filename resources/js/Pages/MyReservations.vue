<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    reservations: {
        type: Array,
        required: true,
    },
});

function deleteReservation(id)
{
    router.delete(route('reservations.delete', {
        'reservation': id
    }))
}

</script>

<template>
    <Head title="Create reservation" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My reservations</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 min-h-screen">
                    <h1 class="text-xl text-gray-900 py-4">My reservations</h1>
                    <div class="py-4" v-for="reservation in reservations">
                        <h1 class="text-md">{{ new Date(reservation.start).toDateString() }} {{ reservation.formatted_time }} - Number of guests: {{ reservation.guests_count }}</h1>
                        <PrimaryButton @click="deleteReservation(reservation.id)">
                            Delete
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
