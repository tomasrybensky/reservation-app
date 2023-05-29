<?php

namespace App\Http\Controllers;

use App\Actions\GetAvailableTimesAction;
use App\Actions\GetUserReservationsAction;
use App\Actions\StoreReservationAction;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Http\Requests\Reservation\GetAvailableDatesRequest;
use App\Http\Requests\Reservation\GetAvailableTimesRequest;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Actions\GetAvailableDatesAction;

class ReservationController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('MyReservations', [
            'reservations' => app(GetUserReservationsAction::class)->execute(
                auth()->user()
            )
        ]);
    }

    public function createReservation(): InertiaResponse
    {
        return Inertia::render('CreateReservation');
    }

    public function store(CreateReservationRequest $request)
    {
        app(StoreReservationAction::class)->execute(
            Carbon::parse($request->datetime),
            $request->guests_count
        );

        return to_route('reservations.index');
    }

    public function getAvailableDates(GetAvailableDatesRequest $request): JsonResponse
    {
        return response()->json([
            'data' => [
                'availableDates' => app(GetAvailableDatesAction::class)->execute(
                    $request->guests_count
                )
            ],
        ]);
    }

    public function getAvailableTimes(GetAvailableTimesRequest $request): JsonResponse
    {
        return response()->json([
            'data' => [
                'availableTimes' => app(GetAvailableTimesAction::class)->execute(
                    Carbon::parse($request->date),
                    $request->guests_count
                )
            ],
        ]);
    }

    public function delete(Reservation $reservation): RedirectResponse
    {
        if (!auth()->user()->can('delete', $reservation)) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $reservation->delete();
        return back();
    }
}
