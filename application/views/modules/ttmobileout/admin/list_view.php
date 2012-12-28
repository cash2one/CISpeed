<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
</head>
<body>
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/7.gif" alt="" />管理员列表</h1>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Group</th>
		<th>相关操作</th>
	</tr>
	<?php
	echo $list?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
	});
	function checkDel(id,name)
	{
	    if(confirm("确定要删除"+name+"?"))
	      window.location.href="/ttmobileout/admin/del?aid="+id;
	    else
	      return; 
	}
</script>
</body>
</html>