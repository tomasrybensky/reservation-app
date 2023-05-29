<?php

namespace Tests\Feature\Reservations;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAvailableTimesTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_available_times(): void
    {
        $user = User::factory()->create();
        Table::factory([Table::SEATS_COUNT => 5])->create();

        $response = $this
            ->actingAs($user)
            ->getJson(route('available-times', [
                'date' => now()->addDay()->toDateString(),
                'guests_count' => 5
            ]));

        $response->assertSuccessful();

        $times = json_decode($response->getContent(), true)['data']['availableTimes'];

        $start = now()->addDay()->setTime(config('reservations.opening_hours.from'), 0);
        $limit = now()->addDay()->setTime(config('reservations.opening_hours.to'), 0)
            ->subHours(config('reservations.reservation_duration'));

        while ($start < $limit) {
            $this->assertTrue(in_array($start->toDateTimeString(), $times));
            $start->addHour();
        }
    }

    public function test_get_available_times_time_with_no_available_table_is_not_returned(): void
    {
        $user = User::factory()->create();
        $table = Table::factory([Table::SEATS_COUNT => 5])->create();

        Reservation::factory()->create([
            Reservation::TABLE_ID => $table->{Table::ID},
        ]);

        $response = $this
            ->actingAs($user)
            ->getJson(route('available-times', [
                'date' => now()->addDay()->toDateString(),
                'guests_count' => 5
            ]));

        $response->assertSuccessful();

        $times = json_decode($response->getContent(), true)['data']['availableTimes'];

        $start = now()->addDay()->setTime(config('reservations.opening_hours.from'), 0);
        $this->assertFalse(in_array($start->toDateTimeString(), $times));
    }
}
