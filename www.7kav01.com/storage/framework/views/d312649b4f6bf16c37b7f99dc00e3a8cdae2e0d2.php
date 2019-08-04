<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>报错管理</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="<?php echo e(asset('layui/css/layui.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
	<legend>报错管理</legend>
</fieldset>
<div style="margin:15px;">
	<div class="search">
		<div class="layui-form">
			<div class="layui-input-inline">
				<select name="state"  lay-search="">
				  <option value="0" selected >待处理</option>
				  <option value="1" >有效</option>
				  <option value="2" >无效</option>
				</select>
			</div>
			<button class="layui-btn retrieval">搜索</button>
		</div>
	</div>
	<table id="res_list" lay-filter="res_list"></table>
</div>

<section class="popup layui-form" style="padding:15px;display:none;">
	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
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

<script type="text/html" id="conTpl">
	<a href="../play?eid={{ d.eid }}" target="_blank">{{ d.album }}</a>
</script>

<script type="text/html" id="stateTpl">
	{{#  if(d.state === 0){ }}
		<span style="color: #5FB878;">待处理</span>
	{{#  } else if(d.state === 1) { }}
		<span style="color: #5FB878;">有效</span>
	{{#  } else { }}
		<span style="color: #F581B1;">无效</span>
	{{#  } }}
</script>

<script type="text/html" id="toolbar">
	<a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="sure" >确认</a>
	<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del" >取消</a>
</script>  
<script type="text/javascript" src="<?php echo e(asset('layui/layui.js')); ?>"></script>
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
					,{field: 'album',align:'center', title: '视频名称' ,templet: '#conTpl'}
					,{field: 'user',align:'center',width: 200, title: '用户名'}
					,{field: 'type', title: '错误类型'}
					,{field: 'time', title: '日期',width: 200}
					,{field: 'state', title: '状态',width: 200,templet: '#stateTpl'}
					,{fixed: 'right',title: '操作', width: 300,align:'center', toolbar: '#toolbar'}
				  ]] //设置表头
		  ,url: "<?php echo e(url('video/get_errors_list')); ?>" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'res_list'
		});
	var _token = '<?php echo e(csrf_token()); ?>';
	
	$(".search .retrieval").click(function(){
		var state = $('select[name="state"] option:selected').val();
		table.reload('res_list',{where:{state:state},page: {curr: 1 }});
	});
	
	//监听单元格事件
	table.on('tool(res_list)', function(obj){
		var data = obj.data,
			layEvent = obj.event;
		
		if(layEvent === 'sure'){
			$('input[name="id"]').val(data.uid);
			$('.user').html(data.user);
			layer.confirm('确认执行该操作么？',{icon: 3,title:'提示'}, function(index){
				layer.close(index);
				$.post("<?php echo e(url('video_errors_del')); ?>",{_token:_token,id:data.id,state:1},function(res){
					if(res.status==2){
						layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
					}						
					if(res.status==1){
						layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
						setTimeout(function(){
							obj.del();
							layer.open({
							  type: 1,
							  title: ['积分奖励','font-size: 22px;font-weight: 800;'],
							  shadeClose: true,
							  area:['520px'],
							  content: $('.popup')
							});
						}, 1500);
					}
				})
			})
		}
		
		if(layEvent === 'del'){
			layer.confirm('确认执行该操作么？',{icon: 3,title:'提示'}, function(index){
				layer.close(index);
				$.post("<?php echo e(url('video_errors_del')); ?>",{_token:_token,id:data.id,state:2},function(res){
					if(res.status==2){
						layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
					}						
					if(res.status==1){
						layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
						setTimeout(function(){
							obj.del();
						},1500);
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
				url:"<?php echo e(url('account/increase_account')); ?>",
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