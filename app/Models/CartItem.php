<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CartItem extends Model
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
        'cart_id',
        'product_id',
        'product_name',
        'description',
        'price',
        'discount_price',
        'quantity',
        'active_price',
        'business_id'
    ];
        
        
        public function cart()
        {
            return $this->belongsTo(Cart::class);
        }
    
        public function item()
        {
            return $this->belongsTo(Product::class);
            
        }
       
}
