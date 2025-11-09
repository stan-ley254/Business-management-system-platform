<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\{Business, SupplierInvoiceItem, Supplier};

class SupplierInvoice extends Model
{
    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'status',           // 'draft', 'finalized', 'cancelled'
        'total_cost',
        'notes',
        'business_id',
        'created_by'
    ];

    protected $casts = [
    'confirmed_at' => 'datetime',
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

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(SupplierInvoiceItem::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper: total recalculation
    public function recalcTotal()
    {
        $this->total_cost = $this->items->sum(fn($i) => $i->quantity * $i->unit_cost);
        $this->save();
    }
}
