<?php
Route::post('signin', 'ApiController@meb_login');

Route::get('/s/{key?}','ApiController@spread');

Route::get('/','ApiController@judge');

Route::get('sign', function(){
	return view('pc/sign');
});

Route::get('test', 'ApiController@test');

Route::get('test1', 'ApiController@test1');

Route::get('activity_judge', 'ApiController@activity_judge');

Route::get('logout', 'ApiController@meb_logout');

Route::get('ucenter', 'Pc\IndexController@ucenter');

//PC端
Route::get('index','Mob\IndexController@index')->name('pc_index');

Route::get('video','Mob\CourseController@video');

Route::post('reset_pword', 'ApiController@reset_pword');

Route::post('signup', 'ApiController@meb_signup');

Route::get('freecourse', 'Mob\CourseController@freecourse');

/*手机端*/

Route::get('web/down_video', 'ApiController@down_video');

Route::get('m/index', 'Mob\IndexController@index')->name('index');

Route::get('m/video', 'Mob\CourseController@video');

Route::get('get_episode_url', 'Mob\CourseController@get_episode_url');

Route::get('course/getCourseList', 'Mob\CourseController@getCourseList');

Route::get('m/freecourse', 'Mob\CourseController@freecourse')->name('freecourse');

Route::get('m/list','Mob\CourseController@course_list')->name('list');

Route::get('list','Mob\CourseController@course_list')->name('pc_list');

Route::get('web/get_play_url', 'ApiController@get_play_url');


Route::get('m/free', function(){
	return view('mobile/free');
})->name('free');

Route::get('m/signin', function(){
	return view('mobile/signin');
});

Route::get('m/signup', function(){
	return view('mobile/signup');
});

Route::get('m/reset', function(){
	return view('mobile/reset');
});

Route::get('m/notice', 'Mob\IndexController@notice');

Route::get('m/notice_xg', 'Mob\IndexController@notice_xg');

Route::get('notice', 'Mob\IndexController@notice');

Route::get('notice_xg', 'Mob\IndexController@notice_xg');

Route::get('get_notice_list', 'Mob\IndexController@get_notice_list');

Route::any('pay/notify', 'Mob\PayController@notify');

/*签到*/
Route::post('web/insign', 'ApiController@insign');

Route::post('web/album_rate', 'ApiController@album_rate');

Route::post('web/album_collect', 'ApiController@album_collect');

Route::post('web/episode_evaluate', 'ApiController@episode_evaluate');

Route::post('web/download_video', 'ApiController@download_video');

Route::group(['middleware' => ['front_end']], function() {
	
	Route::get('m/ucenter', 'Mob\IndexController@ucenter')->name('ucenter');
	
	Route::get('m/play', 'Mob\CourseController@play');
	
	Route::get('play', 'Mob\CourseController@play');
	
	Route::post('web/errors_jduge', 'Mob\CourseController@errors_jduge');
	
	Route::get('collect','Mob\IndexController@collect');
	
	Route::get('m/collect', 'Mob\IndexController@collect');
	
	Route::get('course/getCollectList', 'Mob\IndexController@getCollectList');
	
	Route::get('category', 'Mob\PayController@category');
	
	Route::get('m/category', 'Mob\PayController@category');
	
	Route::post('pay/creat_trade', 'Mob\PayController@creat_trade');
	
	Route::get('m/mation', 'Mob\IndexController@mation');
	
	Route::post('mation_check', 'Mob\IndexController@mation_check');
	
	Route::post('mation_save', 'Mob\IndexController@mation_save');
	
	Route::post('takecash', 'Mob\IndexController@takecash');
	
	Route::get('m/account', 'Mob\IndexController@account');
	
	Route::get('get_account_detail', 'Mob\IndexController@get_account_detail');
});


//后台
Route::get('manage/login',['as'=>'login',function () {
    return view('admin/login');
}]);

Route::post('manage/login','Admin\AdminController@check_login');

Route::get('manage/logout','Admin\AdminController@logout');


Route::group(['middleware' => ['back_end']], function() {
	
	Route::get('manage/index','Admin\AdminController@index');
	
	Route::get('manage/changepword',function () {
			return view('admin/changepword');
		});
	
	Route::get('manage/intro','Admin\AdminController@intro');
	
	//账号管理
	/*1,管理员账户*/
	Route::get('manage/manager_list','Admin\AccountController@manager_list');
	
	Route::get('account/get_managerList','Admin\AccountController@get_managerList');
	
	Route::post('account/del_manager','Admin\AccountController@del_manager');
	
	Route::post('account/add_manager','Admin\AccountController@add_manager');
	
	Route::post('account/edit_manager','Admin\AccountController@edit_manager');
	
	/*2,会员管理*/
	Route::get('manage/meb_list','Admin\AccountController@meb_list');
	
	Route::get('account/get_mebList','Admin\AccountController@get_mebList');
	
	Route::post('account/meb_freeze/{id}','Admin\AccountController@meb_freeze');
	
	Route::post('account/meb_thaw/{id}','Admin\AccountController@meb_thaw');
	
	Route::post('account/del_account/{id}','Admin\AccountController@del_account');
	
	Route::post('account/increase_account','Admin\AccountController@increase_account');
	
	Route::get('manage/account_detail','Admin\AccountController@account_detail');
	
	Route::get('account/get_accountDetail','Admin\AccountController@get_accountDetail');
	
	Route::get('manage/balance_detail','Admin\AccountController@balance_detail');
	
	Route::get('account/get_balanceDetail','Admin\AccountController@get_balanceDetail');
	
	Route::get('manage/meb_mation','Admin\AccountController@meb_mation');
	
	Route::post('account/modify_account','Admin\AccountController@modify_account');
	
	/*3,充值管理*/
	Route::get('manage/meb_paid','Admin\AccountController@meb_paid');
	
	Route::get('account/get_meb_paid','Admin\AccountController@get_meb_paid');

	Route::post('account/edit_meb_paid','Admin\AccountController@edit_meb_paid');
	
	Route::post('account/del_meb_paid','Admin\AccountController@del_meb_paid');
	
	//素材管理
	/*1,分类管理*/
	Route::get('manage/video_type','Admin\VideoController@video_type');
	
	Route::get('video/get_video_type','Admin\VideoController@get_video_type');

	Route::post('video/edit_video_type','Admin\VideoController@edit_video_type');
	
	Route::post('video/del_type','Admin\VideoController@del_type');
	
	/*2,标签管理*/
	Route::get('manage/video_tag','Admin\VideoController@video_tag');
	
	Route::get('video/get_video_tag','Admin\VideoController@get_video_tag');
	
	Route::get('video/get_tag_list','Admin\VideoController@get_tag_list');

	Route::post('video/edit_video_tag','Admin\VideoController@edit_video_tag');
	
	Route::post('video/del_tag','Admin\VideoController@del_tag');
	
	/*3,视频管理*/
	Route::get('manage/video_list','Admin\VideoController@video_list');
	
	Route::get('video/get_video_list','Admin\VideoController@get_video_list');
	
	Route::post('video/change_video_state/{id}','Admin\VideoController@change_video_state');
	
	Route::get('video/add_video','Admin\VideoController@add_video');
	
	Route::get('video/edit_video','Admin\VideoController@edit_video');
	
	Route::post('video/save_edit','Admin\VideoController@save_edit');
	
	Route::post('video/del_video/{id}','Admin\VideoController@del_video');
	
	/*4,报错管理*/
	Route::get('manage/video_errors','Admin\VideoController@video_errors');
	
	Route::get('video/get_errors_list','Admin\VideoController@get_errors_list');
	
	Route::post('video_errors_del','Admin\VideoController@video_errors_del');
	
	//公告管理
	/*公告列表*/
	Route::get('manage/notice_list','Admin\InfoController@notice_list');
	
	Route::get('info/get_notice_list','Admin\InfoController@get_notice_list');
	
	Route::get('info/notice','Admin\InfoController@notice');
	
	Route::get('info/notice_xg','Admin\InfoController@notice_xg');
	
	Route::post('info/notice_eait','Admin\InfoController@notice_eait');
	
	Route::post('info/notice_hide','Admin\InfoController@notice_hide');
	
	Route::post('info/del_notice','Admin\InfoController@del_notice');
	
	/*广告列表*/
	Route::get('manage/ad','Admin\InfoController@ad');
	
	Route::post('info/save_ad','Admin\InfoController@save_ad');
	
	/*设置参数*/
	Route::get('manage/para_set','Admin\InfoController@para_set');
	
	Route::post('info/save_para','Admin\InfoController@save_para');
	
	/*交易订单*/
	Route::get('manage/trade_list','Admin\AccountController@trade_list');
	
	Route::get('account/get_tradeList','Admin\AccountController@get_tradeList');
	
	//提现
	Route::get('manage/withdraw_list','Admin\AccountController@withdraw_list');
	
	Route::get('manage/withdraw_list_del','Admin\AccountController@withdraw_list_del');
	
	Route::get('account/get_withdrawList','Admin\AccountController@get_withdrawList');
	
	Route::post('account/del_withdraw','Admin\AccountController@del_withdraw');
	
});

//支付宝支付
Route::get('alipay/alipay_wappay','ZFB\ZfpayController@alipay_wappay');

Route::get('alipay/pay',function(){
	return view('mobile/alipay/pay');
});

Route::any('alipay/return','ZFB\ZfpayController@return_url');

Route::any('alipay/notify','ZFB\ZfpayController@notify_url');
