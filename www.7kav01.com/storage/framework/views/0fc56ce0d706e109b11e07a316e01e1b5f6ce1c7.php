

<?php $__env->startSection('title', '公告详情'); ?>

<?php $__env->startSection('header'); ?>
	<?php echo $__env->make('moudel.pc_header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<style>
		.title{text-align:center;font-size:22px;font-weight:600;}
		.time{text-align:right;font-size:16px;margin:0;padding:5px 10px;}
		.content{font-size:16px;padding:5px 10px;text-indent:2em;margin:0;}
	</style>
	<div class="content-box bg_w pd-2">
		<div class="title"><?php echo e($notice['title']); ?></div>
		<p class="time"><?php echo e(date('Y年m月d日',$notice['time'])); ?></p>
		<div class="content">
			<?php echo html_entity_decode($content); ?>

		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
   <?php echo $__env->make('moudel.pc_footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pc', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>