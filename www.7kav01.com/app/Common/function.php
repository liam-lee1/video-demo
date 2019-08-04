<?php
/*bootstrap/autoload.php引入*/
use Illuminate\Support\Facades\Cache;

function createNoncestr( $length = 32 ) 
{
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";  
	$str ="";
	for ( $i = 0; $i < $length; $i++ )  {  
		$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
	}  
	return $str;
}

/**
 *  作用：array转xml
 */
function toXml($arr)
{
	$xml = "<xml>";
	foreach ($arr as $key=>$val)
	{
		 if (is_numeric($val))
		 {
			$xml.="<".$key.">".$val."</".$key.">"; 

		 }
		 else
			$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
	}
	$xml.="</xml>";
	return $xml; 
}

/**
 *  作用：将xml转为array
 */
function toArray($xml)
{       
	//将XML转为array        
	$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);      
	return $array_data;
}

function value_check($data){
	$rule = ' /\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\（|\）|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\||\s+/';
	if(is_array($data)){
		if(!empty($data['email'])){unset($data['email']);}
		foreach(array_values($data) as $v){
			if(preg_match($rule,$v))
				return true;
		}
	}else{
		if(preg_match($rule,$data))
				return true;
	}
	return false;
}

function get_client_ip($type = 0, $adv = false) {
	$type	   =  $type ? 1 : 0;
	static $ip  =   NULL;
	if ($ip !== NULL) return $ip[$type];
	if($adv){
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr	=   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos	=   array_search('unknown',$arr);
			if(false !== $pos) unset($arr[$pos]);
			$ip	 =   trim($arr[0]);
		}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip	 =   $_SERVER['HTTP_CLIENT_IP'];
		}elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip	 =   $_SERVER['REMOTE_ADDR'];
		}
	}elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u",ip2long($ip));
	$ip   = $long ? array($ip, $long) : array('', 0);
	return $ip[$type];
}


/**
　　* 是否移动端访问访问
　　*
　　* @return bool
**/
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    return true;

    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    {
    // 找不到为flase,否则为true
    return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            return true;
    }
        // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    {
    // 如果只支持wml并且不支持html那一定是移动设备
    // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        }
    }
    return false;
}

function Cachekey($key){
	if(!cache($key)){
		$table = 'video_'.$key;
		$val = DB::table($table)->where('hide',0)->orderBy('sort','asc')->orderBy('id','asc')->pluck($key,'id')->toArray();
		Cache::forever($key,$val);
	}
	return cache($key);
}


function is_freeze($openid){
	if(!cache('is_freeze_'.$openid)){
		$is_freeze = DB::table('member')->where('openid',session('openid'))->value('is_freeze');
		cache(['is_freeze_'.$openid => $is_freeze],12*60);
	}
	return cache('is_freeze_'.$openid);
}

//用户登录判定
function is_meb_login()
{
    $meb = json_decode(Cookie::get('meb_auth'),true);
	if(empty($meb)){
        return 0;
    } else {
        return Cookie::get('meb_auth_sign') == data_auth_sign($meb) ? $meb['uid'] : 0;
    }
}

//后台登录判定
function is_admin_login()
{
    $admin = json_decode(Cookie::get('admin_auth'),true);
	if(empty($admin)){
        return 0;
    } else {
        return Cookie::get('admin_auth_sign') == data_auth_sign($admin) ? $admin['admin_id'] : 0;
    }
}

function data_auth_sign($data)
{
    //数据类型检测
    if (!is_array($data)) {
        $data = (array)$data;
    }
    ksort($data); //排序
	$key = '4zh95zdz5tsy11ajms0i2hq7vapb9bj1';
    $code = http_build_query($data); //url编码并生成query字符串
	$code .= '&key='.$key.'$time='.date('y-m-d');
    $sign = sha1($code); //生成签名
    return $sign;
}

//字符串编码
function encode($string) {
	$data = base64_encode($string);
	$data = str_replace(array('+','/','='),array('-','_',''),$data);
	return $data;
}

/// 解码安全的URL文本字符串的Base64
function decode($string) {
   $data = str_replace(array('-','_'),array('+','/'),$string);
   $mod4 = strlen($data) % 4;
   if ($mod4) {
       $data .= substr('====', $mod4);
   }
   return base64_decode($data);
}

function is_weixin() { 
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) { 
        return true; 
    }
	return false; 
}

function transTime($time,$type=0){
	if(empty($time) || $time <= 0)return 0;
	$re = '';
	$h = floor($time/3600);
	$m = floor(($time-3600 * $h)/60);
	$s = floor((($time-3600 * $h) - 60 * $m) % 60);
	if($type==1){
		if($h>0)$re .= sprintf('%02s',$h).':';
		if($m>0)$re .= sprintf('%02s',$m).':';
		$re .= sprintf('%02s',$s);
	}else{
		if($h>0)$re .= $h.'小时';
		if($m>0)$re .= $m.'分钟';
		$re .= $s.'秒';
	}
	return $re;
}

/**
 * 只保留字符串首尾字符，隐藏中间用*代替（两个字符时只显示第一个）
 * @param string $user_name 姓名
 * @return string 格式化后的姓名
 */
function yc_name($user_name){
    $strlen     = mb_strlen($user_name, 'utf-8');
    $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
    $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
    return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
}


//自定义函数手机号隐藏中间四位
function yc_tel($str){
    $str=$str;
    $resstr=substr_replace($str,'****',3,4);
    return $resstr;
}

/**
 * 获取缓存或者更新缓存
 * @param string $config_key 缓存文件名称
 * @param array $data 缓存数据  array('k1'=>'v1','k2'=>'v3')
 * @return array or string or bool
 */
function tpCache($config_key,$data=[]){
	$param = explode('.', $config_key);
    if(empty($data)){
		$config = cache($param[0]);
		if(!$config){
			$res = DB::table('config')->where('inc_type',$param[0])->select('name','value')->get()->toArray();
			if($res){
				foreach($res as $v){
					$config[$v['name']]=$v['value'];
				}
				Cache::forever($param[0],$config);
			}
		}
		if(count($param)>1)
            return $config[$param[1]];
        else
            return $config;
    }else{
        //更新缓存
		$res = DB::table('config')->where('inc_type',$param[0])->select('name','value')->get()->toArray();
		if($res){
			foreach($res as $val){
				$temp[$val['name']] = $val['value'];
			}
			foreach($data as $k=>$v){
				$newArr = ['name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]];
				if(!isset($temp[$k])){
					DB::table('config')->insert($newArr);
				}else{
					if($v!=$temp[$k]){
						DB::table('config')->where('name',$k)->update($newArr);
					}
				}
			}
			$newRes = DB::table('config')->where('inc_type',$param[0])->select('name','value')->get()->toArray();
			foreach($newRes as $v){
				$newData[$v['name']] = $v['value'];
			}
		}else{
			foreach($data as $k=>$v){
                $newArr[] = ['name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]];
				
            }
			DB::table('config')->insert($newArr);
            $newData = $data;
		}
        return Cache::forever($param[0],$newData);
    }
}

function trans_json($json){
	$data = json_decode($json,true);
	if(!empty($data))
		array_multisort(array_column($data,'sort'),SORT_ASC,$data);
	return $data;
}

function enc_aes($str){
	return base64_encode(openssl_encrypt($str, 'AES-128-CBC',config('deploy.SECRETKEY'), OPENSSL_RAW_DATA , config('deploy.iv')));
}

function dec_aes($str) {
	return openssl_decrypt(base64_decode($str), 'AES-128-CBC', config('deploy.SECRETKEY') , OPENSSL_RAW_DATA, config('deploy.iv'));
}

function meb_ispaid(){
	if(empty(session('openid')))
		return 0;
	return DB::table('member')->where('openid',session('openid'))->value('ispaid');
}

function browser_judge(){
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($agent,'MSIE') || strpos($agent,'rv:11.0') || strpos($agent,'UCBrowser') || strpos($agent,'UCWEB'))
		return false;
	return true;
}