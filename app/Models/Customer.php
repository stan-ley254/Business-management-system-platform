<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    //
    use HasFactory;
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
use HasFactory;
protected $fillable = [
    'customer_name', 'phone_number', 'location', 'total_debt'
];

public function debts()
{
    return $this->hasMany(Debt::class);
}
}
