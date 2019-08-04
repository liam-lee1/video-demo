<?php
namespace App\Http\Controllers\Mob;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Logic\VideoLogic;
use App\Logic\AccountLogic;
use App\Model\Course;
use Request;
use Session;
use Cookie;

class IndexController extends Controller
{
    public function index(){
		$arr['new'] = DB::table('course_album as a')->where('hide',0)
					->join('course_episode as e','a.id','=','e.album_id')
					->select('a.id','a.name','a.image','a.level','a.videopreview','a.isfree','a.time','e.id as eid')
					->orderBy('a.time','desc')->skip(0)->take(16)->get()->toArray();
		
		$arr['praise'] =  DB::table('course_album as a')->where([['hide',0],['time','>=',time()-30*24*3600]])
						->join('course_episode as e','a.id','=','e.album_id')
						->select('a.id','a.name','a.image','a.level','a.videopreview','a.isfree','a.time','e.id as eid')
						->orderBy('e.like','desc')->skip(0)->take(16)->get()->toArray();
		
		$arr['recom'] =  DB::table('course_album as a')->where([['hide',0],['isrecom',1]])
						->join('course_episode as e','a.id','=','e.album_id')
						->select('a.id','a.name','a.image','a.level','a.videopreview','a.isfree','a.time','e.id as eid')
						->orderBy('a.time','desc')->skip(0)->take(16)->get()->toArray();
		if(isMobile()){
			return view('mobile/index',$arr);
		}else{
			return view('pc/index',$arr);
		}
	}
	
	public function notice(){
		if(session('openid')){
			$last = DB::table('notice')->orderBy('id','desc')->select('id')->first();
			if($last['id'])
				DB::table('member')->where('openid',session('openid'))->update(['notice_id'=>$last['id']]);
		}
		
		if(isMobile()){
			return view('mobile/notice');
		}else{
			return view('pc/notice');
		}
		
	}
	
	public function notice_xg(){
		$nid = isset($_GET['nid']) ? $_GET['nid'] : '';
		if($nid)$notice = DB::table('notice')->where('id',$nid)->first();
		if(empty($notice))$this->error('获取公告失败');
		$content = $notice['content'] ? file_get_contents('images/intro/'.$notice['content']) : '';
		
		$view = isMobile() ? 'mobile/notice_xg' : 'pc/notice_xg';
		
		return view($view,[
			'notice' => $notice,'content'=>$content
		]);
	}
	
	public function get_notice_list(){
		$page = $_GET['page'];
		$where[] = ['hide',0];
		$pn = 6;
		$offset = ($page-1)*$pn;
		$re = DB::table('notice')->where($where)
			->select('id','title','time')
			->offset($offset)->limit($pn)
			->orderBy('sort','asc')->orderBy('id','desc')->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['time'] = date('m-d',$v['time']);
		}
		$count = DB::table('notice')->where($where)->count();
		$pages = ceil($count/$pn);
		if(isMobile())
			return ['data'=>$re,'pages'=>$pages];
		else
			return ['data'=>$re,'count'=>$count];
	}
	
	public function ucenter(){
		if(!browser_judge()){
			header('HTTP/1.1 403 Forbidden');
			return '浏览器被禁止';
		}
		if(!isMobile())return redirect('/ucenter');
		if(empty(session('openid')))
			return $this->error('请先登录',url('m/signin'));
		
		$meb = DB::table('member')->where('openid',session('openid'))->select('id','user','credit','email','level','exp','join_time','ispaid','active_time','balance')->first();
		return view('mobile/ucenter',[
			'meb' => $meb,'key'=>strrev(bin2hex(json_encode(['s'=>$meb['id']])))
		]);
	}
	
	public function takecash(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$cash = abs($_POST['cash']);
		if($cash<=0 || !preg_match('/(^[1-9]\d*$)/',$cash))return ['status'=>2,'msg'=>'提现金额有误'];
		$limit = tpCache('para.withdraw_limit');
		if($cash<$limit)return ['status'=>2,'msg'=>'最低提现额度为'.$limit];
		$openid = session('openid');
		$balance = DB::table('member')->where('openid',$openid)->value('balance');
		if($cash>$balance)return ['status'=>2,'msg'=>'账户余额不足'];
		
		$withdraw = [
			'openid' => $openid,
			'cash'	 => $cash,
			'time'	 => time(),
			'state'	 => 0
		];
		DB::table('cash_withdraw')->insertGetId($withdraw);
		$AccountLogic = new AccountLogic();
		$AccountLogic->balance_detail($openid,-$cash,2);
		return ['status'=>1,'msg'=>'提现申请成功'];
	}
	
	public function mation(){
		if(empty(session('openid')))
			return $this->error('请先登录',url('m/signin'));
		$mation = DB::table('meb_mation')->where('openid',session('openid'))->first();
		return view('mobile/mation',['mation'=>$mation]);
	}
	
	public function mation_check(){
		if(!Request::ajax())
			return ['status'=>3,'msg'=>'数据传输错误'];
		$meb_mation = DB::table('meb_mation')->where('openid',session('openid'))->value('id');
		$complete = !empty($meb_mation) ? 1 : 0;
		if($complete==0)
			return ['status'=>2,'msg'=>'请先完善信息'];
		return ['status'=>1];
	}
	
	public function mation_save(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = $_POST;
		$openid = session('openid');
		
		if(!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$data['payee_real_name']))
			return ['status'=>2,'msg'=>'姓名格式有误'];
		
		if(!preg_match('/^1[3-9]\d{9}$/',$data['payee_account']) && !preg_match('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',$data['payee_account']))
			return ['status'=>2,'msg'=>'支付宝账户有误'];
		
		$mation = [
			'payee_real_name' => $data['payee_real_name'],
			'payee_account' => $data['payee_account']
		];
		$check = DB::table('meb_mation')->where('openid',$openid)->value('id');
		if(!$check){
			$mation['openid'] = $openid;
			DB::table('meb_mation')->insert($mation);
			$msg = '保存成功';
		}else{
			DB::table('meb_mation')->where('openid',$openid)->update($mation);
			$msg = '更新成功';
		}
		return ['status'=>1,'msg'=>$msg];
	}
	
	public function collect(){
		if(!browser_judge()){
			header('HTTP/1.1 403 Forbidden');
			return '浏览器被禁止';
		}
		if(empty(session('openid'))){
			$url = isMobile() ? url('m/signin') : url('sign');
			return $this->error('请先登录',$url);
		}
		$view = isMobile() ? 'mobile/collect' : 'pc/collect';
		return view($view);
	}
	
	public function getCollectList(){
		$page = $_GET['page']?$_GET['page']:1;
		$where = [];
		$pn = 16;
		$offset = ($page-1)*$pn;
		$where[] = ['openid',session('openid')];
		$re = DB::table('meb_collect')->where($where)->offset($offset)->limit($pn)->orderBy('time','desc')->get()->toArray();
		foreach($re as $k=>$v){
			$album = DB::table('course_album')->where([['id',$v['album_id'],['hide',0]]])->select('id','name','image','videopreview','level','isfree','time')->first();
			if($album){
				$album['eid'] = DB::table('course_episode')->where('album_id',$album['id'])->value('id');
				$album['time'] = date('m-d',$v['time']);
				$re[$k] = $album;
			}else{
				unset($album[$k]);
			}
		}
		
		$count = DB::table('meb_collect')->where($where)->count();
		
		return ['data'=>$re,'count'=>$count];
	}
	
	public function account(){
		if(!browser_judge()){
			header('HTTP/1.1 403 Forbidden');
			return '浏览器被禁止';
		}
		if(empty(session('openid'))){
			$url = isMobile() ? url('m/signin') : url('sign');
			return $this->error('请先登录',$url);
		}
		return view('mobile/account');
	}
	
	public function get_account_detail(){
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$page = $_GET['page'];
		$pn = 6;
		$offset = ($page-1)*$pn;
		$where[] = ['openid',session('openid')];
		switch($type){
			case 'account':
				$re = DB::table('account_detail')->where($where)->orderBy('id','desc')
				->offset($offset)->limit($pn)->get()->toArray();
				foreach($re as $k=>$v){
					$re[$k]['type'] = config('deploy.account_detail')[$v['type']];
					$re[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
				}
				$count = DB::table('account_detail')->where($where)->count();
				$pages = ceil($count/$pn);
			break;
			case 'withdraw':
				$re = DB::table('cash_withdraw')->where($where)->orderBy('id','desc')
				->offset($offset)->limit($pn)->get()->toArray();
				foreach($re as $k=>$v){
					$re[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
					$re[$k]['del_time'] = $v['del_time']>0 ? date('Y-m-d H:i:s',$v['del_time']) : '';
					switch($v['state']){
						case -1:
							$state = '申请驳回';
						break;
						case 0:
							$state = '待处理';
						break;
						case 1:
							$state = '申请通过';
						break;
					}
					$re[$k]['state'] = $state;
				}
				$count = DB::table('cash_withdraw')->where($where)->count();
				$pages = ceil($count/$pn);
			break;
			case 'balance':
				$re = DB::table('balance_detail')->where($where)->orderBy('id','desc')
				->offset($offset)->limit($pn)->get()->toArray();
				foreach($re as $k=>$v){
					$re[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
					$re[$k]['reason'] = config('deploy.balance_detail')[$v['reason']];
				}
				$count = DB::table('balance_detail')->where($where)->count();
				$pages = ceil($count/$pn);
			break;
		}
		return ['data'=>$re,'pages'=>$pages];
	}
}