<!doctype html>
<html>
<head>
    <title>公告编辑</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="{{ asset('layui/css/layui.css') }}">
	<script type="text/javascript" src="{{ asset('layui/layui.js') }}"></script>
	<script type="text/javascript" src="{{asset('ueditor/ueditor.config.js')}}"></script>
	<script type="text/javascript" src="{{asset('ueditor/ueditor.all.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('ueditor/lang/zh-cn/zh-cn.js')}}"></script>
    <style type="text/css">
        .content{
            width:100%;margin:0 auto;max-width:1024px;padding:20px;
        }
		#sub{width: 100px;height: 36px;color: #fff;font-size: 15px;letter-spacing: 1px; background: #1E9FFF;
				border: none;outline: medium;-webkit-appearance: none;-webkit-border-radius: 0;}
    </style>
</head>
<body>
<div class="layui-form content">
	<div class="layui-form-item">
		<label class="layui-form-label">公告标题</label>
		<div class="layui-input-inline" style="width:300px;">
		  <input type="text" name="title" lay-verify="required" value="{{$notice['title']}}" autocomplete="off" placeholder="请输入标题" class="layui-input" maxlength=20>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">排序</label>
		<div class="layui-input-inline" style="width:300px;">
		  <input type="text" name="sort" value="{{$notice['sort']}}" lay-verify="required" autocomplete="off" placeholder="请输入排序" class="layui-input">
		</div>
	</div>
    <script id="editor" type="text/plain" style="height:500px;">{!!html_entity_decode($content)!!}</script>
	<div style="text-align:center;margin:10px 0;">
		<button id="sub">提交发布</button>
	</div>
</div>
<script type="text/javascript">
    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');
	layui.use(['jquery','layer'], function(){
		var $ = layui.jquery
			,layer = layui.layer;
		
		$(function(){
			var draft = ue.execCommand( "getlocaldata" );
			if(draft!=''){
				ue.execCommand('insertHtml',draft);
			}
			$('#sub').click(function(){
				var check = ue.hasContents();
				if(check==false){
					layer.msg('公告内容不能为空',{icon:2,shade: 0.3,time:1500});return false;
				}
				var content = ue.getContent(),
					title = $('input[name="title"]').val(),
					sort = $('input[name="sort"]').val();
				if(title==""){
					layer.msg('公告标题不能为空',{icon:2,shade: 0.3,time:1500});return false;
				}
				$.ajax({
					type:"post",
					dataType:'json',
					url:'../info/notice_eait',
					data:{
						content:content,
						title:title,
						sort:sort,
						id:"{{$_GET['id']}}",
						_token:"{{ csrf_token() }}"
					},
					beforeSend:function(){
						layer.load(0,{shade: 0.2});
					},
					
					success: function(d,s){
						if(d.status==2){
							layer.msg(d.msg,{icon: 2,time: 1500,shade:0.3});
						}
						if(d.status==1){
							layer.msg(d.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
							setTimeout(function(){
							  ue.execCommand("clearlocaldata" );
							  window.location.reload();
							}, 1500);
						}
					},
					
					error: function(d,s){
						layer.alert("未知错误");
					},
					
					complete:function(){
						layer.closeAll('loading');
					}
				})
			})
			
		})
	})
	
</script>
</body>
</html>