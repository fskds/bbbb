<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
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
		if (Auth::guard('admin')->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        }

		// 左侧菜单
        view()->composer('admin.layout', function($view) {
            $menus = \App\Models\Admin\Permission::with([
                'childs' => function($query){$query->with('icon');}
                ,'icon'])->where('parent_id',0)->orderBy('sort', 'desc')->get();
            $view->with('menus',$menus);
        });
        return $next($request);
    }
}
