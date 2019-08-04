

<?php $__env->startSection('title', '登录注册'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-box clear-bottom container bg-w">
	<ul class="account-tab">
		<li class="active">登录</li>
		<a href="../m/signup"><li>注册</li></a>
	</ul>
	<div class="layui-form">
		<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
		<div class="account-form-group">
			<div class="layui-input-inline full">
			  <input type="text" name="user" lay-verify="required" value="<?php if(!empty(Cookie::get('user'))): ?><?php echo e(Cookie::get('user')); ?><?php endif; ?>" autocomplete="off" maxlength="11" placeholder="账号" class="layui-input">
			</div>
		</div>
		<div class="account-form-group">
			<div class="layui-input-inline full">
			  <input type="password" name="pword" lay-verify="required" autocomplete="off" placeholder="请输入密码" class="layui-input">
			</div>
		</div>
		
		<div class="account-form-group">
			<div class="account-form-tip errorHint js-error"></div>
			<button class="account-form-btn" lay-submit="" lay-filter="login">登录</button>
		</div>
		<div class="account-form-group center">
			<a href="../m/reset">忘记密码</a>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_ad'); ?>
   <?php echo $__env->make('moudel.footer_ad', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
   <?php echo $__env->make('moudel.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
layui.use(['element','form'], function(){
	var form = layui.form
		,element = layui.element;
	
	//自定义验证规则
	form.verify({
		user: [/^[a-zA-Z0-9]{6,16}$/, '用户名需为6-16位字母数字']
		,pword: [/^.{6,12}$/, '密码长度需为6-12位']
	});
	
	form.on('submit(login)', function(data){
		var this_ = $(this);
		$.ajax({
			type:"post",
			dataType:'json',
			url:"<?php echo e(url('signin')); ?>",
			data:data.field,
			beforeSend:function(){
				layer.load(0,{shade:0.3});
			},
			success: function(d,s){
				if(d.status==2){
					layer.msg(d.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
				}
				if(d.status==1){
					layer.msg(d.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
					setTimeout(function(){
						location.href = "../m/index";
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
	var mark = true;
	$('.insign').click(function(){
		if(mark == false)return;
		mark = false;
		$.post("<?php echo e(url('web/insign')); ?>",{_token:'<?php echo e(csrf_token()); ?>'},function(res){
			mark = true;
			if(res.status==3){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					window.location.href = res.url;
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
			}
			if(res.status==1){
				layer.msg(res.msg,{icon: 1,time: 2000,anim: 0,shade:0.3});
			}
		})
	})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mb', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>