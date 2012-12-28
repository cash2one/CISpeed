<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
<script src="/res/script/common.js" type="text/javascript"></script>
<script type="text/javascript"
	src="/res/script/My97DatePicker/WdatePicker.js"></script>
</head>

<body>
<form id="form1" method="post" action="/speed/machinecode">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />装机卸载量 <input
	type="button" onclick="window.location.href = location;" class="button"
	value="刷新" /></h1>

产品：<select id="proList" name="proList">
	<option value="43">ET客户端</option>
	<option value="8888">虚拟视频</option>
</select> 类型：<select id="installType" name="installType">
	<option value="1">安装</option>
	<option value="2">卸载</option>
</select> 日期：<input name="QueryDate1" id="QueryDate1" type="text"
	size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM'})" class="input" /> <input
	type="button" value="查询" class="button" onclick="checkthis();" />
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th></th>
		<th>总量</th>
		<th>01</th>
		<th>02</th>
		<th>03</th>
		<th>04</th>
		<th>05</th>
		<th>06</th>
		<th>07</th>
		<th>08</th>
		<th>09</th>
		<th>10</th>
		<th>11</th>
		<th>12</th>
		<th>2011</th>
	</tr>
	<tr>
		<td>装机量</td><?php
		echo $installSum?></tr>
	<tr>
		<td>卸载量</td><?php
		echo $uninstallSum?></tr>
</table>
<div id="chartdiv" align="left">T</div>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>日期</th>
		<th>装机量</th>
		<th>卸载量</th>
	</tr>
	<?php
	echo $list?>
</table>

<script src="/res/script/speed.js" type="text/javascript"></script> <script
	type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php
		echo $date;
		?>");
		$("#proList").val("<?php
		echo $proList;
		?>");
		$("#installType").val("<?php
		echo $installType;
		?>");
	});
</script> <script src="/res/fusion/fusioncharts.js"
	type="text/javascript"></script> <script type="text/javascript">
	var myChart = new FusionCharts("/res/fusion/charts/MSCombi2D.swf", "myChartId", "100%", "250");
	var str=
	"<chart palette='3' caption='每天曲线' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>"+
	"<categories><?php
	echo $fusionCategory?></categories>"+
	"<dataset seriesName='<?php
	echo $date?>' renderAs='Line' ><?php
	echo $fusionDataset?></dataset>"+
	"<?php
	echo $fusionDataset2?>"+
        "</chart>";
	myChart.setDataXML(str);
	myChart.render("chartdiv");
</script></form>
</body>
</html>