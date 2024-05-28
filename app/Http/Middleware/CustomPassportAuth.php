<?php

namespace App\Http\Middleware;

require_once __DIR__ . '/../../Exceptions/CustomException.php';

use App\Exceptions\Unauthorized;
use Closure;
use Illuminate\Support\Facades\Auth;

class CustomPassportAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::guard('api')->check()) {
            return $next($request);
        }
        throw new Unauthorized("You are not allowed to access this API");
    }
}
