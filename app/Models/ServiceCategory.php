<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    //
    protected $fillable = [
    'name',
    'description',
    'business_id',
];

public function services()
{
    return $this->hasMany(Service::class);
}

public function business()
{
    return $this->belongsTo(Business::class);
}

}
