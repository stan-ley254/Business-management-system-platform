<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Product;
use App\Models\Sales;

class Business extends Model
{
    //

    protected $fillable = [
        'name',
        'next_payment_due',
        'is_active',
        'mpesa_short_code',
        'mpesa_consumer_key',
        'mpesa_consumer_secret',
        'mpesa_passkey',
        'mpesa_initiator_name',
        'mpesa_security_credential'
            ];

            protected $casts = [
    'next_payment_due' => 'datetime',
];


                public function users() { return $this->hasMany(User::class); }
                public function products() { return $this->hasMany(Product::class); }
                public function sales() { return $this->hasMany(Sales::class); }
                // etc.
            
                public function owner()
                {
                    return $this->hasMany(User::class);
                }

            
            
}
