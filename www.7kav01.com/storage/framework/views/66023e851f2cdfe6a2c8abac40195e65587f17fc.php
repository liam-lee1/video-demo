

<?php $__env->startSection('title', ''); ?>

<?php $__env->startSection('header'); ?>
	<?php echo $__env->make('moudel.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div id="kernel" class="bg-w">
	<div class="course-box">
		<h2 class="title">
			<?php if(!empty($_GET['key'])): ?>搜索<?php elseif(!empty($_GET['type'])): ?><?php echo e(Cachekey('type')[$_GET['type']]); ?><?php elseif(!empty($_GET['tag'])): ?>标签<?php else: ?>视频<?php endif; ?>列表>>
		</h2>
		<div class="layui-row course-item pd-2">
			
		</div>
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
	
	function getCourse(curr,type,tag,key){
		$.get("<?php echo e(url('course/getCourseList')); ?>",{
			page: curr || 1,type:type,tag:tag,key:key
		},function(res){
			var string = "";
			if(res.count==0){
				var string = '<div class="not-conts">\
								<span>暂无相关视频</span>\
							</div>';
				$(".course-item").html(string);
				$("#page").empty();
			}else{
				$.each(res.data, function(i,item){
					var isfree = '';
					if(item.isfree==1){
						isfree = '<!--<font>试看</font>-->';
					}
					string += '<div class="layui-col-xs4 course-item-box">\
								<a class="item-intro" href="javascript:;" data-preview="'+item.videopreview+'" data-eid="'+item.eid+'">\
									<image src="../images/course/'+item.image+'" title="'+item.name+'" >\
									<span>'+item.time+'</span>'+isfree+'\
									<!--<div class="coursr-rate" data-value="'+item.level+'"></div>-->\
									<p class="text-omit" title="'+item.name+'">'+item.name+'</p>\
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
						  getCourse(obj.curr,type,tag,key);
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
	
	$(function(){
		var type = "<?php echo e(isset($_GET['type']) ? $_GET['type'] : ''); ?>",
			tag  = "<?php echo e(isset($_GET['tag']) ? $_GET['tag'] : ''); ?>",
			key  = "<?php echo e(isset($_GET['key']) ? $_GET['key'] : ''); ?>",
			tags = '<?php echo $tag; ?>',
			str = '';
			
		$.each(JSON.parse(tags),function(k,v){
			str += '<span data-value="'+k+'" >'+v+'</span>';
		})
		
		$('body').on('click','.tags-box span',function(){
			var sel = [];
			if($(this).hasClass('clear_all')){
				console.log(1);
				$(this).parent().find('span').not('.clear_all').remove();
				$(this).before(str);
			}else{
				$(this).toggleClass('selected');
				if($(this).hasClass('selected')){
					var fir = $('.tags-box span:eq(0)');
					$(this).insertBefore(fir);
				}else{
					var last = $('.tags-box span').last();
					$(this).insertBefore(last);
				}
				
			}
			$.each($('.tags-box span.selected'),function(){
				sel.push($(this).data('value'));
			})
			type = '',key = '';
			$('.menu-item ul li').removeClass('active');
			$('input[type="search"]').val('');
			tag = sel.length==0 ? '' : sel.join(',');
			$('.title').html('标签列表');
			getCourse(1,type,tag,key);
		})
		
		if(tag==''){
			getCourse(1,type,tag,key);
		}else{
			$('.tags-box span[data-value="'+tag+'"]').click();
		}
	})
})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mb', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>