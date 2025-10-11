<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission = null)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

       
        if (!$permission) {
            $routeName  = $request->route()?->getName();
            $permission = function_exists('getPermissionFromRoute')
                ? getPermissionFromRoute($routeName)
                : null;
        }

       
        if (!$permission) {
            abort(403, 'Permission mapping missing for this route.');
        }

        if (!function_exists('hasPermission') || !hasPermission($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}