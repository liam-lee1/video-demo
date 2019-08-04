<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>账户明细</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="<?php echo e(asset('layui/css/layui.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<style>
	
</style>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>账户明细</legend>
</fieldset>
<div style="margin:15px;">
	<div class="search layui-form">
		<div class="layui-form-item">
			<div class="layui-input-inline">
				<select name="type" lay-verify="required">
					<option value="">变动原因</option>
					<?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($k); ?>"><?php echo e($v); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
			<button class="layui-btn layui-btn-normal retrieval">搜索</button>
		</div>
	</div>
	<table id="account_list" lay-filter="account_list"></table>
</div>
<script type="text/javascript" src="<?php echo e(asset('layui/layui.js')); ?>"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
layui.use(['table','layer','form','jquery'], function(){
	var table = layui.table,
	  layer = layui.layer,
	  form = layui.form,
	  $ = layui.$;
	
	var tableIns = table.render({
		  elem: '#account_list'
		  ,cellMinWidth: 180
		  ,height:'full-160'
		  ,totalRow: true
		  ,where:{openid:"<?php echo e($_GET['openid']); ?>"}
		  ,cols: [[ //标题栏
					{field: 'sort', title: '序号', width: 80,sort:true,totalRowText: '合计'}
					,{field: 'credit', title: '积分',totalRow: true}
					,{field: 'exp', title: '经验值',totalRow: true,}
					,{field: 'type', title: '变动原因',sort:true,width: 250}
					,{field: 'time', title: '变更时间',sort:true}
				  ]] //设置表头
		  ,url: "<?php echo e(url('account/get_accountDetail')); ?>" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'account_list'
		});
	
	$(".search .retrieval").click(function(){
		var type = $('select[name="type"] option:selected').val();
		table.reload('account_list',{where:{type:type}});
	});
});
</script>
</body>
</html>