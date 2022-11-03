<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SuperAdminAccessOnly
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if(Auth::user() !== null || (Auth::user() && Auth::user()->isSuperAdmin())){
            return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
