<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统统计</title>
<link rel="stylesheet" href="<?php echo e(asset('layui/css/layui.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
</head>
<body>
<div class="layui-fluid">
	<div class="layui-row layui-col-space15">
		<div class="layui-col-sm6 layui-col-md3">
			<div class="layui-card">
			  <div class="layui-card-header">
				会员总数
				<span class="layui-badge layui-bg-blue layuiadmin-badge">全部</span>
			  </div>
			  <div class="layui-card-body layuiadmin-card-list">
				<p class="layuiadmin-big-font"><?php echo e($data['all']); ?></p>
				<p>
				  总计 
				  <span class="layuiadmin-span-color"><i class="layui-inline layui-icon layui-icon-group"></i></span>
				</p>
			  </div>
			</div>
		</div>
		<div class="layui-col-sm6 layui-col-md3">
			<div class="layui-card">
			  <div class="layui-card-header">
				今日新增
				<span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
			  </div>
			  <div class="layui-card-body layuiadmin-card-list">
				<p class="layuiadmin-big-font"><?php echo e($data['new']); ?></p>
				<p>
				  总计 
				  <span class="layuiadmin-span-color"><i class="layui-inline layui-icon layui-icon-user"></i></span>
				</p>
			  </div>
			</div>
		</div>
		<div class="layui-col-sm6 layui-col-md3">
			<div class="layui-card">
			  <div class="layui-card-header">
				资源数量
				<span class="layui-badge layui-bg-blue layuiadmin-badge">all</span>
			  </div>
			  <div class="layui-card-body layuiadmin-card-list">
				<p class="layuiadmin-big-font"><?php echo e($data['episode']); ?></p>
				<p>
				  专辑总计 
				  <span class="layuiadmin-span-color"><?php echo e($data['album']); ?><i class="layui-inline layui-icon layui-icon-upload"></i></span>
				</p>
			  </div>
			</div>
		</div>
		<div class="layui-col-sm6 layui-col-md3">
			<div class="layui-card">
			  <div class="layui-card-header">
				推广积分
				<span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
			  </div>
			  <div class="layui-card-body layuiadmin-card-list">
				<p class="layuiadmin-big-font"><?php echo e($data['credit_today']); ?></p>
				<p>
				  总计 
				  <span class="layuiadmin-span-color"><?php echo e($data['credit_all']); ?><i class="layui-inline layui-icon layui-icon-auz"></i></span>
				</p>
			  </div>
			</div>
		</div>
		<div class="layui-col-sm12">
			<div class="layui-card">
			  <div class="layui-card-header">
				每日会员量统计
				<div class="layui-btn-group layuiadmin-btn-group">
				  <a href="javascript:;" class="layui-btn layui-btn-orange layui-btn-xs">半月</a>
				</div>
			  </div>
			  <div class="layui-card-body">
				<div class="layui-row">
				  <div class="layui-col-sm12">
					  <div id="chartmain" style="width:100%; height: 400px;"></div>
				  </div>
				</div>
			  </div>
			</div>
		</div>		
	</div>
</div>

<script type="text/javascript" src="<?php echo e(asset('js/echarts.simple.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('layui/layui.js')); ?>"></script>
<script>
layui.use(['layer','jquery'], function(){
	var layer = layui.layer
		,$ = layui.jquery;
	
	//指定图标的配置和数据
	var option = {
		title:{
			text:'一周会员量统计'
		},
		tooltip:{},
		legend:{
			data:['用户来源']
		},
		xAxis:{
			data:<?php echo $re['xAxis']; ?>

		},
		yAxis:{

		},
		series:[{
			name:'会员量',
			type:'line',
			data:<?php echo $re['yAxis']; ?>

		}]
	};
	//初始化echarts实例
	var myChart = echarts.init(document.getElementById('chartmain'));

	//使用制定的配置项和数据显示图表
	myChart.setOption(option);
	
})
</script>
</body>
</html>