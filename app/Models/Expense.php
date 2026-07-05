<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'supplier_id',
        'category',
        'description',
        'amount',
        'currency',
        'payment_method',
        'receipt_path',
        'spent_at',
    ];

    protected function casts(): array
    {
        return ['spent_at' => 'date'];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
