@extends('layouts.pc')

@section('title', '充值中心')

@section('header')
	@include('moudel.pc_header')
@endsection

@section('content')
<div class="show-box bg-w">
	<h2 class="title">充值中心</h2>
	<div class="vip-box pd-2">
		<div class="invoice"><span>会员类型</span></div>
		<div class="sale-box line-ud">
			@foreach($meb_paid as $v)
				<div class="sale-info flex-box" data-paid="{{$v['id']}}">
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
		</div>
	</div>
	<div class="pay-box pd-2">
		<div class="invoice"><span>支付方式</span></div>
		<div class="pay-way">
			@if(tpCache('para.zfb_scan')==1)
			<div class="qr-code ali">
				<div class="code-img">
					<img src="../images/al-pay.png" />
				</div>
				<span type="1101">支付宝支付</span>
			</div>
			@endif
			@if(tpCache('para.wx_scan')==1)
			<div class="qr-code wechat">
				<div class="code-img">
					<img src="../images/wx-pay.png" />
				</div>
				<span type="1102">微信支付</span>
			</div>
			@endif
			<div class="qr-code ye">
				<div class="code-img">
					<img src="../images/ye-pay.png" />
				</div>
				<span type="1000">余额支付</span>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
   @include('moudel.pc_footer')
@endsection

@section('js')
<script>
layui.use(['element','layer'], function(){
	var element = layui.element
		,layer = layui.layer;
	
	
	var paid='';
	$('.sale-info').click(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
		$(this).find('.checked-box i').addClass('layui-icon-radio');
		$(this).siblings().find('.checked-box i').removeClass('layui-icon-radio');
		paid = $(this).data('paid');
	})
	
	$('.qr-code span').click(function(){
		if(paid==''){
			layer.msg('请先选择会员类型',{icon: 2,time: 2000,anim: 6,shade:0.3});return;
		}
		var type = $(this).attr('type');
		layer.load(0,{shade:0.3});
		$.post("{{url('pay/creat_trade')}}",{_token:"{{ csrf_token() }}",paid:paid,type:type},function(res){
			if(res.status==3){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					layer.open({
					  type: 2,
					  title:false,
					  area: ['1240px', '580px'],
					  fixed: false, //不固定
					  content: res.url
					});
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}						
			if(res.status==1){
				switch(type){
					case '1000':
						layer.msg(res.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
						setTimeout(function(){
							location.href = "../ucenter";
						}, 1500);
					break;
					case '1101':
						var a = $("<a href='"+res.payUrl+"' target='_blank'>支付</a>").get(0);   
						var e = document.createEvent('MouseEvents');
						e.initEvent( 'click', true, true );
						a.dispatchEvent(e);
					break;
					case '1102':
						
					break;
				}
				layer.open({
					type: 1
					,title: false //不显示标题栏
					,closeBtn: false
					,area: '300px;'
					,shade: 0.3
					,id: 'LAY_pro_1' //设定一个id，防止重复弹出
					,btn: ['支付成功' , '遇到问题/支付失败']
					,btnAlign: 'c'
					,content: '<div style="padding:45px;line-height: 22px; background-color: #fff; color: #333; font-weight: 600;font-size:18px;text-align:center;border-bottom:1px solid #efefef;">支付结果</div>'
					,yes: function(){
						layer.alert('内容',{icon: 1,closeBtn: 0},function(){
							location.href = "../ucenter";
						})
					}
					,btn2: function(){
						layer.alert('内容', {
						  icon: 0,
						  skin: 'layui-layer-lan',
						  closeBtn: 0
						},function(){
							location.href = '../ucenter';
						})
					}
				});
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