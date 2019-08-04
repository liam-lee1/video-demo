<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>视频列表</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
  <link href="{{ asset('Fliter/Fliter.css') }}" rel="stylesheet" />
	<script src="{{ asset('Fliter/Filter.js') }}"></script>
</head>
<style>
	.layui-table-fixed{display:none;}
</style>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
	<legend>视频列表</legend>
</fieldset>
<div style="margin:15px;">
	<div class="search">
		<button type="button" class="layui-btn layui-btn-normal add_new">添加视频</button>
		<div class="screen layui-form">
			<input type="hidden" name="tag" value="">
			<div class="layui-input-inline">
				<select name="type"  lay-search="">
				  <option value="">全部分类</option>
				  @foreach($type as $k=>$v)
					<option value="{{$k}}">{{$v}}</option>
				  @endforeach
				</select>
			</div>
			<div class="layui-input-inline">
				<select name="level"  lay-search="">
				  <option value="">观看等级</option>
				  <option value="1">★☆☆</option>
				  <option value="2">★★☆</option>
				  <option value="3">★★★</option>
				</select>
			</div>
			<div class="layui-input-inline" style="width:350px;">
				<input type="text" name="name" value="" autocomplete="off" placeholder="名称查找" class="layui-input">
			</div>
			<button class="layui-btn retrieval">搜索</button>
		</div>
		
		<div class="tag-item"></div>
		
	</div>
	<table id="video_list" lay-filter="video_list"></table>
</div>

<script type="text/html" id="nameTpl">
	<p>@{{ d.name }}<i class="video_image layui-icon layui-icon-picture" data-value="@{{ d.image }}"></i></p>
</script>
<script type="text/html" id="levelTpl">
	<div class="meb_level" data-value="@{{ d.level }}"></div>
</script>
<script type="text/html" id="tagTpl">
	@{{#  layui.each(d.tag, function(k, v){ }}
		<span class="list-tag">@{{ v }}</span>
	@{{#  }); }}
</script>
<script type="text/html" id="toolbar">
	<a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit" >编辑</a>
	@if(json_decode(Cookie::get('admin_auth'),true)['admin_lev'] == 1)
		<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del" >删除</a>
	@endif
	@{{#  if(d.hide == 0){ }}
		<a class="layui-btn layui-btn-sm" lay-event="hide" >隐藏</a>
	@{{#  } else { }}
		<a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="show" >显示</a>
	@{{#  } }}
</script>  
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
layui.use(['table','layer','form','rate'], function(){
	var table = layui.table,
	  layer = layui.layer,
	  form = layui.form,
	  rate = layui.rate;
	
	function meb_level(){
		$('.meb_level').each(function(){
			var val = $(this).data('value');
			rate.render({
				elem: $(this)
				,value: val
				,length:3
				,readonly: true
				,theme:'#111'
			});
		})
	}
	
	function video_image(){
		$('.video_image').mouseover(function(){
			var img = $(this).data('value');
			layer.tips("<img alt='' src='../images/course/"+img+"' style='width:150px;'>",$(this),{
			  tips: [1,'rgba(0, 0, 0)'],
			  time: 0
			})
		}).mouseout(function(){
			layer.closeAll('tips')
		})
	}
	
	var tag = @if(isset($_GET['tag'])){{$_GET['tag']}}@else''@endif,
		type = @if(isset($_GET['type'])){{$_GET['type']}}@else''@endif;
	
	
	var tableIns = table.render({
		  elem: '#video_list'
		  ,cellMinWidth: 180
		  ,height:'full-225'
		  ,where:{tag:tag,type:type}
		  ,cols: [[ //标题栏
					{field: 'sort', title: '序号', width: 80,sort:true}
					,{field: 'type', title: '视频分类', width: 200}
					,{field: 'name', title: '名称',templet: '#nameTpl'}
					,{field: 'level', title: '观看等级', width: 180,templet: '#levelTpl'}
					,{field: 'tag', title: '选中标签',templet: '#tagTpl'}
					,{field: 'view', title: '播放量',sort:true,width: 120}
					,{field: 'time', title: '添加时间', width: 150,sort:true}
					,{field: 'admin', title: '上传人员', width: 150}
					,{fixed: 'right',title: '操作', width: 250,align:'center', toolbar: '#toolbar'}
				  ]] //设置表头
		  ,url: "{{url('video/get_video_list')}}" //设置异步接口
		  ,page: true
		  ,limit: 30
		  ,id:'video_list',
		  done: function(res, curr, count){
			  meb_level();
			  video_image();
		  }
		});
	
	table.on('sort(video_list)', function(obj){
		meb_level();
		video_image();
	})
	
	$(".search .retrieval").click(function(){
		var name = $('input[name="name"]').val(),
			tag = $('input[name="tag"]').val(),
			type = $('select[name="type"] option:selected').val(),
			level = $('select[name="level"] option:selected').val();
		table.reload('video_list',{where:{name:name,tag:tag,type:type,level:level},page: {curr: 1 }});
	});
	
	
	var data = @if(!empty($tag)){!!$tag!!}@else[]@endif;
	$('.tag-item').comboboxfilter({
		url: '',
		scope: 'tags-item',
		multiple: true,
		unlimit:false,
		text:'标签',
		data:data,
		onChange:function(tag){
			$('input[name="tag"]').val(tag);
		}
	});
	
	var _token = '{{ csrf_token() }}';
	//监听单元格事件
	table.on('tool(video_list)', function(obj){
		var data = obj.data,
			layEvent = obj.event;
		
		if(layEvent === 'edit'){
			layer.open({
				  type: 2,
				  title:['视频编辑','font-size: 22px;font-weight: 800;'],
				  shadeClose: true,
				  skin: 'layui-layer-rim',
				  content: "{{url('video/edit_video')}}?vid="+data.id,
				  cancel: function(){ 
						window.location.reload();
					}
			  });
		}
		
		if(layEvent === 'del'){
			layer.confirm('确认删除该视频么？',{icon: 3,title:'提示'}, function(index){
				$.post("{{url('video/del_video')}}/"+data.id,{_token:_token},function(res){
					layer.close(index);
					if(res.status==2){
						layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
					}						
					if(res.status==1){
						layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
						setTimeout(function(){
							obj.del();
						}, 1500);
					}
				})
			})
		}
		
		if(layEvent === 'hide'){
			layer.confirm('确认隐藏该视频么？',{icon: 3,title:'提示'}, function(index){
				$.post("{{url('video/change_video_state')}}/"+data.id,{_token:_token,state:1},function(res){
					layer.close(index);
					if(res.status==2){
						layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
					}						
					if(res.status==1){
						layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
						setTimeout(function(){
							window.location.reload();
						}, 1500);
					}
				})
			})
		}
		
		if(layEvent === 'show'){
			layer.confirm('确认显示该视频么？',{icon: 3,title:'提示'}, function(index){
				$.post("{{url('video/change_video_state')}}/"+data.id,{_token:_token,state:0},function(res){
					layer.close(index);
					if(res.status==2){
						layer.msg(res.msg,{icon: 2,time: 1500,anim: 6,shade:0.3});
					}						
					if(res.status==1){
						layer.msg(res.msg,{icon: 1,time: 1500,anim: 1,shade:0.3});
						setTimeout(function(){
							window.location.reload();
						}, 1500);
					}
				})
			})
		}
	})
	
	form.on('submit(sure)', function(data){
		layer.confirm('确认执行该操作么？',{icon: 3,title:'提示'}, function(index){
			$.ajax({
				type:"post",
				dataType:'json',
				url:"{{url('account/increase_account')}}",
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
							window.location.reload();
						}, 1500);
					}
				},
				
				error: function(d,s){
					layer.alert("未知错误");
				},
				
				complete:function(d,s){
					layer.closeAll('loading');
				},
			})
			return false;
		})
	});	
	
	$('.add_new').click(function(){
		layer.open({
			  type: 2,
			  title:['新增视频','font-size: 22px;font-weight: 800;'],
			  shadeClose: true,
			  skin: 'layui-layer-rim',
			  content: "{{url('video/add_video')}}",
			  cancel: function(){ 
					window.location.reload();
				}
		  });
	})
});
</script>

</body>
</html>