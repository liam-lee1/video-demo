<!doctype html>
<html>
<head>
    <title>广告列表</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="{{ asset('layui/css/layui.css') }}">
	<script type="text/javascript" src="{{ asset('layui/layui.js') }}"></script>
</head>
<style>
	.layui-tab-content,.layui-table td, .layui-table th{text-align:center;}
	.layui-table{margin:10px auto;}
</style>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>广告列表</legend>
</fieldset>
<div style="margin:15px;">
<button type="button" class="layui-btn layui-btn-normal add_new">增加广告</button>
<div class="layui-tab">
  <ul class="layui-tab-title">
    <li class="layui-this">PC顶部通栏</li>
    <li>PC底部通栏</li>
    <li>PC内容页通栏</li>
	<li>PC内容页350*450广告</li>
    <li>PC播放页通栏</li>
	<li>WAP顶部通栏</li>
	<li>WAP底部通栏</li>
	<li>WAP内容页通栏</li>
    <li>WAP播放页通栏</li>
  </ul>
  <div class="layui-tab-content">
    <div class="layui-tab-item layui-show">
		<div class="layui-form">
			<input type="hidden" name="type" value="pc_header" >
			<table class="layui-table" style="width:1460px">
				<colgroup>
				  <col width="250">
				  <col width="250">
				  <col width="100">
				  <col width="100">
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>内容预览</th>
					<th>跳转链接</th>
					<th>排序</th>
					<th>操作</th>
				  </tr> 
				</thead>
				<tbody>
					@if(!empty(json_decode($pc_header,true)))
					@foreach(json_decode($pc_header,true) as $v)
						<tr>
							<td>
								<div class="layui-upload">
								  <div class="layui-upload-list">
									<img class="layui-upload-img direct" src="../images/ad/{{$v['image']}}">
									<input type="hidden" name="image[]" value="{{$v['image']}}">
								  </div>
								</div>
							</td>
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><input type="text" name="sort[]" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" lay-verify="required" value="{{$v['sort']}}" autocomplete="off"></td>
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
    </div>
    <div class="layui-tab-item">
		<div class="layui-form">
			<input type="hidden" name="type" value="pc_footer" >
			<table class="layui-table" style="width:1460px">
				<colgroup>
				  <col width="250">
				  <col width="250">
				  <col width="100">
				  <col width="100">
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>内容预览</th>
					<th>跳转链接</th>
					<th>排序</th>
					<th>操作</th>
				  </tr> 
				</thead>
				<tbody>
					@if(!empty(json_decode($pc_footer,true)))
					@foreach(json_decode($pc_footer,true) as $v)
						<tr>
							<td>
								<div class="layui-upload">
								  <div class="layui-upload-list">
									<img class="layui-upload-img direct" src="../images/ad/{{$v['image']}}">
									<input type="hidden" name="image[]" value="{{$v['image']}}">
								  </div>
								</div>
							</td>
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><input type="text" name="sort[]" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" lay-verify="required" value="{{$v['sort']}}" autocomplete="off"></td>
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
	</div>
    <div class="layui-tab-item">
		<div class="layui-form">
			<input type="hidden" name="type" value="pc_content" >
			<table class="layui-table" style="width:1460px">
				<colgroup>
				  <col width="250">
				  <col width="250">
				  <col width="100">
				  <col width="100">
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>内容预览</th>
					<th>跳转链接</th>
					<th>排序</th>
					<th>操作</th>
				  </tr> 
				</thead>
				<tbody>
					@if(!empty(json_decode($pc_content,true)))
					@foreach(json_decode($pc_content,true) as $v)
						<tr>
							<td>
								<div class="layui-upload">
								  <div class="layui-upload-list">
									<img class="layui-upload-img direct" src="../images/ad/{{$v['image']}}">
									<input type="hidden" name="image[]" value="{{$v['image']}}">
								  </div>
								</div>
							</td>
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><input type="text" name="sort[]" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" lay-verify="required" value="{{$v['sort']}}" autocomplete="off"></td>
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
	</div>
	<div class="layui-tab-item">
		<div class="layui-form">
			<input type="hidden" name="type" value="pc_content_w" >
			<table class="layui-table" style="width:1460px">
				<colgroup>
				  <col width="250">
				  <col width="250">
				  <col width="100">
				  <col width="100">
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>内容预览</th>
					<th>跳转链接</th>
					<th>排序</th>
					<th>操作</th>
				  </tr> 
				</thead>
				<tbody>
					@if(!empty($pc_content_w))
					@foreach(json_decode($pc_content_w,true) as $v)
						<tr>
							<td>
								<div class="layui-upload">
								  <div class="layui-upload-list">
									<img class="layui-upload-img direct" src="../images/ad/{{$v['image']}}">
									<input type="hidden" name="image[]" value="{{$v['image']}}">
								  </div>
								</div>
							</td>
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><input type="text" name="sort[]" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" lay-verify="required" value="{{$v['sort']}}" autocomplete="off"></td>
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
    </div>
    <div class="layui-tab-item">
		<div class="layui-form">
			<input type="hidden" name="type" value="pc_play" >
			<table class="layui-table" style="width:1460px">
				<colgroup>
				  <col width="250">
				  <col width="250">
				  <col width="100">
				  <col width="100">
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>内容预览</th>
					<th>跳转链接</th>
					<th>排序</th>
					<th>操作</th>
				  </tr> 
				</thead>
				<tbody>
					@if(!empty(json_decode($pc_play,true)))
					@foreach(json_decode($pc_play,true) as $v)
						<tr>
							<td>
								<div class="layui-upload">
								  <div class="layui-upload-list">
									<img class="layui-upload-img direct" src="../images/ad/{{$v['image']}}">
									<input type="hidden" name="image[]" value="{{$v['image']}}">
								  </div>
								</div>
							</td>
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><input type="text" name="sort[]" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
	</div>
    <div class="layui-tab-item">
		<div class="layui-form">
			<input type="hidden" name="type" value="wap_header" >
			<table class="layui-table" style="width:1460px">
				<colgroup>
				  <col width="250">
				  <col width="250">
				  <col width="100">
				  <col width="100">
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>内容预览</th>
					<th>跳转链接</th>
					<th>排序</th>
					<th>操作</th>
				  </tr> 
				</thead>
				<tbody>
					@if(!empty(json_decode($wap_header,true)))
					@foreach(json_decode($wap_header,true) as $v)
						<tr>
							<td>
								<div class="layui-upload">
								  <div class="layui-upload-list">
									<img class="layui-upload-img direct" src="../images/ad/{{$v['image']}}">
									<input type="hidden" name="image[]" value="{{$v['image']}}">
								  </div>
								</div>
							</td>
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><input type="text" name="sort[]" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" lay-verify="required" value="{{$v['sort']}}" autocomplete="off"></td>
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
	</div>
	<div class="layui-tab-item">
		<div class="layui-form">
			<input type="hidden" name="type" value="wap_footer" >
			<table class="layui-table" style="width:1460px">
				<colgroup>
				  <col width="250">
				  <col width="250">
				  <col width="100">
				  <col width="100">
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>内容预览</th>
					<th>跳转链接</th>
					<th>排序</th>
					<th>操作</th>
				  </tr> 
				</thead>
				<tbody>
					@if(!empty(json_decode($wap_footer,true)))
					@foreach(json_decode($wap_footer,true) as $v)
						<tr>
							<td>
								<div class="layui-upload">
								  <div class="layui-upload-list">
									<img class="layui-upload-img direct" src="../images/ad/{{$v['image']}}">
									<input type="hidden" name="image[]" value="{{$v['image']}}">
								  </div>
								</div>
							</td>
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><input type="text" name="sort[]" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" lay-verify="required" value="{{$v['sort']}}" autocomplete="off"></td>
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
	</div>
	<div class="layui-tab-item">
		<div class="layui-form">
			<input type="hidden" name="type" value="wap_content" >
			<table class="layui-table" style="width:1460px">
				<colgroup>
				  <col width="250">
				  <col width="250">
				  <col width="100">
				  <col width="100">
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>内容预览</th>
					<th>跳转链接</th>
					<th>排序</th>
					<th>操作</th>
				  </tr> 
				</thead>
				<tbody>
					@if(!empty(json_decode($wap_content,true)))
					@foreach(json_decode($wap_content,true) as $v)
						<tr>
							<td>
								<div class="layui-upload">
								  <div class="layui-upload-list">
									<img class="layui-upload-img direct" src="../images/ad/{{$v['image']}}">
									<input type="hidden" name="image[]" value="{{$v['image']}}">
								  </div>
								</div>
							</td>
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><input type="text" name="sort[]" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" lay-verify="required" value="{{$v['sort']}}" autocomplete="off"></td>
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
	</div>
	<div class="layui-tab-item">
		<div class="layui-form">
			<input type="hidden" name="type" value="wap_play" >
			<table class="layui-table" style="width:1460px">
				<colgroup>
				  <col width="250">
				  <col width="250">
				  <col width="100">
				  <col width="100">
				  <col>
				</colgroup>
				<thead>
				  <tr>
					<th>内容预览</th>
					<th>跳转链接</th>
					<th>排序</th>
					<th>操作</th>
				  </tr> 
				</thead>
				<tbody>
					@if(!empty(json_decode($wap_play,true)))
					@foreach(json_decode($wap_play,true) as $v)
						<tr>
							<td>
								<div class="layui-upload">
								  <div class="layui-upload-list">
									<img class="layui-upload-img direct" src="../images/ad/{{$v['image']}}">
									<input type="hidden" name="image[]" value="{{$v['image']}}">
								  </div>
								</div>
							</td>
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="{{$v['url']}}" autocomplete="off"></td>
							<td><input type="text" name="sort[]" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="layui-input" lay-verify="required" value="{{$v['sort']}}" autocomplete="off"></td>
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>
						</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
	</div>
  </div>
</div>
</div>
<script type="text/javascript">
layui.use(['jquery','layer','element','upload','form'], function(){
	var $ = layui.jquery
		,upload = layui.upload
		,element = layui.element
		,form = layui.form
		,layer = layui.layer;
		
	function imguload(elem){
		var uploadInst = upload.render({
			elem: elem
			,accept: 'image'
			,auto: false
			,number:1
			,choose: function(obj){
				var item = this.item;
				obj.preview(function(index, file, result){
					item.parent().find('input[name="image[]"]').val(result);
					item.attr('src',result);
			  });
			}
		});
	}
	
	form.on('submit(edit)', function(data){
		data.field._token = "{{ csrf_token() }}";
		layer.confirm('确认保存数据么？',{icon: 3,title:'提示'}, function(index){
			$.ajax({
				type:"post",
				dataType:'json',
				url:"{{url('info/save_ad')}}",
				data:data.field,
				beforeSend:function(){
					layer.load(0,{shade:0.3});
				},
				success: function(d,s){
					if(d.status==2){
						layer.msg(d.msg,{icon: 2,time: 2000,anim: 6,shade:0.3});					
					}
					if(d.status==1){
						layer.msg(d.msg,{icon: 1,time: 1500,anim: 0,shade:0.3});
						setTimeout(function(){
							window.location.reload()
						}, 1500);
					}
				},
				
				error: function(d,s){
					layer.alert("未知错误");
				},
				
				complete:function(){
					layer.closeAll('loading');
				},
			})
			return false;
		})
	})
	
	$(function(){
		imguload('.direct');
		$('.add_new').click(function(){
			var index = $('tr').length;
			var elem = 'direct-'+index
			var html = '<tr>\
							<td>\
								<div class="layui-upload">\
								  <div class="layui-upload-list">\
									<img class="layui-upload-img '+elem+'" src="../images/default.jpg">\
									<input type="hidden" name="image[]" value="">\
								  </div></div>\
							</td>\
							<td><input type="text" name="url[]" class="layui-input" lay-verify="required" value="" autocomplete="off"></td>\
							<td><input type="text" name="sort[]" class="layui-input" lay-verify="required" value="10" autocomplete="off"></td>\
							<td><button class="layui-btn layui-btn-sm layui-btn-danger del">删除</button></td>\
						</tr>';
			$('.layui-tab-item.layui-show').find('tbody').append(html);
			imguload('.'+elem);
		})
		
		$('body').on('click','.del',function(){
			$(this).parents('tr').remove();
		})
	})
})
</script>
</body>
</html>