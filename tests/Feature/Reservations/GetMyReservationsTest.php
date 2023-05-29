<?php

namespace Tests\Feature\Reservations;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetMyReservationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_my_reservations(): void
    {
        $user = User::factory()->create();

        // first reservation - should be returned
        Reservation::factory()->create([
            Reservation::USER_ID => $user->{User::ID}
        ]);

        // second reservation - should not be returned
        Reservation::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('reservations.index'));

        $response->assertInertia(fn ($page) => $page
            ->has('reservations', 1)
        );;
    }
}
