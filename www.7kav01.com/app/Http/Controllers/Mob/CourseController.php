<?php
namespace App\Http\Controllers\Mob;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Logic\CourseLogic;
use App\Model\Member;
use Request;
use Session;
use Cookie;

class CourseController extends Controller
{
    public function course_list(){
		$view = isMobile() ? 'mobile/list' : 'pc/list';
		return view($view,[
			'tag'=>json_encode(Cachekey('tag'))
		]);
	}
	
	public function video(){
		$aid = isset($_GET['aid']) ? $_GET['aid'] : '';
		$s = isset($_GET['s']) ? $_GET['s'] : '';
		if($aid)
			$album = DB::table('course_album')->where([['id',$aid],['hide',0]])
			->select('id','name','image','level','lector','tag','type','duration','intro','isfree')
			->first();
		if(empty($album))return $this->error('未查询到相关视频');
		
		if($s && empty(session('mark'))){
			session(['recom_mation'=>$s],24*60);
			$Member = new Member;
			$Member->meb_relation(1);
			session(['mark'=> uniqid()],24*60);
			sleep(0.1);
		}
		
		$episode = DB::table('course_episode')->where('album_id',$album['id'])->pluck('id');
		
		$CourseLogic = new CourseLogic();
		$tag = $CourseLogic->get_arr($album['tag'],'tag');
		$type = $CourseLogic->get_arr($album['type'],'type');
		
		$intro = !empty($album['intro']) ? file_get_contents('images/intro/'.$album['intro']) : '';
		
		$is_rate = 0;
		if(session('openid')){
			$rate = DB::table('rate_record')->where([['openid',session('openid')],['album_id',$aid]])->value('rate');
			if(!$rate)
				$is_rate = 1;
			else
				$is_rate = 2;
		}
		
		
		$collect = DB::table('meb_collect')->where([['openid',session('openid')],['album_id',$aid]])->value('id');
		
		$ad = trans_json(tpCache('ad.wap_content'));
		
		$ad_w = isMobile() ? '' : trans_json(tpCache('ad.pc_content_w'));
		
		$meb_level = !empty(session('openid')) ? DB::table('member')->where('openid',session('openid'))->value('level') : 0;
		
		$ispaid = !empty(session('openid')) ? DB::table('member')->where('openid',session('openid'))->value('ispaid') : 0;
		
		$view = isMobile() ? 'mobile/video' : 'pc/video';
		
		return view($view,[
			'album'=>$album,'episode'=>$episode,'tag'=>$tag,'type'=>$type,'intro'=>$intro,'ad'=>$ad,
			'rate' => !empty($rate) ? $rate/2 : 0, 'is_rate'=>$is_rate,'collect' => !empty($collect) ? $collect :0,
			'meb_level' => $meb_level,'ad_w'=>$ad_w,'ispaid'=>$ispaid
		]);
	}
	
	public function play(){
		$eid = isset($_GET['eid']) ? $_GET['eid'] : '';
		$s = isset($_GET['s']) ? $_GET['s'] : '';
		if($eid)
			$episode = DB::table('course_episode')->where('id',$eid)->first();
		if(empty($episode))return $this->error('未查询到相关视频');
		
		if($s && empty(session('mark'))){
			session(['recom_mation'=>$s],24*60);
			$Member = new Member;
			$Member->meb_relation(1);
			session(['mark'=> uniqid()],24*60);
			sleep(0.1);
		}
		
		$album = DB::table('course_album')->where('id',$episode['album_id'])->select('name','tag','type','level','isfree')->first();
		
		/*if(empty(session('openid'))&&$album['isfree'] == 0)return redirect()->route('freecourse');
		
		if(!empty(session('openid'))){
			$meb = DB::table('member')->where('openid',session('openid'))->select('id','level','ispaid')->first();
			if($album['isfree'] == 0 && $meb['level']< $album['level'] && $meb['ispaid']==0)return $this->error('账户等级不足');
			
			$type = DB::table('meb_evaluate')->where([['openid',session('openid')],['episode_id',$eid]])->value('type');
			
			$collect = DB::table('meb_collect')->where([['openid',session('openid')],['album_id',$episode['album_id']]])->value('id');
		}*/
		
		
		if(!Cookie::get('video_play_'.$eid)){
			DB::table('course_episode')->where('id',$eid)->increment('view');
			Cookie::queue('video_play_'.$eid,1,60);
		}
		
		$CourseLogic = new CourseLogic();
		$album = DB::table('course_album')->where('id',$episode['album_id'])->select('name','tag','type')->first();
		$tag = $CourseLogic->get_arr($album['tag'],'tag');
		$type_a = $CourseLogic->get_arr($album['type'],'type');
		$types = explode("|",$album['type']);
		$recom = $CourseLogic->randRecom($types,12);
		
		$album_type = DB::table('video_type')->where('id',$types[0])->select('id','type')->first();
		
		$ad = trans_json(tpCache('ad.wap_play'));
		
		$url = url('/play').'?eid='.$eid;
		
		$share_url = !empty($meb) ? $url.'&s='.strrev(bin2hex(json_encode(['s'=>$meb['id']]))) : $url;
		
		$view = isMobile() ? 'mobile/play' : 'pc/play';
		
		$t = time();
		
		$k =  uniqid();
		
		$isMp4 = strpos($episode['url'],'.mp4') ? 1 : 0;
		
		if($isMp4)session(['allow_'.$k => enc_aes($eid)],1);
		
		$key = enc_aes(json_encode(['k'=>$k,'t'=>time()]));
		
		return view( $view ,[
			'episode' => $episode,'tag'=>$tag,'type_a'=>$type_a,'recom'=>$recom,'name'=>$album['name'],'reduce' => tpCache('para.reduce'),
			'collect' => !empty($collect) ? $collect :0,'type'=>!empty($type) ? $type : '','share_url'=>$share_url,
			'album_type' => $album_type,'ad'=>$ad,'t'=>$t,'key' => urlencode($key),'isMp4' => $isMp4
		]);
	}
	
	public function errors_jduge(){
		if(!Request::ajax())
			return ['status'=>2,'msg'=>'数据传输错误'];
		
		if(!isset($_POST['eid']) || !isset($_POST['type']))
			return ['status'=>2,'msg'=>'数据传输错误'];
		
		$eid = $_POST['eid'];
		$type = $_POST['type'];
		
		if(empty(session('openid'))){
			if(isMobile())
				$url = url('m/signin');
			else
				$url = url('sign');
			return ['status'=>3,'msg'=>'请先登录','url'=>$url];
		}
		$count = DB::table('errors')->where('eid',$eid)->count();
		if($count>=10)return ['status'=>2,'msg'=>'已有人提交错误'];
		$check = DB::table('errors')->where([['openid',session('openid')],['eid',$eid]])->value('id');
		if($check)return ['status'=>2,'msg'=>'你已提交过报错'];
		DB::table('errors')->insert(['openid'=>session('openid'),'eid'=>$eid,'type'=>$type,'time'=>time()]);
		return ['status'=>1,'msg'=>'提交成功'];
	}
	
	public function get_episode_url(){
		$eid = $_GET['eid'];
		$url = DB::table('course_episode')->where('id',$eid)->value('url');
		return $url;
	}
	
	public function freecourse(){
		$view = isMobile() ? 'mobile/freecourse' : 'pc/freecourse';
		return view($view);
	}
	
	public function getCourseList(){
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$tag = isset($_GET['tag']) ? $_GET['tag'] : '';
		$key = isset($_GET['key']) ? $_GET['key'] : '';
		$isfree = isset($_GET['isfree']) ? $_GET['isfree'] : '';
		$page = $_GET['page']?$_GET['page']:1;
		$where = [];
		$pn = 16;
		$offset = ($page-1)*$pn;
		$where[] = ['hide',0];
		if($isfree){
			$where[] = ['isfree',1];
		}else{
			if($type)$where[] = ['type','like','%'.$type.'%'];
			if($key)$where[] = ['name','like','%'.$key.'%'];
		}
		
		$re = DB::table('course_album')->where($where)
					->when($tag, function ($query) use ($tag) {
					$arr = explode(',',$tag);
					foreach($arr as $v){
						$con[] = ['tag','like','%'.$v.'%'];
					}
                    return $query->where($con);
                })
				
				->select('id','name','image','level','videopreview','isfree','time')->offset($offset)->limit($pn)->orderBy('time','desc')->get()->toArray();
		
		foreach($re as $k=>$v){
			$re[$k]['eid'] = DB::table('course_episode')->where('album_id',$v['id'])->value('id');
			$re[$k]['time'] = date('m-d',$v['time']);
		}
		$count = DB::table('course_album')->where($where)
					->when($tag, function ($query) use ($tag) {
					$arr = explode(',',$tag);
					foreach($arr as $v){
						$con[] = ['tag','like','%'.$v.'%'];
					}
                    return $query->where($con);
                })->count();
		
		return ['data'=>$re,'count'=>$count];
	}
}