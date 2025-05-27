<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Debt extends Model
{
    //
    use HasFactory;
   

    protected $fillable = [
        'customer_id', 'amount', 'status','business_id'
    ];
    public function items()
    {
        return $this->hasMany(DebtItem::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($debt) {
            $debt->updateCustomerTotalDebt();
        });

        static::deleted(function ($debt) {
            $debt->updateCustomerTotalDebt();
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function updateCustomerTotalDebt()
    {
        $customer = $this->customer;
        $customer->total_debt = $customer->debts()->sum('amount');
        $customer->save();
    }
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
}
