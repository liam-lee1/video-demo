<?php
namespace App\Http\Controllers;

use App\Model\Member;
use Session;
use Cookie;
use Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Logic\AccountLogic;
use App\Logic\UploadLogic;
use App\Logic\CourseLogic;
use App\Logic\PayLogic;

class ApiController extends Controller
{	
	public function judge(){
		if(isMobile()){
			return redirect()->route('index');
		}else{
			return redirect()->route('pc_index');
		}
	}
	
	public function spread($key=''){
		if(empty(session('mark'))){
			if(!empty($key)){
				session(['recom_mation'=>$key],24*60);
				$Member = new Member;
				$Member->meb_relation(1);
				session(['mark'=> uniqid()],24*60);
				sleep(0.1);
			}
		}
		if(isMobile()){
			return redirect()->route('index');
		}else{
			return redirect()->route('pc_index');
		}
	}
	
	public function meb_login(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误','result'=>''];
		$data = Request::all();
		$Member = new Member;
		return $Member->login_check($data);
	}
	
	public function meb_signup(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误','result'=>''];
		$data = Request::all();
		$Member = new Member;
		return $Member->register_check($data);
	}
	
	public function meb_logout(){
		Session()->forget('openid');
		Session()->forget('meb');
		Cookie::queue(Cookie::forget('meb_auth'));
		Cookie::queue(Cookie::forget('meb_auth_sign'));
		if(isMobile()){
			return redirect()->route('index');
		}else{
			return redirect()->route('pc_index');
		}
	}
	
	
	public function reset_pword(){
		if(!Request::ajax())
			return json_encode(['status'=>2,'msg'=>'数据传输错误']);
		$data=Request::all();
		$Member = new Member;
		return $Member->reset_pword($data);
	}
	
	public function insign(){
		if(!Request::ajax())
			return json_encode(['status'=>2,'msg'=>'数据传输错误']);
		if(empty(session('openid')))
			return ['status'=>3,'msg'=>'请先登录','url'=>url('m/signin')];
		$check = DB::table('sign_record')->where([['openid',session('openid')],['time','>',strtotime(date('Ymd'))]])->value('id');
		if($check)return ['status'=>2,'msg'=>'今日已签到'];
		$accountLogic = new AccountLogic();
		$accountLogic->account_detail(session('openid'),tpCache('para.sign_credit'),tpCache('para.sign_exp'),5);
		DB::table('sign_record')->insert(['openid'=>session('openid'),'time'=>time()]);
		$Member = new Member;
		$Member->meb_level(session('openid'),tpCache('para.sign_exp'));
		$msg = '签到成功，获取'.tpCache('para.sign_credit').'积分和'.tpCache('para.sign_exp').'经验';
		return ['status'=>1,'msg'=>$msg];
	}
	
	public function album_rate(){
		if(!Request::ajax())
			return json_encode(['status'=>2,'msg'=>'数据传输错误']);
		if(empty(session('openid')))
			return ['status'=>3,'msg'=>'请先登录','url'=>url('m/signin')];
		$aid = $_POST['aid'];
		$rate = $_POST['rate'];
		$check = DB::table('rate_record')->where([['openid',session('openid')],['album_id',$aid]])->value('id');
		if($check)return ['status'=>2,'msg'=>'该用户已评分'];
		DB::table('rate_record')->insert(['openid'=>session('openid'),'album_id'=>$aid,'rate'=>$rate,'time'=>time()]);
		return ['status'=>1,'msg'=>'评分成功'];
	}
	
	public function album_collect(){
		if(!Request::ajax())
			return json_encode(['status'=>2,'msg'=>'数据传输错误']);
		if(empty(session('openid'))){
			if(isMobile())
				$url = url('m/signin');
			else
				$url = url('sign');
			return ['status'=>3,'msg'=>'请先登录','url'=>$url];
		}
		$aid = $_POST['aid'];
		$check = DB::table('meb_collect')->where([['openid',session('openid')],['album_id',$aid]])->value('id');
		if($check){
			DB::table('meb_collect')->where('id',$check)->delete();
			$state = 2;
			$msg = '收藏到个人中心';
		}else{
			DB::table('meb_collect')->insert(['openid'=>session('openid'),'album_id'=>$aid,'time'=>time()]);
			$msg = '收藏成功';
			$state = 1;
		}
		return ['status'=>1,'msg'=>$msg,'state'=>$state];
	}
	
	public function episode_evaluate(){
		if(!Request::ajax())
			return json_encode(['status'=>2,'msg'=>'数据传输错误']);
		if(empty(session('openid'))){
			if(isMobile())
				$url = url('m/signin');
			else
				$url = url('sign');
			return ['status'=>3,'msg'=>'请先登录','url'=>$url];
		}
		$type = $_POST['type'];
		$eid = $_POST['eid'];
		
		$album_id = DB::table('course_episode')->where('id',$eid)->value('album_id');
		
		switch($type){
			case 1:
				$filed = 'like';
				$other = 'dislike';
			break;
			case 2:
				$filed = 'dislike';
				$other = 'like';
			break;
		}
		
		$where[] = ['openid',session('openid')];
		$where[] = ['episode_id',$eid];
		$check = DB::table('meb_evaluate')->where($where)->value('type');
		if(!empty($check)){
			if($check != $type){
				DB::table('meb_evaluate')->where($where)->update(['type'=>$type]);
				DB::table('course_episode')->where('id',$eid)->increment($filed);
				DB::table('course_episode')->where('id',$eid)->increment($other,-1);
				if($check == 1){
					DB::table('course_album')->where('id',$album_id)->increment('overall',-1);
				}else{
					DB::table('course_album')->where('id',$album_id)->increment('overall',1);
				}
				$state = 1;
			}else{
				DB::table('meb_evaluate')->where($where)->delete();
				DB::table('course_episode')->where('id',$eid)->increment($filed,-1);
				$state = 2;
				if($check == 1){
					DB::table('course_album')->where('id',$album_id)->increment('overall',-1);
				}
			}
		}else{
			DB::table('meb_evaluate')->insert(['openid'=>session('openid'),'episode_id'=>$eid,'type'=>$type,'time'=>time()]);
			DB::table('course_episode')->where('id',$eid)->increment($filed);
			if($type == 1){
				DB::table('course_album')->where('id',$album_id)->increment('overall',1);
			}
			$state = 3;
		}
		/*$add_time = DB::table('course_album')->where('id',$album_id)->value('time');
		if($add_time >= time()-30*24*3600){
			$CourseLogic = new CourseLogic();
			$CourseLogic->album_overall($album_id);
		}*/
		return ['status'=>1,'msg'=>'评价成功','state'=>$state];
	}
	
	public function download_video(){
		if(!Request::ajax())
			return json_encode(['status'=>2,'msg'=>'数据传输错误']);
		if(empty(session('openid'))){
			if(isMobile())
				$url = url('m/signin');
			else
				$url = url('sign');
			return ['status'=>3,'msg'=>'请先登录','url'=>$url];
		}
		$eid = $_POST['eid'];
		$openid = session('openid');
		$even = DB::table('meb_download')->where([['openid',$openid],['episode_id',$eid]])
				->orderBy('id','desc')->select('time')->first();
		
		if(empty($even) || !empty($even)&&($even['time'] < time()-tpCache('para.reduce_time')*3600)){
			$need = tpCache('para.reduce');
			$credit = DB::table('member')->where('openid',$openid)->value('credit');
			if($credit < $need )
				return ['status'=>2,'msg'=>'账户积分不足'];
			DB::table('meb_download')->insert(['openid'=>$openid,'episode_id'=>$eid,'time'=>time()]);
			$accountLogic = new AccountLogic();
			$accountLogic->account_detail($openid,-5,0,3);
		}
		
		cache(['allow_'.$openid => enc_aes($eid)],5);
		
		$url = url('web/down_video').'?t='.time();
		return ['status'=>1,'msg'=>'正在获取...','url'=>$url,'key'=>enc_aes(json_encode(['openid'=>$openid,'t'=>time()]))];
	}
	
	public function get_play_url(){
		$t = isset($_GET['t']) ? $_GET['t'] : '';
		$key = isset($_GET['key']) ? $_GET['key'] : '';
		if($t < time()-60 || empty($t) || !$key)return ['status'=>2,'msg'=>'error'];
		$arr = json_decode(dec_aes($key),true);
		if($t!=$arr['t'])return ['status'=>2,'msg'=>'error'];
		$eid = dec_aes(session('allow_'.$arr['k']));
		if(!$eid)return ['status'=>2,'msg'=>'error'];
		$url = DB::table('course_episode')->where('id',$eid)->value('url');
		Session::forget('allow_'.$arr['k']);
		return redirect($url);
	}
	
	public function down_video(){
		$key = isset($_GET['key']) ? $_GET['key'] : '';
		$goUrl = isMobile() ? url('m/index') : url('index');
		
		if($_GET['t'] < time()-5*60 || empty($_GET))
			return $this->error('数据传输错误',$goUrl);
		
		$arr = json_decode(dec_aes($key),true);
		
		if( $_GET['t']!=$arr['t'] )
			return $this->error('数据传输错误',$goUrl);
		
		$eid = dec_aes(cache('allow_'.$arr['openid']));
		
		Cache::forget('allow_'.$arr['openid']);
		
		if(empty($eid))
			return $this->error('数据传输错误',$goUrl);
		
		$episode = DB::table('course_episode')->where('id',$eid)->select('album_id','downUrl')->first();
		$name = DB::table('course_album')->where('id',$episode['album_id'])->value('name');
		$upload = new UploadLogic();
		$upload->DownFile($episode['downUrl'],$name.'.mp4');			
	}
	
	public function activity_judge(){
		$key = !empty($_GET['key']) ? $_GET['key'] : '';
		if($key != 'EyQHpdJiQE7h5JG4uZhd6In2t79n9wnz'){
			return '权限不足';
		}
		$where[] = ['last_login','<',time()-7*24*3600];
		$where[] = ['last_judge','<',date('Y-m-d')];
		$where[] = ['exp','>',0];
		$meb_check = DB::table('member')->where($where)->select('id','openid','level','exp')->get()->toArray();
		if(count($meb_check)>0){
			$accountLogic = new AccountLogic();
			$Member = new Member;
			foreach($meb_check as $v){
				$exp = $v['exp']>5 ? -5 : -$v['exp'];
				$accountLogic->account_detail($v['openid'],0,$exp,6);
				if($v['level']>1){
					$Member->meb_level($v['openid'],$exp);
				}
				DB::table('member')->where('id',$v['id'])->update(['last_judge'=>time()]);
			}
		}
	}
	
	public function test(){
		$data = DB::table('course_episode')->get()->toArray();
		foreach($data as $k=>$v){
			$file = [];
			preg_match('/mp4\/(.*?).mp4/',$v['url'],$file);
			if(!empty($file[1])){
				$url = 'https://mp4.sbdouyin.com/m3u8/'.md5($file[1].'.mp4').'/'.md5($file[1].'.mp4').'.m3u8';
				DB::table('course_episode')->where('id',$v['id'])->update(['url'=>$url,'downUrl'=>$v['url']]);
			}
		}
	}
	
	public function test1(){
		$data = DB::table('course_album')->select('id')->get()->toArray();
		foreach($data as $k=>$v){
			$file = [];
			$url = DB::table('course_episode')->where('album_id',$v['id'])->value('downUrl');
			preg_match('/mp4\/(.*?).mp4/',$url,$file);
			if(!empty($file[1])){
				$videopreview = 'https://videopreview.sbdouyin.com/videopreview/'.md5($file[1].'.mp4169').'.mp4';
				DB::table('course_album')->where('id',$v['id'])->update(['videopreview'=>$videopreview]);
			}
		}
	}
}