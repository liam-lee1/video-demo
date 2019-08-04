<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Validator;
use Session;
use Cookie;
use App\Logic\AccountLogic;


class Member extends Model
{
    // 默认表名students
    // 手动指定表名
    protected $table = 'member';

    // 默认是id字段作为主键，指定id为主键
    protected $primaryKey = 'id';
	
	/**
	* 该模型是否被自动维护时间戳
	*
	* @var bool
	*/
    public $timestamps = false;
	
	public function register_check($data){
		if(value_check($data))return['status' => 2,'msg'=>'不允许带有特殊字符'];
		if(!preg_match('/^[a-zA-Z0-9]{6,16}$/',$data['user']))return ['status'=>2,'msg'=>'用户名需为6-16位字母数字'];
		if(!preg_match('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',$data['email']))
			return ['status'=>2,'msg'=>'请输入正确邮箱'];
		if(!preg_match('/^.{6,12}$/',$data['pword']))
				return['status' => 2,'msg'=>'密码长度需为6-12位'];
		if($data['pword'] != $data['repword'])
			return ['status'=>2,'msg'=>'两次密码不一致'];
		if(!$data['question'])return ['status'=>2,'msg'=>'请输入密保问题'];
		if(!$data['ans'])return ['status'=>2,'msg'=>'请输入密保答案'];
		
		$check = $this->where('user',$data['user'])->value('id');
		if($check)return ['status'=>2,'msg'=>'该用户已注册'];
		
		$email = $this->where('email',$data['email'])->value('id');
		if($email)return ['status'=>2,'msg'=>'该邮箱已注册'];
		
		$ip = get_client_ip(0,true);
		$new = [
			'user' => $data['user'],
			'openid' => $this->get_openid(),
			'email' => $data['email'],
			'pword' => encrypt($data['pword']),
			'credit' => tpCache('para.enroll_credit'),
			'exp' => tpCache('para.enroll_exp'),
			'question' => $data['question'],
			'ans' => $data['ans'],
			'ip' => $ip,
			'join_time' => time(),
			'last_login' => time()
		];
		$info = $this->insertGetId($new);
		if(!empty($data['recom_id'])){
			$recom = $this->where('id',$data['recom_id'])->value('openid');
			if($recom)DB::table('meb_recom')->insert(['openid'=>$new['openid'],'manage'=>$recom,'time'=>time()]);
		}
		if($info){
			if(empty(session('sign'))){
				$count = $this->where([['ip',$ip],['join_time','>',time()-24*3600]])->count();
				if($count==1){
					session(['sign'=> uniqid()],24*60);
					$this->meb_relation(2,$new['openid']);
				}
				Session::forget('recom_mation');
			}
			
			session(['openid'=>$new['openid']],12*60);
			$auth = [
				'uid'=>$info,
				'openid'=>$new['openid']
			];
			Cookie::queue('user',$data['user'],7*24*60);
			Cookie::queue('meb_auth',json_encode($auth),12*60);
			Cookie::queue('meb_auth_sign',data_auth_sign($auth),12*60);
			return ['status'=>1,'msg'=>'注册成功'];
		}	
		else{
			return ['status'=>2,'msg'=>'注册失败,请刷新重试'];
		}
		
	}
	
	protected function get_openid(){
		$openid = 'oODsNb'.createNoncestr(26);
		$check = DB::table('member')->where('openid',$openid)->value('id');
		if($check){
			$this->get_openid();
		}else{
			return $openid;
		}
	}
	
	public function login_check($data){
		$where[] = ['user',$data['user']];
		$meb = $this->where($where)->select('id','openid','pword','is_freeze')->first();
		if(empty($meb))return ['status'=>2,'msg'=>'用户不存在'];
		if($meb['is_freeze'] != 0)return ['status'=>2,'msg'=>'该用户已被冻结'];
		if($data['pword'] != decrypt($meb['pword']))return ['status'=>2,'msg'=>'密码不正确'];
		session(['openid'=>$meb['openid']],12*60);
		$auth = [
			'uid'=>$meb['id'],
			'openid'=>$meb['openid']
		];
		Cookie::queue('user',$data['user'],7*24*60);
		Cookie::queue('meb_auth',json_encode($auth),12*60);
		Cookie::queue('meb_auth_sign',data_auth_sign($auth),12*60);
		$this->where($where)->update(['last_login'=>time()]);
		return	['status'=>1,'msg'=>'登录成功'];
	}
	
	protected function captcha_check($captcha){
		if(!session()->has('milkcaptcha'))
			return ['status'=>-1,'msg'=>'验证码错误'];
		
		if($captcha != session('milkcaptcha'))
			return ['status'=>2,'msg'=>'验证码错误'];
		
		return ['status'=>1];
	}
	
	public function reset_pword($data){
		if(value_check($data))return['status' => -1,'msg'=>'不允许带有特殊字符'];
		if($data['user'])
			$meb = $this->where('user',$data['user'])->select('email','question','ans')->first();
		if(empty($meb))return['status' => -1,'msg'=>'用户不存在'];
		if($data['key']>0){
			if(!$data['email'])return['status' => -1,'msg'=>'请输入邮箱'];
			if($data['email']!=$meb['email'])return ['status' => -1,'msg'=>'邮箱不正确'];
		}
		if($data['key']>1){
			if(!$data['ans'])return['status' => -1,'msg'=>'请输入密保答案'];
			if(strtolower($data['ans'])!=strtolower($meb['ans']))return ['status' => -1,'msg'=>'密保答案不正确'];
		}
		if($data['key']>2){
			if(!$data['pword'])return['status' => -1,'msg'=>'请输入新密码'];
			if(!preg_match('/^.{6,12}$/',$data['pword']))
				return['status' => -1,'msg'=>'密码长度需为6-12位'];
		}
		switch($data['key']){
			case 0:
				return ['status'=>2,'key'=>$data['key']+1,'name'=>'email'];
			break;
			
			case 1:
				return ['status'=>2,'key'=>$data['key']+1,'name'=>'ans','question'=>$meb['question']];
			break;
			
			case 2:
				return ['status'=>2,'key'=>$data['key']+1,'name'=>'pword'];
			break;
			default:;
		}
		$this->where('user',$data['user'])->update(['pword'=>encrypt($data['pword'])]);		
		return ['status'=>1,'msg'=>'重设密码成功'];
	}
	
	public function meb_relation($gener,$openid=''){
		if(empty(session('recom_mation')))return;
		$recom = json_decode(pack("H*",strrev(session('recom_mation'))),true);
		if(empty($recom['s']))return;
		$meb = DB::table('member')->where('id',$recom['s'])->value('openid');
		if(empty($meb))return;
		if($gener == 1){
			session(['mark'=> uniqid()],24*60);
			$ip = get_client_ip(0,true);
			if(empty($ip))return;
			$click = DB::table('recom_click')->where([['recom_id',$recom['s']],['ip',$ip],['time','>',time()-24*3600]])->count();
			if($click>=5)return;
			$info = DB::table('recom_click')->insert(['recom_id'=>$recom['s'],'ip'=>$ip,'time'=>time()]);
		}
		
		if(!empty($openid))
			DB::table('meb_recom')->insert(['openid'=>$openid,'manage'=>$meb,'time'=>time()]);
		
		$this->recom_reword($meb,1,$gener);
		
		$sec = DB::table('meb_recom')->where('openid',$meb)->value('manage');
		if(!empty($sec)){
			$this->recom_reword($sec,2,$gener);
		}
	}
	
	protected function recom_reword($openid,$type,$gener){
		switch($type){
			case 1: //直推
				if($gener == 1){ //点击
					$creit = tpCache('para.recom_click_credit');
					$exp = tpCache('para.recom_click_exp');
				}else{ //注册
					$creit = tpCache('para.recom_enroll_credit');
					$exp = tpCache('para.recom_enroll_exp');
				}
			break;
			case 2://间推
				if($gener == 1){ //点击
					$creit = tpCache('para.recom_sec_click_credit');
					$exp = tpCache('para.recom_sec_click_exp');
				}else{ //注册
					$creit = tpCache('para.recom_sec_enroll_credit');
					$exp = tpCache('para.recom_sec_enroll_exp');
				}
			break;
			default:return;
		}
		if($creit==0 && $exp == 0) return;
		
		$reason = ($gener==1) ? 2 : 1;
		$this->meb_level($openid,$exp);
		$accountLogic = new AccountLogic();
		$accountLogic->account_detail($openid,$creit,$exp,$reason);
	}
	
	public function meb_level($openid,$val){
		$level = json_decode(tpCache('para.level'),true);
		rsort($level);
		$i=3;
		$meb = $this->where('openid',$openid)->select('exp','level')->first();
		$all = $meb['exp'] + $val;
		foreach($level as $k=> $v){
			if($all>$v){
				$this->where('openid',$openid)->update(['level'=>$i]);
				/* if($i > $meb['level']){
					$this->where('openid',$openid)->update(['level'=>$i]);
				} */
				break;
			}
			$i--;
		}
	}
}
