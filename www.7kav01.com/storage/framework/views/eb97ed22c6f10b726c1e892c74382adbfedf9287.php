<script>
location.href="/m/free";
</script>


<?php $__env->startSection('title', '视频列表'); ?>

<?php $__env->startSection('header'); ?>
	<?php echo $__env->make('moudel.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="unlogin pd-2">
	<p>您还未登录，只能观看试看片</p>
	<p>如需观看更多，请先登录</p>
</div>


<div class="course-box">
	<h2 class="title">试看片>></h2>
	<div class="layui-row course-item pd-2">
		
	</div>
</div>
<div id="page" class="t-center"></div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_ad'); ?>
   <?php echo $__env->make('moudel.footer_ad', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
   <?php echo $__env->make('moudel.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
layui.use(['element','laypage','rate','layer'], function(){
	var laypage = layui.laypage,
		rate = layui.rate,
		element = layui.element;
	
	function getCourse(curr){
		$.get("<?php echo e(url('course/getCourseList')); ?>",{
			page: curr || 1,isfree:1,
		},function(res){
			var string = "";
			if(res.count==0){
				var string = '<div class="not-conts">\
								<span>暂无免费视频</span>\
							</div>';
				$(".course-item").html(string);
				$("#page").empty();
			}else{
				$.each(res.data, function(i,item){
					var isfree = '';
					if(item.isfree==1){
						isfree = '<font>试看</font>';
					}
					string += '<div class="layui-col-xs4 course-item-box">\
								<a class="item-intro" href="javascript:;" data-preview="'+item.videopreview+'" data-eid="'+item.eid+'">\
									<image src="../images/course/'+item.image+'">\
									<span>'+item.time+'</span>'+isfree+'\
									<div class="coursr-rate" data-value="'+item.level+'"></div>\
									<p class="text-omit">'+item.name+'</p>\
								</a>\
							</div>';
					
				});
				$(".course-item").html(string);
				//显示分页
				laypage.render({
					elem: 'page', 
					limit: 16,
					count: res.count,
					curr: curr || 1,
					theme: '#ec145e',
					groups:5,
					prev:'上一页',
					next:'下一页',
					jump: function(obj, first){
						if(!first){
						  getCourse(obj.curr);
						}
					}
				});
				$('.coursr-rate').each(function(){
					var val = $(this).data('value');
					if(val!== 'undefined'){
						rate.render({
							elem: $(this)
							,value: val
							,length:3
							,readonly: true
							,theme:'#ff055a'
						});
					}
				})
			}
			$("img").one("error", function(e){
				$(this).attr("src", "../images/default.jpg");
			});
		})
	}
	
	getCourse();
})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mb', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>