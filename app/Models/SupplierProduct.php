<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Supplier;
use App\Models\Business;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'business_id',
        'supplier_product_name',
        'barcode',
        'default_cost_price',
        'description',
        'linked_product_id',
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

    // 🧩 RELATIONSHIPS

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // Optional link to internal product
    public function linkedProduct()
    {
        return $this->belongsTo(Product::class, 'linked_product_id');
    }
}
