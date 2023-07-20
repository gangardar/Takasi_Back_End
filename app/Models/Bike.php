<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bike extends Model
{
    use HasFactory;

    protected $fillable =[
        'driver_id',
        'manufacturer',
        'model',
        'year',
        'lincencePlateNumber',
        'lincenceDocument',
        'frontPic',
        'rightPic',
        'backPic',
        'leftPic',
        
    ];

    protected $hidden =[
        'created-at',
        'updated-at'
    ];

    public function driver(): BelongsTo
{
    return $this->belongsTo(Driver::class, 'foreign_key');
}


}
