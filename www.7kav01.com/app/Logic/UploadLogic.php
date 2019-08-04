<?php
namespace App\Logic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Http\Requests;
use Request;

class UploadLogic
{
	public function upload($name,$exts=0,$path='ad'){
		if (!Request::isMethod('post'))return false;
		$file = Request::file($name);
		if(!$file->isValid())return false;
		// 获取文件相关信息
		//$originalName = $file->getClientOriginalName(); // 文件原名
		$ext = $file->getClientOriginalExtension();     // 扩展名
		$realPath = $file->getRealPath();   //临时文件的绝对路径
		$type = $file->getClientMimeType();     // image/jpeg
		if($exts!=0){
			$allow = config('deploy.exts')[$exts];
			if(!in_array($ext,$allow))
				return -1;
		}
		
		// 上传文件
		$filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
		
		$bool = Storage::disk($path)->put($filename, file_get_contents($realPath));
		if($bool)
			return $path.'/'.$filename;
		return $bool;
	}
	
	function base64_image_content($base64_image_content,$path){
		//匹配出图片的格式
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
			$type = $result[2];
			$file = $path.'/';
			if(!file_exists($file)){
				//检查是否有该文件夹，如果没有就创建，并给予最高权限
				mkdir($file, 0700);
			}
			$filename = date('ymd').'-'.uniqid().".{$type}";
			$new_file = $file.$filename;
			if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
				return $filename;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/*本站资源下载*/
	public function down($path,$file=''){
		header("Content-type:text/html;charset=utf-8"); 
		
		//用以解决中文不能显示出来的问题 
		$file_name=iconv("utf-8","gb2312",$file); 
		$file_sub_path = "public/".$path."/";
		$file_path = $file_sub_path.$file_name; 
		//首先要判断给定的文件存在与否 
		if(!file_exists($file_path)){
			echo "没有相关数据下载！";
			return ;
		} 
		
		$fp=fopen($file_path,"r"); 
		$file_size=filesize($file_path); 
		//下载文件需要用到的头 
		Header("Content-type: application/octet-stream"); 
		Header("Accept-Ranges: bytes"); 
		Header("Accept-Length:".$file_size); 
		Header("Content-Disposition: attachment; filename=".$file_name); 
		$buffer=1024; 
		$file_count=0; 
		//向浏览器返回数据 
		while(!feof($fp) && $file_count<$file_size){
			$file_con=fread($fp,$buffer); 
			$file_count+=$buffer; 
			echo $file_con; 
		} 
		fclose($fp); 
	}
	
	public function del_file($path,$filename){
		$exists = Storage::disk($path)->exists($filename);
		if($exists){
			Storage::disk($path)->delete($filename);
		}
	}
	
	/*网址资源下载*/
	public function DownFile($url,$name=''){
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition:attachment; filename='.basename($url));
		if($name)
			header("Content-Disposition:inline;filename=".$name);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		//header('Content-Length: ' . filesize($url));
		ob_clean();
		flush();
		readfile($url);
		return;
	}
}