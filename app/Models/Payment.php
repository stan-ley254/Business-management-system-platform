<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
    'service_record_id',
    'method',
    'amount',
];

public function serviceRecord()
{
    return $this->belongsTo(ServiceRecord::class);
}

}
