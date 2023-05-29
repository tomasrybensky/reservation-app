<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Reservation::START => now()
                ->addDay()
                ->setTime(config('reservations.opening_hours.from'), 0),

            Reservation::END => now()
                ->addDay()
                ->setTime(config('reservations.opening_hours.from'), 0)
                ->addHours(config('reservations.reservation_duration')),

            Reservation::TABLE_ID => Table::factory()->create(),
            Reservation::USER_ID => User::factory()->create(),
            Reservation::GUESTS_COUNT => 2,
        ];
    }
}
