<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ZohoHookAuthenticated
{

    public function handle(Request $request, Closure $next, ...$guards)
    {
        if($request->header('Zoho-Authorization-Token') !== config('zoho.ZOHO_TOKEN')){
            return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
