<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'invoice_id',
        'provider',
        'provider_reference',
        'type',
        'gross_amount',
        'fee_amount',
        'net_amount',
        'currency',
        'base_currency',
        'exchange_rate',
        'base_gross_amount',
        'base_fee_amount',
        'base_net_amount',
        'status',
        'processed_at',
    ];

    protected function casts(): array
    {
        return ['processed_at' => 'datetime'];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }
}
