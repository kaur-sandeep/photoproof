<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
class AdminActivityLog extends Model
{
   protected $fillable = [
    'admin_id',
    'action',
    'module',
    'description',
    'ip_address',
];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
