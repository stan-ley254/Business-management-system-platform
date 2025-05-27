<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HeldCart extends Model
{
    //
    use HasFactory;
    protected $fillable = [
'cart_id',
'product_name',
'product_id',
'description',
'price',
'discount_price',
'quantity',
'active_price',
'business_id'

    ];

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
}
