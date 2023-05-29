<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {ref} from "vue";
import VueNumberInput from "@chenfengyuan/vue-number-input";

const form = useForm({
    date: '',
    datetime: '',
    guests_count: 0,
});

const submit = () => {
    form.post(route('reservations.store'), {
        onFinish: () => form.reset('date', 'datetime'),
    });
};

let availableDates = [];
let availableTimes = [];
let renderDatePicker = ref(true);
let renderTimePicker = ref(false);
const datepicker = ref(null);
const timepicker = ref(null);


function updateAvailableDates(guestCount)
{
    axios.get(route('available-dates', {
        guests_count: guestCount
    }))
    .then(
        response => {
            availableDates = response.data.data.availableDates

            // force date picker to refresh available dates and clear selected value
            renderDatePicker.value = false;
            renderTimePicker.value = false;

            if (availableDates.length > 0) {
                renderDatePicker.value = true;
                datepicker.value.clearValue();
            }
        }
    )
    .catch(error => console.log(error))
}

function updateAvailableTimes()
{
    if (datepicker.value && form.date && form.guests_count) {
        axios.get(route('available-times', {
            date: form.date,
            guests_count: form.guests_count
        }))
        .then(
            response => {
                availableTimes = response.data.data.availableTimes
                if (availableTimes.length > 0) {
                    renderTimePicker.value = true;
                }
            }
        )
    } else {
        renderTimePicker.value = false;
    }
}

</script>

<template>
    <Head title="Create reservation" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Reservation</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 min-h-screen">
                    <h1 class="text-xl text-gray-900 py-4">Create reservation</h1>
                    <div class="w-72">
                        <form @submit.prevent="submit">
                            <div class="mt-4">
                                <InputLabel for="guests_count" value="Number of guests" />

                                <vue-number-input
                                    id="guests_count"
                                    v-model="form.guests_count"
                                    required
                                    @update:model-value="updateAvailableDates(form.guests_count)"
                                    controls
                                    :min=1
                                />

                                <InputError class="mt-2" :message="form.errors.guests_count" />

                                <h1 v-if="!renderDatePicker" class="text-red-500">
                                    We can not offer you any date right now, please try again later,
                                </h1>
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    v-if="renderDatePicker"
                                    for="date"
                                    value="Date"
                                />

                                <VueDatePicker
                                    v-if="renderDatePicker"
                                    ref="datepicker"
                                    id="date"
                                    type="text"
                                    class="mt-1 border-none p-0"
                                    v-model="form.date"
                                    required
                                    autofocus
                                    autocomplete="datetime"
                                    :enable-time-picker=false
                                    :allowed-dates=availableDates
                                    format="dd. MM. yyyy"
                                    @update:model-value="updateAvailableTimes"
                                    @cleared="updateAvailableTimes"
                                />

                                <InputError class="mt-2" :message="form.errors.datetime" />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    v-if="renderTimePicker"
                                    for="time"
                                    value="Time"
                                />

                                <select v-model="form.datetime" v-if="renderTimePicker" ref="timepicker" class="w-72 rounded-md border-gray-300">
                                    <option v-for="availableTime in availableTimes" :value="availableTime">
                                        {{ new Date(availableTime).getHours() + ':00' }}
                                    </option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.datetime" />
                            </div>

                            <div v-if="renderTimePicker" class="flex items-center justify-start mt-8">
                                <PrimaryButton class="" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Book
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
