<?php

namespace App\Http\Middleware;

use App\Traits\ApiTrait;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    use ApiTrait;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $this->expectsJson($request)) {
            return route('login');
        }
    }
}
