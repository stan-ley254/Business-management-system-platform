<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnOutward extends Model
{
    //
    protected $fillable = ['business_id', 'product_id', 'product_name', 'quantity', 'amount', 'date'];

}
