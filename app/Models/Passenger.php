<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Passenger extends Model implements Authenticatable
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
        'remember_token',
        'created-at',
        'updated-at'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];  

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
