<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadedSale extends Model
{
    protected $fillable = ['business_id', 'file_path'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}

