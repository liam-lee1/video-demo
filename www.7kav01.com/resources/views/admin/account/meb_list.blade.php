<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>会员列表</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<style>
	.layui-table-fixed{display:none;}
</style>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
	<legend>会员列表</legend>
</fieldset>
<div style="margin:15px;">
	<div class="search layui-form">
		<div class="layui-input-inline">
			<input type="text" name="user" value="" autocomplete="off" placeholder="请输入会员账号" class="layui-input">
		</div>
		<button class="layui-btn retrieval">搜索</button>
	</div>
	<table id="meb_list" lay-filter="meb_list"></table>
</div>
<section class="popup layui-form" style="padding:15px;display:none;">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="id" value="">
	<div class="layui-form-item">
		<label class="layui-form-label">会员账号</label>
		<div class="layui-form-mid layui-word-aux user"></div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">积分</label>
		<div class="layui-input-block">
		  <input type="text" name="credit" lay-verify="required" autocomplete="off" onkeyup="this.value=(this.value.replace(/[^\d]/g,''))" placeholder="请输入增添积分" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">经验值</label>
		<div class="layui-input-block">
		  <input type="text" name="exp" lay-verify="required" autocomplete="off" onkeyup="this.value=(this.value.replace(/[^\d]/g,''))" placeholder="请输入增添经验值" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">执行密码</label>
		<div class="layui-input-block">
		  <input type="password" name="password" lay-verify="required" autocomplete="off" placeholder="请输入执行密码" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item" style="text-align:center;">
		<button class="layui-btn" lay-submit="" lay-filter="sure">确认操作</button>
	</div>
</section>
<script type="text/html" id="levelTpl">
	<div class="meb_level" data-value="@{{ d.level }}"></div>
</script>
<script type="text/html" id="toolbar">
	<a class="layui-btn layui-btn-sm layui-btn-primary" lay-event="account" >账户明细</a>
	<a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="balance" >余额明细</a>
	<a class="layui-btn layui-btn-sm" lay-event="add" >加分</a>
	<a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit" >编辑</a>
	<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del" >删除</a>
	@{{#  if(d.is_freeze == 0){ }}
		<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="freeze" >冻结</a>
	@{{#  } else { }}
		<a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="thaw" >解冻</a>
	@{{#  } }}
</script>  
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
layui.use(['table','layer','form','jquery','rate'], function(){
	var table = layui.table,
	  layer = layui.layer,
	  form = layui.form,
	  rate = layui.rate,
	  $ = layui.$;
	
	function meb_level(){
		$('.meb_level').each(function(){
			var val = $(this).data('value');
			rate.render({
				elem: $(this)
				,value: val
				,length:3
				,readonly: true
				,theme:'#111'
			});
		})
	}
	
	var tableIns = table.render({
		  elem: '#meb_list'
		  ,cellMinWidth: 180
		  ,height:'full-140'
		  ,totalRow: true
		  ,cols: [[ //标题栏
					{field: 'sort', title: '序号', width: 80,sort:true,totalRowText: '合计'}
					,{field: 'user', title: '会员账号', width: 200}
					,{field: 'level', title: '会员等级', width: 200,templet: '#levelTpl'}
					,{field: 'exp', title: '经验值',sort:true,width: 200,totalRow: true}
					,{field: 'credit', title: '积分',sort:true, width: 200,totalRow: true}
					,{field: 'balance', title: '余额',sort:true, width: 200,totalRow: true}
					,{field: 'time', title: '注册时间',sort:true}
					,{field: 'last_login', title: '最后登录时间',sort:true}
					,{field: 'ispaid', title: '付费会员',sort:true}
					,{field: 'active_time', title: '有效时间',sort:true}
					,{fixed: 'right',title: '操作', width: 450,align:'center', toolbar: '#toolbar'}
				  ]] //设置表头
		  ,url: "{{url('account/get_mebList')}}" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'meb_list',
		  done: function(res, curr, count){
			  meb_level();
		  }
		});
	
	
	$(".search .retrieval").click(function(){
		var user = $('input[name="user"]').val();
		table.reload('meb_list',{where:{user:user},page: {curr: 1 }});
	});
	
	var _token = '{{ csrf_token() }}';
	//监听单元格事件
	table.on('tool(meb_list)', function(obj){
		var data = obj.data,
			layEvent = obj.event;
		
		if(layEvent === 'edit'){
			layer.open({
				  type: 2,
				  title:['账户编辑','font-size: 22px;font-weight: 800;'],
				  shadeClose: true,
				  area:['480px','450px'],
				  content: "{{url('manage/meb_mation')}}?uid="+data.id
			  });
		}
		
		if(layEvent === 'account'){
			layer.open({
				  type: 2,
				  title:['账户明细【'+data.user+'】','font-size: 22px;font-weight: 800;'],
				  shadeClose: true,
				  skin: 'layui-layer-rim', //加上边框
				  content: "{{url('manage/account_detail')}}?openid="+data.openid
			  });
		}
		
		if(layEvent === 'balance'){
			layer.open({
				  type: 2,
				  title:['余额明细【'+data.user+'】','font-size: 22px;font-weight: 800;'],
				  shadeClose: true,
				  skin: 'layui-layer-rim', //加上边框
				  content: "{{url('manage/balance_detail')}}?user="+data.user
			  });
		}
		
		if(layEvent === 'add'){
			$('input[name="id"]').val(data.id);
			$('.user').html(data.user);
			layer.open({
			  type: 1,
			  title: ['余额增添','font-size: 22px;font-weight: 800;'],
			  shadeClose: true,
			  area:['520px'],
			  content: $('.popup')
			});
		}
		
		if(layEvent === 'del'){
			layer.confirm('确认删除该会员账户么？',{icon: 3,title:'提示'}, function(index){
				$.post("{{url('account/del_account')}}/"+data.id,{_token:_token},function(res){
					layer.close(index);
					if(res.status==2){
						layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
					}						
					if(res.status==1){
						layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
						setTimeout(function(){
							window.location.reload();
						}, 1500);
					}
				})
			})
		}
		
		if(layEvent === 'freeze'){
			layer.confirm('确认冻结该账户么？',{icon: 3,title:'提示'}, function(index){
				$.post("{{url('account/meb_freeze')}}/"+data.id,{_token:_token},function(res){
					layer.close(index);
					if(res.status==2){
						layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
					}						
					if(res.status==1){
						layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
						setTimeout(function(){
							window.location.reload();
						}, 1500);
					}
				})
			})
		}
		
		if(layEvent === 'thaw'){
			layer.confirm('确认解冻该账户么？',{icon: 3,title:'提示'}, function(index){
				$.post("{{url('account/meb_thaw')}}/"+data.id,{_token:_token},function(res){
					layer.close(index);
					if(res.status==2){
						layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
					}						
					if(res.status==1){
						layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
						setTimeout(function(){
							window.location.reload();
						}, 1500);
					}
				})
			})
		}
	})
	
	form.on('submit(sure)', function(data){
		layer.confirm('确认执行该操作么？',{icon: 3,title:'提示'}, function(index){
			$.ajax({
				type:"post",
				dataType:'json',
				url:"{{url('account/increase_account')}}",
				data:data.field,
				beforeSend:function(){
					layer.load(0,{shade:0.3});
				},
				success: function(d,s){
					if(d.status==2){
						layer.msg(d.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});
					}
					if(d.status==1){
						layer.msg(d.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
						setTimeout(function(){
							window.location.reload();
						}, 1500);
					}
				},
				
				error: function(d,s){
					layer.alert("未知错误");
				},
				
				complete:function(d,s){
					layer.closeAll('loading');
				},
			})
			return false;
		})
	});	
});
</script>

</body>
</html>