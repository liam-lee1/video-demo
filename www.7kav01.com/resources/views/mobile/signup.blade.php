@extends('layouts.mb')

@section('title', '登录注册')

@section('content')
<div class="content-box clear-bottom container bg-w">
	<ul class="account-tab">
		<a href="../m/signin"><li>登录</li></a>
		<li class="active">注册</li>
	</ul>
	<div class="layui-form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="account-form-group">
			<div class="layui-input-inline full">
			  <input type="text" name="user" lay-verify="required|user" value="" autocomplete="off" maxlength="16" placeholder="账号" class="layui-input">
			</div>
		</div>
		<div class="account-form-group">
			<div class="layui-input-inline full">
			  <input type="password" name="pword" lay-verify="required|pword" autocomplete="off" maxlength="12" placeholder="密码" class="layui-input">
			</div>
		</div>
		<div class="account-form-group">
			<div class="layui-input-inline full">
			  <input type="password" name="repword" lay-verify="required|pword" autocomplete="off" maxlength="12" placeholder="确认密码" class="layui-input">
			</div>
		</div>
		<div class="account-form-group">
			<div class="layui-input-inline full">
			  <input type="email" name="email" lay-verify="required|email" autocomplete="off" placeholder="电子邮箱" class="layui-input">
			</div>
		</div>
		<div class="account-form-group">
			<div class="layui-input-inline full">
			  <input type="text" name="question" lay-verify="required" value="" autocomplete="off" maxlength="30" placeholder="密保问题" class="layui-input">
			</div>
		</div>
		<div class="account-form-group">
			<div class="layui-input-inline full">
			  <input type="text" name="ans" lay-verify="required" value="" autocomplete="off" maxlength="30" placeholder="密保答案" class="layui-input">
			</div>
			<div class="account-form-tip errorHint js-error">请牢记邮箱和密保答案，用于密码找回</div>
		</div>
		
		<div class="account-form-group">
			<div class="account-form-tip errorHint js-error"></div>
			<button class="account-form-btn" lay-submit="" lay-filter="signup">注册</button>
		</div>
	</div>
</div>
@endsection

@section('footer_ad')
   @include('moudel.footer_ad')
@endsection

@section('footer')
   @include('moudel.footer')
@endsection

@section('js')
<script>
layui.use(['element','form'], function(){
	var form = layui.form
		,element = layui.element;
	
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
						location.href = "../m/index";
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
});
	var mark = true;
	$('.insign').click(function(){
		if(mark == false)return;
		mark = false;
		$.post("{{ url('web/insign') }}",{_token:'{{ csrf_token() }}'},function(res){
			mark = true;
			if(res.status==3){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					window.location.href = res.url;
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
			}
			if(res.status==1){
				layer.msg(res.msg,{icon: 1,time: 2000,anim: 0,shade:0.3});
			}
		})
	})
</script>
@endsection