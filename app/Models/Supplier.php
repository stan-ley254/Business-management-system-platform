<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Business;
use App\Models\SupplierProduct;
use App\Models\SupplierInvoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'phone_number',
        'description',
        'amount',
        'balance',
        'status',
        'location',
        'business_id',
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

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class);
    }

    // 🧠 Potential future link: invoices created from supplier transactions
    public function invoices()
    {
        return $this->hasMany(SupplierInvoice::class);
    }

    // 🧹 SANITIZERS
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
