<?php
namespace App\Logic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Request;
use App\Logic\AccountLogic;

class PayLogic
{
	public function set_payMaiotn(){
		$data=Request::all();
		if(!empty($data['openid'])){
			$openid = $data['openid'];
			$is_exit = DB::table('member')->where('openid',$openid)->value('id');
			if(!$is_exit)return ['status'=>2,'msg'=>'用户不存在','url'=>url('login')];
		}else{
			$openid = session('openid');
		}
		if(!$openid)return ['status'=>2,'msg'=>'未获取到用户信息','url'=>url('login')];
		
		$paid = $data['paid'];
		$type = $data['type'];
		
		if(empty(config('tirParty.serviceType')[$type]))
			return ['status'=>2,'msg'=>'支付类型有误','url'=>'javascript:history.back();'];
		
		$meb_paid = DB::table('meb_paid')->where('id',$paid)->select('price','active','reword')->first();
		$price = $meb_paid['price'];
		if(!$price)return ['status'=>2,'msg'=>'未查询到充值类型','url'=>'javascript:history.back();'];
		
		$where[] = ['paid',$paid];
		$where[] = ['serviceType',$type];
		$where[] = ['orderStatus',0];
		$trade = DB::table('trade')->where($where)->first();
		
		if(empty($trade) || (!empty($trade) && $trade['orderTime'] < time()-5*60)){
			$trade = [
				'order_no' => $this->get_order_no(),
				'openid' => $openid,
				'price' => $price,
				'orderTime' => time(),
				'serviceType' => $type,
				'paid' => $paid
			];
			DB::table('trade')->insert($trade);
		}
		
		return $trade;
	}
	
	/**
	 * 获取订单 out_trade_no
	 * @return string
	 */
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
	
	public function handle_trade($trade,$trade_no=''){
		$update = [
			'trade_no'=>$transaction_id,
			'orderStatus'=>1,
			'dealTime'=> time()
		];
		DB::table('trade')->where('id',$trade['id'])->update($update);
		
		$meb_paid = DB::table('meb_paid')->where('id',$trade['paid'])->select('active','reword')->first();
		$AccountLogic = new AccountLogic();
		if($meb_paid['reword']>0){
			$AccountLogic->recom_reword($trade['openid'],$meb_paid['reword']);
		}
		$AccountLogic->meb_vip($trade['openid'],$meb_paid['active']);
		
	}
}