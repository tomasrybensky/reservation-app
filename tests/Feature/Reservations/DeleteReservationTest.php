<?php

namespace Tests\Feature\Reservations;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_reservation(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            Reservation::USER_ID => $user->{User::ID},
        ]);

        $this->assertDatabaseHas('reservations', [
            Reservation::ID => $reservation->{Reservation::ID}
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('reservations.delete', [
                'reservation' => $reservation->{Reservation::ID}
            ]));

        $response->assertRedirect();

        $this->assertDatabaseMissing('reservations', [
            Reservation::ID => $reservation->{Reservation::ID}
        ]);
    }

    public function test_delete_reservation_unauthorized(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create();

        $this->assertDatabaseHas('reservations', [
            Reservation::ID => $reservation->{Reservation::ID}
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('reservations.delete', [
                'reservation' => $reservation->{Reservation::ID}
            ]));

        $response->assertUnauthorized();

    }
}
