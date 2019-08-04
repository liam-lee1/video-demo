@extends('layouts.mb')

@section('title', '公告详情')

@section('header')
	@include('moudel.header')
@endsection

@section('content')
	<style>
		.title{text-align:center;font-size:.55rem;font-weight:600;}
		.time{text-align:right;font-size:.375rem;margin:0;padding:.15rem .35rem;}
		.content{font-size:.4rem;padding:.2rem .3rem;text-indent:2em;margin:0;}
		.content img{width:auto;max-width:100%;object-fit: contain;}
	</style>
	<div class="content-box clear-bottom bg_w pd-2">
		<div class="title">{{$notice['title']}}</div>
		<p class="time">{{date('Y年m月d日',$notice['time'])}}</p>
		<div class="content">
			{!!html_entity_decode($content)!!}
		</div>
	</div>
@endsection

@section('footer')
   @include('moudel.footer')
@endsection