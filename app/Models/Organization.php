<?php

namespace App\Models;
use App\Models\User;
use App\Models\OrganizationSubscriptions;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Model;
use App\Models\PhotoDetail;

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
        'created_by',
        'enable_photo_email',
        'organization_logo'
    ];


    public function users()
    {
        return $this->hasMany(User::class, 'organization_id', 'id');
    }

    public function subscription()
    {
        return $this->hasOne(OrganizationSubscriptions::class, 'organization_id', 'id');
    }
    public function subscriptions() { return $this->hasMany(OrganizationSubscriptions::class); }
    public function orders() { return $this->hasMany(Order::class); }
public function photoDetails(): HasManyThrough
{
    return $this->hasManyThrough(
        PhotoDetail::class,
        User::class,
        'organization_id', // Foreign key on users table
        'user_id',         // Foreign key on photo_details table -- confirm this column name
        'id',              // Local key on organizations table
        'id'               // Local key on users table
    );
}
    
}
