<?php

namespace App\Http\Middleware;

use Closure;
use Dcat\Admin\Admin;
use Illuminate\Http\Request;
use Dcat\Admin\Http\Auth\Permission;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccessControl
{
    protected $denyMethods = ['POST', 'PUT', 'DELETE'];


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!config('app.BOTPRO_DEMO')){
            return $next($request); 
        }
        $excepts=[
            'POST' => [
                config('admin.route.prefix').'/auth/login',
                config('admin.route.prefix').'/form/step',
                config('admin.route.prefix').'/form',
                config('admin.route.prefix').'/dcat-api/value',
                config('admin.route.prefix').'/dcat-api/form',
            ],
        ];
        foreach ($excepts as $method => $route) {
            if ($request->isMethod($method) && $request->is(...$route)) {
                return $next($request);
            }
        }

        if (in_array($request->getMethod(), $this->denyMethods)) {
            try {
                Permission::error();
            } catch (HttpException $e) {
                return Admin::json()->error('对不起，演示站点不支持修改数据。')->send();
            }
        }
        return $next($request);
    }
}
