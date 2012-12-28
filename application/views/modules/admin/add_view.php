<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
</head>

<body>
<form id="form1" method="post" action="/admin/add">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/11.gif" alt="" />添加管理员</h1>
<div>用户：<input class="input" name="name" type="text" /></div>
<div>职能：<select name="group">
	<option value="0">管理员</option>
	<option value="1">运营</option>
	<option value="2">运维</option>
	<option value="3">研发</option>
</select></div>
<div>密码：<input class="input" name="pwd" type="password" /></div>
<div>确认：<input class="input" name="pwdconfirm" type="password" /></div>
<div><input class="button" name="submit" type="submit" value="确定" /></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
                <?php
																echo $tips?>
	});
</script></form>
</body>
</html>