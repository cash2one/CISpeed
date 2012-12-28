<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
    <script src="/res/script/jquery.js" type="text/javascript"></script>
    <script src="/res/script/jquery.corner.js" type="text/javascript"></script>
    <script src="/res/script/jquery.tableui.js" type="text/javascript"></script>
    <script src="/res/script/common.js" type="text/javascript"></script>
</head>

<body>
<form id="form1" method="post" action="">
<div id="headarea" class="Corner">
    <h1><img src="/res/images/admin/icon/9.gif" alt="" />手机通通统计数据</h1>
    <div id="chartdiv" align="left">T</div>
    <div id="chartdiv2" align="left">T</div>
    <div id="chartdiv3" align="left">T</div>
    <div>截止到今天的注册用户数：<?php echo $regAll ?></div>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>日期</th>
		<th>峰值人数</th>		
		<th>增幅</th>
		<th>峰值时间</th>
		<th>登录人数</th>	
		<th>增幅</th>
		<th>3天登录</th>
		<th>7天登录</th>
		<th>15天登录</th>
		<th>30天登录</th>
		<!--th>登录人次</th-->
		<th>成功邀请数</th>
		<th>激活人数</th>	
		<th>新登(注册)数</th>	
		<th>增幅</th>
		<th>注册失败人数</th>
		<th>截止当天用户数</th>	
		<th>增幅</th>
	</tr>
	<?php echo $list; ?>
</table>

<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();$(".table").tableUI();
	});
</script>
<script src="/res/fusion/fusioncharts.js" type="text/javascript"></script>
<script type="text/javascript">
	var myChart = new FusionCharts("/res/fusion/charts/MSCombi2D.swf", "myChartId", "100%", "220");
	var str=
	"<chart palette='3' yAxisMinValue='100'  caption='峰值人数' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>"+
	"<categories fontSize='8'><?php echo $fusionCategory?></categories>"+
	"<?php echo $fusionDataset?>"+
        "</chart>";
	myChart.setDataXML(str);
	myChart.render("chartdiv");
	
	myChart = new FusionCharts("/res/fusion/charts/MSCombi2D.swf", "myChartId", "100%", "220");
	str=
	"<chart palette='3' yAxisMinValue='100' caption='登录人数' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>"+
	"<categories fontSize='8' ><?php echo $fusionCategory?></categories>"+
	"<?php echo $fusionDataset3?>"+
        "</chart>";
	myChart.setDataXML(str);
	myChart.render("chartdiv2");
	
	myChart = new FusionCharts("/res/fusion/charts/MSCombi2D.swf", "myChartId", "100%", "220");
	str=
	"<chart palette='3'  caption='新登人数' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>"+
	"<categories fontSize='8'><?php echo $fusionCategory?></categories>"+
	"<?php echo $fusionDataset4?>"+
        "</chart>";
	myChart.setDataXML(str);
	myChart.render("chartdiv3");
</script>
</form>
</body>
</html>