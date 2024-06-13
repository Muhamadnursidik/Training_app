<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckDashboard
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
        $active_route = $request->route()->getName();
        $active_dashboard = Auth::user()->active_dashboard;
        if (empty($active_dashboard)) {
            return redirect()->route('set_dashboard');
        } else {
            if (!preg_match("/^app.{$active_dashboard}/", $active_route)) {
                abort(404);
            }
        }
        return $next($request);
    }
}
