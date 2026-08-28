<?php

namespace App\Models;

use App\Model\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = [
        'business_id',
        'cart_id',
        'product_name',
        'description',
        'price',
        'active_price',
        'price_per_item',
        'discount_price',
        'quantity',
        'total',
        'payment_status',
        'status',
        'restored_at',
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

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
