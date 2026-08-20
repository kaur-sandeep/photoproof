<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopupPlan extends Model
{
    protected $fillable = ['name', 'code', 'photo_quantity', 'price', 'state'];
    protected $casts = ['price' => 'decimal:2', 'state' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('state', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
