<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>交易列表</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
</head>
<style>
	.layui-table-page{text-align: center;}
	.layui-layer-rim{width:95%!important;height:95%!important;}
</style>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>交易列表</legend>
</fieldset>
<div style="margin:15px;">
	<div class="search layui-form">
		<div class="screen layui-form-item" style="display:inline-block;" >
			<div class="layui-input-inline">
				<input type="text" name="tel" placeholder="会员账号" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-input-inline">
				<select name="type" lay-search="">
					<option value="">支付方式</option>
					@foreach($type as $k=>$v)
						<option value="{{$k}}">{{$v}}</option>
					@endforeach
				</select>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline" style="width: 150px;">
					<input type="text" name="start" id="start" placeholder="开始时间" autocomplete="off" class="layui-input">
				</div>
				<div class="layui-form-mid">-</div>
				<div class="layui-input-inline" style="width: 150px;">
					<input type="text" name="end" id="end" placeholder="结束时间" autocomplete="off" class="layui-input">
				</div>
			</div>
			<button class="layui-btn layui-btn-normal">搜索</button>
		</div>
	</div>
	<table id="record" lay-filter="withdraw"></table>
</div>

<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<script>
layui.use(['table','layer','laydate'], function(){
	var table = layui.table,
	  layer = layui.layer,
	  laydate = layui.laydate,
	  $ = layui.$;
	
	laydate.render({
		elem: '#start'
		,type:'datetime'
	  });  
	
	laydate.render({
		elem: '#end'
		,type:'datetime'
	  });
	
	var _token = '{{ csrf_token() }}';
	
	var tableIns = table.render({
		  elem: '#record'
		  ,cellMinWidth: 180
		  ,height:'full-160'
		  ,totalRow: true
		  ,cols: [[ //标题栏
					{field: 'sort', title: '序号', width: 80, totalRowText: '合计'}
					,{field: 'user', title: '注册账号'}
					,{field: 'mation', title: '账户信息'}
					,{field: 'order_no', title: '订单号'}
					,{field: 'trade_no', title: '交易单号'}
					,{field: 'price', title: '交易金额', totalRow: true}
					,{field: 'serviceType', title: '支付方式'}
					,{field: 'orderTime', title: '提交订单时间'}
					,{field: 'dealTime', title: '交易完成时间'}
				  ]] //设置表头
		  ,url: "{{url('account/get_tradeList')}}" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'trade'
		});
	
	$(".screen .layui-btn").click(function(){
		var user = $('input[name="user"]').val(),
			type = $('select[name="type"] option:selected').val(),
			start = $('input[name="start"]').val(),
			end = $('input[name="end"]').val();
		table.reload('trade',{where:{user:user,type:type,start:start,end:end},page: {curr: 1 }});
	});
});
</script>

</body>
</html>