<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationPhotoUsage extends Model
{
    protected $table = 'organization_photo_usage';
    protected $fillable = ['organization_id', 'subscription_id', 'user_id', 'photo_id', 'usage_type', 'usage_date', 'photo_count'];
    protected $casts = ['usage_date' => 'date'];
    public function organization() { return $this->belongsTo(Organization::class); }
    public function subscription() { return $this->belongsTo(OrganizationSubscriptions::class, 'subscription_id'); }
    public function user() { return $this->belongsTo(User::class); }
    public function photo() { return $this->belongsTo(PhotoDetail::class, 'photo_id'); }
}
