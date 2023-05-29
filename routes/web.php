<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::controller(ReservationController::class)->group(function () {
        Route::post('/reservations', 'store')->name('reservations.store');
        Route::get('/reservations', 'index')->name('reservations.index');
        Route::delete('/reservations/{reservation}', 'delete')->name('reservations.delete');
        Route::get('/create-reservation', 'createReservation')->name('create-reservation');
        Route::get('/available-dates', 'getAvailableDates')->name('available-dates');
        Route::get('/available-times', 'getAvailableTimes')->name('available-times');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
