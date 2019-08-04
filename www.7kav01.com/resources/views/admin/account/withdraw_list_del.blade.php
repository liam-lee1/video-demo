<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>提现处理列表</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
</head>
<style>
	.layui-table-page{text-align: center;}
</style>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>提现处理列表</legend>
</fieldset>
<div style="margin:15px;">
	<div class="search layui-form">
		<div class="screen layui-form-item" style="display:inline-block;" >
			<div class="layui-input-inline">
				<select name="state" lay-search="">
					<option value="">提现状态</option>
					<option value="-1">驳回</option>
					<option value="1" selected >通过</option>
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

<script type="text/html" id="sexTpl">
  @{{#  if(d.state == 1){ }}
    <span style="color: #5fb878;">提现通过</span>
  @{{#  } else { }}
    <span style="color: #F581B1;">提现驳回</span>
  @{{#  } }}
</script>

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
		  ,toolbar: true
		  ,totalRow: true
		  ,where:{state:1}
		  ,title: '会员每日提现申请'
		  ,defaultToolbar: ['filter', 'print', 'exports']
		  ,cols: [[ //标题栏
					{field: 'sort', title: '序号', width: 80, totalRowText: '合计'}
					,{field: 'user', title: '注册账号'}
					,{field: 'mation', title: '支付宝信息'}
					,{field: 'cash', title: '提现金额', totalRow: true}
					,{field: 'time', title: '申请时间'}
					,{field: 'del_time', title: '处理时间'}
					,{field: 'state', title: '处理状态',templet: '#sexTpl'}
				  ]] //设置表头
		  ,url: "{{url('account/get_withdrawList')}}" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'withdraw'
		});
	
	$(".screen .layui-btn").click(function(){
		var state = $('select[name="state"] option:selected').val()
			start = $('input[name="start"]').val(),
			end = $('input[name="end"]').val();
		state = (state!='')?state:1;
		table.reload('withdraw',{where:{state:state,start:start,end:end},page: {curr: 1 }});
	});
});
</script>

</body>
</html>