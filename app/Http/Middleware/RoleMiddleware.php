<?php

namespace App\Http\Middleware;

require_once __DIR__ . '/../../Exceptions/CustomException.php';

use App\Exceptions\Unauthorized;
use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        /** @var Authenticatable $user */
        $user = auth('api')->user();

        if (is_null($user))
            throw new Unauthorized("You are not allowed to access this API");

        $user_role = $user->permission->role;
        $constroller = new Controller();
        $got_permission = $constroller->searchValueInArray($roles, $user_role)['data_found'];
        if (!$got_permission)
            throw new Unauthorized("You have insufficient permission to access this API");

        return $next($request);
    }
}
