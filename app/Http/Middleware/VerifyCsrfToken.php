<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/passenger/login', // Add the login route here
        'api/passenger/register',
        'api/driver/login',
        'api/driver/register',
        'api/admin/register',
        'api/admin/login',

    ];
}
