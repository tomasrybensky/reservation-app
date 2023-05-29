<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TablesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Table::firstOrCreate([Table::SYSTEM_NAME => 'table1', Table::SEATS_COUNT => 2]);
        Table::firstOrCreate([Table::SYSTEM_NAME => 'table3', Table::SEATS_COUNT => 4]);
        Table::firstOrCreate([Table::SYSTEM_NAME => 'table5', Table::SEATS_COUNT => 8]);
    }
}
