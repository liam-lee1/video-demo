<?php
namespace App\Http\Controllers\Admin;
use App\Model\Member;
use App\Http\Controllers\Controller;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Session;
use Cookie;
use App\Http\Requests;
use Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Logic\UploadLogic;

class AdminController extends Controller
{  
   public function index(){
	    $ident = json_decode(Cookie::get('admin_auth'),true)['admin_lev'];
		if($ident!=1)
			$where[] = ['is_dev',$ident];
		$where[] = ['pid',0];
		$where[] = ['hide',0];
		$menu = DB::table('admin_menu')->where($where)->orderBy('sort','asc')->get()->toArray();
		foreach($menu as $k=>$v){
			$con = [];
			$menu[$k]['spread'] = intval($v['spread']);
			$menu[$k]['href'] = url($v['href']);
			$con[] = ['pid',$v['id']];
			$con[] = ['hide',0];
			if($ident!=1)
				$con[] = ['is_dev',$ident];
			$children = DB::table('admin_menu')->where($con)->orderBy('sort','asc')->get()->toArray();
			foreach($children as $m=>$n){
				$children[$m]['href'] = url($n['href']);
			}
			$menu[$k]['children'] = $children;
			unset($children);
		}
		return view('admin/index',[
			'menu'=>json_encode($menu),'ident'=>$ident
		]);
   }
   
   public function intro(){
	   $day = strtotime(date('y-m-d'));	   
	   $data['new'] = DB::table('member')->where('join_time','>=',$day)->count();
	   $data['all'] = DB::table('member')->count();
	   $data['album'] = DB::table('course_album')->count();
	   $data['episode'] = DB::table('course_episode')->count();
	   
	   $data['credit_today'] = DB::table('account_detail')->where([['time','>=',strtotime(date('Ymd',time()))]])->whereBetween('type',[1,2])->sum('credit');
	   $data['credit_all'] = DB::table('account_detail')->whereBetween('type',[1,2])->sum('credit');
	   
	   $re = $this->get_data();
	   return view('admin/intro',[
			're'=>$re,'data'=>$data
	   ]);
   }
   
   public function get_data(){
	   $today = strtotime(date('ymd'));
	   $xAxis = [];
	   $yAxis = [];
	   for($i=15;$i>0;$i--){
		   $count = DB::table('member')->where([['join_time','>=',$today-$i*24*3600],['join_time','<',$today-($i-1)*24*3600]])->count();
		   array_push($yAxis,$count);
		   array_push($xAxis,date('y-m-d',$today-$i*24*3600));
	   }
	   $re = [
		'xAxis'=>json_encode($xAxis),
		'yAxis'=>json_encode($yAxis)
	   ];
	   return $re;
   }
   
   public function check_login(){
	   if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		$mebname = $data['mebname'];
		$pword = $data['pword'];
		if(!$mebname || !$pword)
			return ['status'=>2,'msg'=>'账户或密码不能为空'];
		$mation = DB::table('admin_meb')->where('mebname',$mebname)->select('id','pword','ident')->first();
		if(empty($mation))
			return ['status'=>2,'msg'=>'账户不存在'];
		
		if(md5(md5($pword).config('deploy.implement_pword')) != $mation['pword'])
			return ['status'=>2,'msg'=>'密码错误'];
		
		$auth = [
			'admin_id'=>$mation['id'],
			'admin_lev'=>$mation['ident']
		];
		Cookie::queue('admin_auth',json_encode($auth),12*60);
		Cookie::queue('admin_auth_sign',data_auth_sign($auth),12*60);
		
		return ['status'=>1,'msg'=>'登录成功'];
   }
   
   public function change_pword(){
	   if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
	   $data = Request::all();
	   $old = $data['old'];
	   $pword = $data['pword'];
	   $repword = $data['repword'];
	   if(!$old || !$pword ||!$repword)
			return ['status'=>2,'msg'=>'数据传输错误，请重试'];
	   if($pword!=$repword)
			return ['status'=>2,'msg'=>'输入的两次密码不一致'];
	   if(!preg_match('/^[A-Za-z0-9]{6,}$/',$repword))
			return ['status'=>2,'msg'=>'请输入6位以上包含数字或字母的密码'];
	   $admin = Cookie::get('admin_auth')['admin_id'];
	   $password = DB::table('admin_meb')->where('id',$admin)->value('pword');
	   if(md5(md5($old).config('deploy.implement_pword'))!= $password)
			return ['status'=>2,'msg'=>'你输入的密码不正确'];
		   
	   $info = DB::table('admin_meb')->where('id',$admin)->update(['pword'=>md5(md5($pword).config('deploy.implement_pword'))]);
	   if(!$info)
		   return ['status'=>2,'msg'=>'密码修改失败，请刷新重试'];
	   return ['status'=>1,'msg'=>'密码修改成功'];
   }
   
   public function logout(){
		Cookie::queue(Cookie::forget('admin_auth'));
		Cookie::queue(Cookie::forget('admin_auth_sign'));
		return redirect()->route('login');
   }
}