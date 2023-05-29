<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FullTable extends Model
{
    use HasFactory;

    const DATE = 'date';
    const TABLE_ID = 'table_id';

    protected $fillable = [
        self::DATE,
        self::TABLE_ID,
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
