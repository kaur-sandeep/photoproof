<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public const BILLING_CYCLES = ['monthly', 'yearly'];

    protected $fillable = [
        'name',
        'photo_limit',
        'price',
        'billing_cycle',
        'state'
    ];
}
