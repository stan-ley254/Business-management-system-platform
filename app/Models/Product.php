<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\{Business, CartItem, User, SupplierInvoiceItem, InventoryMovement};

class Product extends Model
{
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
        'cost_price',       // average or latest purchase cost
        'price',            // selling price
        'discount_price',
        'quantity',         // stock on hand
        'barcode',
        'category',
        'business_id'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(SupplierInvoiceItem::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    // Sanitization Mutators
    public function setProductNameAttribute($value)
    {
        $this->attributes['product_name'] = strip_tags($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strip_tags($value);
    }

    public function setCategoryAttribute($value)
    {
        $this->attributes['category'] = strip_tags($value);
    }
}
