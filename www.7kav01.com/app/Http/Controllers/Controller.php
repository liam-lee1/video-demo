<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use App\Logic\AccountLogic;
use App\Model\Member;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	function __construct(){
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if(strpos($agent,'MSIE') || strpos($agent,'rv:11.0') || strpos($agent,'UCBrowser') || strpos($agent,'UCWEB')){
			header('HTTP/1.1 444 No Response');
			echo '<h1>抱歉！本站已禁止UC浏览器访问,IE浏览器兼容性存在问题,也被本站禁用,请更换其它浏览器尝试！推荐使用chrome谷歌浏览器，firefox火狐浏览器等访问！</h1>';
			exit;
		}
		$con[] = ['ispaid',1];
		$con[] = ['active_time','<',time()];
		DB::table('member')->where($con)->update(['ispaid'=>0,'active_time'=>0]);
	}
	
	protected function dispatch_success_tmpl() {
		return 'jump.dispatch_jump';
	}

	protected function dispatch_error_tmpl() {
		return 'jump.dispatch_jump';
	}

	// 操作成功跳转的快捷方法
	protected function success($msg = '', $url = null, $data = '', $wait = 3, array $header = []) {
		session()->flash('success', $msg);

		if (is_null($url)) {
			$url = url()->previous();
		}

		$result = [
			'code' => 1,
			'msg'  => $msg,
			'data' => $data,
			'url'  => $url,
			'wait' => $wait,
		];

		if(request()->ajax()) {
			$response =  response()->json($result)->withHeaders($header);
		} else {
			$response =  response()->view($this->dispatch_success_tmpl(), $result)->withHeaders($header);
		}

		throw new HttpResponseException($response);
	}

	// 操作失败跳转的快捷方法
	protected function error($msg = '', $url = null, $data = '', $wait = 3, array $header = []) {
		session()->flash('warning', $msg);

		if (is_null($url)) {
			$url = request()->ajax() ? '' : 'javascript:history.back(-1);';
		}

		$result = [
			'code' => 0,
			'msg'  => $msg,
			'data' => $data,
			'url'  => $url,
			'wait' => $wait,
		];

		if(request()->ajax()) {
			$response = response()->json($result)->withHeaders($header);
		} else {
			$response = response()->view($this->dispatch_error_tmpl(), $result)->withHeaders($header);
		}

		throw new HttpResponseException($response);
	}
}
