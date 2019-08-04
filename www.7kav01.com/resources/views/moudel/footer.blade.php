<footer>
	<a @if($as == 'index') class="active" href="javascript:;" @else href="../m/index" @endif>
		<i class="layui-icon layui-icon-home"></i>
		<span class="text-omit">首页</span>
	</a>
	<a href="javascript:;" class="insign" >
		<i class="layui-icon layui-icon-read"></i>
		<span class="text-omit">签到</span>
	</a>
	<a @if($as == 'free') class="active" href="javascript:;" @else href="../m/free" @endif>
		<i class="layui-icon layui-icon-flag"></i>
		<span class="text-omit" >必看说明</span>
	</a>
	<a @if($as == 'ucenter') class="active" href="javascript:;" @else href="../m/ucenter" @endif>
		<i class="layui-icon layui-icon-user"></i>
		<span class="text-omit">个人中心</span>
	</a>
</footer>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?73691e7c0215b4eaf6962a10c9b8f9cf";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<script type="text/javascript" src="//js.users.51.la/20143761.js"></script>
<div id="index_tan" style="left:0px;width:100%;background-color:#00000099;text-align:center;position:fixed;z-index:100;top:-3px;">
<span style="font-size:0.41rem;color:#ffffff;">请记住本站中文域名 <span style="color:#36ff06">不迷路.com</span> 永不迷路！</span>
</div>
<script>
 function countSecond() { $("#index_tan").slideUp(1000)}
$(function(){
    setTimeout("countSecond()", 5000);

});
</script>