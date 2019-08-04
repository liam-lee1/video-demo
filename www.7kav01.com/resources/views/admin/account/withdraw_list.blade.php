<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>提现列表</title>
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
  <legend>提现列表</legend>
</fieldset>
<div style="margin:15px;">
	<div class="search layui-form">
		<div class="screen" style="display:inline-block;" >
			<div class="layui-form-item">
			  <label class="layui-form-label">查询时间段</label>
				  <div class="layui-input-inline" style="width: 150px;">
					<input type="text" name="start" id="start" placeholder="开始时间" autocomplete="off" class="layui-input">
				  </div>
				  <div class="layui-form-mid">-</div>
				  <div class="layui-input-inline" style="width: 150px;">
					<input type="text" name="end" id="end" placeholder="结束时间" autocomplete="off" class="layui-input">
				  </div>
				  <button class="layui-btn layui-btn-normal">搜索</button>
			</div>
		</div>
	</div>
	<table id="record" lay-filter="withdraw"></table>
</div>

<script type="text/html" id="toolbar">
	<a class="layui-btn layui-btn-sm" lay-event="mation" >明细</a>
	<a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="agree" >通过</a>
	<a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="reject" >驳回</a>
</script>
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<script>
layui.use(['table','layer','laydate','jquery'], function(){
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
		  ,title: '会员每日提现申请'
		  ,cols: [[ //标题栏
					{field: 'sort', title: '序号', width: 80, totalRowText: '合计'}
					,{field: 'user', title: '注册账号'}
					,{field: 'mation', title: '支付宝信息'}
					,{field: 'cash', title: '提现金额', totalRow: true}
					,{field: 'time', title: '申请时间'}
					,{fixed: 'right', title: '操作',width: 300,align:'center', toolbar: '#toolbar'}
				  ]] //设置表头
		  ,url: "{{url('account/get_withdrawList')}}" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'withdraw'
		});
	
	$(".screen .layui-btn").click(function(){
		var start = $('input[name="start"]').val(),
			end = $('input[name="end"]').val();
		table.reload('withdraw',{where:{start:start,end:end},page: {curr: 1 }});
	});
	
	function del_withdraw(id,state,obj,reason=''){
		$.ajax({
			type:'post',
			dataType:'json',
			url:"{{url('account/del_withdraw')}}",
			data:{id:id,state:state,_token:_token,reason:reason},
			beforeSend:function(){
				layer.load(0,{shade:0.3});
			},
			success:function(res){
				if(res.status==2){
					layer.msg(res.msg,{icon: 2,time: 1500});
				}
				if(res.status==1){
					layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
					setTimeout(function(){
					  obj.del();
					}, 1500);
				}
			},
			error: function(d,s){
				var msg = '';
				switch(s){
					case 'timeout':
						msg='请求超时';break;
					case 'error':
						msg='回调有误';break;
					default:
						msg='请求失败，错误类型：' + s;
				}
				layer.msg(msg);
			},
			complete:function(d,s){
				layer.closeAll('loading');
			},
		})
	}
	
	//监听单元格事件
	table.on('tool(withdraw)', function(obj){
		var data = obj.data,
			layEvent = obj.event;
		
		if(layEvent=='agree'){
			layer.confirm('确认执行提现处理么？',{icon: 3,title:'提示'}, function(index){
				layer.close(index);
				del_withdraw(data.id,1,obj,'');
			})
		}
		
		if(layEvent=='reject'){
			layer.prompt({formType: 2,title: '请输入驳回原因'}, function(value, index){
				layer.close(index);
				del_withdraw(data.id,-1,obj,value);
			})
		}
		//mation
		if(layEvent=='mation'){
			layer.open({
				type: 2,
				title:[data.user+'账户信息','font-size: 22px;font-weight: 800;'],
				shadeClose: true,
				skin: 'layui-layer-rim', //加上边框
				content: "{{url('manage/balance_detail')}}?user="+data.user
			});
		}
	})
});
</script>

</body>
</html>