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
<form id="form1" method="post" action="/admin/changepwd">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/11.gif" alt="" />修改密码</h1>
<div>旧密码：<input class="input" name="old" type="password" /></div>
<div>新密码：<input class="input" name="new" type="password" /></div>
<div>确&nbsp;&nbsp;&nbsp;认：<input class="input" name="newconfirm"
	type="password" /></div>
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