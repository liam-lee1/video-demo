<?php
namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Logic\CourseLogic;
use Request;
use Session;
use Cookie;

class IndexController extends Controller
{
    
	public function  ucenter(){
		if(isMobile())return redirect('/m/ucenter');
		if(!empty(session('openid')))
		$meb = DB::table('member')->where('openid',session('openid'))->select('id','user','credit','exp','join_time','ispaid','active_time','balance')->first();
		return view('pc/ucenter',[
			'meb' => !empty($meb) ? $meb : [],'key'=>  !empty($meb) ? strrev(bin2hex(json_encode(['s'=>$meb['id']]))) : ''
		]);
	}
}