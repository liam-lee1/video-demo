@extends('layouts.mb')

@section('title', '公告')

@section('header')
	@include('moudel.header')
@endsection

@section('content')
<div class="notice-box bg-w">
	<h2 class="title">公告</h2>
	<div class="notice-c pd-2">
		
	</div>
</div>
@endsection

@section('footer_ad')
   @include('moudel.footer_ad')
@endsection

@section('footer')
   @include('moudel.footer')
@endsection

@section('js')
<script>
layui.use(['element','layer','flow'], function(){
	var element = layui.element
		,layer = layui.layer
		,flow = layui.flow;
		
	flow.load({
		elem: '.notice-c'
		,isAuto: true
		,done: function(page, next){
			var lis = [];
			$.get('../get_notice_list',{page:page},function(list){
				if(list.data.length == 0){
					var empty_html = '<div class="not-conts">\
										<span>暂无公告信息</span>\
									</div>';
					$('.notice-c').html(empty_html);return;
				}
				layui.each(list.data, function(index, item){
					lis.push('<a class="flex-box" href="../m/notice_xg?nid='+item.id+'">\
								<p>'+item.title+'</p>\
								<span>'+item.time+'</span>\
							</a>');
				})
				next(lis.join(''), page < list.pages);
			})
		}
	})
})
</script>
@endsection