<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    protected $fillable = ['subscription', 'thumbnail' ,'price'];

    public function details()
    {
        return $this->hasMany(SubscriptionDetails::class, 'subscription_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'subscription_id');
    }
}
