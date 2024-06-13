<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UnlockAppconfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $unlocked_appconfig = session('unlocked_appconfig');

        if ($unlocked_appconfig) {
            return $next($request);
        }

        return redirect()->route('tools.appconfig.unlock');
    }
}
