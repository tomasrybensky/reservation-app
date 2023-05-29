<?php

namespace App\Models;

use App\Actions\GetAvailableTimesAction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    const ID = 'id';
    const SYSTEM_NAME = 'system_name';
    const SEATS_COUNT = 'seats_count';

    protected $fillable = [
        self::SYSTEM_NAME,
        self::SEATS_COUNT,
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function fullTables(): HasMany
    {
        return $this->hasMany(FullTable::class);
    }

    public function checkAvailabilityForDate(Carbon $date): void
    {
        $availableTimes = app(GetAvailableTimesAction::class)->execute(
            $date,
            $this->{self::SEATS_COUNT},
            collect([$this->load('reservations')])
        );

        if (empty($availableTimes)) {
            FullTable::firstOrCreate([
                FullTable::DATE => $date->toDateString(),
                FullTable::TABLE_ID => $this->{self::ID},
            ]);
        } else {
            FullTable::where(FullTable::DATE, $date->toDateString())
                ->where(FullTable::TABLE_ID, $this->{self::ID})
                ->delete();
        }
    }
}
