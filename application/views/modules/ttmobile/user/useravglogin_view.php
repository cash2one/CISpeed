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
<form id="form1" method="post" action="">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />用户登录情况
	    <input type="submit" name="queryByDate" value="刷新" class="button" />
	</h1>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr><th>日期</th><th>平均登录次数</th><th>超过平均30-50次的用户数</th><th>超过平均50-100次的用户数</th><th>超过平均>=100次的用户数</th><th>超过平均>=1000次的用户数</th>
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