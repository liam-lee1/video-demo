<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>后台登录</title>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/login.css')); ?>" />
</head>
<body>
<div class="container">
	<section id="content">
		<form class="form_login">
			<h1>后台登录</h1>
			<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
			<div>
				<input type="text" name="mebname" placeholder="账户" required="required" autocomplete="off" id="username" />
			</div>
			<div>
				<input type="password" name="pword" placeholder="密码" required="required" autocomplete="off" id="password" />
			</div>
			<div class="">
				<span class="help-block u-errormessage" id="js-server-helpinfo" style="color:red;">&nbsp;</span>
			</div> 
			<div>
				<input type="submit" value="登录" class="btn btn-primary" id="js-btn-login"/>
			</div>
		</form>
		 <div class="button">
			<span class="help-block u-errormessage" id="js-server-helpinfo">&nbsp;</span>
			<a>欢迎访问</a>	
		</div>
	</section>
</div>
<script type="text/javascript" src="<?php echo e(asset('layui/layui.js')); ?>"></script>
<script>
layui.use(['layer','jquery'], function(){
	var layer = layui.layer
		,$ = layui.jquery;
	
	$(function(){
		$('#js-btn-login').click(function(){
			if($('#username').val()=='' || $('#password').val()==''){
				$('#js-server-helpinfo').html('请输入账户或密码');return;
			}
			$.ajax({
                type:"post",
                dataType:'json',
                url:"<?php echo e(url('manage/login')); ?>",
                data:$(".form_login").serialize(),
				async: false,
				beforeSend:function(){
					layer.load(0,{shade:0.3});
				},
                success: function(d,s){
					if(d.status==2){
						$('#js-server-helpinfo').html(d.msg);
					}
					if(d.status==1){
						layer.msg(d.msg,{icon: 1,time: 1500,anim: 5,shade:0.3});
						setTimeout(function(){
						  location.href="<?php echo e(url('manage/index')); ?>";
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
			event.preventDefault() 
		})
		return false;
	})
})
</script>
</body>
</html>