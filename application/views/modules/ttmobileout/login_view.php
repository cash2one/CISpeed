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
	width: 230px;
	/*text-align: center;*/
}
</style>
</head>

<body>
<form id="form1" method="post" action="/ttmobileout/admin/login">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/4.gif" alt="" />手机通通渠道登录</h1>
<div>用户：<input class="input" name="name" type="text" /></div>
<div>密码：<input class="input" name="pwd" type="password" /></div>
<div>验证码<input class="input" name="vcode" type="text" /><img style="position: absolute" src="/ttmobileout/admin/yanzheng" /></div>
<div><input class="button" name="submit" type="submit" value="确定" /></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
		<?php echo $tips?>
	});
</script></form>
</body>
</html>