<?php

namespace App\Http\Middleware;

use Closure;

class Acl
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
        $whitelist = config('acl.whitelist');
        $route_name = $request->route()->getName();
        // dd($route_name);
        // if (!in_array($route_name, $whitelist)) {
        //     if (!auth()->user()->can($route_name)) {
        //         abort(403);
        //     }
        // }
        
        return $next($request);
    }
}
