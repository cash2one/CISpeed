<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
<script src="/res/fusion/fusioncharts.js" type="text/javascript"></script>
</head>
<body>
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/7.gif" alt="" />
	    <?php
					echo $_SESSION ["adminname"] . "，您好"?>
	</h1>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>最近10次登录时间</th>
		<th>最近登录IP</th>
	</tr>
        <?php
								echo $list?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
	});
</script>
</body>
</html>

