<?php
namespace App\Http\Middleware;
use Closure;
use Cookie;
class CheckLogin
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
		if(!is_admin_login()){
			Cookie::queue(Cookie::forget('admin_auth'));
			Cookie::queue(Cookie::forget('admin_auth_sign'));
			return redirect()->route('login');
		}
		
		//这里存放中间件验证之前执行的代码
        $response=$next($request);
        
		//这里存放经过中间件验证之后执行的代码
        return $response;
    }
}