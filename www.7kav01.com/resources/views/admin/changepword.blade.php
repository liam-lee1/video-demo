<!DOCTYPE html>
<html>

	<head lang="en">
		<meta charset="utf-8">
		<meta name="renderer" content="webkit">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>修改密码</title>	
		<link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
	</head>
	<style>
	html,body{  
		height: 100%;  
	}  
	body{
		background:url("../images/bg.png") no-repeat;
		background-size:100% 100%;  
	}
	.umlanded-box{
		position:absolute;
		width:350px;
		top:25%;
		left:42%;
		text-align:center;
	}
	.umlanded-b{
		background-color:#fff;
		margin-top:-2rem;
	}
	.umlanded{
		background: #fff;
		width: 5.5rem;
		height: 5.5rem;
		border-radius: 50%;
		margin:0 auto;
	}
	.umlanded img{
		width:50%;
		margin-top:0.7rem
	}
	.land{
		padding:2rem 1rem 1rem 0;
	}
	.umland{
		font-size:20px;
		padding-top:2rem ;
	}
	.land a{
		text-decoration:underline;
	}
	</style>
<body>
<div class="umlanded-box">
	<div class="umlanded"><img src="../images/lock.png" /></div>
	<div class="umlanded-b">
		<div class="umland">修改密码</div>
		<div class="layui-form land">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <div class="layui-form-item">
				<label class="layui-form-label">原密码</label>
				<div class="layui-input-block">
				  <input type="password" name="old" lay-verify="required" autocomplete="off" placeholder="请输入原密码" class="layui-input">
				</div>
			  </div>
			  
			  <div class="layui-form-item">
				<label class="layui-form-label">新密码</label>
				<div class="layui-input-block">
				  <input type="password" name="pword" lay-verify="required" autocomplete="off" placeholder="请输入新密码" class="layui-input">
				</div>
			  </div>
			  
			  <div class="layui-form-item">
				<label class="layui-form-label">确认密码</label>
				<div class="layui-input-block">
				  <input type="password" name="repword" lay-verify="required" autocomplete="off" placeholder="请输入确认密码" class="layui-input">
				</div>
			  </div>
			  <div class="layui-form-item">
				<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="changePword" >确认修改</button>
			  </div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<script type="text/javascript">
layui.use(['element','jquery','layer','form'], function(){
  var element = layui.element
	  ,layer = layui.layer
	  ,form = layui.form
	  ,$ = layui.jquery;
	  
	  form.on('submit(changePword)', function(data){
		$.post("{{url('manage/changepword')}}",data.field,function(res){
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}
			if(res.status==1){
				layer.msg(res.msg,{time:1500,icon: 1,shade: 0.3});
				setTimeout(function(){
				  parent.location.href= "{{url('manage/logout')}}";
				}, 1500);
			}
		})
		return false;
	  });
})
</script>
</body>
</html>