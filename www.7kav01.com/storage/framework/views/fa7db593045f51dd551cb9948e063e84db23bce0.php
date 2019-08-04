<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>新增影片</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="<?php echo e(asset('layui/css/layui.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
  <script type="text/javascript" src="<?php echo e(asset('ueditor/ueditor.config.js')); ?>"></script>
  <script type="text/javascript" src="<?php echo e(asset('ueditor/ueditor.all.min.js')); ?>"></script>
  <script type="text/javascript" src="<?php echo e(asset('ueditor/lang/zh-cn/zh-cn.js')); ?>"></script>
</head>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>新增影片</legend>
</fieldset>
<div class="layui-form">
	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
	<div class="layui-form-item">
		  <label class="layui-form-label">影片名称</label>
		  <div class="layui-input-inline">
			<input type="text" name="name" value="" lay-verify="required" placeholder="请输入影片名称" autocomplete="off" class="layui-input">
		  </div>
	</div>
	
	<div class="layui-form-item upload">
		<label class="layui-form-label">缩略图片</label>
		<div class="layui-upload">
		  <div class="layui-upload-list">
			<img class="layui-upload-img direct" src="<?php echo e(asset('images/default.jpg')); ?>">
		  </div>
		</div> 
		<div class="layui-input-inline til layui-hide">
			<input type="text" name="image" lay-verify="required" value="" placeholder="影片图片" class="layui-input" />
		</div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">播放等级</label>
		  <div class="level"></div>
		  <input type="hidden" name="level" value="">
		  <input type="checkbox" name="isfree" value="1" lay-skin="primary" title="是否加入未登录试看">
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">视频分类</label>
		  <div class="layui-input-inline">
				<select name="type" lay-filter="video_type" lay-search="">
				  <option value=""></option>
				  <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($k); ?>"><?php echo e($v); ?></option>
				  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
		  </div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">附属分类</label>
		  <div class="layui-input-block sub_type">
			<div class="layui-form-mid layui-word-aux">请先选择视频分类</div>
		  </div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">标签选择</label>
		  <div class="layui-input-block">
			<button type="button" class="layui-btn layui-btn-normal refresh">刷新</button>
		  </div>
		  <div class="layui-input-block">
				<div class="tags" style="padding-top: 5px;">
					<?php $__currentLoopData = $tag; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<span class="list-tag" data-value="<?php echo e($k); ?>" ><?php echo e($v); ?></span>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
				<input type="hidden" name="tag" value="">
		  </div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">讲师</label>
		  <div class="layui-input-inline">
				<input type="text" name="lector"  value="" placeholder="影片讲师" class="layui-input" />
		  </div>
		  <div class="layui-form-mid layui-word-aux">如果不填显示未知</div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">预览视频</label>
		  <div class="layui-input-inline" style="width:500px;" >
				<input type="text" name="videopreview"  autocomplete="off" value="" placeholder="预览视频网址" class="layui-input" />
		  </div>
	</div>
	
	<!--
	<div class="layui-form-item">
		  <label class="layui-form-label">播放地址</label>
		  <div class="layui-input-inline">
			<button class="layui-btn raise">增集</button>
		  </div>
	</div>
	-->
	
	<div class="layui-form-item">
		<label class="layui-form-label">视频地址</label>
		<div class="layui-input-block episode-box">
			<div class="episode">
				<input type="text" name="episode[]" class="layui-input" autocomplete="off" value="" placeholder="播放地址" />
				<input type="text" name="downUrl[]" class="layui-input" lay-verify="required" autocomplete="off" value="" placeholder="下载地址" />
			</div>
		</div>
	<div>
	
	<div class="layui-form-item">
		<label class="layui-form-label">是否推荐</label>
		<div class="layui-input-block">
		  <input type="checkbox" value="1" name="isrecom" lay-skin="switch" lay-text="是|否">
		</div>
	</div>
	<!--
	<div class="layui-form-item">
		  <label class="layui-form-label">内容简介</label>
		  <div class="layui-input-block">
				<script id="editor" type="text/plain" style="height:500px;max-width:1440px;"></script>
		  </div>
	</div>
	-->
	<div class="layui-form-item">
		<div class="layui-input-block">
		  <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">确认添加</button>
		</div>
	</div>
	
</div>

<script type="text/javascript" src="<?php echo e(asset('layui/layui.js')); ?>"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
//var ue = UE.getEditor('editor');
layui.use(['upload','layer','form','rate','jquery'], function(){
	var layer = layui.layer,
		upload = layui.upload,
		form = layui.form,
		$ = layui.jquery,
		rate = layui.rate;
	
	rate.render({
		elem: '.level'
		,length:3
		,setText: function(value){
		  $('input[name="level"]').val(value);
		}
	});
	
	upload.render({
		elem: '.direct'
		,accept: 'image'
		,auto: false
		,number:1
		,choose: function(obj){
			obj.preview(function(index, file, result){
				$('input[name="image"]').val(result);
				$('.direct').attr('src',result);
		  });
		}
	});
	
	form.on('select(video_type)', function(data){
		var type = <?php echo json_encode($type); ?>,
			t_html = '';
		$.each(type,function(k,v){
			if(data.value != k){
				t_html += '<input type="checkbox" name="types[]" value="'+k+'" title="'+v+'">';
			}
		})
		$('.sub_type').html(t_html);
		form.render('checkbox');
	})
	
	form.on('submit(edit)', function(data){
		/*if(ue.hasContents() == false){
			layer.msg('课程内容不能为空',{icon:2,shade: 0.3,time:1500});return false;
		}
		data.field.intro = ue.getContent();*/
		layer.confirm('确认保存数据么？',{icon: 3,title:'提示'}, function(index){
			$.ajax({
				type:"post",
				dataType:'json',
				url:"<?php echo e(url('video/save_edit')); ?>",
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
							//ue.execCommand("clearlocaldata" );
							window.location.reload()
						}, 1500);
					}
				},
				
				error: function(d,s){
					layer.alert("未知错误");
				},
				
				complete:function(){
					layer.closeAll('loading');
				},
			})
			return false;
		})
	})
	
	$(function(){
		/*var draft = ue.execCommand( "getlocaldata" );
		if(draft!=''){
			ue.execCommand('insertHtml',draft);
		}*/
		
		$('body').on('click','.list-tag',function(){
			var tag = '';
			$(this).toggleClass('active');
			$.each($('.list-tag.active'),function(){
				var val = $(this).data('value');
				tag += '|'+val;
			})
			$('input[name="tag"]').val(tag.substr(1));
		})
		
		
		$('.raise').click(function(){
			$('.episode-box')
			var html = '<div class="episode">\
							<input type="text" name="episode[]" lay-verify="required" autocomplete="off" value="" placeholder="播放地址" class="layui-input" />\
							<input type="text" name="downUrl[]" lay-verify="required" autocomplete="off" value="" placeholder="下载地址" class="layui-input" />\
							<button class="layui-btn layui-btn-danger del">删除</button>\
						</div>'
			$('.episode-box').append(html);
		})
		
		$('.episode-box').on('click','.del',function(){
			$(this).parent().remove();
		})
		
		$('.refresh').click(function(){
			$.get('../video/get_tag_list',function(res){
				$('.tags').empty();
				var str = '';
				$.each(res,function(k,v){
					str += '<span class="list-tag" data-value="'+v.id+'" >'+v.tag+'</span>';
				})
				$('.tags').html(str);
			})
		})
	})
});
</script>

</body>
</html>