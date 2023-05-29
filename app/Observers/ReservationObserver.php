<?php

namespace App\Observers;

use App\Models\Reservation;

class ReservationObserver
{
    public function created(Reservation $reservation): void
    {
        $reservation->table->checkAvailabilityForDate($reservation->{Reservation::START});
    }

    public function deleted(Reservation $reservation): void
    {
        $reservation->table->checkAvailabilityForDate($reservation->{Reservation::START});
    }

}
