<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>标签管理</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="<?php echo e(asset('layui/css/layui.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
	<legend>标签管理</legend>
</fieldset>
<div style="margin:15px;">
	<button type="button" class="layui-btn layui-btn-normal add_new">增加标签</button>
	<table id="res_list" lay-filter="res_list"></table>
</div>
<section class="popup layui-form" style="padding:15px;display:none;">
	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
	<input type="hidden" name="id" value="">
	<div class="layui-form-item">
		<label class="layui-form-label">标签名称</label>
		<div class="layui-input-block">
		  <input type="text" name="tag" lay-verify="required" autocomplete="off" placeholder="请输入标签名称" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">导航排序</label>
		<div class="layui-input-block">
		  <input type="text" name="sort" lay-verify="required" autocomplete="off" value="0" placeholder="请输入导航排序" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">是否显示</label>
		<div class="layui-input-block">
		  <input type="radio" name="hide" value="0" checked title="显示">
		  <input type="radio" name="hide" value="1" title="隐藏">
		</div>
	</div>
	<div class="layui-form-item" style="text-align:center;">
		<button class="layui-btn" lay-submit="" lay-filter="sure">确认操作</button>
	</div>
</section>

<script type="text/html" id="conTpl">
	<a href="../manage/video_list?tag={{ d.id }}">共计{{ d.count }}素材</a>
</script>

<script type="text/html" id="hideTpl">
	{{#  if(d.hide === 0){ }}
		<span style="color: #5FB878;">显示</span>
	{{#  } else { }}
		<span style="color: #F581B1;">隐藏</span>
	{{#  } }}
</script>

<script type="text/html" id="toolbar">
	<a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit" >编辑</a>
	<?php if(json_decode(Cookie::get('admin_auth'),true)['admin_lev'] == 1): ?>
	<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del" >删除</a>
	<?php endif; ?>
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
					,{field: 'tag',align:'center', title: '标签名称'}
					,{field: 'sort',align:'center',width: 200, title: '导航排序'}
					,{field: 'count', title: '标签内容',templet: '#conTpl'}
					,{field: 'hide', title: '状态',width: 200,templet: '#hideTpl'}
					,{fixed: 'right',title: '操作', width: 300,align:'center', toolbar: '#toolbar'}
				  ]] //设置表头
		  ,url: "<?php echo e(url('video/get_video_tag')); ?>" //设置异步接口
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
			$('input[name="tag"]').val(data.tag);
			$('input[name="sort"]').val(data.sort);
			$('input[name="hide"][value="'+data.hide+'"]').attr('checked','true');
			form.render('radio');
			layer.open({
			  type: 1,
			  title: ['标签编辑','font-size: 22px;font-weight: 800;'],
			  shadeClose: true,
			  area:['520px'],
			  content: $('.popup')
			});
		}
		
		if(layEvent === 'del'){
			if(data.count != 0){
				layer.msg('该标签下仍存在素材，限制删除',{icon: 2,time: 1500,anim: 6,shade:0.3});return false;
			}
			
			layer.confirm('确认删除该标签么？',{icon: 3,title:'提示'}, function(index){
				$.post("<?php echo e(url('video/del_tag')); ?>",{_token:_token,id:data.id},function(res){
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
				url:"<?php echo e(url('video/edit_video_tag')); ?>",
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
		  title: ['新增标签','font-size: 22px;font-weight: 800;'],
		  shadeClose: true,
		  area:['520px'],
		  content: $('.popup')
		});
	})
});
</script>

</body>
</html>