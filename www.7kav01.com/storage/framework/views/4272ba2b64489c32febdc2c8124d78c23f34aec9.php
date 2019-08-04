

<?php $__env->startSection('title', '首页'); ?>

<?php $__env->startSection('header'); ?>
	<?php echo $__env->make('moudel.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(!empty($new)): ?>
	<div class="course-box">
		<h2 class="title">最近更新>></h2>
		<div class="layui-row course-item pd-2">
			<?php $__currentLoopData = $new; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="layui-col-xs4 course-item-box">
					<a class="item-intro" href="javascript:;" data-preview="<?php echo e($v['videopreview']); ?>" data-eid="<?php echo e($v['eid']); ?>">
						<image src="../images/course/<?php echo e($v['image']); ?>" title="<?php echo e($v['name']); ?>" >
						<span><?php echo e(date('m-d',$v['time'])); ?></span>
						<?php if($v['isfree'] == 1): ?>
							<!--<font>试看</font>-->
						<?php endif; ?>
						<!--<div class="coursr-rate" data-value="<?php echo e($v['level']); ?>"></div>-->
						<p class="text-omit" title="<?php echo e($v['name']); ?>" ><?php echo e($v['name']); ?></p>
					</a>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	</div>
<?php endif; ?>

<?php if(!empty($praise)): ?>
	<div class="course-box">
		<h2 class="title">最佳视频>></h2>
		<div class="layui-row course-item pd-2">
			<?php $__currentLoopData = $praise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="layui-col-xs4 course-item-box">
					<a class="item-intro" href="javascript:;" data-preview="<?php echo e($v['videopreview']); ?>" data-eid="<?php echo e($v['eid']); ?>">
						<image src="../images/course/<?php echo e($v['image']); ?>" title="<?php echo e($v['name']); ?>" >
						<span><?php echo e(date('m-d',$v['time'])); ?></span>
						<?php if($v['isfree'] == 1): ?>
							<!--<font>试看</font>-->
						<?php endif; ?>
						<!--<div class="coursr-rate" data-value="<?php echo e($v['level']); ?>"></div>-->
						<p class="text-omit" title="<?php echo e($v['name']); ?>" ><?php echo e($v['name']); ?></p>
					</a>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	</div>
<?php endif; ?>

<?php if(!empty($recom)): ?>
	<div class="course-box">
		<h2 class="title">推荐视频>></h2>
		<div class="layui-row course-item pd-2">
			<?php $__currentLoopData = $recom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="layui-col-xs4 course-item-box">
					<a class="item-intro" href="javascript:;" data-preview="<?php echo e($v['videopreview']); ?>" data-eid="<?php echo e($v['eid']); ?>">
						<image src="../images/course/<?php echo e($v['image']); ?>" title="<?php echo e($v['name']); ?>" >
						<span><?php echo e(date('m-d',$v['time'])); ?></span>
						<?php if($v['isfree'] == 1): ?>
							<!--<font>试看</font>-->
						<?php endif; ?>
						<!--<div class="coursr-rate" data-value="<?php echo e($v['level']); ?>"></div>-->
						<p class="text-omit" title="<?php echo e($v['name']); ?>" ><?php echo e($v['name']); ?></p>
					</a>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_ad'); ?>
   <?php echo $__env->make('moudel.footer_ad', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
   <?php echo $__env->make('moudel.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mb', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>