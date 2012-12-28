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
<form id="form1" method="post" action="/et/data/logindataall">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />ST服务器在线人数总览 <input
	type="button" onclick="window.location.href = location;" class="button"
	value="刷新" /></h1>
选择日期：<input name="QueryDate1" id="QueryDate1" type="text" size="20"
	onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="input" /> <input
	type="button" value="查询" class="button" onclick="checkthis();" />
        <?php
								echo $chartDiv;
								?>
</div>
<script src="/res/script/speed.js" type="text/javascript"></script> <script
	type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php
		echo $date;
		?>");
	});
</script> <script src="/res/fusion/fusioncharts.js"
	type="text/javascript"></script> <script type="text/javascript">
<?php
echo $scriptFusion;
?>
</script></form>
</body>
</html>