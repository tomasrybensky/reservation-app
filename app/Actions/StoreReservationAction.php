<?php

namespace App\Actions;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class StoreReservationAction
{
    public function execute(Carbon $datetime, int $guestsCount): void
    {
        $this->validateDatetime($datetime);
        $table = $this->getTable($datetime, $guestsCount);

        if (!$table) {
            throw ValidationException::withMessages([
                'datetime' => __('No table available')
            ]);
        }

        Reservation::create([
            Reservation::START => $datetime,
            Reservation::END => $datetime->copy()->addHours(2),
            Reservation::USER_ID => auth()->user()->{User::ID},
            Reservation::TABLE_ID => $table->{Table::ID},
            Reservation::GUESTS_COUNT => $guestsCount
        ]);
    }

    protected function validateDatetime(Carbon $datetime): void
    {
        if ($datetime->hour < config('reservations.opening_hours.from')
            || $datetime->hour > config('reservations.opening_hours.to')) {
            throw ValidationException::withMessages([
                'datetime' => __('No table available')
            ]);
        }
    }

    protected function getTable(Carbon $datetime, int $guestsCount): ?Table
    {
        return Table::where(Table::SEATS_COUNT, '>=', $guestsCount)
            ->whereDoesntHave('reservations', function (Builder $builder) use ($datetime) {
                $builder
                    ->where(function (Builder $builder) use ($datetime) {
                        $builder
                            ->where(Reservation::START, '>=', $datetime)
                            ->where(Reservation::START, '<', $datetime->copy()->addHours(2));
                    })
                    ->orWhere(function (Builder $builder) use ($datetime) {
                        $builder
                            ->where(Reservation::END, '>', $datetime)
                            ->where(Reservation::END, '<=', $datetime->copy()->addHours(2));
                    });
            })
            ->orderBy(Table::SEATS_COUNT)
            ->first();
    }
}
