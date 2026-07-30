<?php

namespace App\Models;
use App\Models\User;
use App\Models\OrganizationSubscriptions;

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
        'message',
        'state',
        'created_by'
        
    ];


    public function users()
    {
        return $this->hasMany(User::class, 'organization_id', 'id');
    }

    public function subscription()
    {
        return $this->hasOne(OrganizationSubscriptions::class, 'organization_id', 'id');
    }
}
