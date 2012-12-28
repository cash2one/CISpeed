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
<form id="form1" method="post" action="/ttmobile/user/monthcallfeedetail">
<div id="headarea" class="Corner">
    <h1><img src="/res/images/admin/icon/9.gif" alt="" />前500位话费统计
    <input type="button" onclick="window.location.href = location;" class="button" value="刷新" /></h1>
    日期：<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM',minDate:'2011-10',maxDate:'%y-#{%M-1}'})" class="input" />
    <input type="submit" value="查询" class="button" /><br/>
    按次计不足1分钟的按1分钟计,本地市话按0.1元每分钟，国内长途按0.5元每分钟，国际话费按2元每分钟。
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>手机号</th>
		<th>市话时长</th>
		<th>市话资费</th>
		<th>国内长途时长</th>
		<th>国内长途资费</th>
		<th>国际通话时长</th>
		<th>国际通话资费</th>
		<th>总通话资费</th>
	</tr>
	<?php echo $list?>
</table>
<script
	type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php echo $date; ?>");
	});
</script></form>
</body>
</html>