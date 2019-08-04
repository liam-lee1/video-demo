<?php
namespace App\Logic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Http\Requests;
use Request;
use Cookie;

class CourseLogic
{
	public function get_arr($value,$file){
		$arr = explode("|",$value);
		$table = 'video_'.$file;
		foreach($arr as $v){
			$return[$v] = DB::table($table)->where('id',$v)->value($file);
		}
		return $return;
	}
	
	public function randRecom($type,$pn=8){
		$re = DB::table('course_album')->where('type','like','%'.$type[array_rand($type,1)].'%')
				->select('id','name','image','videopreview','isfree','level','time')
				->inRandomOrder()->offset(0)->limit($pn)->get()->toArray();
		foreach($re as $k=>$v){
			$re[$k]['eid'] = DB::table('course_episode')->where('album_id',$v['id'])->value('id');
		}
		return $re;
	}
	
	public function album_overall($album_id){
		$episode = DB::table('course_episode')->where('album_id',$album_id)->get()->toArray();
		foreach($episode as $k=>$v){
			$like[] = DB::table('meb_evaluate')->where([['episode_id',$v['id']],['type',1],['time','>=',time()-14*24*3600]])->count();
			$dislike[] = DB::table('meb_evaluate')->where([['episode_id',$v['id']],['type',2],['time','>=',time()-14*24*3600]])->count();
		}
		$overall = array_sum($like) - array_sum($dislike);
		DB::table('course_album')->where('id',$album_id)->update(['overall'=>$overall]);
	}
}