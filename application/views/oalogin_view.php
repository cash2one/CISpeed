<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>用户登陆</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
<style type="text/css">
#headarea {
	width: 240px;
	/*text-align: center;*/
}

.input {
	width: 200px
}
</style>
</head>

<body>
<form id="form1" method="post" action="/admin/oalogin">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/4.gif" alt="" />后台登录</h1>
<div style="color: red">请使用员工账号、密保登录</div>
<div>用户：<input class="input" name="name" type="text" /></div>
<div>密码：<input class="input" name="pwd" type="password" /></div>
<div>密保：<input class="input" name="vcode" type="text" /></div>
<div><input class="button" name="submit" type="submit" value="确定" /></div>
<?php
echo $response?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
		<?php
		echo $tips?>
	});
</script>

</form>
</body>
</html>