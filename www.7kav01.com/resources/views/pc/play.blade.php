@extends('layouts.pc')

@section('title', '视频播放')

@section('style')
	<link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.css')}}">
	<script type="text/javascript" src="{{ asset('js/clipboard.min.js') }}"></script>
	<link rel="stylesheet" href="../css/DPlayer.min.css">
	<script src="../js/hls.min.js"></script>
	<script src="../js/DPlayer.min.js"></script>
@endsection

@section('header')
	@include('moudel.pc_header')
@endsection

@section('content')
<style>
	.dplayer-video-wrap .dplayer-video{max-height:600px;}
	.dplayer-paused .dplayer-bezel .dplayer-bezel-icon{opacity:1;}
</style>
<div class="play-box bg-w">
	<fieldset class="layui-elem-field layui-field-title">
		<legend>
			<a href="../m/index">首页</a> > <a href="../m/list?type={{$album_type['id']}}">{{$album_type['type']}}</a> > 《{{$name}}》
		</legend>
	</fieldset>
	
	<div id="dplayer"></div>
	
	<div class="play-mation">
		<div class="fl">
			<i class="fa fa-youtube-play" aria-hidden="true"></i>
			<b>{{$episode['view']}}</b>次观看
		</div>
		<div style="display:inline-block;">
			<span class="evaluate @if(!empty($type)&& $type==1 ) active @endif " data-type="1">
				<i class="fa fa-thumbs-up" aria-hidden="true"></i>
				<t>{{$episode['like']}}</t>
			</span>
			<span class="evaluate @if(!empty($type)&& $type==2 ) active @endif" data-type="2">
				<i class="fa fa-thumbs-down" aria-hidden="true"></i>
				<t>{{$episode['dislike']}}</t>
			</span>
		</div>
		@if(!empty($collect))
			<span class="collect active">
				<i class="fa fa-folder" aria-hidden="true"></i>
				<t>已收藏</t>
			</span>
		@else
			<span class="collect">
				<i class="fa fa-folder" aria-hidden="true"></i>
				<t>收藏</t>
			</span>

		@endif
		
		<span class="share" data-clipboard-text="{{$share_url}}">
			<i class="layui-icon layui-icon-share" aria-hidden="true"></i>
			分享
		</span>
		<font>分享可提升会员等级和增加积分</font>
		
		<span class="errors">
			<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
			报错
		</span>
		<font>（若情况属实，会奖赏一定的积分）</font>
		
		<div class="fr download">
			<i class="fa fa-download" aria-hidden="true"></i>
			下载本视频
			<font>下载扣除{{ $reduce }}积分</font>
		</div>
	</div>
	<div class="tag-item">
		<p>所属分类：@foreach($type_a as $k=>$v) <a href="../list?type={{$k}}">{{$v}} </a>@endforeach</p>
	</div>
	<div class="tag-item">
		<p>当前标签：</p>
		@foreach($tag as $k=>$v)
			<a href="../list?tag={{$k}}">{{$v}}</a>
		@endforeach
	</div>
</div>
<div class="course-box bg-w">
	<fieldset class="layui-elem-field layui-field-title">
		<legend>相关视频</legend>
	</fieldset>
	<div class="layui-row course-item">
		@foreach($recom as $v)
			<div class="layui-col-xs6 layui-col-sm3 course-item-box">
				<a class="item-intro" href="javascript:;" data-preview="{{$v['videopreview']}}" data-eid="{{$v['eid']}}" >
					<image src="../images/course/{{$v['image']}}">
					<span>{{ date('m-d',$v['time']) }}</span>
					@if($v['isfree'] == 1)
						<!--<font>试看</font>-->
					@endif
					<!--<div class="coursr-rate" data-value="{{$v['level']}}"></div>-->
					<p class="text-omit">{{$v['name']}}</p>
				</a>
			</div>
		@endforeach
	</div>
</div>

<section class="errors_box pd-2" style="display:none;">
	<h2>请选择错误类型</h2>
	<h3 style=color:red>&nbsp;&nbsp;&nbsp;&nbsp;人工审核，乱举报直接封号处理!</h3>
	<div class="layui-form">
		<input type="hidden" name="eid" value="{{$_GET['eid']}}">
		<div class="layui-form-item">
			@foreach(config('deploy.errors_type') as $k=>$v)
			<div class="layui-input-block">
				<input type="radio" name="type" value="{{$k}}" title="{{$v}}" >
			</div>
			@endforeach
		</div>
		<div class="layui-form-item t-center">
			  <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="errors">立即提交</button>
		</div>
	</div>
</section>
@endsection

@section('footer')
	@include('moudel.pc_footer')
@endsection

@section('js')
<script>
layui.use(['rate','element','layer','form'], function(){
	var rate = layui.rate
		,layer = layui.layer
		,form = layui.form
		,element = layui.element;
	
	var _token = "{{ csrf_token() }}";
	
	$("video").on("contextmenu",function(){return false;});
	
	const dp = new DPlayer({
		container: document.getElementById('dplayer'),
		screenshot: false,
		height:'700',
		video: {
			@if($isMp4 == 1)
				url: '../web/get_play_url?t={{$t}}&key={{$key}}',
			@else
				url:"{{$episode['url']}}",
			@endif
			pic: '../images/poster/loding.gif',
			//thumbnails: '../images/poster/loding.gif',
		}
	});
	
	dp.on('fullscreen',function(){
		$('video').css('max-height','100%');
	});
	
	dp.on('fullscreen_cancel',function(){
		$('video').css('max-height','600px');
	});
	
	$('.collect').click(function(){
		$.post("{{url('web/album_collect')}}",{_token:_token,aid:"{{$episode['album_id']}}"},function(res){
			if(res.status==3){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					layer.open({
					  type: 2,
					  title:false,
					  area: ['1240px', '580px'],
					  fixed: false, //不固定
					  content: res.url
					})
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}						
			if(res.status==1){
				if(res.state==1){
					layer.msg('收藏成功');
					$('.collect t').html('已收藏');
					$('.collect').addClass('active');
					
				}
				if(res.state==2){
					layer.msg('已取消收藏');
					$('.collect t').html('收藏');
					$('.collect').removeClass('active');
				}
			}
		})
	})
	
	$('.evaluate').click(function(){
		var type = $(this).data('type')
			_this = $(this);
		$.post("{{url('web/episode_evaluate')}}",{_token:_token,eid:"{{$_GET['eid']}}",type:type},function(res){
			if(res.status==3){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					layer.open({
					  type: 2,
					  title:false,
					  area: ['1240px', '580px'],
					  fixed: false, //不固定
					  content: res.url
					})
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}						
			if(res.status==1){
				layer.msg(res.msg);
				var t = parseInt(_this.find('t').text());
				if(res.state==1){
					var a = parseInt(_this.siblings().find('t').text());
					_this.addClass('active').siblings().removeClass('active');
					_this.find('t').text(t+1);
					_this.siblings().find('t').text(a-1);
				}
				if(res.state==2){
					_this.removeClass('active');
					_this.find('t').text(t-1);
				}
				
				if(res.state==3){
					_this.addClass('active');
					_this.find('t').text(t+1);
				}
			}
		})
	})
	
	$('.download').click(function(){
		$.post("{{url('web/download_video')}}",{_token:_token,eid:"{{$_GET['eid']}}"},function(res){
			if(res.status==3){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					layer.open({
					  type: 2,
					  title:false,
					  area: ['1240px', '580px'],
					  fixed: false, //不固定
					  content: res.url
					})
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
			}
			if(res.status==1){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					var url = res.url+'&key='+encodeURIComponent(res.key);
					location.href = url;
				}, 1500);
			}
		})
	})
	
	$('.errors').click(function(){
		layer.open({
		  type: 1,
		  title:0,
		  shadeClose: true,
		  skin: 'layui-layer-rim',
		  area: ['480px'],
		  content: $('.errors_box')
		});
	});
	
	form.on('submit(errors)', function(data){
		data.field._token = _token;
		if(typeof(data.field.type)=='undefined'){
			layer.msg('请选择错误类型',{icon: 2,time: 1500,anim: 6,shade:0.3});return;
		}
		$.post("{{url('web/errors_jduge')}}",data.field,function(res){
			if(res.status==3){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					layer.open({
					  type: 2,
					  title:false,
					  area: ['1240px', '580px'],
					  fixed: false, //不固定
					  content: res.url
					})
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}						
			if(res.status==1){
				layer.msg(res.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					layer.closeAll();
				},1500);
			}
		})
	});
	
	var clipboard = new Clipboard('.share');

	clipboard.on('success', function(e) {
		layer.msg('复制分享链接成功，请分享给你的好友',{icon:1,time:1500});
	});

	clipboard.on('error', function(e) {
		layer.msg('复制失败');
	});
})
</script>
@endsection