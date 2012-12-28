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
<form id="form1" method="post"
	action="http://123.103.17.22:8081/ttmobile/api/addUserSuggest">
<div id="headarea" class="Corner"><input type="hidden" name="userid"
	value="<?php
	echo $userid?>" /> <input type="hidden" name="mail"
	value="<?php
	echo $mail?>" /> <input type="hidden" name="content"
	value="<?php
	echo $content?>" /> <input type="hidden" name="mac"
	value="<?php
	echo $mac?>" /> <input type="submit" value="s" /></div>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();	
	});	
</script></form>
</body>
</html>