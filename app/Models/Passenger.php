<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Passenger extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'photo',
        'phone',
        'email',
        'password',
        'status',
        'accountStatus',
        'current_latitude',
        'current_longitude'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($passenger) {
            $baseSlug =Str::slug($passenger->name);
            $slug = $baseSlug;

            $counter = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $passenger->slug = $slug;
        });

    }

    protected $hidden=[
        'password',
        'created-at',
        'updated-at'
    ];

    protected $casts=[
        'password' => 'hashed'
    ];    


}
