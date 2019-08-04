@extends('layouts.pc')

@section('title', '公告')

@section('header')
	@include('moudel.pc_header')
@endsection

@section('content')
<div class="show-box bg-w">
	<h2 class="title">公告</h2>
	<div class="notice-c pd-2">
		
	</div>
</div>
<div id="page" class="t-center"></div>
@endsection

@section('footer')
   @include('moudel.pc_footer')
@endsection

@section('js')
<script>
layui.use(['element','layer','laypage'], function(){
	var element = layui.element
		,layer = layui.layer
		,laypage = layui.laypage;
	
	function getNotice(curr){
		$.get("{{url('get_notice_list')}}",{
			page: curr || 1
		},function(res){
			var string = "";
			if(res.count==0){
				var string = '<div class="not-conts">\
								<span>暂无公告信息</span>\
							</div>';
				$(".notice-c").html(string);
				$("#page").empty();
			}else{
				$.each(res.data, function(i,item){
					string += '<a class="flex-box" href="../notice_xg?nid='+item.id+'">\
								<p>'+item.title+'</p>\
								<span>'+item.time+'</span>\
							</a>';
					
				});
				$(".notice-c").html(string);
				//显示分页
				laypage.render({
					elem: 'page', 
					limit: 10,
					count: res.count,
					curr: curr || 1,
					theme: '#ec145e',
					groups:5,
					prev:'上一页',
					next:'下一页',
					jump: function(obj, first){
						if(!first){
						  getNotice(obj.curr);
						}
					}
				});
			}
		})
	}
	getNotice();
})
</script>
@endsection