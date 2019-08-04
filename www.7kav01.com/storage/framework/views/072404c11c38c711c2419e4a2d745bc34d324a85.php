

<?php $__env->startSection('title', '视频播放'); ?>

<?php $__env->startSection('style'); ?>
	<link rel="stylesheet" href="<?php echo e(asset('font-awesome/css/font-awesome.css')); ?>">
	<script type="text/javascript" src="<?php echo e(asset('js/clipboard.min.js')); ?>"></script>
	<link rel="stylesheet" href="../css/DPlayer.min.css">
	<script src="../js/hls.min.js"></script>
	<script src="../js/DPlayer.min.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>
	<?php echo $__env->make('moudel.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
	.dplayer-video-wrap .dplayer-video{max-height:600px;}
	.dplayer-paused .dplayer-bezel .dplayer-bezel-icon{opacity:1;}
	.errors_box h2{text-align: center;margin: 15px 0 10px;}
	.errors_box .layui-input-block{margin-left:50px;}
</style>
<div class="play-box bg-w">
	<h2 class="title text-omit"><a href="../m/index">首页</a> > <a href="../m/list?type=<?php echo e($album_type['id']); ?>"><?php echo e($album_type['type']); ?></a> > <?php echo e($name); ?></h2>	
	<div class="video-item pd-2">
		<h3 class="line-ud">《<?php echo e($name); ?>》</h3>
		<div id="dplayer"></div>
		
		<div class="play-mation pd-2">
			<div class="clearfix">
				<p class="fl">
					<i class="fa fa-youtube-play" aria-hidden="true"></i>
					<b><?php echo e($episode['view']); ?></b>次观看
				</p>
				
				<span class="fr evaluate <?php if(!empty($type)&& $type==2 ): ?> active <?php endif; ?>" data-type="2">
					<i class="fa fa-thumbs-down" aria-hidden="true"></i>
					<t><?php echo e($episode['dislike']); ?></t>
				</span>
				
				<span class="fr evaluate <?php if(!empty($type)&& $type==1 ): ?> active <?php endif; ?>" data-type="1">
					<i class="fa fa-thumbs-up" aria-hidden="true"></i>
					<t><?php echo e($episode['like']); ?></t>
				</span>
			</div>
			
			<div class="clearfix">
				<?php if(!empty($collect)): ?>
					<span class="fl collect active">
						<i class="fa fa-folder" aria-hidden="true"></i>
						<t>已收藏</t>
					</span>
				<?php else: ?>
					<span class="fl collect">
						<i class="fa fa-folder" aria-hidden="true"></i>
						<t>收藏</t>
					</span>
				<?php endif; ?>
				<span class="fr download">
					<i class="fa fa-download" aria-hidden="true"></i>
					下载本视频
					<font>（扣除<?php echo e($reduce); ?>积分）</font>
				</span>
			</div>
			<span class="share" data-clipboard-text="<?php echo e($share_url); ?>">
				<i class="layui-icon layui-icon-share" aria-hidden="true"></i>
				分享
			</span>
			<font>分享可提升会员等级和增加积分</font>
			<div class="clearfix">
				<span class="errors">
					<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
					报错
				</span>
				<font>（若情况属实，会奖赏一定的积分）</font>
			</div>
		</div>
	</div>
</div>

<div class="tags-item pd-2">
	<p>所属分类：<?php $__currentLoopData = $type_a; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <a href="../list?type=<?php echo e($k); ?>"><?php echo e($v); ?> </a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></p>
</div>

<div class="tags-item pd-2">
		<p>当前标签：</p>
		<?php $__currentLoopData = $tag; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<a href="../m/list?tag=<?php echo e($k); ?>"><?php echo e($v); ?></a>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php if(!empty($ad)): ?>
	<div class="ad-item bg-w">
		<?php $__currentLoopData = $ad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<a href="<?php echo e($v['url']); ?>"><image src="../images/ad/<?php echo e($v['image']); ?>"></a>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
<?php endif; ?>	

<div class="course-box">
	<h2 class="title">相关推荐>></h2>
	<div class="layui-row course-item pd-2">
		<?php $__currentLoopData = $recom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="layui-col-xs4 course-item-box">
				<a class="item-intro" href="javascript:;" data-preview="<?php echo e($v['videopreview']); ?>" data-eid="<?php echo e($v['eid']); ?>">
					<image src="../images/course/<?php echo e($v['image']); ?>">
					<span><?php echo e(date('m-d',$v['time'])); ?></span>
					<?php if($v['isfree'] == 1): ?>
						<!--<font>试看</font>-->
					<?php endif; ?>
					<!--<div class="coursr-rate" data-value="<?php echo e($v['level']); ?>"></div>-->
					<p class="text-omit"><?php echo e($v['name']); ?></p>
				</a>
			</div>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_ad'); ?>
   <?php echo $__env->make('moudel.footer_ad', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
	<section class="errors_box pd-2" style="display:none;">
		<h2>请选择错误类型</h2>
		<h3 style=color:red>&nbsp;&nbsp;&nbsp;&nbsp;人工审核，乱举报直接封号处理!</h3>
		<div class="layui-form">
			<input type="hidden" name="eid" value="<?php echo e($_GET['eid']); ?>">
			<div class="layui-form-item">
				<?php $__currentLoopData = config('deploy.errors_type'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="layui-input-block">
					<input type="radio" name="type" value="<?php echo e($k); ?>" title="<?php echo e($v); ?>" >
				</div>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
			<div class="layui-form-item t-center">
				  <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="errors">立即提交</button>
			</div>
		</div>
	</section>
   <?php echo $__env->make('moudel.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript" src="<?php echo e(asset('js/clipboard.min.js')); ?>"></script>
<script>
layui.use(['rate','element','form'], function(){
	var rate = layui.rate
		,form = layui.form
		,element = layui.element;
	
	var _token = "<?php echo e(csrf_token()); ?>";
	
	$("video").on("contextmenu",function(){return false;});
	
	const dp = new DPlayer({
		container: document.getElementById('dplayer'),
		screenshot: false,
		video: {
			<?php if($isMp4 == 1): ?>
				url: '../web/get_play_url?t=<?php echo e($t); ?>&key=<?php echo e($key); ?>',
			<?php else: ?>
				url:"<?php echo e($episode['url']); ?>",
			<?php endif; ?>
			pic: '../images/poster/loding.gif',
		},
	});
	
	dp.on('fullscreen',function(){
		$('video').css('max-height','100%');
	})
	
	dp.on('fullscreen_cancel',function(){
		$('video').css('max-height','600px');
	})
	
	$('body').on('click','#dplayer',function(){
		var elem = $(this).parent();
		if(!elem.hasClass('dplayer-paused') && !elem.hasClass('dplayer-playing')){
			dp.play();
		}
	})
	
	$('.collect').click(function(){
		$.post("<?php echo e(url('web/album_collect')); ?>",{_token:_token,aid:"<?php echo e($episode['album_id']); ?>"},function(res){
			if(res.status==3){
				layer.confirm(res.msg, function(){
				  window.location.href = res.url;
				})
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}						
			if(res.status==1){
				if(res.state==1){
					layer.msg('收藏成功');
					$('.collect t').html('已收藏');
					$('.collect').addClass('active');
					
				}
				if(res.state==2){
					layer.msg('已取消收藏');
					$('.collect t').html('收藏');
					$('.collect').removeClass('active');
				}
			}
		})
	})
	
	$('.evaluate').click(function(){
		var type = $(this).data('type')
			_this = $(this);
		$.post("<?php echo e(url('web/episode_evaluate')); ?>",{_token:_token,eid:"<?php echo e($_GET['eid']); ?>",type:type},function(res){
			if(res.status==3){
				layer.confirm(res.msg, function(){
				  window.location.href = res.url;
				})
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}						
			if(res.status==1){
				layer.msg(res.msg);
				var t = parseInt(_this.find('t').text());
				if(res.state==1){
					var a = parseInt(_this.siblings().find('t').text());
					_this.addClass('active').siblings().removeClass('active');
					_this.find('t').text(t+1);
					_this.siblings().find('t').text(a-1);
				}
				if(res.state==2){
					_this.removeClass('active');
					_this.find('t').text(t-1);
				}
				
				if(res.state==3){
					_this.addClass('active');
					_this.find('t').text(t+1);
				}
			}
		})
	})
	
	$('.download').click(function(){
		$.post("<?php echo e(url('web/download_video')); ?>",{_token:_token,eid:"<?php echo e($_GET['eid']); ?>"},function(res){
			if(res.status==3){
				layer.confirm(res.msg, function(){
				  window.location.href = res.url;
				})
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
			}
			if(res.status==1){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					var url = res.url+'&key='+encodeURIComponent(res.key);
					location.href = url;
				}, 1500);
			}
		})
	})
	
	$('.errors').click(function(){
		layer.open({
		  type: 1,
		  title:0,
		  shadeClose: true,
		  skin: 'layui-layer-rim',
		  area: ['90%'],
		  content: $('.errors_box')
		});
	})
	
	form.on('submit(errors)', function(data){
		data.field._token = _token;
		if(typeof(data.field.type)=='undefined'){
			layer.msg('请选择错误类型',{icon: 2,time: 1500,anim: 6,shade:0.3});return;
		}
		console.log(data.field.type);
		$.post("<?php echo e(url('web/errors_jduge')); ?>",data.field,function(res){
			if(res.status==3){
				layer.confirm(res.msg, function(){
				  window.location.href = res.url;
				})
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
			}						
			if(res.status==1){
				layer.msg(res.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					layer.closeAll();
				},1500);
			}
		})
	});
	
	var clipboard = new Clipboard('.share');

	clipboard.on('success', function(e) {
		layer.msg('复制分享链接成功，请分享给你的好友',{icon:1,time:1500});
	});

	clipboard.on('error', function(e) {
		layer.msg('复制失败');
	});
	
})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mb', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>