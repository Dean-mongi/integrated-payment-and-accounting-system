<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FxRate extends Model
{
    protected $fillable = ['from_currency', 'to_currency', 'rate', 'quoted_at'];

    protected function casts(): array
    {
        return ['quoted_at' => 'datetime'];
    }
}
