@extends('layouts.pc')

@section('title', '登录注册')

@section('style')
	<link rel="stylesheet" href="{{ asset('css/sign.css') }}">
	<style>
		.container .hide{display:none;}
		.sign-form .step span{color:#FF2D50;float: left;padding: 3px 8px;font-size: 13px;text-align: left;}
	</style>
@endsection
@section('content')
<div class="pd-5 sign-box">
	<div class="container" id="container">
		<div class="form-container sign-up-container">
			<div class="sign-form layui-form">
				<h1>注册</h1>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="text" lay-verify="required|user" name="user" autocomplete="off" placeholder="用户名" />
				<input type="email" lay-verify="required|email" name="email" autocomplete="off" placeholder="邮箱" />
				<input type="password" lay-verify="required|pword" name="pword" autocomplete="off" placeholder="密码" />
				<input type="password" lay-verify="required|pword" name="repword" autocomplete="off" placeholder="确认密码" />
				<input type="text" lay-verify="required" name="question" autocomplete="off" placeholder="问题" />
				<input type="text" lay-verify="required" name="ans" autocomplete="off" placeholder="答案" />
				<button class="signup" lay-submit="" lay-filter="signup" >注册</button>
			</div>
		</div>
		<div class="form-container sign-in-container">
			<div class="sign-form login-box">
				<h1>登录</h1>
				<input type="text" name="user_in" lay-verify="required" value="@if(!empty(Cookie::get('user'))){{Cookie::get('user')}}@endif" placeholder="用户名" />
				<input type="password" name="pword_in" lay-verify="required" placeholder="密码" />
				<a href="javascript:;" class="reset">忘记密码？</a>
				<button class="signin">登录</button>
			</div>
			<div class="sign-form reset-box layui-form">
				<h1>密码找回</h1>
				<div class="pd-2">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="key" value="0">
					<div class="step">
						<input type="text" lay-verify="required|user" name="user" autocomplete="off" placeholder="账号" />
						<span>输入您要找回密码的账号</span>
					</div>
					<div class="step hide">
						<input type="text" lay-verify="" name="email" autocomplete="off" placeholder="邮箱" />
						<span>输入注册时绑定的邮箱</span>
					</div>
					<div class="step hide">
						<input type="text" lay-verify="" name="ans" autocomplete="off" placeholder="答案" />
						<span>你的密保问题是“<font class="question">xxx</font>”请输入答案</span>
					</div>
					<div class="step hide">
						<input type="password" lay-verify="" maxlength="12" name="pword" autocomplete="off" placeholder="新密码" />
						<span>请输入新的密码</span>
					</div>
				</div>
				<button class="reset_bt" lay-submit="" lay-filter="reset">下一步</button>
			</div>
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<h1>欢迎回来！</h1>
					<p>已有账号：</p>
					<button class="ghost" id="signIn">立即登录</button>
				</div>
				<div class="overlay-panel overlay-right">
					<h1>你好朋友！</h1>
					<p>还没有账号：</p>
					<button class="ghost" id="signUp">立即注册</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
layui.use(['element','form','jquery'], function(){
	var form = layui.form
	,element = layui.element
	,$ = layui.jquery;
	
	//自定义验证规则
	form.verify({
		user: [/^[a-zA-Z0-9]{6,16}$/, '用户名需为6-16位字母数字']
		,pword: [/^.{6,12}$/, '密码长度需为6-12位']
	});
	
	form.on('submit(signup)', function(data){
		var this_ = $(this);
		$.ajax({
			type:"post",
			dataType:'json',
			url:"{{url('signup')}}",
			data:data.field,
			beforeSend:function(){
				layer.load(0,{shade:0.3});
			},
			success: function(d,s){
				if(d.status==2){
					layer.msg(d.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
				}
				if(d.status==1){
					layer.msg(d.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
					setTimeout(function(){
						parent.location.reload();
					}, 1500);
				}
			},
			error: function(d,s){
				layer.alert("未知错误");
			},
			
			complete:function(d,s){
				layer.closeAll('loading');
			},
		})
		return false;
	})
	
	form.on('submit(reset)', function(data){
		var this_ = $(this);
		$.ajax({
			type:"post",
			dataType:'json',
			url:"{{url('reset_pword')}}",
			data:data.field,
			beforeSend:function(){
				layer.load(0,{shade:0.3});
			},
			success: function(d,s){
				if(d.status == -1){
					layer.msg(d.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
				}
				if(d.status==2){
					$('.step').eq(d.key).show().siblings().hide();
					$('.step').eq(d.key).find('input').attr({'name':d.name,'lay-verify':'required|'+d.name});
					$('input[name="key"]').val(d.key);
					if(d.key==2){
						$('.question').html(d.question);
					}
					if(d.key==3){
						$('.reset').html('确定');
					}else{
						$('.reset').html('下一步');
					}
				}
				if(d.status==1){
					layer.msg(d.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
					setTimeout(function(){
						window.location.reload();
					}, 1500);
				}
			},
			error: function(d,s){
				layer.alert("未知错误");
			},
			
			complete:function(d,s){
				layer.closeAll('loading');
			},
		})
		return false;
	})
	
$(function(){
	$('.reset').click(function(){
		$('.login-box').hide()
		$('.reset-box').show();
	})
	
	$('#signUp').click(function(){
		$('#container').addClass('right-panel-active');
	})
	
	$('#signIn').click(function(){
		$('#container').removeClass('right-panel-active');
	})
	
	var _token = "{{ csrf_token() }}"
	$('.signin').click(function(){
		var user = $('input[name="user_in"]').val(),
			pword = $('input[name="pword_in"]').val();
			if(user =='' || pword == ''){
				layer.msg('请输入用户名或密码',{icon: 2,time: 1500,anim: 6,shade:0.3});return;
			}
			
		$.post('../signin',{user:user,pword:pword,_token:_token},function(res){
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}
			if(res.status==1){
				layer.msg(res.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					parent.location.reload();
					//self.opener.location.reload();
				}, 1500);
			}
		})
	})
	
	$('.container').keydown(function(event){
	　　if(event.keyCode==13){
			if($('.container').hasClass('right-panel-active')){
				$('.signup').click();
			}else{
				if($('.login-box').is(':hidden')){
					$('.reset_bt').click();
				}else{
					$('.signin').click();
				}
			}
	　　}
	});
	
	@if(!empty($_GET['type']))
		@if($_GET['type'] == 2)
			$('#signUp').click();
		@endif
	@endif
})
	
	
})
</script>
@endsection