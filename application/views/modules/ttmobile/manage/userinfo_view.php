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
<form id="form1" method="post" action="/ttmobile/manage/userinfo">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />用户信息查询</h1>
手机号码：<input name="mobile" id="mobile" type="text" /> <select id="type"
	name="type">
	<option value="1">历史上线记录</option>
	<option value="2">历史通话记录</option>
</select> 开始日期：<input name="QueryDate1" id="QueryDate1" type="text"
	size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="input" />
结束日期：<input name="QueryDate2" id="QueryDate2" type="text" size="20"
	onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="input" /> <input
	type="button" value="查询" class="button" onclick="checkthis();" /></div>

<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();		
		$("#QueryDate1").val("<?php
		echo $date;
		?>");
		$("#QueryDate2").val("<?php
		echo $date2;
		?>");
	});	
</script></form>
</body>
</html>