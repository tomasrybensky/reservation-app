<?php

namespace App\Actions;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Collection;

class GetUserReservationsAction
{
    public function execute(User $user): Collection
    {
        return $user->reservations->each(function (Reservation $reservation) {
            $reservation->formatted_time = $reservation->start->format('H:i');
        });
    }
}
