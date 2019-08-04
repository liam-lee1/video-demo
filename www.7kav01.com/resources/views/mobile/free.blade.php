@extends('layouts.mb')

@section('title', '个人中心')

@section('header')
	@include('moudel.header')
@endsection

@section('content')
<div class="bg-g">
<div class="show-box bg-w mb-1">

			<div class="show-c layui-row">
			<br />
					<h2 style=color:#f9010a> 为了回馈广大会员，自2019.6.22起取消观看限制，活动结束时间待定。注册登录后即可下载或收藏影片，个人积分后续可以换RMB！感谢支持！</h2><br />
					<h2 style=color:#1f1f1f;font-size:0.4rem;> 在观看过程中,有什么意见和建议,比如希望上架哪些影片,地区播放是否会出现卡顿(请注明地区和运营商以及观看时间),以及浏览器兼容问题，都可以发送邮件到7000avcom@gmail.com,我们会积极处理每一个意见建议。</h2><br />
					<h2 style=color:#0035ff;font-size:0.4rem;> 网站采用全新的视频预览功能,PC端鼠标放上去即可加载预览视频，手机端通过手指滑动位置播放预览，给你前所未有的用户体验。同时网站标签可以联合查询，查询更符合你的个人口味的视频，同时可以通过搜索功能精准定位视频。</h2>
			</div>
		</div>
<!--
	<div class="show-box bg-w mb-1">
		<h2 class="title">会员等级、积分、经验值说明</h2>
		<div class="show-c layui-row">
			<h4>等级说明：</h4>
			<p>默认注册等级为★☆☆（一级 100经验值），<s>通过升级200经验值即可成为二星，</s><a style=color:#f9010a;border:0;background:#ffffff>现在你只需要把本站通过个人中心连接分享给5个好友注册登录，即可升级2星！</a>最高等级为★★★（三级），不同等级可以播放对应等级及以下等级的视频，200经验值以上即可永久免费观看视频。</p>

		</div>
		<div class="show-c layui-row">
			<h4>积分说明：</h4>
			<p>积分用于下载视频使用，每次下载将会扣除相应的积分。</p>
		</div>
		<div class="show-c layui-row">
			<h4>经验值说明：</h4>
			<p>经验值直接影响用户等级<font color="#f9010a">  用户超过七天未登录,每天扣除5经验值,付费VIP不受此限制。</font></p>

		</div>
		<div class="show-c layui-row">
			<h4 style=color:#f9010a>付费VIP说明：</h4>
			<p>暂未开通。敬请期待！</p>
		<!--	<p>付费VIP有效期间，可无限制下载视频（不扣积分），观看网站所有星级视频，VIP有效期内，七天不登录经验也不会减少。</p>--这里>

		</div>
	</div>	
	<div class="show-box bg-w mb-1">
		<h2 class="title">如何获得经验值和积分</h2>
		<div class="show-c layui-row">
			<h3>推广赚积分：</h3>
			<p>把本站分享给您的好友，好友访问增加{{ tpCache('para.recom_click_credit') }}积分{{ tpCache('para.recom_click_exp') }}经验，</p>
			<p>好友注册登录您将会获得<font>{{ tpCache('para.recom_enroll_credit') }}积分</font>和<font>{{ tpCache('para.recom_enroll_exp') }}经验值</font></p>
			<p>注意：您的好友每推荐一个他的好友加入,您还将获得{{ tpCache('para.recom_sec_enroll_credit') }}积+{{ tpCache('para.recom_sec_enroll_exp') }}经验值。</p>
		</div>
		<div class="show-c layui-row">
			<h3>签到赚经验和积分：</h3>
			<p>每日签到送2积分和2经验</p>
			<p style=color:#f9010a>推广等级达到满级以后，只需要每周登录一次，即可永久免费观看所有视频！</p>
		</div>
		<div class="show-c">
			<p>登录注册后，复制个人中心链接即可推广！</p>
			<br />
			<p class="t-center">
				@if(!empty(session('openid')))
					<a href="../m/ucenter">
				@else
					<a href="../m/signin">
				@endif
				点击推广</a>
			</p>
		</div>
	</div>-->
</div>
@endsection

@section('footer_ad')
   @include('moudel.footer_ad')
@endsection

@section('footer')
   @include('moudel.footer')
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/clipboard.min.js') }}"></script>
<script>
layui.use(['element','layer'], function(){
	var layer = layui.layer
		,element = layui.element;
	
	var clipboard = new Clipboard('.copyBtn');

	clipboard.on('success', function(e) {
		layer.msg('复制成功',{time:1500});
	});

	clipboard.on('error', function(e) {
		layer.msg('复制失败');
	});
})
</script>
@endsection