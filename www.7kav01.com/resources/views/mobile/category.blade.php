@extends('layouts.mb')

@section('title', '充值中心')

@section('header')
	@include('moudel.header')
@endsection

@section('content')
<div class="notice-box bg-w">
	<h2 class="title">充值中心</h2>
	<div class="vip-box">
		<div class="invoice line-ud">
			<span>会员类型：</span>
		</div>
		
		@foreach($meb_paid as $v)
			<div class="sale-info line-ud flex-box" data-paid="{{$v['id']}}" data-price="{{$v['price']}}">
				<div class="checked-box">
					<i class="layui-icon layui-icon-circle"></i>
				</div>
				<div class="sale-wrap">
					<div class="wrap-type flex-box">
						<h4>{{$v['name']}}</h4>
						<p>￥{{$v['price']}}</p>
					</div>
					<p class="depict">{{$v['depict']}}</p>
				</div>
			</div>
		@endforeach
		
		<div class="invoice line-ud">
			<span>支付方式：</span>
		</div>
		<div class="layui-row pd-2">
			
			@if(tpCache('para.zfb_h5')==1)
				<div class="layui-col-xs4 payway" type="1101">
					<img src="../images/zfb.png" >支付宝<span></span>
				</div>
			@endif
			
			@if(tpCache('para.wx_h5')==1)
				<div class="layui-col-xs4 payway" type="1102">
					<img src="../images/zfb.png" >微信<span></span>
				</div>
			@endif
			
			<div class="layui-col-xs4 payway" type="1000">
				<img src="../images/ye.png" >余额<span></span>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer_ad')
   @include('moudel.footer_ad')
@endsection

<footer class="footer-btn">
	<div class="pay-info">
		<p>实付<span>￥0</span></p>
	</div>
	<div class="pay-btn">
		<span>立即充值</span>
	</div>
</footer>

@section('js')
<script>
layui.use(['element','layer'], function(){
	var element = layui.element
		,layer = layui.layer;
	
	var type = '',paid='';
	$('.payway').click(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
		type = $(this).attr('type');
	})
	
	$('.sale-info').click(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
		$(this).find('.checked-box i').addClass('layui-icon-radio');
		$(this).siblings().find('.checked-box i').removeClass('layui-icon-radio');
		paid = $(this).data('paid');
		$('.pay-info p span').html('￥'+$(this).data('price'));
	})
	
	$('.pay-btn').click(function(){
		if(paid==''){
			layer.msg('请选择会员类型',{icon: 2,time: 2000,anim: 6,shade:0.3});return;
		}
		if(type==''){
			layer.msg('请选择支付类型',{icon: 2,time: 2000,anim: 6,shade:0.3});return;
		}
		layer.load(0,{shade:0.3});
		$.post("{{url('pay/creat_trade')}}",{_token:"{{ csrf_token() }}",paid:paid,type:type},function(res){
			if(res.status==3){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					window.location.href = res.url;
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}						
			if(res.status==1){
				if(type == '1000'){
					layer.msg(res.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
					setTimeout(function(){
						location.href = "../m/ucenter";
					}, 1500);
				}else{
					layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
					setTimeout(function(){
						window.location.href = res.payUrl;
					}, 1500);
				}
			}
		}).fail(function(s){
			layer.msg(s.statusText,{icon: 2,time: 1500,anim: 6,shade:0.3});return;
		}).always(function(){
			layer.closeAll('loading');
		});
	})
})
</script>
@endsection