<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cart extends Model
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
    protected $fillable = [
        'status',
              'session_id',
              'business_id'
      
          ];
          public function cartItems()
          {
              return $this->hasMany(CartItem::class);
          }
      public function user(){
          return $this->belongTo(User::class);
      }

}
