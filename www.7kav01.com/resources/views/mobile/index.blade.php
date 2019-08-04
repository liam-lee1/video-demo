@extends('layouts.mb')

@section('title', '首页')

@section('header')
	@include('moudel.header')
@endsection

@section('content')

@if(!empty($new))
	<div class="course-box">
		<h2 class="title">最近更新>></h2>
		<div class="layui-row course-item pd-2">
			@foreach($new as $v)
				<div class="layui-col-xs4 course-item-box">
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

@if(!empty($praise))
	<div class="course-box">
		<h2 class="title">最佳视频>></h2>
		<div class="layui-row course-item pd-2">
			@foreach($praise as $v)
				<div class="layui-col-xs4 course-item-box">
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

@if(!empty($recom))
	<div class="course-box">
		<h2 class="title">推荐视频>></h2>
		<div class="layui-row course-item pd-2">
			@foreach($recom as $v)
				<div class="layui-col-xs4 course-item-box">
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

@section('footer_ad')
   @include('moudel.footer_ad')
@endsection

@section('footer')
   @include('moudel.footer')
@endsection