<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
    <script src="/res/script/jquery.js" type="text/javascript"></script>
    <script src="/res/script/jquery.corner.js" type="text/javascript"></script>
    <style type="text/css">
    body {
            line-height: 25px;
    }
    </style>
</head>

<body>
<form id="form1" method="post" action="/ttmobileout/admin/getPermission?aid=<?php echo $aid; ?>">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/7.gif" alt="" />管理员权限</h1>
        <?php echo $modulesHTML ?>
	<script>
	var flags =  '<?php echo $flag?>'.split(",");
	$(":checkbox").each(function(){	
		for(var i = 0;i<flags.length;i++)
		{
			if($(this).attr("value")===flags[i])
			{
				$(this).attr("checked",true);
			}
		}
	});
	</script>
<div><input type="submit" name="submit" class="button" value="确定" /></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
	});
</script>
</form>
</body>
</html>