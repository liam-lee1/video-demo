<style>
	.badge{
		width:.5335rem;
		height:.5335rem;
		border-radius:50%;
		position: absolute;
		background: #ff0606;
		font-size: .325rem;
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		top: .01rem;
		right: .1rem;
		animation: shake 3s infinite;
	}
	@-webkit-keyframes shake {
		0% {
			opacity:1;
		}
		50% {
			opacity:0;
		}
		100% {
			opacity:1;
		}
	}
</style>
<header class="flex-box">
	<a href="../m/index" class="nav-l">
		<img src="../images/logo.jpg">
	</a>
	<div class="nav-c search-box">
		<i class="layui-icon layui-icon-search search-btn"></i>
		<input type="search" class="search-input" placeholder="搜索" >
	</div>
	<a href="../m/notice" class="item-icon">
		<i class="layui-icon layui-icon-speaker"></i>
		<font>公告</font>
		<?php if($toRead > 0): ?>
			<span class="badge"><?php echo e($toRead); ?></span>
		<?php endif; ?>
	</a>
</header>

<div class="menu-item">
	<ul>
		<?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<li <?php if($as == 'list' && isset($_GET['type'])&&$_GET['type']==$k ): ?> class="active" <?php endif; ?> >
				<a href="../m/list?type=<?php echo e($k); ?>" ><?php echo e($v); ?></a>
			</li>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
</div>

<div class="tags-item bg-w pd-2">
	<div class="tags-box box-min">
		<p>标签：</p>
		<?php $__currentLoopData = $tag; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<?php if($as == 'list' && !isset($_GET['type']) && !isset($_GET['key'])): ?>
				<span data-value="<?php echo e($k); ?>" ><?php echo e($v); ?></span>
			<?php else: ?>
				<a href="../m/list?tag=<?php echo e($k); ?>"><?php echo e($v); ?></a>
			<?php endif; ?>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<?php if($as == 'list' && !isset($_GET['type']) && !isset($_GET['key'])): ?>
			<span class="clear_all" data-value="-1">全部清除</span>
		<?php endif; ?>
		<div class="J_FilterMore">
			<font class="open">展开</font>
			<font class="close">收起</font>
			<i class="layui-icon layui-icon-down"></i>
		</div>
	</div>
</div>


<?php if(!empty($ad)): ?>
	<div class="ad-item bg-w">
		<?php $__currentLoopData = $ad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<a href="<?php echo e($v['url']); ?>" target="_blank"><image src="../images/ad/<?php echo e($v['image']); ?>"></a>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
<?php endif; ?>
<script>
layui.use(['element','laypage','rate','layer'], function(){
	var laypage = layui.laypage,
		rate = layui.rate,
		element = layui.element;
	
	$('.J_FilterMore').click(function(){
		$(this).parent().toggleClass('tags-more box-min');
	})
	
	var mark = true;
	$('.insign').click(function(){
		if(mark == false)return;
		mark = false;
		$.post("<?php echo e(url('web/insign')); ?>",{_token:'<?php echo e(csrf_token()); ?>'},function(res){
			mark = true;
			if(res.status==3){
				layer.msg(res.msg,{icon: 16,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					window.location.href = res.url;
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
			}
			if(res.status==1){
				layer.msg(res.msg,{icon: 1,time: 2000,anim: 0,shade:0.3});
			}
		})
	})
	
	function coursr_level(){
		$('.coursr-rate').each(function(){
			var val = $(this).data('value');
			if(val!== 'undefined'){
				rate.render({
					elem: $(this)
					,length:3
					,value: val
					,readonly: true
					,theme:'#ff055a'
				});
			}
		})
	}
	
	coursr_level();
	
	$('.search-btn').click(function(){
		key = $('input[type="search"]').val();
		if(key==''){
			layer.msg('请输入搜索词',{icon: 2,time: 2000,anim: 6,shade:0.3});return;
		}
		location.href="../m/list?key="+key;
	})
	
	
	$('body').on('touchstart','.course-item-box .item-intro',function(){
		if($(this).find(".video-play").length < 1){
			$('.course-item-box .item-intro .video-play').remove();
		}
		$(".course-item-box .item-intro .videopv").hide();
		$('.course-item-box .item-intro .progress').hide();
		var videopreview = $(this).data('preview');
		var videoHtml = "<video id='my-video' muted class='video-play' autoplay loop preload='auto' playsinline x5-playsinline='true' webkit-playsinline='true'>"
			+	"<source src='"+videopreview+"' type='video/mp4' />"
			+"</video>"
			if($(this).find(".videopv").length < 1){
				var html = "<div class='videopv'>"+videoHtml+"<div class='progress'><div class='bar' style='width:0%'></div></div></div>"
				$(this).prepend(html)
			}else{
				$(this).find(".videopv").show();
				// $(this).find('.progress').show();
				if($(this).find(".video-play").length < 1){
					$(this).find(".videopv").prepend(videoHtml)
				}
			}
		var _this = $(this)
		var player = document.querySelectorAll('video')[0];
			player.addEventListener('waiting',function(){
			})
			player.addEventListener('loadeddata',function(){
				$('.course-item-box .item-intro .video-play').css({'background-color':'#000'})
			})
			player.addEventListener('playing',function(){
				_this.find('.progress').hide();
			})
			player.addEventListener('ended',function(){
			})
	}).on('click','.course-item-box .item-intro',function(){
		var eid = $(this).data('eid'),
			_token = "<?php echo e(csrf_token()); ?>",
			isfree = ($(this).find('font').length > 0) ? 1 : 0,
			ispaid = <?php echo e(meb_ispaid()); ?>,
			need = $(this).find('.coursr-rate').data('value'),
			meb_level = <?php echo e($level); ?>;
		
		if(isfree==0 || isfree==1 || meb_level>0){
			/*if( meb_level<need && isfree ==0 && ispaid ==0 ){
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
						 window.open('../m/category');
					}
					,btn2: function(){
						location.href = "../m/ucenter";
					}
				});
			}else{*/
				location.href="../m/play?eid="+eid;
			//}
		}else{
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
					location.href = "../m/signin";
				}
				,btn2: function(){
					location.href = '../m/freecourse';
				}
			});
		}
	})
})
</script>