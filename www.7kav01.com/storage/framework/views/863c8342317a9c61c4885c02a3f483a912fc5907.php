<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>余额明细</title>
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
  <legend>余额明细</legend>
</fieldset>
<div style="margin:15px;">
	<div class="search layui-form">
		<div>
			<div class="layui-form-item">
				<?php if(empty($_GET['user'])): ?>
				<div class="layui-input-inline">
					<input type="text" name="user" value="" autocomplete="off" placeholder="注册账号" class="layui-input">
				</div>
				<?php endif; ?>
				<div class="layui-input-inline">
					<select name="reason" lay-verify="required">
						<option value="">变动原因</option>
						<?php $__currentLoopData = $reason; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($k); ?>"><?php echo e($v); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<button class="layui-btn layui-btn-normal retrieval">搜索</button>
			</div>
		</div>
	</div>
	<table id="balance_list" lay-filter="balance_list"></table>
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
		  elem: '#balance_list'
		  ,cellMinWidth: 180
		  ,height:'full-160'
		  ,totalRow: true
		  ,where:{user:"<?php if(isset($_GET['user'])): ?><?php echo e($_GET['user']); ?><?php else: ?>''<?php endif; ?>"}
		  ,cols: [[ //标题栏
					{field: 'sort', title: '序号', width: 80,sort:true,totalRowText: '合计'}
					,{field: 'user', title: '注册账号'}
					,{field: 'mation', title: '支付宝信息'}
					,{field: 'born', title: '账户原本金额'}
					,{field: 'balance', title: '变动金额',sort:true,totalRow: true}
					,{field: 'reason', title: '变动原因',sort:true,width: 180}
					,{field: 'time', title: '变动时间',sort:true}
				  ]] //设置表头
		  ,url: "<?php echo e(url('account/get_balanceDetail')); ?>" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'balance_list'
		});
	
	$(".search .retrieval").click(function(){
		var user = $('input[name="user"]').val(),
			reason = $('select[name="reason"] option:selected').val();
		table.reload('balance_list',{where:{user:user,reason:reason},page: {curr: 1 }});
	});
});
</script>
</body>
</html>