<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>影片编辑</title>
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
  <legend>影片编辑</legend>
</fieldset>
<div class="layui-form">
	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
	<input type="hidden" name="id" value="<?php echo e($album['id']); ?>">
	<div class="layui-form-item">
		  <label class="layui-form-label">影片名称</label>
		  <div class="layui-input-inline" style="width:350px;">
			<input type="text" name="name" lay-verify="required" value="<?php echo e($album['name']); ?>" placeholder="请输入影片名称" autocomplete="off" class="layui-input">
		  </div>
	</div>
	
	<div class="layui-form-item upload">
		<label class="layui-form-label">缩略图片</label>
		<div class="layui-upload">
		  <div class="layui-upload-list">
			<img class="layui-upload-img direct" src="<?php echo e(asset('images/course')); ?>/<?php echo e($album['image']); ?>">
		  </div>
		</div> 
		<div class="layui-input-inline til layui-hide">
			<input type="text" name="image" lay-verify="required" value="<?php echo e($album['image']); ?>" placeholder="影片图片" class="layui-input" />
		</div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">播放等级</label>
		  <div class="level"></div>
		  <input type="hidden" name="level" value="<?php echo e($album['level']); ?>">
		  <input type="checkbox" name="isfree" value="1" <?php if($album['isfree']==1): ?> checked <?php endif; ?> lay-skin="primary" title="是否加入未登录试看">
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">视频分类</label>
		  <div class="layui-input-inline">
				<select name="type" lay-filter="video_type" lay-search="">
				  <option value=""></option>
				  <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($k); ?>" <?php if( $main == $k): ?> selected <?php endif; ?> ><?php echo e($v); ?></option>
				  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
		  </div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">附属分类</label>
		  <div class="layui-input-block sub_type">
			<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<input type="checkbox" name="types[]" <?php if(in_array($k,$sub)): ?> checked <?php endif; ?> value="<?php echo e($k); ?>" title="<?php echo e($v); ?>">
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		  </div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">标签选择</label>
		  <div class="layui-input-block">
				<div style="padding-top: 5px;">
					<?php $__currentLoopData = $tag; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<span class="list-tag <?php if(in_array($k,$tags)): ?> active <?php endif; ?>" data-value="<?php echo e($k); ?>" ><?php echo e($v); ?></span>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
				<input type="hidden" name="tag" value="<?php echo e($album['tag']); ?>">
		  </div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">讲师</label>
		  <div class="layui-input-inline">
				<input type="text" name="lector"  value="<?php echo e($album['lector']); ?>" placeholder="影片讲师" class="layui-input" />
		  </div>
		  <div class="layui-form-mid layui-word-aux">如果不填显示未知</div>
	</div>
	
	<div class="layui-form-item">
		  <label class="layui-form-label">预览视频</label>
		  <div class="layui-input-inline" style="width:500px;">
				<input type="text" name="videopreview" autocomplete="off" value="<?php echo e($album['videopreview']); ?>" placeholder="预览视频网址" class="layui-input" />
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
		<label class="layui-form-label">播放地址</label>
		<div class="layui-input-block episode-box">
			<table class="layui-table" style="width:1400px">
				<colgroup>
				  <col width="500">
				  <col width="500">
				  <col width="100">
				  <col width="100">
				  <col width="100">
				  <!--<col width="100">-->
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>播放地址</th>
					<th>下载地址</th>
					<th>播放量</th>
					<th>赞数</th>
					<th>踩数</th>
					<!--<th>操作</th>-->
				  </tr> 
				</thead>
				
				<tbody>
					<?php $__currentLoopData = $episode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<input type="hidden" name="eid[]" value="<?php echo e($v['id']); ?>">
							<td><input type="text" name="url[]" class="layui-input" value="<?php echo e($v['url']); ?>" autocomplete="off"></td>
							<td><input type="text" name="downUrl[]" class="layui-input" lay-verify="required" value="<?php echo e($v['downUrl']); ?>" autocomplete="off"></td>
							<td><?php echo e($v['view']); ?></td>
							<td><?php echo e($v['like']); ?></td>
							<td><?php echo e($v['dislike']); ?></td>
							<!--<td><button class="layui-btn layui-btn-sm layui-btn-danger del_e">删除</button></td>-->
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>
	<div>
	
	<div class="layui-form-item">
		<label class="layui-form-label">是否推荐</label>
		<div class="layui-input-block">
		  <input type="checkbox" name="isrecom" value="1" <?php if($album['isrecom']==1): ?> checked <?php endif; ?> lay-skin="switch" lay-text="是|否">
		</div>
	</div>
	<!--
	<div class="layui-form-item">
		  <label class="layui-form-label">内容简介</label>
		  <div class="layui-input-block">
				<script id="editor" type="text/plain" style="height:500px;max-width:1440px;"><?php echo html_entity_decode($intro); ?></script>
		  </div>
	</div>
	-->
	<div class="layui-form-item">
		<div class="layui-input-block">
		  <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
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
		,value:"<?php echo e($album['level']); ?>"
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
		$('.list-tag').click(function(){
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
							<input type="hidden" name="eid[]" value="">\
							<input type="text" name="episode[]" lay-verify="required" value="" placeholder="播放地址" class="layui-input" />\
							<input type="text" name="downUrl[]" class="layui-input" lay-verify="required" value="" autocomplete="off">\
							<button class="layui-btn layui-btn-danger del">删除</button>\
						</div>'
			$('.episode-box').append(html);
		})
		
		$('.episode-box').on('click','.del',function(){
			$(this).parent().remove();
		})
		
		$('.del_e').click(function(){
			$(this).parents('tr').remove();
		})
	})
});
</script>

</body>
</html>