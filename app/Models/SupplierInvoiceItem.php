<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Product, SupplierProduct, SupplierInvoice, InventoryMovement};

class SupplierInvoiceItem extends Model
{
    protected $fillable = [
        'supplier_invoice_id',
        'supplier_product_id',
        'product_id',
        'quantity',
        'cost_price',
        'subtotal',
        'business_id',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->business_id = auth()->user()->business_id;
            }
        });
    }

    /** Relationships **/

    public function invoice()
    {
        return $this->belongsTo(SupplierInvoice::class, 'supplier_invoice_id');
    }

    public function supplierProduct()
    {
        return $this->belongsTo(SupplierProduct::class, 'supplier_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function inventoryMovement()
    {
        return $this->hasOne(InventoryMovement::class, 'source_id')
                    ->where('source_type', self::class);
    }

    /** Auto subtotal calculation **/
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = $value;

        $cost = $this->attributes['cost_price'] ?? 0;
        $this->attributes['subtotal'] = $cost * $value;
    }
}
