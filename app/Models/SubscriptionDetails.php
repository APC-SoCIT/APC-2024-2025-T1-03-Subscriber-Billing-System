<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionDetails extends Model
{
    use HasFactory;
    protected $table = 'subscriptiondetails';

    protected $fillable = ['subscription_id', 'details'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    
}
