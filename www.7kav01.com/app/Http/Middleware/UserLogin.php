<?php
namespace App\Http\Middleware;
use Closure;
use Session;
use Cookie;

class UserLogin
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
		if(!empty(session('openid'))){
			if(!is_meb_login()){
				Cookie::queue(Cookie::forget('meb_auth'));
				Cookie::queue(Cookie::forget('meb_auth_sign'));
				return redirect('/logout');
			}
			if(is_freeze(session('openid'))!=0)return redirect('/logout');
		}
		
		//这里存放中间件验证之前执行的代码
        $response=$next($request);
        
		//这里存放经过中间件验证之后执行的代码
        return $response;
    }
}