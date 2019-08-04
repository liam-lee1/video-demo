<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>公告列表</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
	<legend>公告列表</legend>
</fieldset>
<div style="margin:15px;">
	<button type="button" class="layui-btn layui-btn-normal add_new">添加公告</button>
	<table id="res_list" lay-filter="res_list"></table>
</div>
<script type="text/html" id="toolbar">
	<a class="layui-btn layui-btn-sm" lay-event="edit" >编辑</a>
	<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del" >删除</a>
	@{{#  if(d.hide == 0){ }}
		<a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="hide" >隐藏</a>
	@{{#  } else { }}
		<a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="show" >显示</a>
	@{{#  } }}
</script>  
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
layui.use(['table','layer','form','jquery'], function(){
	var table = layui.table,
	  layer = layui.layer,
	  form = layui.form,
	  $ = layui.$;
	
	var tableIns = table.render({
		  elem: '#res_list'
		  ,cellMinWidth: 180
		  ,height:'full-140'
		  ,cols: [[ //标题栏
					{field: 'sorts', title: '序号',align:'center', width: 80,sort:true,totalRowText: '合计'}
					,{field: 'title',align:'center', title: '标题'}
					,{field: 'sort',align:'center',width: 200, title: '排序'}
					,{field: 'time',align:'center',width: 200, title: '发布时间'}
					,{fixed: 'right',title: '操作', width: 400,align:'center', toolbar: '#toolbar'}
				  ]] //设置表头
		  ,url: "{{url('info/get_notice_list')}}" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'res_list'
		});
	var _token = '{{ csrf_token() }}';
	//监听单元格事件
	table.on('tool(res_list)', function(obj){
		var data = obj.data,
			layEvent = obj.event;
		
		if(layEvent === 'edit'){
			layer.open({
			  type: 2,
			  title:['公告编辑','font-size: 22px;font-weight: 800;'],
			  shadeClose: true,
			  skin: 'layui-layer-rim',
			  content: "{{url('info/notice_xg')}}?id="+data.id,
			  cancel: function(){ 
					window.location.reload();
				}
		  });
		}
		
		if(layEvent === 'del'){
			layer.confirm('确认删除该公告么？',{icon: 3,title:'提示'}, function(index){
				$.post("{{url('info/del_notice')}}",{_token:_token,id:data.id},function(res){
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
		
		if(layEvent === 'hide'){
			layer.confirm('确认隐藏该视频么？',{icon: 3,title:'提示'}, function(index){
				$.post("{{url('info/notice_hide')}}",{_token:_token,id:data.id,state:1},function(res){
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
		
		if(layEvent === 'show'){
			layer.confirm('确认显示该公告么？',{icon: 3,title:'提示'}, function(index){
				$.post("{{url('info/notice_hide')}}",{_token:_token,id:data.id,state:0},function(res){
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
				url:"{{url('account/add_manager')}}",
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
	
	form.on('submit(edit)', function(data){
		layer.confirm('确认执行该操作么？',{icon: 3,title:'提示'}, function(index){
			$.ajax({
				type:"post",
				dataType:'json',
				url:"{{url('account/edit_manager')}}",
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
	
	$('.add_new').click(function(){
		layer.open({
			  type: 2,
			  title:['新增公告','font-size: 22px;font-weight: 800;'],
			  shadeClose: true,
			  skin: 'layui-layer-rim',
			  content: "{{url('info/notice')}}",
			  cancel: function(){ 
					window.location.reload();
				}
		  });
	})
});
</script>

</body>
</html>