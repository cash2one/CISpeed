<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
	<link type="text/css" rel="stylesheet" href="/res/css/tinybox.css" />
</head>

<body>
<form id="form1" method="post" action="/ttmobile/user/suggestlist">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />手机通通用户意见反馈</h1>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>用户ID</th>
		<th>意见描述</th>
		<th>联系方式</th>
		<th>提交时间</th>
		<th>处理结果</th>
		<th></th>
		<th></th>
		<th>意见编号</th>
	</tr>
	<?php echo $list; ?>
</table>
<div>
    <?php echo $page; ?>
</div>
<?php echo $scriptjs?>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
	});	
</script></form>
</body>
</html>