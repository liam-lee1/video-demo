@extends('layouts.pc')

@section('title', '首页')

@section('header')
	@include('moudel.pc_header')
@endsection

@section('content')
<div class="course-box">
	<fieldset class="layui-elem-field layui-field-title">
		<legend>最新发布</legend>
	</fieldset>
	<div class="layui-row course-item">
		@foreach($new as $v)
			<div class="layui-col-xs6 layui-col-sm3 course-item-box">
				<a class="item-intro" href="javascript:;" data-preview="{{$v['videopreview']}}" data-eid="{{$v['eid']}}">
					<image src="../images/course/{{$v['image']}}" title="{{$v['name']}}" >
					<span>{{ date('m-d',$v['time']) }}</span>
					@if($v['isfree'] == 1)
						<!--<font>试看</font>-->
					@endif
					<!--<div class="coursr-rate" data-value="{{$v['level']}}"></div>-->
					<p class="text-omit" title="{{$v['name']}}" >{{$v['name']}}</p>
				</a>
			</div>
		@endforeach
	</div>
</div>

@if(!empty($praise))
	<div class="course-box">
		<fieldset class="layui-elem-field layui-field-title">
			<legend>最佳视频</legend>
		</fieldset>
		<div class="layui-row course-item">
			@foreach($praise as $v)
				<div class="layui-col-xs6 layui-col-sm3 course-item-box">
					<a class="item-intro" href="javascript:;" data-preview="{{$v['videopreview']}}" data-eid="{{$v['eid']}}">
						<image src="../images/course/{{$v['image']}}" title="{{$v['name']}}" >
						<span>{{ date('m-d',$v['time']) }}</span>
						@if($v['isfree'] == 1)
							<!--<font>试看</font>-->
						@endif
						<!--<div class="coursr-rate" data-value="{{$v['level']}}"></div>-->
						<p class="text-omit" title="{{$v['name']}}">{{$v['name']}}</p>
					</a>
				</div>
			@endforeach
		</div>
	</div>
@endif

@if(!empty($recom))
	<div class="course-box">
		<fieldset class="layui-elem-field layui-field-title">
			<legend>推荐视频</legend>
		</fieldset>
		<div class="layui-row course-item">
			@foreach($recom as $v)
				<div class="layui-col-xs6 layui-col-sm3 course-item-box">
					<a class="item-intro" href="javascript:;" data-preview="{{$v['videopreview']}}" data-eid="{{$v['eid']}}">
						<image src="../images/course/{{$v['image']}}" title="{{$v['name']}}" >
						<span>{{ date('m-d',$v['time']) }}</span>
						@if($v['isfree'] == 1)
							<!--<font>试看</font>-->
						@endif
						<!--<div class="coursr-rate" data-value="{{$v['level']}}"></div>-->
						<p class="text-omit" title="{{$v['name']}}" >{{$v['name']}}</p>
					</a>
				</div>
			@endforeach
		</div>
	</div>
@endif

@endsection

@section('footer')
	@include('moudel.pc_footer')
@endsection

@section('js')
<script>
layui.use(['rate','element'], function(){
	var rate = layui.rate
		,element = layui.element;
	
})
</script>
@endsection