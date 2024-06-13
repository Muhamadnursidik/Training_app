<?php
namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Redirect;

class PasswordExpired
{

    public function handle($request, Closure $next)
    {
        
        $user = $request->user();
        $password_expired = config('auth.password_expires_days');
        $request->attributes->add(['password_expired' => false,'password_reset' => false]);
        
        if(isset($user->created_at)){
            $password_changed_at = new Carbon(isset($user->password_created) ? $user->password_created : $user->created_at);
            if (Carbon::now()->diffInDays($password_changed_at) >= $password_expired) {    
                $request->attributes->add(['password_expired' => true]);
            }

            if($user->reset_password == true){
                $request->attributes->add([
                    'password_expired' => true,
                    'password_reset' => true
                ]);
            }

            if(empty($user->password_created)){
                $request->attributes->add(['password_expired' => true]);
            }
        }

        return $next($request);
    }
}