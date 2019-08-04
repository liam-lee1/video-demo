<?php if(!empty($ad)): ?>
	<div class="ad-item bg-w">
		<?php $__currentLoopData = $ad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<a href="<?php echo e($v['url']); ?>"><image src="../images/ad/<?php echo e($v['image']); ?>"></a>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
<?php endif; ?>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?73691e7c0215b4eaf6962a10c9b8f9cf";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<script type="text/javascript" src="//js.users.51.la/20143761.js"></script>
<div id="index_tan" style="left:0px;width:100%;background-color:#00000099;text-align:center;position:fixed;z-index:100;top:-3px;">
<span style="font-size: 17px;color: #ffffff;">请记住本站中文域名 <span style="color:#36ff06">不迷路.com</span> 永不迷路！</span>
</div>
<footer class="t-center">
	<p>©7000AV.com 版权所有</p>
	<p>发送邮件到	7000avcom@gmail.com即可获取最新地址！</p>

</footer>