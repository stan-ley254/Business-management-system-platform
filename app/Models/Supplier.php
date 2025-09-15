<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    //
    protected static function booted()
{
    static::addGlobalScope('business', function (Builder $builder) {
        if (auth()->check()) {
            $builder->where('business_id', auth()->user()->business_id);
        }
    });

    static::creating(function ($model) {
        if (auth()->check()) {
            $model->business_id = auth()->user()->business_id;
        }
    });
}
use HasFactory;
protected $fillable = [
'supplier_name',
'phone_number',
'description',
'amount',
'balance',
'status',
'location',
'business_id'

];
public function setSupplierNameAttribute($value)
    {
        $this->attributes['supplier_name'] = strip_tags($value);
    }
    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = strip_tags($value);
    }
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strip_tags($value);
    }
    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = strip_tags($value);
    }
}
