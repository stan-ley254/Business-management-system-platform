<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Product;
class ProductImportLog extends Model
{
    //
    protected $fillable = [
        'business_id',
        'product_name',
        'quantity_added',
         'cost_price',
        'imported_by',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
