<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Schema;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_iso_code',
        'buying_rate',
        'selling_rate',
        'average_rate',
        'application_date',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_iso_code', 'iso_code');
    }

    public static function getTableName(): string
    {
        return with(new static())->getTable();
    }

    public static function getColumnNames(): array
    {
        return Schema::getColumnListing(self::getTableName());
    }
}
