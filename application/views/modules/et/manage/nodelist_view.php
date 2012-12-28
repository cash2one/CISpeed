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
<form id="form1" method="post" action="/et/manage/node">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />Node管理</h1>
<div>
<div>编号：<input class="input" name="id" id="id" type="text" /></div>
<div>ip&nbsp;&nbsp;&nbsp;：<input class="input" name="ip" id="ip"
	type="text" /></div>
<div>名称：<input class="input" name="name" id="name" type="text" /></div>
<div>状态：<select id="type" name="type">
	<option value="1">ST Server</option>
	<option value="0">TS Server</option>
</select></div>
<input class="button" type="button" onclick="checkthis()" id="submitbtn"
	name="add" value="添加" /> <input class="button" type="button"
	onclick="window.location.href='/node/nodelist'" style="display: none"
	id="cancel" value="取消" /></div>
<input type="hidden" id="flag" name="flag" value="add"></input> <input
	type="hidden" id="oldid" name="oldid" value="0"></input></div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>操作</th>
		<th>node编号</th>
		<th>node IP</th>
		<th>node名称</th>
		<th>所属group</th>
		<th>端口</th>
		<th>ping端口</th>
		<th>状态</th>
		<th>最近更新时间</th>
	</tr>	
        <?php
								echo $list?>
</table>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
                <?php
																echo $jsCode?>
	});	
</script></form>
</body>
</html>