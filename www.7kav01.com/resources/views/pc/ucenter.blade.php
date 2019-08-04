@extends('layouts.pc')

@section('title', '登录注册')

@section('header')
	@include('moudel.pc_header')
@endsection

@section('content')
<div class="bg-g">
	@if(!empty($meb))
		<div class="show-box mb-1">
			<h3 class="line-ud">用户信息 
			<a class="fr" style="color:#ec145e;border: 1px solid #ec145e;padding: 4px 10px;" href="../collect">我的收藏</a>
			<a href="javascript:;" class="fr account" style="color:#1e9fff;border: 1px solid #1e9fff;padding: 4px 10px;margin-right:10px;">账户明细</a>
			</h3>
			<div class="msg-item">
				<span>会员账号：{{$meb['user']}}</span>
				<span>注册时间：{{date('Y年m月d日',$meb['join_time'])}}</span>
				<span>会员等级：<div class="meb_level narrow"></div></span>
				<span>可用积分：{{$meb['credit']}}</span>
				<span>当前经验值：{{$meb['exp']}}</span>
			</div>
			<div class="msg-item">
				<span>付费会员：@if($meb['ispaid']==1) 是 @else 否 @endif
				<a href="../category" class="layui-btn layui-btn-danger layui-btn-sm">@if($meb['ispaid']==1) 续费 @else 点击开通 @endif</a>
				</span>
				@if($meb['active_time']>0)
					<span>会员有效期：{{date('Y-m-d H:i:s',$meb['active_time'])}}</span>
				@endif
				<span>账户余额：{{$meb['balance']}} <button class="layui-btn layui-btn-sm layui-btn-warm withdraw-btn">提现</button><button class="layui-btn layui-btn-sm layui-btn-normal zfb">支付宝绑定</button></span>
			</div>
		</div>
	@endif
	<!--
	<div class="show-box mb-1">
		<h3 class="line-ud">推广返利！！网赚必备</h3>
		<div class="msg-item">
			<div class="msg-item-c" >
			<p style=font-size:15px>只需通过个人中心复制你的专属链接，推广到各种群，或者通过社交软件留言推广，分享给好友推广，任意方式推广，每一个用户注册充值，您将获得30%的现金返现，以后该用户所有续费充值您都可以获得30%的反现，通过绑定支付宝，满50即可提现，或1.5倍换专属彩票平台彩金(详询客服）</p>
			<p style=font-size:15px>以月费会员28，返利可得8.4，您只需推广有6个人充值月卡，即可提现，余额亦可直接购买VIP使用。</p>
			<p style=font-size:15px>以季费会员58，返利可得17.4，您只需推广有3个人充值月卡，即可提现，余额亦可直接购买VIP使用。</p>
			<p style=font-size:15px>以年费会员198，返利可得59.4，您只需推广有1个人充值月卡，即可提现，余额亦可直接购买VIP使用。</p>
			<p><h4>赶紧注册开始吧！！</h4></p>
		</div>
	</div>-->

	<div class="show-box mb-1">
		<h3 class="line-ud"><span style=color:#f9010a;font-weight:700;font-size:18px;padding:10px 15px;>为了回馈广大会员，自2019.6.22起取消观看限制，活动结束时间待定。注册登录后即可下载或收藏影片，个人积分后续可以换RMB！感谢支持！</span></h3>
	</div>
	
	<div class="show-box mb-1">
		<h3 class="line-ud">会员等级、积分、经验值、付费VIP说明</h3>
		<div class="msg-item">
			<div class="msg-item-c">
			等级说明：<span>默认注册等级为<div class="level_1 narrow"></div>（一级 100经验值），<s>通过升级200经验值即可成为二星，</s><a style=color:#f9010a;border:0;background:#ffffff>现在你只需要把本站通过个人中心连接分享给5个好友注册登录，即可升级2星！</a>最高等级为<div class="level_3 narrow"></div>（三级 <s>300</s> 200经验值），不同等级可以播放对应等级及以下等级的视频，200经验值以上即可永久免费观看视频。</span>
			</div>
			<p>积分说明：<span>积分用于下载视频使用，每次下载将会扣除相应的积分。</span></p>
			<p>经验值说明：<span>经验值直接影响用户等级</span><font color="#f9010a">用户超过七天未登录,每天扣除5经验值,付费VIP不受此限制。</font><font color="#3f51b5">影片等级请查看图片右下角位置</font></p>
			<p style=color:#f9010a>付费VIP说明：<!--<span>付费VIP有效期间，可无限制下载视频（不扣积分），观看网站所有星级视频，VIP有效期内，七天不登录经验也不会减少。</span>-->暂未开通，敬请期待！</p>
		</div>
	</div>

	<div class="show-box mb-1">
		<h3 class="line-ud">如何获得经验值和积分</h3>
		<div class="msg-item">
			<p>推广赚经验积分</p>
			<p><span>把本站分享给您的好友，好友访问增加{{ tpCache('para.recom_click_credit') }}积分{{ tpCache('para.recom_click_exp') }}经验，好友注册登录您将会获得<font color="#3f51b5">{{ tpCache('para.recom_enroll_credit') }}积分</font>和<font color="#3f51b5">{{ tpCache('para.recom_enroll_exp') }}经验值</font></span>，用户充值即可获取30%现金返利哦！</p>
			<p><font color="#f9010a">注意：您的好友每推荐一个他的好友加入，您还将获得{{ tpCache('para.recom_sec_enroll_credit') }}积分+{{ tpCache('para.recom_sec_enroll_exp') }}经验值。</font></p>
			@if(!empty($meb))
			<p><font color="#3f51b5">你的推广链接：</font>
				<input type="text" class="layui-input" id="copyVal" value="{{url('/s')}}/{{$key}}" />
				<button type="button" class="layui-btn layui-btn-normal copyBtn" data-clipboard-action="copy" data-clipboard-target="#copyVal">复制链接</button>
			</p>
			@endif
			<p>签到赚经验和积分</p>
			<p><span>每日签到送积分和经验</span></p>
			<p style=color:#f9010a>推广等级达到满级以后，只需要每周登录一次，即可永久免费观看所有视频！</p>
		</div>
	</div>
</div>
	<br />
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
@endsection

@section('footer')
	@include('moudel.pc_footer')
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/clipboard.min.js') }}"></script>
<script>
layui.use(['rate','element','layer'], function(){
	var rate = layui.rate
		,layer = layui.layer
		,element = layui.element;
		
	rate.render({
		elem: '.level_1'
		,value: 1
		,length:3
		,readonly: true
		,theme:'#101010'
	});
	
	rate.render({
		elem: '.level_3'
		,value: 3
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
	
	$('.zfb').click(function(){
		layer.open({
		  type: 2,
		  title:false,
		  area: ['425px','400px'],
		  fixed: false, //不固定
		  content: "../m/mation"
		});
	})
	@if(!empty($meb))
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
					  area:['425px'],
					  content: $('.withdraw')
					});
				}
			});
		})
		
		$('.account').click(function(){
			layer.open({
			  type: 2,
			  title:false,
			  area: ['375px','768px'],
			  fixed: false, //不固定
			  content: "../m/account"
			});
		})
		
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
	@endif
})
</script>
@endsection