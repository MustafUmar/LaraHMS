<?php

namespace App\Http\Middleware;

use Closure;

class AccountantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if($user->role == 'Account' && $user->active  && !$user->isreset) {
            return $next($request);
        }

        else {
            if($request->ajax())
                return response()->json(['message' => 'Unauthorized'], 401);
            else
                return redirect('/404');
        }
    }
}
