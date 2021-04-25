<?php

namespace App\Http\Middleware;

use App\BotPro\Core;
use Closure;
use Illuminate\Http\Request;

class BotAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $core = new Core();
        if(!$core->ver()){
            if(!$request->is(config('admin.route.prefix').'/auth',config('admin.route.prefix').'/auth/*',config('admin.route.prefix').'/Core/auth')){
                return redirect(config('admin.route.prefix').'/auth');
            }
            return $next($request);
        }else{
            return $next($request);
        }
        
    }
}
