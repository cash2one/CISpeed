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
<form id="form1" method="post" action="/ttmobile/data/systemcalldata">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />系统电话数据 <input type="button" onclick="window.location.href = location;" class="button" value="刷新" /></h1>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr><th>时间</th><th>通通打系统人数</th><th>通通打系统次数</th><th>系统打系统人数</th><th>系统打系统次数</th></tr>
        <?php echo $list?>
</table>
</form>
</body>
</html>