<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use app\Models\Role;

abstract class BaseModel extends Authenticatable
{
    protected static function booted()
    {
        static::addGlobalScope('business', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('business_id', auth()->user()->business_id);
            }
        });
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
