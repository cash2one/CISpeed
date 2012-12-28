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
<form id="form1" method="post" action="/ttmobile/user/nettypedata">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />网络类型分布 <input type="button" onclick="window.location.href = location;" class="button" value="刷新" /></h1>
	选择日期：<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-09-05',maxDate:'%y-%M-#{%d-1}'})" class="input" />
	<input type="submit" value="查询" class="button" name="submit" />
	<div id="chartdiv" align="left" style="margin-top: 5px;">T</div>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr><th>网络类型</th><th>数量</th></tr>
	<?php echo $list; ?>
</table>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();		
		$("#QueryDate1").val("<?php echo $date; ?>");
	});	
</script>
<script src="/res/fusion/fusioncharts.js" type="text/javascript"></script>
<script type="text/javascript">
	var myChart = new FusionCharts("/res/fusion/charts/Pie3D.swf", "myChartId", "100%", "250");
	var str=
	"<graph caption='网络类型分布图' showNames='1 ' decimalPrecision='0' formatNumberScale='0'>"+
        "	<?php echo $fusionData?>"+
        "</graph>";
	myChart.setDataXML(str);
	myChart.render("chartdiv");
</script>
</form>
</body>
</html>