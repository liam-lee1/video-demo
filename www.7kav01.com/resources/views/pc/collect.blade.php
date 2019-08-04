@extends('layouts.pc')

@section('title', '收藏列表')

@section('header')
	@include('moudel.pc_header')
@endsection

@section('content')
<div id="kernel" class="bg-w">
	<div class="course-box">
		<h2 class="title">收藏列表>></h2>
		<div class="layui-row course-item pd-2">
			
		</div>
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
	
	function getCollect(curr){
		$.get("{{url('course/getCollectList')}}",{
			page: curr || 1
		},function(res){
			var string = "";
			if(res.count==0){
				var string = '<div class="not-conts">\
								<span>暂无收藏视频</span>\
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
								<a class="item-intro" href="javascript:;" data-preview="'+item.videopreview+'" data-eid="'+item.eid+'" >\
									<image src="../images/course/'+item.image+'">\
									<span>'+item.time+'</span>'+isfree+'\
									<!--<div class="coursr-rate" data-value="'+item.level+'"></div>-->\
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
	getCollect();
})
</script>
@endsection