<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'logo_path',
        'type',
        'next_payment_due',
        'is_active',
        'mpesa_short_code',
        'mpesa_consumer_key',
        'mpesa_consumer_secret',
        'mpesa_passkey',
        'mpesa_initiator_name',
        'mpesa_security_credential',
    ];

    protected $casts = [
        'next_payment_due' => 'datetime',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function expense()
    {
        return $this->hasMany(Expense::class);
    }
    // etc.

    public function owner()
    {
        return $this->hasMany(User::class);
    }

    // Mutators for sanitization
    public function setProductNameAttribute($value)
    {
        $this->attributes['name'] = strip_tags($value);
    }

    public function setMpesaShortCodeAttribute($value)
    {
        $this->attributes['mpesa_short_code'] = strip_tags($value);
    }

    public function setMpesaConsumerKeyAttribute($value)
    {
        $this->attributes['mpesa_consumer_key'] = strip_tags($value);
    }

    public function setMpesaConsumerSecretAttribute($value)
    {
        $this->attributes['mpesa_consumer_secret'] = strip_tags($value);
    }

    public function setMpesaPassKeyAttribute($value)
    {
        $this->attributes['mpesa_passkey'] = strip_tags($value);
    }

    public function setMpesaInitiatorNameAttribute($value)
    {
        $this->attributes['mpesa_initiator_name'] = strip_tags($value);
    }

    public function setMpesaSecretCredentialAttribute($value)
    {
        $this->attributes['mpesa_secret_credential'] = strip_tags($value);
    }
}
