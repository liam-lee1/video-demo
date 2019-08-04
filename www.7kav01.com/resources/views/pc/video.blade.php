@extends('layouts.pc')

@section('title', '视频介绍')

@section('header')
	@include('moudel.pc_header')
@endsection

@section('content')
<link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.css')}}">
<div class="course-box">
	<fieldset class="layui-elem-field breadcrumb-item layui-field-title">
		<legend>
			当前位置：
			<span class="layui-breadcrumb" lay-separator=">">
			  <a href="../index">首页</a>
			  <a href="../list?type={{ key($type) }}">{{reset($type)}}
			  <a><cite>《{{$album['name']}}》</cite></a>
			</span>
		</legend>
	</fieldset>
	<div class="layui-row video-item">
		<div class="layui-col-xs9">
			<div class="flex-box item-box line-ud">
				<div class="item-img">
					<image src="../images/course/{{$album['image']}}">
				</div>
				<div class="item-content">
					@if($collect!=0)
						<p class="collect active">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>收藏成功</span>
						</p>
					@else
						<p class="collect">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>收藏到个人中心</span>
						</p>
					@endif
					
					<h2>《{{$album['name']}}》</h2>
					<div class="my-rate">
						<span>影片评分</span>
						<div class="video-rate"></div>
					</div>
					<p>主演：{{$album['lector']}}</p>
					<!--<p>时长：{{transTime($album['duration'])}}</p>-->
					<div class="need-rate">播放所需等级：
						<div class="meb_level_need narrow"></div><a href="../ucenter">（我要提升等级）</a>
					</div>
					<p>分类：@foreach($type as $k=>$v) <a href="../list?type={{$k}}">{{$v}} </a>@endforeach</p>
					<div class="tag-item">
						<p>标签：</p>
						@foreach($tag as $k=>$v)
							<a href="../list?tag={{$k}}">{{$v}}</a>
						@endforeach
					</div>
					@if(!empty($ad))
						<div class="ad-item bg-w">
							@foreach($ad as $v)
								<a href="{{$v['url']}}"><image src="../images/ad/{{$v['image']}}"></a>
							@endforeach
						</div>
					@endif
				</div>
			</div>
			<div class="episode">
				@foreach($episode as $v)
					<a href="javascript:;" data-eid="{{$v}}" >第{{$loop->iteration}}集</a>
				@endforeach
			</div>
		</div>
		<div class="layui-col-xs3">
			@if(!empty($ad_w))
				<div class="ad-item">
					@foreach($ad_w as $v)
						<a href="{{$v['url']}}"><image src="../images/ad/{{$v['image']}}"></a>
					@endforeach
				</div>
			@endif
		</div>
	</div>
	
	<div class="video-intro">
		<fieldset class="layui-elem-field layui-field-title">
			<legend>视频介绍</legend>
		</fieldset>
		<div class="pd-2">
			{!!html_entity_decode($intro)!!}
		</div>
	</div>
</div>
@endsection

@section('footer')
	@include('moudel.pc_footer')
@endsection

@section('js')
<script>
layui.use(['rate','element','layer'], function(){
	var rate = layui.rate
		,layer = layui.layer
		,element = layui.element;
	rate.render({
		elem: '.video-rate'
		,half: true
		,theme:'#ff055a'
		,text: true
		,setText: function(value){
		  this.span.text(value*2);
		}
	});
	
	rate.render({
		elem: '.meb_level_need'
		,value: "{{$album['level']}}"
		,length:3
		,readonly: true
		,theme:'#101010'
	});
	
	var _token = "{{ csrf_token() }}";
	
	rate.render({
		elem: '.video-rate'
		,half: true
		,value: "{{$rate}}"
		,theme:'#ff055a'
		@if($is_rate == 2)
			,readonly:true
		@endif
		,text: true
		,setText: function(value){
		  this.span.text(value*2);
		  @if($is_rate == 0)
				if(value>0){
					layer.open({
					  type: 2,
					  title:false,
					  area: ['1240px', '580px'],
					  fixed: false, //不固定
					  content: "../sign"
					});
				}
			@endif
		  @if($is_rate == 1)
			  if(value>0){
				 layer.confirm('确认评分为'+value*2+'分么？（仅能评分一次）',{icon: 3,title:'提示'}, function(index){
					layer.close(index);
					$.post("{{url('web/album_rate')}}",{_token:_token,aid:"{{$_GET['aid']}}",rate:value*2},function(res){
						layer.close(index);
						if(res.status==2){
							layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
						}						
						if(res.status==1){
							layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
							setTimeout(function(){
								window.location.reload();
							}, 1500);
						}
					})
				}) 
			  }
		  @endif
		}
	});
	
	$('.episode a').click(function(){
		var eid = $(this).data('eid');
		
		@if(!empty(session('openid')) || $album['isfree']==1)
			@if($meb_level < $album['level'] && $meb_level>0 && $album['isfree']==0 && $ispaid == 0)
				layer.open({
					type: 1
					,title: false //不显示标题栏
					,closeBtn: false
					,area: '300px;'
					,shade: 0.3
					,id: 'LAY_layuipro' //设定一个id，防止重复弹出
					,btn: ['充值VIP','免费提升','取消']
					,btnAlign: 'c'
					,content: '<div style="padding:45px;line-height: 22px; background-color: #fff; color: #333; font-weight: 600;font-size:18px;text-align:center;border-bottom:1px solid #efefef;">你当前等级不足观看本视频，请提升等级</div>'
					,yes: function(){
						location.href = "../category";
					}
					,btn2: function(){
						location.href = "../ucenter";
					}
				});
			@else
				location.href="../play?eid="+eid;
			@endif
		@else
			layer.open({
				type: 1
				,title: false //不显示标题栏
				,closeBtn: false
				,area: '300px;'
				,shade: 0.3
				,id: 'LAY_layuipro' //设定一个id，防止重复弹出
				,btn: ['立即登录' , '免费试看']
				,btnAlign: 'c'
				,content: '<div style="padding:45px;line-height: 22px; background-color: #fff; color: #333; font-weight: 600;font-size:18px;text-align:center;border-bottom:1px solid #efefef;">您还未登录，请前往登录或者进入免费试看</div>'
				,yes: function(){
					layer.open({
					  type: 2,
					  title:false,
					  area: ['1240px', '580px'],
					  fixed: false, //不固定
					  content: "../sign"
					});
				}
				,btn2: function(){
					location.href = '../freecourse';
				}
			});
		@endif
	})
	
	$('.collect').click(function(){
		$.post("{{url('web/album_collect')}}",{_token:_token,aid:"{{$_GET['aid']}}"},function(res){
				if(res.status==3){
					layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
					setTimeout(function(){
						layer.open({
						  type: 2,
						  title:false,
						  area: ['1240px', '580px'],
						  fixed: false, //不固定
						  content: res.url
						});
					}, 1500);
				}
				if(res.status==2){
					layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
				}						
				if(res.status==1){
					$('.collect span').html(res.msg);
					if(res.state==1){
						layer.msg('收藏成功');
						$('.collect').addClass('active');
					}
					if(res.state==2){
						layer.msg('已取消收藏');
						$('.collect').removeClass('active');
					}
				}
			})
	})
})
</script>
@endsection