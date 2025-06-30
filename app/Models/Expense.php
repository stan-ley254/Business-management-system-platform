<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;

class Expense extends Model
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
    //
     protected $fillable = ['business_id', 'name', 'amount', 'date'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
