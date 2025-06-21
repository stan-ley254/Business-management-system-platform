<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Business;
use App\Models\CartItem;
use App\Models\User;

class Product extends Model
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

protected $fillable = [
    'product_name',
    'description',
     'cost_price',
    'price',
    'discount_price',
    'quantity',
    'category',
    'business_id'
    
        ];
        public function user(){
            return $this->belongsTo(User::class);
        }
        public function cartItems()
        {
            return $this->hasMany(CartItem::class);
        }
        public function business(){
            return $this->belongsTo(Business::class);
        }
}
