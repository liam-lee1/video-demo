<style>
	.badge{
		width:20px;
		height:20px;
		border-radius:50%;
		position: absolute;
		background: #ff0606;
		font-size: 12px;
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		right: -8px;
		top: -4px;
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
	.ad-item img {
		width:100%;
	}
</style>
<header class="bg-w">
	<div class="header-c flex-box">
		<a href="../index" class="nav-l">
			<img src="../images/logo.jpg">
		</a>
		<div class="nav-c">
			<div class="search-box">
				<i class="layui-icon layui-icon-search search-btn"></i>
				<input type="search" value="<?php if(isset($_GET['key'])): ?> <?php echo e($_GET['key']); ?> <?php endif; ?>" class="search-input" placeholder="输入你要查找的内容" >
			</div>
		</div>
		<div class="nav-r">
			<div class="user-box flex-box">
				<?php if(!empty($user)): ?>
					<div class="grid-row r-grid flex-box">
						<a class="grid-row-item" href="../ucenter">
							<i class="layui-icon layui-icon-user"></i>
							<span><?php echo e($user['user']); ?></span>
						</a>
						<a class="grid-row-item" href="../logout">
							<i class="layui-icon layui-icon-password"></i>
							<span>退出</span>
						</a>
						<a class="grid-row-item f-specil" href="../ucenter">
							<i class="layui-icon layui-icon-release"></i>
							<span>个人中心</span>
						</a>
					</div>
					<div class="grid-row r-grid flex-box">
						<div class="grid-row-item f-specil">
							<p class="text-omit">会员等级：</p>
							<div class="meb_level narrow" data-value="<?php echo e($user['level']); ?>"></div>
						</div>
						
						<div class="grid-row-item operate-item">
							<a class="insign" href="javascript:;">
								<i class="layui-icon layui-icon-read"></i>
								<span>签到</span>
							</a>
							<a href="../notice" style="position: relative;">
								<i class="layui-icon layui-icon-notice"></i>
								<span>公告</span>
								<?php if($toRead > 0): ?>
									<span class="badge"><?php echo e($toRead); ?></span>
								<?php endif; ?>
							</a>
						</div>
					</div>
				<?php else: ?>
					<div class="grid-row r-grid flex-box">
						<a class="grid-row-item sign" href="javascript:;" data-value="1">
							<i class="layui-icon layui-icon-user"></i>
							<span>登录</span>
						</a>
						<a class="grid-row-item sign" href="javascript:;" data-value="2">
							<i class="layui-icon layui-icon-password"></i>
							<span>注册</span>
						</a>
						<a class="grid-row-item f-specil" href="../ucenter">
							<i class="layui-icon layui-icon-release"></i>
							<span>免费领VIP</span>
						</a>
						<a class="grid-row-item" href="../notice">
							<i class="layui-icon layui-icon-notice"></i>
							<span>公告</span>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</header>
<div class="menu-item bg-w">
	<ul>
		<li <?php if($as == 'pc_index'): ?> class="active" <?php endif; ?> >
			<a href="../index">首页</a>
		</li>
		<?php if(!empty($type)): ?>
			<?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<li <?php if($as == 'pc_list' && isset($_GET['type'])&&$_GET['type']==$k ): ?> class="active" <?php endif; ?> >
					<a href="../list?type=<?php echo e($k); ?>"><?php echo e($v); ?></a>
				</li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<?php endif; ?>
	</ul>
</div>
<?php if(!empty($tag)): ?>
	<div class="tag-item bg-w box-min">
		<div class="J_FilterMore">
			<font class="open">展开</font>
			<font class="close">收起</font>
			<i class="layui-icon layui-icon-down"></i>
		</div>
		<?php $__currentLoopData = $tag; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<?php if($as == 'pc_list' && !isset($_GET['type']) && !isset($_GET['key'])): ?>
				<span data-value="<?php echo e($k); ?>" ><?php echo e($v); ?></span>
			<?php else: ?>
				<a href="../list?tag=<?php echo e($k); ?>"><?php echo e($v); ?></a>
			<?php endif; ?>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<?php if($as == 'pc_list'  && !isset($_GET['type']) && !isset($_GET['key'])): ?>
			<span class="clear_all" data-value="-1">全部清除</span>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if(!empty($ad)): ?>
	<div class="ad-item bg-w">
		<?php $__currentLoopData = $ad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<a href="<?php echo e($v['url']); ?>" target="_blank"><image src="../images/ad/<?php echo e($v['image']); ?>"></a>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
<?php endif; ?>	

<script>
layui.use(['rate','element','layer'], function(){
	var rate = layui.rate
		,layer = layui.layer
		,element = layui.element;
	rate.render({
		elem: '.meb_level'
		,length:3
		,value: $('.meb_level').data('value')
		,readonly: true
		,theme:'#101010'
	});
	
	$('.J_FilterMore').click(function(){
		$(this).parent().toggleClass('tags-more box-min');
	})
	
	$('.search-btn').click(function(){
		key = $('input[type="search"]').val();
		if(key==''){
			layer.msg('请输入搜索词',{icon: 2,time: 2000,anim: 6,shade:0.3});return;
		}
		location.href="../list?key="+key;
	})
	
	$('input[type="search"]').on('keypress', function (event) { 
	   if (event.keyCode == "13") { 
		$(".search-btn").click();
	   }
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
					$('.sign').click();
				}, 1500);
			}
			if(res.status==2){
				layer.msg(res.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});return;
			}
			if(res.status==1){
				layer.msg(res.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
				setTimeout(function(){
					window.location.reload();
				},1500)
			}
		})
	})
	
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
	
	$('.sign').click(function(){
		var type = $(this).data('value');
		layer.open({
		  type: 2,
		  title:false,
		  area: ['1240px', '580px'],
		  fixed: false, //不固定
		  content: '../sign?type='+type,
		});
		$(this).blur();
	})
	
	
	$('body').on('click','.course-item-box .item-intro',function(){
		var eid = $(this).data('eid'),
			_token = "<?php echo e(csrf_token()); ?>",
			isfree = ($(this).find('font').length > 0) ? 1 : 0,
			ispaid = <?php echo e(meb_ispaid()); ?>,
			need = $(this).find('.coursr-rate').data('value'),
			meb_level = <?php if(!empty(session('openid'))): ?><?php echo e($user['level']); ?><?php else: ?> 0 <?php endif; ?>;
		
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
						location.href = "../category";
					}
					,btn2: function(){
						location.href = "../ucenter";
					}
				});
			}else{*/
				location.href="../play?eid="+eid;
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
		}
	}).on('mouseenter','.course-item-box .item-intro',function(){
		var videopreview = $(this).data('preview');
			  var videoHtml = "<video id='my-video' muted class='video-play' autoplay loop x5-video-player-type='h5' x5-video-player-fullscreen='true' x-webkit-airplay='allow' x5-playsinlineplaysinline webkit-playsinline>"
					+	"<source src='"+videopreview+"' type='video/mp4' />"
					+"</video>"
			if($(this).find(".videopv").length < 1){
				var html = "<div class='videopv'>"+videoHtml+"<div class='progress'><div class='bar' style='width:0%'></div></div></div>"
				$(this).prepend(html)
			}else{
				$(this).find(".videopv").show();
			  $(this).find('.progress').show();
				$(this).find(".videopv").prepend(videoHtml)
			}
		  var _this = $(this)
			var player = document.querySelectorAll('video')[0];
				player.addEventListener('waiting',function(){
					
				})
				player.addEventListener('loadeddata',function(){
					$('.course-item .item-intro .video-play').css({'background-color':'#000'})
				})
				player.addEventListener('playing',function(){
					_this.find('.progress').hide();
				})
				player.addEventListener('ended',function(){
					
				})
	}).on('mouseleave','.course-item-box .item-intro',function(){
		$(this).find('.video-play').remove()
		$(this).find(".videopv").hide();
		$(this).find('.progress').hide();
	});
})
</script>