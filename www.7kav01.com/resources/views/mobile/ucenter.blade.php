@extends('layouts.mb')

@section('title', '个人中心')

@section('header')
	@include('moudel.header')
@endsection

@section('content')
<div class="bg-g">
	<div class="show-box bg-w mb-1">
		<h2 class="title">用户信息
			<a class="fr" style="color:#ec145e;" href="../m/collect">我的收藏</a>
			<a class="fr" style="color:#1e9fff;margin-right:.35rem;" href="../m/account">账户明细</a>
		</h2>
		<div class="show-c layui-row">
			<div class="layui-col-xs6">
				<span>会员账户：{{$meb['user']}}</span>
			</div>
			<div class="layui-col-xs6">
				<span>注册时间：{{date('Y年m月d日',$meb['join_time'])}}</span>
			</div>
			<div class="layui-col-xs6">
				<span>会员等级：<div class="meb_level narrow"></div></span>
			</div>
			<div class="layui-col-xs6">
				<span>邮箱：{{$meb['email']}}</span>
			</div>
			<div class="layui-col-xs6">
				<span>可用积分：{{$meb['credit']}}</span>
			</div>
			<div class="layui-col-xs6">
				<span>经验值：{{$meb['exp']}}</span>
			</div>
			<div class="layui-col-xs12">
				<span>付费会员：@if($meb['ispaid']==1) 是 @else 否 @endif
				<a href="../m/category"  target="_blank" class="layui-btn layui-btn-danger layui-btn-sm">@if($meb['ispaid']==1) 续费 @else 充值 @endif</a>
				</span>
			</div>
			@if($meb['active_time']>0)
				<div class="layui-col-xs12">
					<span>会员有效期：{{date('Y-m-d H:i:s',$meb['active_time'])}}</span>
				</div>
			@endif
			<div class="layui-col-xs12">
				<span>账户余额：{{$meb['balance']}} <button class="layui-btn layui-btn-sm layui-btn-warm withdraw-btn">提现</button></span>
			</div>
		</div>
	</div>
	
	<div class="show-box bg-w mb-1">
		<a href="../m/mation"><h2 class="title">支付宝绑定<i class="fr layui-icon layui-icon-right"></i></h2></a>
	</div>
	
	
	<div class="show-box bg-w mb-1">
		<h2 class="title">获取经验值和积分</h2>
		<div class="show-c layui-row">
			<h3>推广赚积分：</h3>
			<p>把本站分享给您的好友，好友访问增加{{ tpCache('para.recom_click_credit') }}积分{{ tpCache('para.recom_click_exp') }}经验，</p>
			<p>好友注册登录您将会获得<font>{{ tpCache('para.recom_enroll_credit') }}积分</font>和<font>{{ tpCache('para.recom_enroll_exp') }}经验值</font></p>
			<p>注意：您的好友每推荐一个他的好友加入,您还将获得{{ tpCache('para.recom_sec_enroll_credit') }}积+{{ tpCache('para.recom_sec_enroll_exp') }}经验值。</p>
		</div>
		
		<div class="show-c layui-row">
			<h3>你的推广链接：</h3>
			<p><input type="text" class="layui-input" id="copyVal" value="{{url('/s')}}/{{$key}}" />
				<button type="button" class="layui-btn layui-btn-normal copyBtn" data-clipboard-action="copy" data-clipboard-target="#copyVal">复制链接</button></p>
		</div>
		
		<div class="show-c layui-row">
			<h3>签到赚积分：</h3>
			<p>每日签到送2积分和2经验</p>
		</div>
	</div>
	
	<div class="account-form-group pd-2 bg-w">
		<a class="account-form-btn" href="../logout">退出登录</a>
	</div>
</div>
@endsection

<section class="draw-box withdraw" style="display:none;">
	<img src="../images/logo.jpg"/>
	<p>请输入提现金额</p>
	<div class="withcash">
		<span>￥</span><input type="text" name="withdraw"  maxlength="6" placeholder="提现金额" autocomplete="off" onkeyup="this.value=this.value.replace(/[^\d]/g,'')">
	</div>
	<div class="operate_btn">
		<button id="withdraw">确认</button>
	</div>
</section>

@section('footer_ad')
   @include('moudel.footer_ad')
@endsection

@section('footer')
   @include('moudel.footer')
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/clipboard.min.js') }}"></script>
<script>
layui.use(['rate','element','layer'], function(){
	var rate = layui.rate
		,layer = layui.layer
		,element = layui.element;
	
	
	rate.render({
		elem: '.meb_level'
		,value: "{{$meb['level']}}"
		,length:3
		,readonly: true
		,theme:'#101010'
	});
	
	var clipboard = new Clipboard('.copyBtn');

	clipboard.on('success', function(e) {
		layer.msg('复制成功',{time:1500});
	});

	clipboard.on('error', function(e) {
		layer.msg('复制失败');
	});
	
	var _token = "{{ csrf_token() }}";
	$('.withdraw-btn').click(function(){
		layer.load(0,{shade:0.3});
		$.post("../mation_check",{_token:_token},function(res){
			layer.closeAll('loading');
			if(res.status==3){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});return;
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					window.location.href="../m/mation";
				}, 1500);
				return;
			}
			if(res.status==1){
				layer.open({
				  type: 1,
				  closeBtn: 0,
				  title: false,
				  skin: 'layui-layer-rim', //加上边框
				  shadeClose: true,
				  area:['90%'],
				  content: $('.withdraw')
				});
			}
		});
	});
	
	$('#withdraw').click(function(){
		var cash = Number($('input[name="withdraw"]').val()),
			limit = "{{ tpCache('para.withdraw_limit') }}";
		if(cash > {{$meb['balance']}} ){
			layer.msg('账户余额不足',{icon: 2,time: 1500,anim: 6,shade:0.3});return;
		}
		if(!(/(^[1-9]\d*$)/.test(cash))){
			layer.msg('提现金额必须是整数',{icon: 2,time: 1500,anim: 6,shade:0.3});return;
		}
		
		if(cash< limit){
			layer.msg('最低提现额度为'+limit,{icon: 2,time: 1500,anim: 6,shade:0.3});return;
		}
		
		layer.load(0,{shade:0.3});
		$.post('../takecash',{cash:cash,_token:_token},function(res){
			layer.closeAll('loading');
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,shade:0.3});return;
			}
			if(res.status==1){
				layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
				setTimeout(function(){
				  window.location.reload();
				}, 1500);
			}
		})
	})
})
</script>
@endsection