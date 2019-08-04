

<?php $__env->startSection('title', '密码找回'); ?>


<?php $__env->startSection('content'); ?>
<div class="content-box clear-bottom container bg-w">
	<ul class="account-tab">
		<li class="active">找回密码</li>
	</ul>
	<div class="layui-form">
		<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
		<input type="hidden" name="key" value="0">
		<div>
			<div class="account-form-group step">
				<div class="layui-input-inline full">
				  <input type="text" name="user" lay-verify="required|user" value="" autocomplete="off" maxlength="16" placeholder="账号" class="layui-input">
				</div>
				<div class="account-form-tip errorHint js-error">输入您要找回密码的账号</div>
			</div>
			
			
			<div class="account-form-group step">
				<div class="layui-input-inline full">
				  <input type="email" name="email" lay-verify="" value="" autocomplete="off" placeholder="邮箱" class="layui-input">
				</div>
				<div class="account-form-tip errorHint js-error">输入注册时绑定的邮箱</div>
			</div>
			
			<div class="account-form-group step">
				<div class="layui-input-inline full">
				  <input type="text" name="ans" lay-verify="" value="" autocomplete="off" placeholder="答案" class="layui-input">
				</div>
				<div class="account-form-tip errorHint js-error">你的密保问题是“<span class="question">xxx</span>”请输入答案</div>
			</div>
			
			<div class="account-form-group step">
				<div class="layui-input-inline full">
				  <input type="password" name="pword" lay-verify="" value="" autocomplete="off" maxlength="12" placeholder="新密码" class="layui-input">
				</div>
				<div class="account-form-tip errorHint js-error">请输入新的密码</div>
			</div>
		</div>
		<div class="account-form-group">
			<button class="account-form-btn reset" lay-submit="" lay-filter="reset">下一步</button>
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
layui.use(['element','layer','jquery','form'], function(){
	var element = layui.element
		,layer = layui.layer
		,form = layui.form
		,$ = layui.jquery;
	
	//自定义验证规则
	form.verify({
		user: [/^[a-zA-Z0-9]{6,16}$/, '用户名需为6-16位字母数字']
		,pword: [/^.{6,12}$/, '密码长度需为6-12位']
	});
	
	form.on('submit(reset)', function(data){
		var this_ = $(this);
		$.ajax({
			type:"post",
			dataType:'json',
			url:"<?php echo e(url('reset_pword')); ?>",
			data:data.field,
			beforeSend:function(){
				layer.load(0,{shade:0.3});
			},
			success: function(d,s){
				if(d.status == -1){
					layer.msg(d.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
				}
				if(d.status==2){
					$('.step').eq(d.key).show().siblings().hide();
					$('.step').eq(d.key).find('input').attr({'name':d.name,'lay-verify':'required|'+d.name});
					$('input[name="key"]').val(d.key);
					if(d.key==2){
						$('.question').html(d.question);
					}
					if(d.key==3){
						$('.reset').html('确定');
					}else{
						$('.reset').html('下一步');
					}
				}
				if(d.status==1){
					layer.msg(d.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
					setTimeout(function(){
						location.href = "../m/signin";
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