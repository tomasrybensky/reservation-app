<?php

namespace Database\Factories;

use App\Models\FullTable;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class FullTableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            FullTable::DATE => now()->addDay()->toDateString(),
            FullTable::TABLE_ID => Table::factory()->create(),
        ];
    }
}
