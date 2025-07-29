<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $fillable = [
    'name',
    'description',
    'price',
    'service_category_id',
    'business_id',
];

public function category()
{
    return $this->belongsTo(ServiceCategory::class, 'service_category_id');
}

public function serviceRecords()
{
    return $this->hasMany(ServiceRecord::class);
}

public function business()
{
    return $this->belongsTo(Business::class);
}

}
