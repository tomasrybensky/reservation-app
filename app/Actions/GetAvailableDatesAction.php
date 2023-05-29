<?php

namespace App\Actions;

use App\Models\FullTable;
use App\Models\Table;

class GetAvailableDatesAction
{
    public function execute(int $guestsCount): array
    {
        $from = now();
        $to = now()->addDays(config('reservations.allowed_days_to_future'));

        $days = [];
        $tables = Table::where(Table::SEATS_COUNT, '>=', $guestsCount)
            ->with('fullTables')->get();

        while ($from <= $to) {
            $isSomeTableAvailable = $tables->filter(function (Table $table) use ($from) {
                return $table->fullTables
                    ->where(FullTable::DATE, $from->toDateString())
                    ->isEmpty();
            })->isNotEmpty();

            if ($isSomeTableAvailable) {
                $days[] = $from->toDateString();
            }

            $from->addDay();
        }

        return $days;
    }
}
