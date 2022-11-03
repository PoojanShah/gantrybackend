<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InstallationIdHeaderRequired
{

    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!$request->header('InstallationId')) {
            return response('Unauthorized. Specify InstallationId', Response::HTTP_UNAUTHORIZED);
        } elseif (!Customer::where('installation_id', '=', $request->header('InstallationId'))->first()) {
            return response('Unauthorized. Such installation id does not exists in system', Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
