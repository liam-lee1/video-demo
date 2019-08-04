<!doctype html>
<html>
<head>
    <title>会员信息</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="<?php echo e(asset('layui/css/layui.css')); ?>">
	<script type="text/javascript" src="<?php echo e(asset('layui/layui.js')); ?>"></script>
</head>
<body>
<div class="layui-form" style="padding:20px;">
		<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
		<input type="hidden" name="id" value="<?php echo e($mation['id']); ?>">
		<div class="layui-form-item">
			<label class="layui-form-label">会员账号</label>
			<div class="layui-input-block">
			  <input type="text" name="user" value="<?php echo e($mation['user']); ?>" readonly lay-verify="required" autocomplete="off" placeholder="请输入会员账号" class="layui-input" maxlength=20>
			</div>
		</div>
		
		<div class="layui-form-item">
			  <label class="layui-form-label">账户密码</label>
			  <div class="layui-input-block">
				<input type="password" name="pword" value="" placeholder="若修改请输入修改后的密码" autocomplete="off" class="layui-input">
			  </div>
		</div>
		
		<div class="layui-form-item">
			  <label class="layui-form-label">注册邮箱</label>
			  <div class="layui-input-block">
				<input type="text" name="email" value="<?php echo e($mation['email']); ?>" lay-verify="required" autocomplete="off" class="layui-input">
			  </div>
		</div>
		
		<div class="layui-form-item">
			  <label class="layui-form-label">问题</label>
			  <div class="layui-input-block">
				<input type="text" name="question" value="<?php echo e($mation['question']); ?>" lay-verify="required" autocomplete="off" class="layui-input">
			  </div>
		</div>
		
		<div class="layui-form-item">
			  <label class="layui-form-label">答案</label>
			  <div class="layui-input-block">
				<input type="text" name="ans" value="<?php echo e($mation['ans']); ?>" lay-verify="required" autocomplete="off" class="layui-input">
			  </div>
		</div>
		
		<div class="layui-form-item" style="text-align:center;margin:10px 0;">
			  <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">提交</button>
		</div>
</div>
<script type="text/javascript">
layui.use(['jquery','layer','form'], function(){
	var $ = layui.jquery
		,form = layui.form
		,layer = layui.layer;
	

	form.on('submit(edit)', function(data){
		layer.confirm('确认执行该操作么？',{icon: 3,title:'提示'}, function(index){
			$.ajax({
				type:"post",
				dataType:'json',
				url:"<?php echo e(url('account/modify_account')); ?>",
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
})
	
</script>
</body>
</html>