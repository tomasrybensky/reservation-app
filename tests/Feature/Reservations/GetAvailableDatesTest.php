<?php

namespace Tests\Feature\Reservations;

use App\Models\FullTable;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAvailableDatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_available_dates(): void
    {
        $user = User::factory()->create();
        Table::factory([Table::SEATS_COUNT => 5])->create();

        $response = $this
            ->actingAs($user)
            ->getJson(route('available-dates', [
                'guests_count' => 5
            ]));

        $response->assertSuccessful();

        $dates = json_decode($response->getContent(), true)['data']['availableDates'];

        $date = now();
        $limit = now()->addDays(config('reservations.allowed_days_to_future'));

        while ($date < $limit) {
            $this->assertTrue(in_array($date->toDateString(), $dates));
            $date->addDay();
        }
    }

    public function test_get_available_dates_full_day_is_not_available(): void
    {
        $user = User::factory()->create();
        $table = Table::factory([Table::SEATS_COUNT => 5])->create();
        $unavailableDate = now()->addDay()->toDateString();

        FullTable::factory()->create([
            FullTable::TABLE_ID => $table->{Table::ID},
            FullTable::DATE => $unavailableDate
        ]);

        $response = $this
            ->actingAs($user)
            ->getJson(route('available-dates', [
                'guests_count' => 5
            ]));

        $response->assertSuccessful();

        $dates = json_decode($response->getContent(), true)['data']['availableDates'];
        $this->assertFalse(in_array($unavailableDate, $dates));
    }
}
