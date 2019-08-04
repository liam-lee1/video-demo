<?php
namespace App\Http\Controllers\ZFB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests;
use Request;
use App\Logic\PayLogic;
use App\Lib\Alipay\AlipayRsa;
use App\Lib\Alipay\AlipayService;

class ZfpayController extends Controller {	
	public function alipay_wappay(){
		$payLogic = new PayLogic();
		$trade = $payLogic->set_payMaiotn();
		if(!empty($trade['status']) && $trade['status']==2)
			return error($trade['msg'],$trade['url']);
		$outTradeNo = $trade['order_no'];
		$orderName = '线上消费';
		//$payAmount = (string)$trade['price'];
		$payAmount = 0.01;
		
		$config = config('tirParty.ZFB');
		$appid = $config['app_id'];
		$returnUrl = url('alipay/return');     //付款成功后的同步回调地址
		$notifyUrl = url('alipay/notify');     //付款成功后的异步回调地址
		$signType = 'RSA2';
		$saPrivateKey= $config['merchant_private_key'];
		
		$aliPay = new AlipayService($appid,$returnUrl,$notifyUrl,$saPrivateKey);
		$sHtml = $aliPay->doPay($payAmount,$outTradeNo,$orderName,$returnUrl,$notifyUrl);
		$queryStr = http_build_query($sHtml);
		
		$gotoUrl = 'https://openapi.alipay.com/gateway.do?'.$queryStr;
		
		$is_wx = is_weixin() ? 1 : 0;
		if($is_wx==0){
			return redirect($gotoUrl);
		}
		
		return view('mobile/alipay/alipay_wappay',[
			'gotoUrl'=>$gotoUrl
		]);
	}
	
	public function return_url(){
		file_put_contents('return_1.txt',json_encode($_POST));
		$config = config('tirParty.ZFB');
		$aliPay = new AlipayRsa($config['alipay_public_key']);		
		$result = $aliPay->rsaCheck($_GET,$_GET['sign_type']);
		file_put_contents('return.txt',$result);
		if($result) {//验证成功
			/* 
			//商户订单号
			$out_trade_no = htmlspecialchars($_GET['out_trade_no']);

			//支付宝交易号
			$trade_no = htmlspecialchars($_GET['trade_no']);
			$total_amount = $_GET['total_amount'];
			 */
			return redirect('m/ucenter');
		}
		else {
			//验证失败
			$res = 2;
		}
	}
	
	public function notify_url(){
		file_put_contents('notify_1.txt',json_encode($_POST));
		$config = config('tirParty.ZFB');
		$aliPay = new AlipayRsa($config['alipay_public_key']);
		//验证签名
		$result = $aliPay->rsaCheck($_POST,$_POST['sign_type']);
		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/
		if($result) {//验证成功	
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			//交易状态
			$trade_status = $_POST['trade_status'];
			if($trade_status == 'TRADE_FINISHED') {
				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
					//如果有做过处理，不执行商户的业务程序
						
				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
			}
			else if ($trade_status == 'TRADE_SUCCESS') {
				$trade = DB::table('trade')->where([['order_no',$order_no],['ispay',0]])->first();
				if(!empty($trade)){
					$payLogic = new PayLogic();
					$payLogic->handle_trade($trade,$trade_no);
				}
			}
			echo "success";		//请不要修改或删除
				
		}else {
			//验证失败
			echo "fail";	//请不要修改或删除

		}
	}
}