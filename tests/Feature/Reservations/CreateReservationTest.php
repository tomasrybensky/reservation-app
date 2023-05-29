<?php

namespace Tests\Feature\Reservations;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_reservation(): void
    {
        $user = User::factory()->create();
        $table = Table::factory([Table::SEATS_COUNT => 2])->create();

        $this->assertDatabaseMissing('reservations', [
            Reservation::TABLE_ID => $table->{Table::ID},
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('reservations.store', [
                'guests_count' => 2,
                'datetime' => now()
                    ->setTime(config('reservations.opening_hours.from'), 0)
                    ->toDateTimeString()
            ]));

        $response->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            Reservation::TABLE_ID => $table->{Table::ID},
        ]);
    }

    public function test_create_reservation_no_table(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('reservations.store', [
                'guests_count' => 2,
                'datetime' => now()
                    ->setTime(config('reservations.opening_hours.from'), 0)
                    ->toDateTimeString()
            ]));

        $response->assertSessionHasErrors();
    }

    public function test_create_reservation_invalid_time(): void
    {
        $user = User::factory()->create();
        $table = Table::factory([Table::SEATS_COUNT => 2])->create();

        $this->assertDatabaseMissing('reservations', [
            Reservation::TABLE_ID => $table->{Table::ID},
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('reservations.store', [
                'guests_count' => 2,
                'datetime' => now()
                    ->setTime(config('reservations.opening_hours.from'), 0)
                    ->subHour()
                    ->toDateTimeString()
            ]));

        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('reservations', [
            Reservation::TABLE_ID => $table->{Table::ID},
        ]);
    }
}
