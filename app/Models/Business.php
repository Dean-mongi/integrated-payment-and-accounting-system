<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'name',
        'tagline',
        'email',
        'phone',
        'tax_number',
        'address',
        'base_currency',
    ];
}
