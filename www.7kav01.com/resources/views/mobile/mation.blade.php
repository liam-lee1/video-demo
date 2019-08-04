@extends('layouts.mb')

@section('title', '账户设置')

@if(isMobile())
	@section('header')
		@include('moudel.header')
	@endsection
@endif

@section('content')
@if(!isMobile())
	<style>
		.main-content{bottom:0;}
	</style>
@endif
	<div class="show-box clear-bottom bg_w pd-2">
		<h2 class="title">支付宝绑定</h2>
		<div class="layui-form container">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="account-form-group">
				<div class="layui-input-inline full">
				  <input type="text" name="payee_real_name" @if(!empty($mation)) readonly @endif lay-verify="required|real_name" autocomplete="off" placeholder="支付宝真实姓名" class="layui-input" maxlength="5" value="@if(!empty($mation['payee_real_name'])){{$mation['payee_real_name']}}@endif" >
				</div>
			</div>
			<div class="account-form-group">
				<div class="layui-input-inline full">
				  <input type="text" name="payee_account" @if(!empty($mation)) readonly @endif lay-verify="required|payee_account" autocomplete="off" placeholder="支付宝账户" class="layui-input" maxlength="19" value="@if(!empty($mation['payee_account'])){{$mation['payee_account']}}@endif" >
				</div>
				@if(empty($mation))
				<div class="account-form-tip errorHint" style="display:block;" >*支付宝登录号，支持邮箱和手机号格式</div>
				@endif
			</div>
			@if(empty($mation))
			<div class="account-form-group">
				<div class="account-form-tip errorHint" style="display:block;" >*提交后不可修改，请谨慎修改</div>
				<button class="account-form-btn save" lay-submit="" lay-filter="apply">保存信息</button>
			</div>
			@endif
		</div>
	</div>
@endsection

@if(isMobile())
	@section('footer')
	   @include('moudel.footer')
	@endsection
@endif

@section('js')
<script>
layui.use(['element','layer','form'], function(){
	var element = layui.element
		,layer = layui.layer
		,form = layui.form;
	
	//自定义验证规则
	form.verify({
		real_name:[/^[\u4e00-\u9fa5]+$/,'请填写真实姓名'],
		payee_account:function(value){
			if(!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value) && !/^1[3-9]\d{9}$/.test(value)){
				return '请输入正确的支付宝账户';
			}
		}
		,tel: function(value){
			if(!/^1[3-9]\d{9}$/.test(value)){
				return '请输入正确的手机号';
			}
		}
		,captcha: function(value){
			if(value.length != 4){
				return '请输入正确的验证码';
			  }
		}
	});
	
	form.on('submit(apply)', function(data){
		$.post('../mation_save',data.field,function(d){
			if(d.status==2){
				layer.msg(d.msg,{icon: 2,time: 1500});
			}
			if(d.status==1){
				layer.msg(d.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					window.location.reload();
				}, 1500);
			}
		})
		return false;
	})
	
	$(function(){
		$('.modify').click(function(){
			$('input').removeAttr("readonly");
			$(this).hide().siblings().show();
			$('.account-form-group').show();
		})
	})
})
</script>
@endsection