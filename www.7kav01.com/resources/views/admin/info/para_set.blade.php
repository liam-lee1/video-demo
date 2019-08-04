<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>设置参数</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<style>
	.layui-input-inline.spe{width:100px;}
	.layui-word-aux{color:#111!important;}
	.meb_level .layui-rate{padding:0;}
</style>
<body>
<div class="layui-form ">
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>系统设置</legend>
</fieldset>
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="layui-form-item">
		<label class="layui-form-label">标题设置</label>
		<div class="layui-input-inline" style="width:500px;">
		  <input type="text" name="title" value="{{$title}}" lay-verify="required" autocomplete="off" placeholder="请输入类别名称" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">关键字</label>
		<div class="layui-input-inline" style="width:500px;">
		  <input type="text" name="keywords" value="{{$keywords}}" autocomplete="off" placeholder="请输入keywords" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">网站介绍</label>
		<div class="layui-input-inline" style="width:500px;">
			<textarea name="description" placeholder="多行输入" class="layui-textarea">{{$description}}</textarea>
		</div>
	</div>
	
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>会员设置</legend>
</fieldset>
	<div class="layui-form-item">
		<div class="layui-inline">
		  <label class="layui-form-label">注册经验</label>
		  <div class="layui-input-inline">
			<input type="text" name="enroll_exp" value="{{$enroll_exp}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
		  </div>
		</div>
		<div class="layui-inline">
		  <label class="layui-form-label">注册积分</label>
		  <div class="layui-input-inline">
			<input type="text" name="enroll_credit" value="{{$enroll_credit}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
		  </div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label"></label>
			<div class="layui-form-mid layui-word-aux">每日签到获得</div>
			<div class="layui-input-inline spe">
				<input type="text" name="sign_exp" value="{{$sign_exp}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">经验值</div>
			<div class="layui-input-inline spe">
				<input type="text" name="sign_credit" value="{{$sign_credit}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">积分</div>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">等级设置</label>
		@for($i=0;$i<3;$i++)
			<div class="layui-input-block">
				<label class="layui-form-label"></label>
				<div class="layui-form-mid layui-word-aux"><div class="meb_level fl" data-value="{{$i+1}}"></div></div>
				<div class="layui-input-inline spe">
					<input type="text" name="level[]" value="{{ json_decode($level,true)[$i]}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
				</div>
				<div class="layui-form-mid layui-word-aux">经验值</div>
			</div>
		@endfor
	</div>
	
	<div class="layui-form-item">
		<label class="layui-form-label">下载设置</label>
		<div class="layui-inline">
			<label class="layui-form-label"></label>
			<div class="layui-form-mid layui-word-aux">每次下载扣除</div>
			<div class="layui-input-inline spe">
				<input type="text" name="reduce" value="{{$reduce}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">积分</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label"></label>
			<div class="layui-form-mid layui-word-aux">重复下载</div>
			<div class="layui-input-inline spe">
				<input type="text" name="reduce_time" value="{{$reduce_time}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">小时内不扣除积分</div>
		</div>
	</div>
	
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>推广设置</legend>
</fieldset>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label"></label>
			<div class="layui-form-mid layui-word-aux">推广新用户点击获得</div>
			<div class="layui-input-inline spe">
				<input type="text" name="recom_click_credit" value="{{$recom_click_credit}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">积分</div>
			<div class="layui-input-inline spe">
				<input type="text" name="recom_click_exp" value="{{$recom_click_exp}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">经验</div>
		</div>
		
		<div class="layui-inline">
			<label class="layui-form-label"></label>
			<div class="layui-form-mid layui-word-aux">注册并登录获得</div>
			<div class="layui-input-inline spe">
				<input type="text" name="recom_enroll_credit" value="{{$recom_enroll_credit}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">积分</div>
			<div class="layui-input-inline spe">
				<input type="text" name="recom_enroll_exp" value="{{$recom_enroll_exp}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">经验</div>
		</div>
	</div>
	
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label"></label>
			<div class="layui-form-mid layui-word-aux">二级推广新用户点击获得</div>
			<div class="layui-input-inline spe">
				<input type="text" name="recom_sec_click_credit" value="{{$recom_sec_click_credit}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">积分</div>
			<div class="layui-input-inline spe">
				<input type="text" name="recom_sec_click_exp" value="{{$recom_sec_click_exp}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">经验</div>
		</div>
		
		<div class="layui-inline">
			<label class="layui-form-label"></label>
			<div class="layui-form-mid layui-word-aux">注册并登录获得</div>
			<div class="layui-input-inline spe">
				<input type="text" name="recom_sec_enroll_credit" value="{{$recom_sec_enroll_credit}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">积分</div>
			<div class="layui-input-inline spe">
				<input type="text" name="recom_sec_enroll_exp" value="{{$recom_sec_enroll_exp}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">经验</div>
		</div>
	</div>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>提现额度设置</legend>
</fieldset>
	
	<div class="layui-form-item">
		<label class="layui-form-label">最低提现</label>
		<div class="layui-input-inline spe">
		  <input type="text" name="withdraw_limit" value="{{$withdraw_limit}}" lay-verify="required" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" autocomplete="off" placeholder="请输入最低提现额度" class="layui-input">
		</div>
		<div class="layui-form-mid layui-word-aux">元</div>
	</div>
	
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>支付类型</legend>
</fieldset>
	<div class="layui-form-item">
		<label class="layui-form-label"></label>
		<div class="layui-form-mid layui-word-aux">支付宝（H5）</div>
		<div class="layui-input-inline">
		  <input type="checkbox" name="zfb_h5" value="1" @if($zfb_h5==1) checked @endif lay-skin="switch" lay-text="开启|关闭">
		</div>
		
		<label class="layui-form-label"></label>
		<div class="layui-form-mid layui-word-aux">微信（H5）</div>
		<div class="layui-input-inline">
		  <input type="checkbox" name="wx_h5" value="1" @if($wx_h5==1) checked @endif lay-skin="switch" lay-text="开启|关闭">
		</div>
	</div>
	
	<div class="layui-form-item">
		<label class="layui-form-label"></label>
		<div class="layui-form-mid layui-word-aux">支付宝（扫码）</div>
		<div class="layui-input-inline">
		  <input type="checkbox" name="zfb_scan" value="1" @if($zfb_scan==1) checked @endif lay-skin="switch" lay-text="开启|关闭">
		</div>
		
		<label class="layui-form-label"></label>
		<div class="layui-form-mid layui-word-aux">微信（扫码）</div>
		<div class="layui-input-inline">
		  <input type="checkbox" name="wx_scan" value="1" @if($wx_scan==1) checked @endif lay-skin="switch" lay-text="开启|关闭">
		</div>
	</div>
	

	<div class="layui-form-item">
		<div class="layui-input-block">
		  <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="edit">保存修改</button>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
layui.use(['layer','rate','form','jquery'], function(){
	var layer = layui.layer,
		form = layui.form,
		rate = layui.rate,
		$ = layui.jquery;
	
	$.each($('.meb_level'),function(){
		var val = $(this).data('value');
		rate.render({
			elem: $(this)
			,value: val
			,length:3
			,readonly: true
			,theme: '#111'
		  });
	})
	
	form.on('submit(edit)', function(data){
		layer.confirm('确认保存更改么？',{icon: 3,title:'提示'}, function(index){
			$.ajax({
				type:"post",
				dataType:'json',
				url:"{{url('info/save_para')}}",
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
	
});
</script>

</body>
</html>