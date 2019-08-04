<?php if(!empty($ad)): ?>
	<div class="ad-item bg-w">
		<?php $__currentLoopData = $ad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<a href="<?php echo e($v['url']); ?>"><image src="../images/ad/<?php echo e($v['image']); ?>"></a>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
<?php endif; ?>