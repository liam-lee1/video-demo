<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="keywords" content="{{isset($keywords) ? $keywords : ''}}">
		<meta name="description" content="{{isset($description) ? $description : ''}}">
		<title>{{isset($title) ? $title : '在线视频'}} - @if(!empty($sub)) {{$sub}} @else @yield('title') @endif</title>
		<link rel="stylesheet" href="{{ asset('css/global.css') }}">
		<link rel="stylesheet" href="{{ asset('layui/css/layui.css') }}">
		<script type="text/javascript" src="{{ asset('layui/layui.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/flexible.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/base.js') }}"></script>
		@section('style')
		@show
	</head>
    <body class="@yield('body')">
		<div class="main-content">
			@section('header')
			@show
			
			@section('content')
			@show
			
			@section('footer_ad')
				
			@show
		</div>
		@section('footer')
		@show
	@section('js')
	@show
    </body>
</html>
