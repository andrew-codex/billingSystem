<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class ForcePasswordChange
{
 public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

     
        if ($user && $user->must_change_password) {
        
            if (! $request->routeIs('password.change') && ! $request->routeIs('password.change.update')) {
                return redirect()->route('password.change')
                    ->with('warning', 'You must change your password before continuing.');
            }
        }

        return $next($request);
    }

}
