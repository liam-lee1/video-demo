<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="keywords" content="<?php echo e(isset($keywords) ? $keywords : ''); ?>">
		<meta name="description" content="<?php echo e(isset($description) ? $description : ''); ?>">
		<title><?php echo e(isset($title) ? $title : '在线视频'); ?> - <?php if(!empty($sub)): ?> <?php echo e($sub); ?> <?php else: ?> <?php echo $__env->yieldContent('title'); ?> <?php endif; ?></title>
		<link rel="stylesheet" href="<?php echo e(asset('css/global.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(asset('layui/css/layui.css')); ?>">
		<script type="text/javascript" src="<?php echo e(asset('layui/layui.js')); ?>"></script>
		<script type="text/javascript" src="<?php echo e(asset('js/flexible.js')); ?>"></script>
		<script type="text/javascript" src="<?php echo e(asset('js/jquery-3.2.1.min.js')); ?>"></script>
		<script type="text/javascript" src="<?php echo e(asset('js/base.js')); ?>"></script>
		<?php $__env->startSection('style'); ?>
		<?php echo $__env->yieldSection(); ?>
	</head>
    <body class="<?php echo $__env->yieldContent('body'); ?>">
		<div class="main-content">
			<?php $__env->startSection('header'); ?>
			<?php echo $__env->yieldSection(); ?>
			
			<?php $__env->startSection('content'); ?>
			<?php echo $__env->yieldSection(); ?>
			
			<?php $__env->startSection('footer_ad'); ?>
				
			<?php echo $__env->yieldSection(); ?>
		</div>
		<?php $__env->startSection('footer'); ?>
		<?php echo $__env->yieldSection(); ?>
	<?php $__env->startSection('js'); ?>
	<?php echo $__env->yieldSection(); ?>
    </body>
</html>
