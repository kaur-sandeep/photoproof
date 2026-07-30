<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
     protected $fillable = [
        'organization_name',
        'business_type',
        'owner_name',
        'organization_email',
        'organization_code',
        'mobile_number',
        'password',
        'subscription_plan',
        'state',
        'created_by'
        
    ];
}
