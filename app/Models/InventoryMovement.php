<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Product;

class InventoryMovement extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryMovementFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'movement_type',  // e.g. 'purchase', 'sale', 'return', 'adjustment'
        'quantity',
        'previous_stock',
        'new_stock',
        'source_id',      // id of supplier_invoice_item or sale item
        'source_type',    // polymorphic: 'App\Models\SupplierInvoiceItem', etc.
        'notes',
        'business_id'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->business_id = auth()->user()->business_id;
            }
        });
    }

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function source()
    {
        return $this->morphTo();
    }
}
