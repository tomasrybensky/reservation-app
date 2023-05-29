<?php

namespace App\Actions;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class GetAvailableTimesAction
{
    public function execute(
        Carbon $date,
        int $guestsCount,
        ?Collection $tables = null
    ): array
    {
        $from = $date->copy()->setTime(config('reservations.opening_hours.from'), 0);
        $to = $date->copy()->setTime(config('reservations.opening_hours.to'), 0);
        $times = [];
        $interval = config('reservations.reservation_duration');

        $tables = $tables ?? $this->getTables($date, $guestsCount);

        while ($from < $to && $from->diffInHours($to) >= $interval) {
            $isSomeTableFree = $tables->filter(function (Table $table) use ($from, $to, $interval) {
                return
                    $table->reservations
                        ->where(Reservation::START, '>=', $from)
                        ->where(Reservation::START, '<', $from->copy()->addHours($interval))
                        ->isEmpty()

                    &&

                    $table->reservations
                        ->where(Reservation::END, '>', $from)
                        ->where(Reservation::END, '<=', $from->copy()->addHours($interval))
                        ->isEmpty();
            })->isNotEmpty();

            if ($isSomeTableFree) {
                $times[] = $from->toDateTimeString();
            }

            $from->addHour();
        }

        return $times;
    }

    protected function getTables(Carbon $date, int $guestsCount): Collection
    {
        return Table::where(Table::SEATS_COUNT, '>=', $guestsCount)
            ->with('reservations', function (HasMany $builder) use ($date) {
                $builder->whereDate(Reservation::START, $date->toDateString());
            })
            ->get();
    }
}
