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
<form id="form1" method="post" action="/speed/node">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />综合统计</h1>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>日期</th>
		<th>登录次数</th>
		<th>登录人数</th>
		<th>登录IP数</th>
		<th>平均在线</th>
		<th>最高在线</th>
		<th>语音平均在线</th>
		<th>语音最高在线</th>
		<th>创建频道数</th>
		<th>新增用户</th>
	</tr>
    <?php
				echo $list;
				?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
	});
</script></form>
</body>
</html>