<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null; // Per API, meglio null (non redirect!)
        }

        // Per richieste web (non API), puoi mandare a /accedi
        return '/accedi';
    }

}
