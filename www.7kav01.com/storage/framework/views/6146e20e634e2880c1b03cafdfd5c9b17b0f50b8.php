<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>管理员账号</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="<?php echo e(asset('layui/css/layui.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<style>
	.layui-table-fixed{display:none;}
</style>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
	<legend>管理员账号</legend>
</fieldset>
<div style="margin:15px;">
	<button type="button" class="layui-btn layui-btn-normal add_new">新增管理员</button>
	<table id="res_list" lay-filter="res_list"></table>
</div>
<section class="popup layui-form" style="padding:15px;display:none;">
	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
	<div class="layui-form-item">
		<label class="layui-form-label">账号</label>
		<div class="layui-input-block">
		  <input type="text" name="mebname" lay-verify="required" autocomplete="off" placeholder="请输入新增账号" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">密码</label>
		<div class="layui-input-block">
		  <input type="password" name="pword" lay-verify="required" autocomplete="off" placeholder="请输入密码" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">确认密码</label>
		<div class="layui-input-block">
		  <input type="password" name="repword" lay-verify="required" autocomplete="off" placeholder="请输入确认" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">权限</label>
		<div class="layui-input-block">
			<select name="ident" lay-filter="required">
				<option value=""></option>
				<?php $__currentLoopData = $ident; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($k); ?>"><?php echo e($v); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
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
<section class="popup_edit layui-form" style="padding:15px;display:none;">
	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
	<input type="hidden" name="id" value="">
	<div class="layui-form-item">
		<label class="layui-form-label">账号</label>
		<div class="layui-form-mid layui-word-aux mebname"></div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">修改密码</label>
		<div class="layui-input-block">
		  <input type="password" name="pword" lay-verify="required" autocomplete="off" placeholder="请输入密码" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">确认密码</label>
		<div class="layui-input-block">
		  <input type="password" name="repword" lay-verify="required" autocomplete="off" placeholder="请输入确认" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">权限</label>
		<div class="layui-input-block">
			<select name="ident" lay-filter="required">
				<option value=""></option>
				<?php $__currentLoopData = $ident; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($k); ?>"><?php echo e($v); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">执行密码</label>
		<div class="layui-input-block">
		  <input type="password" name="password" lay-verify="required" autocomplete="off" placeholder="请输入执行密码" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item" style="text-align:center;">
		<button class="layui-btn" lay-submit="" lay-filter="edit">确认操作</button>
	</div>
</section>
<script type="text/html" id="toolbar">
	<a class="layui-btn layui-btn-sm" lay-event="edit" >编辑</a>
	<a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="del" >删除</a>
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
					{field: 'sort', title: '序号',align:'center', width: 80,sort:true,totalRowText: '合计'}
					,{field: 'mebname',align:'center', title: '账号'}
					,{field: 'ident',align:'center',width: 200, title: '权限'}
					,{fixed: 'right',title: '操作', width: 400,align:'center', toolbar: '#toolbar'}
				  ]] //设置表头
		  ,url: "<?php echo e(url('account/get_managerList')); ?>" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'res_list'
		});
	var _token = '<?php echo e(csrf_token()); ?>';
	//监听单元格事件
	table.on('tool(res_list)', function(obj){
		var data = obj.data,
			layEvent = obj.event;
		
		if(layEvent === 'edit'){
			$('input[name="id"]').val(data.id);
			$('.mebname').html(data.mebname);
			layer.open({
				  type: 1,
				  title:['账户编辑','font-size: 22px;font-weight: 800;'],
				  shadeClose: true,
				  area:['520px'],
				  content: $('.popup_edit')
			  });
		}
		
		if(layEvent === 'del'){
			layer.confirm('确认删除该管理账户么？',{icon: 3,title:'提示'}, function(index){
				$.post("<?php echo e(url('account/del_manager')); ?>",{_token:_token,mid:data.id},function(res){
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
				url:"<?php echo e(url('account/add_manager')); ?>",
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
				url:"<?php echo e(url('account/edit_manager')); ?>",
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
		  type: 1,
		  title: ['新增管理员','font-size: 22px;font-weight: 800;'],
		  shadeClose: true,
		  area:['520px'],
		  content: $('.popup')
		});
	})
});
</script>

</body>
</html>