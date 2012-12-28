<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css?v=20110919" />   
	<script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>  
</head>

<body>
<form id="form1" method="post" action="/ttmobile/user/userfriend">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />好友数量分析
	    <input type="submit" name="queryByDate" value="刷新" class="button" />
	</h1>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr><th>日期</th><th>1个好友</th><th>2个好友</th><th>3个好友</th><th>4个好友</th><th>5个好友</th>
            <th>6-10个好友</th><th>11-20个好友</th><th>21-30个好友</th><th>31-50个好友</th><th>>50个好友</th></tr>
	<?php echo $list;?>
</table>
<div id="morePeople"></div>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();	
	});
</script>
</form>
</body>
</html>