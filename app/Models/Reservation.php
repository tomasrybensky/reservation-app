<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    const ID = 'id';
    const USER_ID = 'user_id';
    const TABLE_ID = 'table_id';
    const START = 'start';
    const END = 'end';
    const GUESTS_COUNT = 'guests_count';

    protected $fillable = [
        self::START,
        self::END,
        self::USER_ID,
        self::TABLE_ID,
        self::GUESTS_COUNT,
    ];

    protected $casts = [
        self::START => 'datetime',
        self::END => 'datetime',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
