@extends('layouts.pc')

@section('header')
	@include('moudel.pc_header')
@endsection

@section('content')
<div class="course-box bg-w">
	<fieldset class="layui-elem-field layui-field-title">
		<legend>@if(!empty($_GET['key']))搜索@elseif(!empty($_GET['type'])){{Cachekey('type')[$_GET['type']]}}@elseif(!empty($_GET['tag']))标签@else视频@endif列表>></legend>
	</fieldset>
	<div class="layui-row course-item pd-2">
			
	</div>
</div>
<div id="page" class="t-center"></div>
@endsection

@section('footer')
	@include('moudel.pc_footer')
@endsection

@section('js')
<script>
layui.use(['element','laypage','rate','layer'], function(){
	var laypage = layui.laypage,
		rate = layui.rate,
		element = layui.element;
	
	function getCourse(curr,type,tag,key){
		$.get("{{url('course/getCourseList')}}",{
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
									<image src="../images/course/'+item.image+'" title="'+item.name+'">\
									<span>'+item.time+'</span>'+isfree+'\
									<!--<div class="coursr-rate" data-value="'+item.level+'"></div>-->\
									<p class="text-omit" title="'+item.name+'" >'+item.name+'</p>\
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
		var type = "{{isset($_GET['type']) ? $_GET['type'] : ''}}",
			tag  = "{{isset($_GET['tag']) ? $_GET['tag'] : ''}}",
			key  = "{{isset($_GET['key']) ? $_GET['key'] : ''}}",
			tags = '{!!$tag!!}',
			str = '';
		
		$.each(JSON.parse(tags),function(k,v){
			str += '<span data-value="'+k+'" >'+v+'</span>';
		})
		
		$('body').on('click','.tag-item span',function(){
			var sel = [];
			if($(this).hasClass('clear_all')){
				$(this).parent().find('span').not('.clear_all').remove();
				$(this).before(str);
			}else{
				$(this).toggleClass('selected');
				if($(this).hasClass('selected')){
					var fir = $('.tag-item span:eq(0)');
					$(this).insertBefore(fir);
				}else{
					var last = $('.tag-item span').last();
					$(this).insertBefore(last);
				}
				
			}
			$.each($('.tag-item span.selected'),function(){
				sel.push($(this).data('value'));
			})
			type = '',key = '';
			$('.menu-item ul li').removeClass('active');
			$('input[type="search"]').val('');
			tag = sel.length==0 ? '' : sel.join(',');
			$('legend').html('标签列表');
			getCourse(1,type,tag,key);
		})
		
		if(tag==''){
			getCourse(1,type,tag,key);
		}else{
			$('.tag-item span[data-value="'+tag+'"]').click();
		}
	})
})
</script>
@endsection