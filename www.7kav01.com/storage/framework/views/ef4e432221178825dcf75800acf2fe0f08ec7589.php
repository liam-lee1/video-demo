

<?php $__env->startSection('title', '公告详情'); ?>

<?php $__env->startSection('header'); ?>
	<?php echo $__env->make('moudel.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<style>
		.title{text-align:center;font-size:.55rem;font-weight:600;}
		.time{text-align:right;font-size:.375rem;margin:0;padding:.15rem .35rem;}
		.content{font-size:.4rem;padding:.2rem .3rem;text-indent:2em;margin:0;}
		.content img{width:auto;max-width:100%;object-fit: contain;}
	</style>
	<div class="content-box clear-bottom bg_w pd-2">
		<div class="title"><?php echo e($notice['title']); ?></div>
		<p class="time"><?php echo e(date('Y年m月d日',$notice['time'])); ?></p>
		<div class="content">
			<?php echo html_entity_decode($content); ?>

		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
   <?php echo $__env->make('moudel.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mb', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>