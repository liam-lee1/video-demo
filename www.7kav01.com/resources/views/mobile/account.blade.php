@extends('layouts.mb')

@section('title', '账户明细')

@if(isMobile())
	@section('header')
		@include('moudel.header')
	@endsection
@endif

@section('content')
<style>
	.layui-tab-title{display:flex;top: 0;width: 100%;background: #fff;z-index: 100;}
	.layui-tab-title li{flex:1;}
	@if(!isMobile())
		.layui-tab-title{position: fixed;}
		.layui-tab-content{margin-top:40px;}
		.main-content{bottom:0;}
	@endif
</style>
<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
  <ul class="layui-tab-title">
    <li class="layui-this" data-type="account">账户明细</li>
    <li data-type="balance" >余额明细</li>
    <li data-type="withdraw" >提现明细</li>
  </ul>
  <div class="layui-tab-content">
    <div class="layui-tab-item layui-show">
		<ul class="layui-timeline"></ul> 
	</div>
    <div class="layui-tab-item"><ul class="layui-timeline"></ul> </div>
    <div class="layui-tab-item"><ul class="layui-timeline"></ul> </div>
  </div>
  <ul class="layui-fixbar" style="right: 30px; bottom: 60px;">
	  <li class="layui-icon layui-icon-top layui-fixbar-top" style="background-color:#777;"></li>
  </ul>
</div> 
@endsection

@if(isMobile())
	@section('footer')
	   @include('moudel.footer')
	@endsection
@endif

@section('js')
<script>
layui.use(['element','layer','flow','util'], function(){
	var element = layui.element
		,layer = layui.layer
		,util = layui.util
		,flow = layui.flow;
	
	function show(elem,type){
		flow.load({
			elem: elem
			,isAuto: true
			,done: function(page, next){
				var lis = [];
				$.get('../get_account_detail',{page:page,type:type},function(list){
					if(list.data.length == 0){
						var empty_html = '<div class="not-conts">\
											<span>暂无相关明细</span>\
										</div>';
						elem.html(empty_html);return;
					}
					
					switch(type){
						case 'account':
							layui.each(list.data, function(index, item){
								lis.push('<li class="layui-timeline-item">\
											<i class="layui-icon layui-icon-circle layui-timeline-axis"></i>\
											<div class="layui-timeline-content layui-text">\
											  <h3 class="layui-timeline-title">'+item.time+'</h3>\
											  <p>\
												变动积分：'+item.credit+'\
												<br>变动经验值：'+item.exp+'\
												<br>变动原因：'+item.type+'\
											  </p>\
											</div>\
										  </li>');
							})
						break;
						case 'balance':
							layui.each(list.data, function(index, item){
								lis.push('<li class="layui-timeline-item">\
											<i class="layui-icon layui-icon-circle layui-timeline-axis"></i>\
											<div class="layui-timeline-content layui-text">\
											  <h3 class="layui-timeline-title">'+item.time+'</h3>\
											  <p>\
												账户原本余额：'+item.born+'\
												<br>变动金额：'+item.balance+'\
												<br>变动原因：'+item.reason+'\
											  </p>\
											</div>\
										  </li>');
							})
						break;
						case 'withdraw':
							layui.each(list.data, function(index, item){
								var reason = '',del_time = '';
								if(item.del_time!='')del_time = '<br>处理时间：'+item.del_time;
								if(item.reason!='')reason = '<br>驳回原因：'+item.reason;
								lis.push('<li class="layui-timeline-item">\
											<i class="layui-icon layui-icon-circle layui-timeline-axis"></i>\
											<div class="layui-timeline-content layui-text">\
											  <h3 class="layui-timeline-title">'+item.time+'</h3>\
											  <p>\
												提现金额：'+item.cash+'\
												<br>处理状态：'+item.state+del_time+reason+'\
											  </p>\
											</div>\
										  </li>');
							})
						break;
					}
					
					
					next(lis.join(''), page < list.pages);
				})
			}
		})
	}
	
	$.each($('.layui-tab-title li'),function(key,index){
		var type = $(this).data('type');
		show($('.layui-tab-item').eq(key).find('ul'),type);
	})
	
	$('.main-content').scroll(function(){
		var top = $(this)[0].scrollTop;
		if(top>200){
			$('.layui-fixbar-top').show();
		}else{
			$('.layui-fixbar-top').hide();
		}
	})
	
	$('.layui-fixbar-top').click(function(){
		$('.main-content').animate({
				scrollTop: 0
		},200);
	})
})
</script>
@endsection