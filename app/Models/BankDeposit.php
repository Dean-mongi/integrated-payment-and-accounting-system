<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BankDeposit extends Model
{
    protected $fillable = [
        'processor',
        'deposit_reference',
        'amount',
        'currency',
        'exchange_rate',
        'base_amount',
        'deposited_at',
    ];

    protected function casts(): array
    {
        return ['deposited_at' => 'date'];
    }

    public function reconciliation(): HasOne
    {
        return $this->hasOne(Reconciliation::class);
    }
}
