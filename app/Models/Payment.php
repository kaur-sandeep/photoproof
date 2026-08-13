<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'payment_method', 'transaction_reference', 'amount', 'payment_date', 'status', 'notes'];
    protected $casts = ['amount' => 'decimal:2', 'payment_date' => 'datetime'];
    public function order() { return $this->belongsTo(Order::class); }
}
