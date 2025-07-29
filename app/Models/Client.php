<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $fillable = [
    'name',
    'phone',
    'email',
    'business_id',
];

public function serviceRecords()
{
    return $this->hasMany(ServiceRecord::class);
}

public function business()
{
    return $this->belongsTo(Business::class);
}

}
