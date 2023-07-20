<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable =[

        'name',
        'lincenceNo',
        'phone',
        'photo',
        'email',
        'status',
        'drivingLincence',
        'password',
        'current_latitude',
        'current_longitude'
    ];

    protected $hidden =[
        'password',
        'created-at',
        'updated-at'
    ];

    protected $casts= [
        'password' => 'hashed'
    ];

    public function bike(): HasOne
    {
        return $this->hasOne(Bike::class);
    }
}
