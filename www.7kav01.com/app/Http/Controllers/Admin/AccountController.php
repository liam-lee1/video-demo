<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Session;
use Cookie;
use Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Logic\UploadLogic;
use App\Logic\MebLogic;
use App\Logic\AccountLogic;
use App\Model\Member;


class AccountController extends Controller
{  
	public function manager_list(){
		return view('admin/account/manager_list',[
			'ident' => config('deploy.amdin_meb_ident')
		]);
	}
	
	public function get_managerList(){
		$where = [];
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('admin_meb')->where($where)->orderBy('ident','asc')->orderBy('id','asc')
			->select('id','mebname','ident')
			->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sort'] = $i;
			$re[$k]['ident'] = config('deploy.amdin_meb_ident')[$v['ident']];
			$i++;
		}
		$count = DB::table('admin_meb')->where($where)->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
	}
	
	public function add_manager(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		if($data['pword'] != $data['repword'])
			return ['status'=>2,'msg'=>'两次密码不一致'];
		
		if($data['password']!=config('deploy.implement_pword'))
			return ['status'=>2,'msg'=>'执行密码有误'];
		
		$check = DB::table('admin_meb')->where('mebname',$data['mebname'])->value('id');
		if($check)return ['status'=>2,'msg'=>'该账户已存在'];
		
		$new = [
			'mebname' => $data['mebname'],
			'pword' => md5(md5($data['pword']).config('deploy.implement_pword')),
			'ident' => $data['ident']
		];
		$info = DB::table('admin_meb')->insertGetId($new);
		if($info)
			return ['status'=>1,'msg'=>'新增成功'];
		else
			return ['status'=>2,'msg'=>'新增失败'];
	}
	
	public function edit_manager(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		if($data['pword'] != $data['repword'])
			return ['status'=>2,'msg'=>'两次密码不一致'];
		
		if($data['password']!=config('deploy.implement_pword'))
			return ['status'=>2,'msg'=>'执行密码有误'];
		
		$edit = [
			'pword' => md5(md5($data['pword']).config('deploy.implement_pword')),
			'ident' => $data['ident']
		];
		DB::table('admin_meb')->where('id',$data['id'])->update($edit);
		return ['status'=>1,'msg'=>'编辑成功'];
	}
	
	public function del_manager(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$id = $_POST['mid'];
		if(json_decode(Cookie::get('admin_auth'),true)['admin_lev']!=1)
			return ['status'=>2,'msg'=>'权限不足'];
		DB::table('admin_meb')->delete($id);
		return ['status'=>1,'msg'=>'已删除该管理账户'];
	}
	
	public function meb_list(){
		return view('admin/account/meb_list');
	}
	
	public function increase_account(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		if($data['password']!=config('deploy.implement_pword'))
			return ['status'=>2,'msg'=>'执行密码有误'];
		$openid = DB::table('member')->where('id',$data['id'])->value('openid');
		if(empty($openid))
			return ['status'=>2,'msg'=>'未查询到会员信息'];
		if($data['exp']>0){
			$Member = new Member;
			$Member->meb_level($openid,$data['exp']);
		}
		$accountLogic = new AccountLogic();
		$accountLogic->account_detail($openid,$data['credit'],$data['exp'],4);
		return ['status'=>1,'msg'=>'增添成功'];
	}
	
	public function del_account($id){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		
		if(json_decode(Cookie::get('admin_auth'),true)['admin_lev']!=1)
			return ['status'=>2,'msg'=>'权限不足'];
		DB::table('member')->delete($id);
		return ['status'=>1,'msg'=>'已删除该账户'];
	}
	
	public function meb_freeze($id){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$openid = DB::table('member')->where('id',$id)->value('openid');
		DB::table('member')->where('id',$id)->update(['is_freeze'=>1]);
		Cache::forget('is_freeze_'.$openid);
		return ['status'=>1,'msg'=>'已冻结该账户'];
	}
	
	public function meb_thaw($id){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		DB::table('member')->where('id',$id)->update(['is_freeze'=>0]);
		return ['status'=>1,'msg'=>'已解冻该账户'];
	}
	
	public function get_mebList(){
		$user = isset($_GET['user']) ? $_GET['user'] : '';
		$where = [];
		if($user){
			$openid = DB::table('member')->where('user',$user)->value('openid');
			if($openid)$where[] = ['openid',$openid];
		}
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('member')->where($where)->orderBy('is_freeze','asc')->orderBy('id','desc')
			->select('id','user','openid','level','credit','exp','join_time','is_freeze','last_login','ispaid','active_time','balance')
			->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sort'] = $i;
			$re[$k]['ispaid'] = $v['ispaid'] == 1 ? '是' : '否';
			$re[$k]['time'] = date('Y-m-d H:i:s',$v['join_time']);
			$re[$k]['last_login'] = ($v['last_login']>0) ? date('Y-m-d H:i:s',$v['last_login']) : '---';
			$re[$k]['active_time'] = ($v['active_time']>0) ? date('Y-m-d H:i:s',$v['active_time']) : '---';
			$i++;
		}
		$count = DB::table('member')->where($where)->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
	}
	
	public function account_detail(){
		return view('admin/account/account_detail',[
			'type' => config('deploy.account_detail')
		]);
	}
	
	public function get_accountDetail(){
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$openid = isset($_GET['openid']) ? $_GET['openid'] : '';
		$where = [];
		if($type != '')$where[] = ['type',$type];
		if($openid) $where[] = ['openid',$openid];
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('account_detail')->where($where)->orderBy('id','desc')
			->select('id','openid','credit','exp','type','time')
			->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sort'] = $i;
			$re[$k]['type'] = config('deploy.account_detail')[$v['type']];
			$re[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
			$i++;
		}
		$count = DB::table('account_detail')->where($where)->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
	}
	
	public function balance_detail(){
		return view('admin/account/balance_detail',[
			'reason' => config('deploy.balance_detail')
		]);
	}
	
	public function get_balanceDetail(){
		$reason = isset($_GET['reason']) ? $_GET['reason'] : '';
		$user = isset($_GET['user']) ? $_GET['user'] : '';
		$where = [];
		if($reason != '')$where[] = ['reason',$reason];
		if($user){
			$openid = DB::table('member')->where('user',$user)->value('openid');
			if($openid)$where[] = ['openid',$openid];
		}
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('balance_detail')->where($where)->orderBy('id','desc')
			->select('id','openid','born','balance','reason','time')
			->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sort'] = $i;
			$re[$k]['user'] = DB::table('member')->where('openid',$v['openid'])->value('user');
			$mation = DB::table('meb_mation')->where('openid',$v['openid'])->select('payee_real_name','payee_account')->first();
			$re[$k]['mation'] = !empty($mation) ? $mation['payee_real_name'].'（'.$mation['payee_account'].'）' : '未完善';
			$re[$k]['reason'] = config('deploy.balance_detail')[$v['reason']];
			$re[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
			$i++;
		}
		$count = DB::table('balance_detail')->where($where)->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
	}
	
	public function meb_mation(){
		$id = isset($_GET['uid']) ? $_GET['uid'] : '';
		if(!$id)$this->error('数据传输错误');
		$mation = DB::table('member')->where('id',$id)->first();
		return view('admin/account/meb_mation',[
			'mation' => $mation
		]);
	}
	
	public function modify_account(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		$update = [
			'email' => $data['email'],
			'question' => $data['question'],
			'ans' => $data['ans']
		];
		if($data['pword'])
			$update['pword'] = encrypt($data['pword']);
		
		DB::table('member')->where('id',$data['id'])->update($update);		
		return ['status'=>1,'msg'=>'编辑成功'];
	}
	
	public function withdraw_list(){
		return view('admin/account/withdraw_list'); 
	}
	
	public function withdraw_list_del(){
		return view('admin/account/withdraw_list_del'); 
	}
	
	public function get_withdrawList(){
		$user = isset($_GET['user']) ? $_GET['user'] : '';
		$state = isset($_GET['state']) ? $_GET['state'] : 0;
		$que['start'] = isset($_GET['start']) ? strtotime($_GET['start']) : '';
		$que['end'] = isset($_GET['end']) ? strtotime($_GET['end']) : '';
		$where = [];
		if($user){
			$openid = DB::table('member')->where('user',$user)->value('openid');
			if($openid)$where[] = ['openid',$openid];
		}
		$where[] = ['state',$state];
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('cash_withdraw')->where($where)->where(function($query) use($que){
					if(!empty($que['start']) && empty($que['end'])){
						$query->where('time','>=',$que['start']);
					}
					if(empty($que['start']) && !empty($que['end'])){
						$query->where('time','<=',$que['end']);
					}
					if(!empty($que['start']) && !empty($que['end'])){
						$query->whereBetween('time',[$que['start'],$que['end']]);
					}
				})
			->orderBy('state','asc')->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sort'] = $i;
			$re[$k]['user'] = DB::table('member')->where('openid',$v['openid'])->value('user');
			$mation = DB::table('meb_mation')->where('openid',$v['openid'])->select('payee_real_name','payee_account')->first();
			$re[$k]['mation'] = $mation['payee_real_name'].'（'.$mation['payee_account'].'）';
			$re[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
			if($v['del_time'])
				$re[$k]['del_time'] = date('Y-m-d H:i:s',$v['del_time']);
			$i++;
		}
		$count = DB::table('cash_withdraw')->where($where)->where(function($query) use($que){
					if(!empty($que['start']) && empty($que['end'])){
						$query->where('time','>=',$que['start']);
					}
					if(empty($que['start']) && !empty($que['end'])){
						$query->where('time','<=',$que['end']);
					}
					if(!empty($que['start']) && !empty($que['end'])){
						$query->whereBetween('time',[$que['start'],$que['end']]);
					}
				})->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
	}
	
	public function del_withdraw(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$id = $_POST['id'];
		$state = $_POST['state'];
		$reason = $_POST['reason'];
		if(!$id||!$state)
			return ['status'=>2,'msg'=>'数据获取失败'];
		$withdraw = DB::table('cash_withdraw')->where('id',$id)->select('openid','cash')->first();
		if($state == -1){
			$accountLogic = new AccountLogic();
			$accountLogic->balance_detail($withdraw['openid'],$withdraw['cash'],3);
		}
		DB::table('cash_withdraw')->where('id',$id)->update(['state'=>$state,'del_time'=>time(),'reason'=>$reason]);
		return ['status'=>1,'msg'=>'提现申请修改成功'];
	}
	
	public function trade_list(){
		return view('admin/account/trade_list',[
			'type' => config('tirParty.serviceType')
		]);
	}
	
	public function get_tradeList(){
		$user = isset($_GET['user']) ? $_GET['user'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$que['start'] = isset($_GET['start']) ? strtotime($_GET['start']) : '';
		$que['end'] = isset($_GET['end']) ? strtotime($_GET['end']) : '';
		$where = [];
		if($user){
			$openid = DB::table('member')->where('user',$user)->value('openid');
			if($openid)$where[] = ['openid',$openid];
		}
		$where[] = ['orderStatus',1];
		if($type)$where[] = ['serviceType',$type];
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('trade')->where($where)->where(function($query) use($que){
					if(!empty($que['start']) && empty($que['end'])){
						$query->where('dealTime','>=',$que['start']);
					}
					if(empty($que['start']) && !empty($que['end'])){
						$query->where('dealTime','<=',$que['end']);
					}
					if(!empty($que['start']) && !empty($que['end'])){
						$query->whereBetween('dealTime',[$que['start'],$que['end']]);
					}
				})
			->orderBy('id','desc')->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sort'] = $i;
			$re[$k]['user'] = DB::table('member')->where('openid',$v['openid'])->value('user');
			$mation = DB::table('meb_mation')->where('openid',$v['openid'])->select('payee_real_name','payee_account')->first();
			$re[$k]['mation'] = !empty($mation) ? $mation['payee_real_name'].'（'.$mation['payee_account'].'）' : '未完善';
			$re[$k]['orderStatus'] = $v['orderStatus']==1 ? '交易成功' : '尚未处理';
			$re[$k]['serviceType'] = config('tirParty.serviceType')[$v['serviceType']];
			$re[$k]['orderTime'] = date('Y-m-d H:i:s',$v['orderTime']);
			$re[$k]['dealTime'] = $v['dealTime'] ? date('Y-m-d H:i:s',$v['dealTime']) : '---';
			$i++;
		}
		$count = DB::table('trade')->where($where)->where(function($query) use($que){
					if(!empty($que['start']) && empty($que['end'])){
						$query->where('end_time','>=',$que['start']);
					}
					if(empty($que['start']) && !empty($que['end'])){
						$query->where('end_time','<=',$que['end']);
					}
					if(!empty($que['start']) && !empty($que['end'])){
						$query->whereBetween('end_time',[$que['start'],$que['end']]);
					}
				})->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
	}
	
	public function meb_paid(){
	   return view('admin/account/meb_paid');
   }
   
   public function get_meb_paid(){
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('meb_paid')->orderBy('id','asc')
			->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sorts'] = $i;
			$i++;
		}
		$count = DB::table('meb_paid')->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
   }
   
   public function edit_meb_paid(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		$type = [
			'name' => $data['name'],
			'price' => $data['price'],
			'depict' => $data['depict'],
			'active' => $data['active'],
			'reword' => $data['reword']
		];
		if($data['id']){
			DB::table('meb_paid')->where('id',$data['id'])->update($type);
			$msg = '更新成功';
		}else{
			DB::table('meb_paid')->insert($type);
			$msg = '新增成功';
		}
		return ['status'=>1,'msg'=>$msg];
   }
   
   public function del_meb_paid(){
	   if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$id = $_POST['id'];
		DB::table('meb_paid')->where('id',$id)->delete();
		return ['status'=>1,'msg'=>'删除成功'];
   }
}