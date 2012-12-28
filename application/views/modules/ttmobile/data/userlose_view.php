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
<h1><img src="/res/images/admin/icon/9.gif" alt="" />用户流失率</h1>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>日期</th>
		<th>新登录数</th>
		<th>3天流失数</th>
		<th>3天流失率</th>
		<th>7天流失数</th>
		<th>7天流失率</th>
		<th>15天流失数</th>
		<th>15天流失率</th>
		<th>30天流失数</th>
		<th>30天流失率</th>
	</tr>
	<?php echo $list;?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
	});
</script></form>
</body>
</html>