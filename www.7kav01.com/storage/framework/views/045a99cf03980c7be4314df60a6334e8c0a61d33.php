

<?php $__env->startSection('title', '公告'); ?>

<?php $__env->startSection('header'); ?>
	<?php echo $__env->make('moudel.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="notice-box bg-w">
	<h2 class="title">公告</h2>
	<div class="notice-c pd-2">
		
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
layui.use(['element','layer','flow'], function(){
	var element = layui.element
		,layer = layui.layer
		,flow = layui.flow;
		
	flow.load({
		elem: '.notice-c'
		,isAuto: true
		,done: function(page, next){
			var lis = [];
			$.get('../get_notice_list',{page:page},function(list){
				if(list.data.length == 0){
					var empty_html = '<div class="not-conts">\
										<span>暂无公告信息</span>\
									</div>';
					$('.notice-c').html(empty_html);return;
				}
				layui.each(list.data, function(index, item){
					lis.push('<a class="flex-box" href="../m/notice_xg?nid='+item.id+'">\
								<p>'+item.title+'</p>\
								<span>'+item.time+'</span>\
							</a>');
				})
				next(lis.join(''), page < list.pages);
			})
		}
	})
})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mb', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>