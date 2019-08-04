<?php $__env->startSection('title', '视频介绍'); ?>

<?php $__env->startSection('header'); ?>
	<?php echo $__env->make('moudel.pc_header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('font-awesome/css/font-awesome.css')); ?>">
<div class="course-box">
	<fieldset class="layui-elem-field breadcrumb-item layui-field-title">
		<legend>
			当前位置：
			<span class="layui-breadcrumb" lay-separator=">">
			  <a href="../index">首页</a>
			  <a href="../list?type=<?php echo e(key($type)); ?>"><?php echo e(reset($type)); ?>

			  <a><cite>《<?php echo e($album['name']); ?>》</cite></a>
			</span>
		</legend>
	</fieldset>
	<div class="layui-row video-item">
		<div class="layui-col-xs9">
			<div class="flex-box item-box line-ud">
				<div class="item-img">
					<image src="../images/course/<?php echo e($album['image']); ?>">
				</div>
				<div class="item-content">
					<?php if($collect!=0): ?>
						<p class="collect active">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>收藏成功</span>
						</p>
					<?php else: ?>
						<p class="collect">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>收藏到个人中心</span>
						</p>
					<?php endif; ?>
					
					<h2>《<?php echo e($album['name']); ?>》</h2>
					<div class="my-rate">
						<span>影片评分</span>
						<div class="video-rate"></div>
					</div>
					<p>主演：<?php echo e($album['lector']); ?></p>
					<!--<p>时长：<?php echo e(transTime($album['duration'])); ?></p>-->
					<div class="need-rate">播放所需等级：
						<div class="meb_level_need narrow"></div><a href="../ucenter">（我要提升等级）</a>
					</div>
					<p>分类：<?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <a href="../list?type=<?php echo e($k); ?>"><?php echo e($v); ?> </a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></p>
					<div class="tag-item">
						<p>标签：</p>
						<?php $__currentLoopData = $tag; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<a href="../list?tag=<?php echo e($k); ?>"><?php echo e($v); ?></a>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php if(!empty($ad)): ?>
						<div class="ad-item bg-w">
							<?php $__currentLoopData = $ad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<a href="<?php echo e($v['url']); ?>"><image src="../images/ad/<?php echo e($v['image']); ?>"></a>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="episode">
				<?php $__currentLoopData = $episode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<a href="javascript:;" data-eid="<?php echo e($v); ?>" >第<?php echo e($loop->iteration); ?>集</a>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
		</div>
		<div class="layui-col-xs3">
			<?php if(!empty($ad_w)): ?>
				<div class="ad-item">
					<?php $__currentLoopData = $ad_w; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<a href="<?php echo e($v['url']); ?>"><image src="../images/ad/<?php echo e($v['image']); ?>"></a>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	
	<div class="video-intro">
		<fieldset class="layui-elem-field layui-field-title">
			<legend>视频介绍</legend>
		</fieldset>
		<div class="pd-2">
			<?php echo html_entity_decode($intro); ?>

		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
	<?php echo $__env->make('moudel.pc_footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
layui.use(['rate','element','layer'], function(){
	var rate = layui.rate
		,layer = layui.layer
		,element = layui.element;
	rate.render({
		elem: '.video-rate'
		,half: true
		,theme:'#ff055a'
		,text: true
		,setText: function(value){
		  this.span.text(value*2);
		}
	});
	
	rate.render({
		elem: '.meb_level_need'
		,value: "<?php echo e($album['level']); ?>"
		,length:3
		,readonly: true
		,theme:'#101010'
	});
	
	var _token = "<?php echo e(csrf_token()); ?>";
	
	rate.render({
		elem: '.video-rate'
		,half: true
		,value: "<?php echo e($rate); ?>"
		,theme:'#ff055a'
		<?php if($is_rate == 2): ?>
			,readonly:true
		<?php endif; ?>
		,text: true
		,setText: function(value){
		  this.span.text(value*2);
		  <?php if($is_rate == 0): ?>
				if(value>0){
					layer.open({
					  type: 2,
					  title:false,
					  area: ['1240px', '580px'],
					  fixed: false, //不固定
					  content: "../sign"
					});
				}
			<?php endif; ?>
		  <?php if($is_rate == 1): ?>
			  if(value>0){
				 layer.confirm('确认评分为'+value*2+'分么？（仅能评分一次）',{icon: 3,title:'提示'}, function(index){
					layer.close(index);
					$.post("<?php echo e(url('web/album_rate')); ?>",{_token:_token,aid:"<?php echo e($_GET['aid']); ?>",rate:value*2},function(res){
						layer.close(index);
						if(res.status==2){
							layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
						}						
						if(res.status==1){
							layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
							setTimeout(function(){
								window.location.reload();
							}, 1500);
						}
					})
				}) 
			  }
		  <?php endif; ?>
		}
	});
	
	$('.episode a').click(function(){
		var eid = $(this).data('eid');
		
		<?php if(!empty(session('openid')) || $album['isfree']==1): ?>
			<?php if($meb_level < $album['level'] && $meb_level>0 && $album['isfree']==0 && $ispaid == 0): ?>
				layer.open({
					type: 1
					,title: false //不显示标题栏
					,closeBtn: false
					,area: '300px;'
					,shade: 0.3
					,id: 'LAY_layuipro' //设定一个id，防止重复弹出
					,btn: ['充值VIP','免费提升','取消']
					,btnAlign: 'c'
					,content: '<div style="padding:45px;line-height: 22px; background-color: #fff; color: #333; font-weight: 600;font-size:18px;text-align:center;border-bottom:1px solid #efefef;">你当前等级不足观看本视频，请提升等级</div>'
					,yes: function(){
						location.href = "../category";
					}
					,btn2: function(){
						location.href = "../ucenter";
					}
				});
			<?php else: ?>
				location.href="../play?eid="+eid;
			<?php endif; ?>
		<?php else: ?>
			layer.open({
				type: 1
				,title: false //不显示标题栏
				,closeBtn: false
				,area: '300px;'
				,shade: 0.3
				,id: 'LAY_layuipro' //设定一个id，防止重复弹出
				,btn: ['立即登录' , '免费试看']
				,btnAlign: 'c'
				,content: '<div style="padding:45px;line-height: 22px; background-color: #fff; color: #333; font-weight: 600;font-size:18px;text-align:center;border-bottom:1px solid #efefef;">您还未登录，请前往登录或者进入免费试看</div>'
				,yes: function(){
					layer.open({
					  type: 2,
					  title:false,
					  area: ['1240px', '580px'],
					  fixed: false, //不固定
					  content: "../sign"
					});
				}
				,btn2: function(){
					location.href = '../freecourse';
				}
			});
		<?php endif; ?>
	})
	
	$('.collect').click(function(){
		$.post("<?php echo e(url('web/album_collect')); ?>",{_token:_token,aid:"<?php echo e($_GET['aid']); ?>"},function(res){
				if(res.status==3){
					layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
					setTimeout(function(){
						layer.open({
						  type: 2,
						  title:false,
						  area: ['1240px', '580px'],
						  fixed: false, //不固定
						  content: res.url
						});
					}, 1500);
				}
				if(res.status==2){
					layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
				}						
				if(res.status==1){
					$('.collect span').html(res.msg);
					if(res.state==1){
						layer.msg('收藏成功');
						$('.collect').addClass('active');
					}
					if(res.state==2){
						layer.msg('已取消收藏');
						$('.collect').removeClass('active');
					}
				}
			})
	})
})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pc', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>