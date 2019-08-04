<?php
namespace App\Http\Controllers\Mob;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Logic\AccountLogic;
use Request;
use Session;
use Cookie;

class PayController extends Controller
{
	public function category(){
		$meb_paid = DB::table('meb_paid')->select('id','name','price','depict')->get()->toArray();
		$view = isMobile() ? 'mobile/category' : 'pc/category';
		return view($view,[
			'meb_paid'=>$meb_paid
		]);
	}
	
	
	public function creat_trade(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		
		if(empty(session('openid'))){
			if(isMobile())
				$url = url('m/signin');
			else
				$url = url('sign');
			return ['status'=>3,'msg'=>'请先登录','url'=>$url];
		}
		$paid = $_POST['paid'];
		$type = $_POST['type'];
		$openid = session('openid');
		/*if(cache('trade_'.$type.$paid.$openid))
			return cache('trade_'.$type.$paid.$openid);*/
		
		$meb_paid = DB::table('meb_paid')->where('id',$paid)->select('price','active','reword')->first();
		$price = $meb_paid['price'];
		if(!$price)return ['status'=>2,'msg'=>'未查询到充值类型'];
		
		if($type==1000){
			//余额支付
			$balance = DB::table('member')->where('openid',$openid)->value('balance');
			if($balance<$price) return ['status'=>2,'msg'=>'账户余额不足'];
			$accountLogic = new AccountLogic();
			$accountLogic->meb_vip($openid,$meb_paid['active']);
			$accountLogic->balance_detail($openid,-$price,4);
			$accountLogic->recom_reword($openid,$meb_paid['reword']);
			return ['status'=>1,'msg'=>'支付成功'];
		}else{
			if(empty(config('tirParty.serviceType')[$type]))
				return ['status'=>2,'msg'=>'支付类型有误'];
			
			switch($type){
				case 1101:
				case 1201:
					$url = url('alipay/alipay_wappay').'?paid='.$paid.'&type='.$type;
				break;
				case 1102:
				case 1202:
					return ['status'=>2,'msg'=>'当前支付暂未开通'];
				break;
			}
			
			return ['status'=>1,'msg'=>'调起支付中...','payUrl'=>$url];
		}
	}
	
	public function trade_cache($name,$val=[]){
		if(empty($val))
			return json_decode(Cookie::get($name),true);
		
		Cookie::queue($name,json_encode($val),1);
		return false;
	}
	
	public function get_order_no()
	{
	    $order_no = null;
	    // 保证不会有重复订单号存在
	    while(true){
	        $order_no = date('YmdHis').rand(1000,9999); // 订单编号	        
	        $order_no_count = DB::table('trade')->where('order_no',$order_no)->count();
	        if($order_no_count == 0)break;
	    }
	    return $order_no;
	}
	
}