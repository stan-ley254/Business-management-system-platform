<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRecord extends Model
{
    //
    protected $fillable = [
    'client_id',
    'service_id',
    'user_id',
    'date',
    'notes',
];

public function client()
{
    return $this->belongsTo(Client::class);
}

public function service()
{
    return $this->belongsTo(Service::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function payment()
{
    return $this->hasOne(Payment::class);
}

}
