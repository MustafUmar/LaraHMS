<?php

namespace App\Http\Middleware;

use Closure;

class AccountResetMiddleware
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
        if(!$user)
            return $next($request);
        if($user->isreset) {
            if($request->ajax())
                return response()->json(['message' => 'Account not active'], 401);
            return redirect('/account/inactive');
        }
        else
            return $next($request);
    }
}
