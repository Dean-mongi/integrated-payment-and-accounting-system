<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reconciliation extends Model
{
    protected $fillable = [
        'bank_deposit_id',
        'date',
        'bank_total',
        'processor_total',
        'invoice_total',
        'discrepancy_amount',
        'status',
        'expected_net_amount',
        'difference',
        'flagged_items',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'flagged_items' => 'array',
        ];
    }

    public function bankDeposit(): BelongsTo
    {
        return $this->belongsTo(BankDeposit::class);
    }
}
