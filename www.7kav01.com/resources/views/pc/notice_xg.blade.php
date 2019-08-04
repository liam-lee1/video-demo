@extends('layouts.pc')

@section('title', '公告详情')

@section('header')
	@include('moudel.pc_header')
@endsection

@section('content')
	<style>
		.title{text-align:center;font-size:22px;font-weight:600;}
		.time{text-align:right;font-size:16px;margin:0;padding:5px 10px;}
		.content{font-size:16px;padding:5px 10px;text-indent:2em;margin:0;}
	</style>
	<div class="content-box bg_w pd-2">
		<div class="title">{{$notice['title']}}</div>
		<p class="time">{{date('Y年m月d日',$notice['time'])}}</p>
		<div class="content">
			{!!html_entity_decode($content)!!}
		</div>
	</div>
@endsection

@section('footer')
   @include('moudel.pc_footer')
@endsection