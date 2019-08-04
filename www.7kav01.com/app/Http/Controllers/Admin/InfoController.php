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


class InfoController extends Controller
{  
	public function notice_list(){
		return view('admin/info/notice_list');
	}
	
	public function get_notice_list(){
		$where = [];
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('notice')->where($where)->orderBy('hide','asc')->orderBy('id','desc')
			->select('id','title','sort','hide','time')
			->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sorts'] = $i;
			$re[$k]['time'] = date('Y-m-d',$v['time']);
			$i++;
		}
		$count = DB::table('notice')->where($where)->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
	}
	
	public function notice(){
		return view('admin/info/notice');
	}
	
	public function notice_xg(){
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id)$notice = DB::table('notice')->where('id',$id)->select('title','sort','content')->first();
		if(empty($notice))$this->error('获取视频失败');
		$content = file_get_contents('images/intro/'.$notice['content']);
		return view('admin/info/notice_xg',[
			'notice'=>$notice,'content'=>$content
		]);
	}
	
	public function notice_eait(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		if(!$data['title'])return ['status'=>2,'msg'=>'公告不能为空'];
		if(!$data['content'])return ['status'=>2,'msg'=>'新闻内容不能为空'];
		unset($data['_token']);
		$data['time'] = time();
		if(empty($data['id'])){
			$name = time().'.txt';
			file_put_contents('images/intro/'.$name,$data['content']);
			$data['content'] = $name;
			DB::table('notice')->insert($data);
			$msg = '新增公告成功';
		}else{
			$content = DB::table('notice')->where('id',$data['id'])->value('content');
			file_put_contents('images/intro/'.$content,$data['content']);
			unset($data['content']);
			DB::table('notice')->where('id',$data['id'])->update($data);
			$msg = '公告修改成功';
		}
		return ['status'=>1,'msg'=>$msg];
	}
	
	public function notice_hide(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$id = $_POST['id'];
		$state = $_POST['state'];
		DB::table('notice')->where('id',$id)->update(['hide'=>$state]);
		return ['status'=>1,'msg'=>'更改状态成功'];
	}
	
	public function del_notice(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$id = $_POST['id'];
		DB::table('notice')->where('id',$id)->delete();
		return ['status'=>1,'msg'=>'删除成功'];
	}
	
	public function ad(){
		$ad = tpCache('ad');
		return view('admin/info/ad',$ad);
	}
	
	public function save_ad(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		$type = $data['type'];
		$ad = json_decode(tpCache('ad.'.$type),true);
		if(empty($ad))$ad = [];
		$Upload = new UploadLogic();
		if(!empty($data['url'])){
			foreach($data['url'] as $k=>$v){
				if(in_array($data['image'][$k],array_column($ad,'image'))){
					$image = $data['image'][$k];
				}else{
					$image = $Upload->base64_image_content($data['image'][$k],'images/ad');
				}
				$arr[] = [
					'image' => $image,
					'url' => $v,
					'sort' => $data['sort'][$k]
				];
			}
		}else{
			$arr = [];
		}
		
		$new[$type] = json_encode($arr);
		tpCache('ad.'.$type,$new);
		return ['status'=>1,'msg'=>'保存成功'];
	}
	
	public function para_set(){
		return view('admin/info/para_set',tpCache('para'));
	}
	
	public function save_para(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		unset($data['_token']);
		$data['level'] = json_encode($data['level']);
		$data['zfb_h5'] = isset($data['zfb_h5']) ? 1 : 0;
		$data['wx_h5'] = isset($data['wx_h5']) ? 1 : 0;
		$data['zfb_scan'] = isset($data['zfb_scan']) ? 1 : 0;
		$data['wx_scan'] = isset($data['wx_scan']) ? 1 : 0;
		tpCache('para',$data);
		return ['status'=>1,'msg'=>'保存成功'];
	}
}