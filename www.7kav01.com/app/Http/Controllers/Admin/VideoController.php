<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Session;
use Cookie;
use App\Http\Requests;
use Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Logic\UploadLogic;

class VideoController extends Controller
{  
   public function video_type(){
	   return view('admin/video/video_type');
   }
   
   public function get_video_type(){
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('video_type')->orderBy('sort','asc')->orderBy('id','asc')
			->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sorts'] = $i;
			$re[$k]['count'] = DB::table('course_album')->where('type','like',$v['id'].'%')->count();
			$i++;
		}
		$count = DB::table('video_type')->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
   }
   
   public function edit_video_type(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		$type = [
			'type' => $data['type'],
			'sort' => $data['sort'],
			'hide' => $data['hide']
		];
		if($data['id']){
			DB::table('video_type')->where('id',$data['id'])->update($type);
			$msg = '更新成功';
		}else{
			DB::table('video_type')->insert($type);
			$msg = '新增成功';
		}
		Cache::forget('type');
		return ['status'=>1,'msg'=>$msg];
   }
   
   public function del_type(){
	   if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		if(json_decode(Cookie::get('admin_auth'),true)['admin_lev'] != 1)
			return ['status'=>2,'msg'=>'权限不足'];
		$id = $_POST['id'];
		$count = DB::table('course_album')->where('type','like','%'.$id.'%')->count();
		if($count!=0)
			return ['status'=>2,'msg'=>'该分类下仍存在素材，限制删除'];
		DB::table('video_type')->where('id',$id)->delete();
		Cache::forget('type');
		return ['status'=>1,'msg'=>'删除成功'];
   }
   
   public function video_tag(){
	   return view('admin/video/video_tag');
   }
   
   public function get_video_tag(){
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$re = DB::table('video_tag')->orderBy('sort','asc')->orderBy('id','asc')
			->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sorts'] = $i;
			$re[$k]['count'] = DB::table('course_album')->where('tag','like','%'.$v['id'].'%')->count();
			$i++;
		}
		$count = DB::table('video_tag')->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
   }
   
   public function get_tag_list(){
	   $tag = DB::table('video_tag')->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
	   return $tag;
   }
   
   public function edit_video_tag(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		$tag = [
			'tag' => $data['tag'],
			'sort' => $data['sort'],
			'hide' => $data['hide']
		];
		if($data['id']){
			$count = DB::table('video_tag')->where([['tag',$data['tag']],['id','<>',$data['id']]])->count();
			if($count > 0)return ['status'=>2,'msg'=>'该标签已存在'];
			DB::table('video_tag')->where('id',$data['id'])->update($tag);
			$msg = '更新成功';
		}else{
			$count = DB::table('video_tag')->where('tag',$data['tag'])->count();
			if($count > 0)return ['status'=>2,'msg'=>'该标签已存在'];
			DB::table('video_tag')->insert($tag);
			$msg = '新增成功';
		}
		Cache::forget('tag');
		return ['status'=>1,'msg'=>$msg];
   }
   
   public function del_tag(){
	   if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		
		if(json_decode(Cookie::get('admin_auth'),true)['admin_lev'] != 1)
			return ['status'=>2,'msg'=>'权限不足'];
		
		$id = $_POST['id'];
		$count = DB::table('course_album')->where('tag','like','%'.$id.'%')->count();
		if($count!=0)
			return ['status'=>2,'msg'=>'该分类下仍存在素材，限制删除'];
		DB::table('video_tag')->where('id',$id)->delete();
		Cache::forget('tag');
		return ['status'=>1,'msg'=>'删除成功'];
   }
   
   public function video_list(){
	   foreach(Cachekey('tag') as $k=>$v){
			$data[] = ['id'=>$k,'text'=>$v];
		}
	   return view('admin/video/video_list',[
			'type' => Cachekey('type'),'tag' => !empty($data) ? json_encode($data) : []
	   ]);
   }
   
   public function get_video_list(){
		$level = isset($_GET['level']) ? $_GET['level'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$tag = isset($_GET['tag']) ? $_GET['tag'] : '';
		$name = isset($_GET['name']) ? $_GET['name'] : '';
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$i =1;
		$where = [];
		if($level)$where[] = ['level',$level];
		if($type)$where[] = ['type','like',$type.'%'];
		if($name)$where[] = ['name','like','%'.$name.'%'];
		$re = DB::table('course_album')->where($where)
					->when($tag, function ($query) use ($tag) {
					$arr = explode(',',$tag);
					foreach($arr as $v){
						$con[] = ['tag','like','%'.$v.'%'];
					}
                    return $query->where($con);
                })->select('id','name','image','level','tag','type','hide','time','admin_id')
				->offset($offset)->limit($limit)->orderBy('hide','asc')->orderBy('id','desc')->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sort'] = $i;
			$re[$k]['view'] = DB::table('course_episode')->where('album_id',$v['id'])->sum('view');
			$re[$k]['type'] = DB::table('video_type')->where('id',explode('|',$v['type'])[0])->value('type');
			$re[$k]['time'] = date('Y-m-d',$v['time']);
			$re[$k]['admin'] = DB::table('admin_meb')->where('id',$v['admin_id'])->value('mebname');
			$tags = [];
			if($v['tag']){
				$video_tag = explode('|',$v['tag']);
				foreach($video_tag as $v){
					$tags[] = DB::table('video_tag')->where('id',$v)->value('tag');
				}
			}
			$re[$k]['tag'] = $tags;
			$i++;
		}
		
		$count = DB::table('course_album')->where($where)
					->when($tag, function ($query) use ($tag) {
					$arr = explode(',',$tag);
					foreach($arr as $v){
						$con[] = ['tag','like','%'.$v.'%'];
					}
                    return $query->where($con);
                })->count();
		
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
		
   }
   
   public function change_video_state($id){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$state = $_POST['state'];
		switch($state){
			case 0:
				$msg = '已显示该视频';
			break;
			case 1:
				$msg = '已隐藏该视频';
			break;
			default:
			return ['status'=>2,'msg'=>'数据传输错误'];
		}
		DB::table('course_album')->where('id',$id)->update(['hide'=>$state]);
		return ['status'=>1,'msg'=>$msg];
   }
   
   public function del_video($id){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		
		if(json_decode(Cookie::get('admin_auth'),true)['admin_lev'] != 1)
			return ['status'=>2,'msg'=>'权限不足'];
		
		DB::table('course_album')->where('id',$id)->delete();
		DB::table('course_episode')->where('album_id',$id)->delete();
		return ['status'=>1,'msg'=>'删除成功'];
   } 
   
   public function add_video(){
		return view('admin/video/add_video',[
			'type' => Cachekey('type'),'tag' => Cachekey('tag')
		]);
   }
   
   public function edit_video(){
	   $vid = isset($_GET['vid']) ? $_GET['vid'] : '';
	   if($vid)$album = DB::table('course_album')->where('id',$vid)->first();
	   if(empty($album))$this->error('获取视频失败');
	   $video_type = explode('|',$album['type']);
	   $main = $video_type[0];
	   unset($video_type[0]);
	   
	   $types = Cachekey('type');
	   unset($types[$main]);
	   
	   $tags = explode('|',$album['tag']);
	   $intro = $album['intro'] ? file_get_contents('images/intro/'.$album['intro']) : '';
	   
	   $episode = DB::table('course_episode')->where('album_id',$vid)->orderBy('id','asc')
				->select('id','url','downUrl','view','like','dislike')->get()->toArray();
	   
	   return view('admin/video/edit_video',[
			'type' => Cachekey('type'),'types' => $types,'tag' => Cachekey('tag'),'album'=>$album,
			'main'=>$main,'sub'=>$video_type,'tags'=>$tags,'intro'=>$intro,'episode'=>$episode
		]);
   }
   
   public function save_edit(){
	   if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		$data = Request::all();
		
		$downUrl = !empty($data['downUrl']) ? $data['downUrl'] : [];
		if(count($downUrl)==0)
			return ['status'=>2,'msg'=>'数据传输错误'];
		
		$episode = !empty($data['episode']) ? $data['episode'] : [];
		
		$data['level'] = !empty($data['level']) ? $data['level'] : 1;
		$data['isfree'] = !empty($data['isfree']) ? $data['isfree'] : 0;
		$data['isrecom'] = !empty($data['isrecom']) ? $data['isrecom'] : 0;
		
		$data['type'] = !empty($data['types']) ? $data['type'].'|'.implode('|',$data['types']) : $data['type'] ;
		
		if(empty($data['lector']))$data['lector'] = '未知';
		unset($data['file']);
		unset($data['types']);
		unset($data['_token']);
		unset($data['episode']);
		unset($data['downUrl']);
		
		if(!$data['videopreview']){
			$files = [];
			preg_match('/mp4\/(.*?).mp4/',$downUrl[0],$files);
			if(!empty($files[1]))
				$data['videopreview'] = 'https://videopreview.sbdouyin.com/videopreview/'.md5($files[1].'.mp4169').'.mp4';
		}
		$data['time'] = time();
		$Upload = new UploadLogic();
		if(empty($data['id'])){
			foreach($downUrl as $k=>$v){
				if(strlen($v)<65)
					return ['status'=>2,'msg'=>'下载地址有误'];
				if(empty($episode[$k])){
					$re = $this->get_m3u8_url($v);
					if(!$re)
						return ['status'=>2,'msg'=>'下载地址有误'];
					else
						$episode[$k] = $re;	
				}
			}
			/*$file = time().'.txt';
			file_put_contents('images/intro/'.$file,$data['intro']);
			$data['intro'] = $file;*/
			$data['admin_id'] = json_decode(Cookie::get('admin_auth'),true)['admin_id'];
			$data['image'] = $Upload->base64_image_content($data['image'],'images/course');
			$info = DB::table('course_album')->insertGetId($data);
			if(!$info)return ['status'=>2,'msg'=>'数据添加失败'];
			
			foreach($episode as $k=>$v){
				DB::table('course_episode')->insert(['album_id'=>$info,'url'=>$v,'downUrl'=>$downUrl[$k]]);
			}
			$msg = '数据新增成功';
		}else{
			$mation = DB::table('course_album')->where('id',$data['id'])->select('image','intro')->first();
			/*file_put_contents('images/intro/'.$mation['intro'],$data['intro']);
			unset($data['intro']);*/
			if($data['image'] != $mation['image']){
				$Upload->del_file('course',$mation['image']);
				$data['image'] = $Upload->base64_image_content($data['image'],'images/course');
			}
			$eid = $data['eid'];
			$url = $data['url'];
			foreach($downUrl as $k=>$v){
				if(strlen($v)<65)
					return ['status'=>2,'msg'=>'下载地址有误'];
				if(empty($url[$k])){
					$re = $this->get_m3u8_url($v);
					if(!$re)
						return ['status'=>2,'msg'=>'下载地址有误'];
					else
						$url[$k] = $re;	
				}
			}
			unset($data['eid']);
			unset($data['url']);
			$course_episode = DB::table('course_episode')->where('album_id',$data['id'])->pluck('id');
			foreach($course_episode as $v){
				if(in_array($v,$eid)){
					DB::table('course_episode')->where('id',$v)->update(['url'=>$url[array_keys($eid,$v)[0]],'downUrl'=>$downUrl[array_keys($eid,$v)[0]]]);
				}else{
					DB::table('course_episode')->where('id',$v)->delete();
				}
			}
			if(!empty($episode)){
				foreach($episode as $k=>$v){
					DB::table('course_episode')->insert(['album_id'=>$data['id'],'url'=>$v,'downUrl'=>$downUrl[$k]]);
				}
			}
			DB::table('course_album')->where('id',$data['id'])->update($data);
			$msg = '数据修改成功';
		}
		return ['status'=>1,'msg'=>$msg];
   }
   
   public function get_m3u8_url($url){
		$file = [];
		preg_match('/mp4\/(.*?).mp4/',$url,$file);
		if(empty($file[1]))return false;
		
		return 'https://mp4.sbdouyin.com/m3u8/'.md5($file[1].'.mp4').'/'.md5($file[1].'.mp4').'.m3u8';
   }
   
   public function video_errors(){
	   return view('admin/video/video_errors');
   }
   
   public function get_errors_list(){
		$state = isset($_GET['state']) ? $_GET['state'] : 0;
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$offset = ($page-1)*$limit;
		$where[] = ['state',$state];
		$i =1;
		$re = DB::table('errors')->orderBy('id','asc')
			->where($where)->offset($offset)->limit($limit)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['sorts'] = $i;
			$user = DB::table('member')->where('openid',$v['openid'])->select('id','user')->first();
			$re[$k]['uid'] = $user['id'];
			$re[$k]['user'] = $user['user'];
			$aid = DB::table('course_episode')->where('id',$v['eid'])->value('album_id');
			$re[$k]['aid'] = $aid;
			$re[$k]['album'] = DB::table('course_album')->where('id',$aid)->value('name');
			$re[$k]['type'] = config('deploy.errors_type')[$v['type']];
			$re[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
			$i++;
		}
		$count = DB::table('errors')->where($where)->count();
		$res = array('code'=>0,'msg'=>"",'count'=>$count,'data'=>$re);
		print_r(json_encode($res));
   }
   
   public function video_errors_del(){
	   if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
	   
	   $state = $_POST['state'];
	   $id = $_POST['id'];
	   DB::table('errors')->where('id',$id)->update(['state'=>$state]);
	   return ['status'=>1,'msg'=>'状态更新成功'];
   }
}