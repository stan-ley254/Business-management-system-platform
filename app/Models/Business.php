<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Product;
use App\Models\Sales;

class Business extends Model
{
    //

    protected $fillable = [
        'name'
            ];

                public function users() { return $this->hasMany(User::class); }
                public function products() { return $this->hasMany(Product::class); }
                public function sales() { return $this->hasMany(Sales::class); }
                // etc.
            
                public function owner()
                {
                    return $this->hasMany(User::class);
                }

            
            
}
