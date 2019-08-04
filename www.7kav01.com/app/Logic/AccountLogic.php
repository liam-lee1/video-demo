<?php
namespace App\Logic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Http\Requests;
use Request;

class AccountLogic
{
	public function account_detail($openid,$credit,$exp,$type){
		if($credit!=0)
			DB::table('member')->where('openid',$openid)->increment('credit',$credit);
		if($exp!=0)
			DB::table('member')->where('openid',$openid)->increment('exp',$exp);
		$detail = [
			'openid' => $openid,
			'credit' => $credit,
			'exp' => $exp,
			'type' => $type,
			'time' => time()
		];
		return DB::table('account_detail')->insertGetId($detail);
	}
	
	public function balance_detail($openid,$value,$reason){
		$born = DB::table('member')->where('openid',$openid)->value('balance');
		DB::table('member')->where('openid',$openid)->increment('balance',$value);
		$detail = [
			'openid' => $openid,
			'born' => $born,
			'balance' => $value,
			'reason' => $reason,
			'time' => time()
		];
		return DB::table('balance_detail')->insertGetId($detail);
	}
	
	/**
	 * 推广奖励
	 * @param string $openid 用户OPENID
	 */
	public function recom_reword($openid,$reword){
		$manage = DB::table('meb_recom')->where('openid',$openid)->value('manage');
		if(empty($manage))return;
		$this->balance_detail($manage,$reword,1);
	}
	
}