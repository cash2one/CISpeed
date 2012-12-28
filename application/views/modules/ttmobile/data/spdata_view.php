<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css?v=20110919" />
        <script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
	<script type="text/javascript" src="/res/script/My97DatePicker/WdatePicker.js"></script>
</head>

<body>
<form id="form1" method="post" action="/ttmobile/data/spdata">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />渠道数据
	    <input type="submit" name="queryByDate" value="刷新" class="button" />
	</h1>
	日期:<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-10-20',maxDate:'%y-%M-#{%d-1}'})" class="input" />
        <input type="submit" name="query"  value="查询" class="button" />
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<!--tr><th>日期</th><th>总注册量</th><th>各版注册量(渠道编号:数量)</th></tr-->	
	<tr><th>日期</th><th>渠道编号</th><th>渠道名称</th><th>新增机器码数</th><th>新增手机号数</th><th>渠道可见</th></tr>
	<?php echo $list;?>
</table>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php echo $QueryDate1 ?>");	
	});
</script>
</form>
</body>
</html>