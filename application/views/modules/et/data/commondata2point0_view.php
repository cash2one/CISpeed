<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
    <script src="/res/script/jquery.js" type="text/javascript"></script>
    <script src="/res/script/jquery.corner.js" type="text/javascript"></script>
    <script src="/res/script/common.js" type="text/javascript"></script>
</head>

<body>
<form id="form1" method="post" action="">
<div id="headarea" class="Corner">
    <h1><img src="/res/images/admin/icon/9.gif" alt="" />ET2.0综合统计</h1>
    <div id="chartdiv" align="left">T</div>
    <div id="chartdiv2" align="left">T</div>
    <div id="chartdiv3" align="left">T</div>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>日期</th>
		<th>点击加速总数</th>
		<th>点击加速独立IP数</th>
		<th>点击加速独立账号数</th>
		<th>登录次数</th>
		<th>登录人数</th>
		<th>登录IP数</th>
		<th>平均在线</th>
		<th>最高在线</th>
		<th>新增用户</th>
	</tr>
	<?php echo $list; ?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
	});
</script>
<script src="/res/fusion/fusioncharts.js" type="text/javascript"></script>
<script type="text/javascript">
	var myChart = new FusionCharts("/res/fusion/charts/MSCombi2D.swf", "myChartId", "100%", "220");
	var str=
	"<chart palette='3' yAxisMinValue='6000'  caption='每天在线' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>"+
	"<categories><?php echo $fusionCategory?></categories>"+
	"<?php echo $fusionDataset?>"+
	"<?php echo $fusionDataset2?>"+
        "</chart>";
	myChart.setDataXML(str);
	myChart.render("chartdiv");
	
	myChart = new FusionCharts("/res/fusion/charts/MSCombi2D.swf", "myChartId", "100%", "220");
	str=
	"<chart palette='3' yAxisMinValue='40000' caption='每天登录账号数' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>"+
	"<categories fontSize='8' ><?php echo $fusionCategory?></categories>"+
	"<?php echo $fusionDataset3?>"+
        "</chart>";
	myChart.setDataXML(str);
	myChart.render("chartdiv2");
	
	myChart = new FusionCharts("/res/fusion/charts/MSCombi2D.swf", "myChartId", "100%", "220");
	str=
	"<chart palette='3'  yAxisMinValue='3000' caption='每天新增数' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>"+
	"<categories><?php echo $fusionCategory?></categories>"+
	"<?php echo $fusionDataset4?>"+
        "</chart>";
	myChart.setDataXML(str);
	myChart.render("chartdiv3");
</script>
</form>
</body>
</html>