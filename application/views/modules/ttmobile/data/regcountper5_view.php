<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
	<script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
	<script src="/res/script/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/res/script/My97DatePicker/WdatePicker.js"></script>
</head>

<body>
<form id="form1" method="post" action="/ttmobile/data/regcountper5">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />用户注册人数统计 <input type="button" onclick="window.location.href = location;" class="button" value="刷新" /></h1>
	选择日期：<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-09-08',maxDate:'%y-%M-%d'})" class="input" />
	<input type="checkbox" name="compare" value="1" checked="true" style="vertical-align: middle">对比</input>
	<div id="vs" style="display: inline">
		对比日期：<input name="QueryDate2" id="QueryDate2" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-09-08',maxDate:'%y-%M-%d'})" class="input" />
	</div>
	<input type="button" value="查询" class="button" onclick="checkthis();" />
	<div id="chartdiv" align="left" style="margin-top: 5px;">T</div>
	<div>同时段注册数对比：<?php echo $sum?></div>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr><th>时间</th><th>人数</th></tr>
        <?php echo $list?>
</table>

<script type="text/javascript" src="/res/script/speed.js"></script>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();		
		$("#QueryDate1").val("<?php echo $date;?>");
		$("#QueryDate2").val("<?php echo $date2;?>");
                <?php echo $compareCheck?>
	});	
</script>
<script src="/res/fusion/fusioncharts.js" type="text/javascript"></script>
<script type="text/javascript">
	var myChart = new FusionCharts("/res/fusion/charts/MSCombi2D.swf", "myChartId", "100%", "250");
	var str=
	"<chart palette='3' caption='每5分钟注册人数' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>"+
	"<categories><?php echo $fusionCategory?></categories>"+
	"<dataset seriesName='<?php echo $date?>' renderAs='Line' ><?php echo $fusionDataset?></dataset>"+
	"<?php echo $fusionDataset2?>"+
	"</chart>";
	myChart.setDataXML(str);
	myChart.render("chartdiv");
</script></form>
</body>
</html>