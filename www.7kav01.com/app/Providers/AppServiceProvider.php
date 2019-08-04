<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts/mb','layouts/mb_1','layouts/pc','layouts/pc_1'], function ($view) {
			$action = Request::route()->getAction();
			$as = !empty($action['as']) ? $action['as'] : '';
			
			$sub = '';
			if($as == 'list' || $as == 'pc_list'){
				$sub = '视频列表';
				if(!empty($_GET['key']))
					$sub = $_GET['key'].'搜索列表';
				if(!empty($_GET['type']))
					$sub = Cachekey('type')[$_GET['type']].'列表';
				if(!empty($_GET['tag']))
					$sub = '标签筛选列表';
			}
			
			$view->with([
				'title' => tpCache('para.title'),'keywords' => tpCache('para.keywords'),
				'description' => tpCache('para.description'),'sub'=>$sub
			]);
		});
		
		View::composer(['moudel/pc_header'], function ($view) {
			$last = DB::table('notice')->orderBy('id','desc')->select('id')->first();
			$toRead = 0;
			$notice_id = !empty($last) ? $last['id'] : 0;
			if(!empty(session('openid'))){
				$user = DB::table('member')->where('openid',session('openid'))->select('user','level','credit','notice_id')->first();
				if($notice_id > $user['notice_id'])
					$toRead = DB::table('notice')->where([['id','>',$user['notice_id']]])->count();
			}else{
				$user = [];
			}
			$action = Request::route()->getAction();
			$as = !empty($action['as']) ? $action['as'] : '';
			$ad = trans_json(tpCache('ad.pc_header'));
			
			$view->with(['type'=>Cachekey('type'),'tag'=>Cachekey('tag'),'toRead'=>$toRead,'user'=>$user,'ad'=>$ad,'as'=>$as]);
			
        });
		
		View::composer(['moudel/header'], function ($view) {
			$last = DB::table('notice')->orderBy('id','desc')->select('id')->first();
			$toRead = 0;
			$notice_id = !empty($last) ? $last['id'] : 0;
			if(!empty(session('openid'))){
				$user = DB::table('member')->where('openid',session('openid'))->select('level','notice_id')->first();
				if($notice_id > $user['notice_id'])
					$toRead = DB::table('notice')->where([['id','>',$user['notice_id']]])->count();
			}
			$level = !empty($user['level']) ? $user['level'] : 0;
			$action = Request::route()->getAction();
			$as = !empty($action['as']) ? $action['as'] : '';
			$ad = trans_json(tpCache('ad.wap_header'));
			$view->with(['type'=>Cachekey('type'),'tag'=>Cachekey('tag'),'toRead'=>$toRead,'ad'=>$ad,'as'=>$as,'level'=>$level]);
		});
		
		View::composer(['moudel/footer_ad'], function ($view) {
			$view->with('ad',trans_json(tpCache('ad.wap_footer')));
        });
		
		View::composer(['moudel/pc_footer'], function ($view) {
			$view->with('ad',trans_json(tpCache('ad.pc_footer')));
        });
		
		View::composer(['moudel/footer'], function ($view) {
			$action = Request::route()->getAction();
			$as = !empty($action['as']) ? $action['as'] : '';
			$view->with('as',$as);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
