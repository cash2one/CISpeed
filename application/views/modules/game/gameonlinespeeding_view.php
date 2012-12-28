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
<form id="form1" method="post" action="/game/gameonlinespeeding">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />游戏在线加速人数统计 <input
	type="button" onclick="window.location.href = location;" class="button"
	value="刷新" /></h1>
<div>游戏名：&nbsp;&nbsp;&nbsp;<select id="game" name="game"><?php
echo $gameList?></select>
</div>
<div>选择日期：<input name="QueryDate1" id="QueryDate1" type="text" size="20"
	onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-09-21',maxDate:'%y-%M-#{%d}'})"
	class="input" /> <input type="button" value="查询" class="button"
	onclick="checkthis();" /></div>
<div id="chartdiv" align="left" style="margin-top: 5px;">T</div>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>时间</th>
		<th>人数</th>
	</tr>
        <?php
								echo $list?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
                <?php
																echo $jsCode?>
	});		
	function checkthis()
	{
		if(!checkvalue("QueryDate1","请填写要查询的日期")){return;}		
		$("#form1").submit();
	}
	function checkvalue(id,msg){
		if(!$('input[name="'+id+'"]').val()){
			alert(msg);
			return false;
		}
		return true;
	}
</script> <script src="/res/fusion/fusioncharts.js"
	type="text/javascript"></script> <script type="text/javascript">
	var myChart = new FusionCharts("/res/fusion/charts/MSCombi2D.swf", "myChartId", "100%", "250");
	var str=
	"<chart palette='3' caption='每5分钟分时曲线' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>"+
	"<categories><?php
	echo $fusionCategory?></categories>"+
	"<dataset seriesName='<?php
	echo $date;
	?>' renderAs='Line' ><?php
	echo $fusionDataset?></dataset>"+
	""+
	"</chart>";
	myChart.setDataXML(str);
	myChart.render("chartdiv");
</script></form>
</body>
</html>