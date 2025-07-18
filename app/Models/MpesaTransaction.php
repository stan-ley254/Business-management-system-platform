<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    //
    protected $fillable =[
        'business_id',
        'phone',
        'checkout_request_id',
        'merchant_request_id',
        'response_code',
        'response_description',
        'amount',
        'transcation_status',
        'raw_response'
    ];
}
