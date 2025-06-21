<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Other_Income extends Model
{
    //
     protected $fillable = ['business_id', 'name', 'amount', 'date'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
