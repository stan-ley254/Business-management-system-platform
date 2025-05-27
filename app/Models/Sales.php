<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Model\Business;

class Sales extends Model
{
    //
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
 public function business(){
    return $this->belongsTo(Business::class);
 }
}
