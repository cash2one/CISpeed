<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
	<script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
	<script src="/res/script/common.js" type="text/javascript"></script>
</head>

<body>
<form id="form1" method="post" action="/vipkf/user/insertcustom">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />添加管理员</h1>	
	<select id="role" name="role">
		<option value="2">普通坐席</option>
		<option value="8">管理员</option>
	</select><br/>
	账号：<input name="id" id="id" class="input"  type="text" /><span class="readme">(只能填写数字)</span><br/>
	密码：<input name="pwd" id="pwd" class="input"  type="password" /><br/>
	真名：<input name="rname" id="rname" class="input"  type="text" /><br/>
	昵称：<input name="name" id="name" class="input"  type="text" /><br/>
	<input type="submit" name="submit" id="submit" value="ok" class="button" />
</div>
<div>
	<table class="table" cellspacing="0" cellpadding="0">
		<tr><th>操作</th><th>账号</th><th>姓名</th><th>昵称</th><th>角色</th></tr>
		<?php echo $list;?>
	</table>
</div>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#role").val('<?php echo $role ?>');	
		$("#id").val('<?php echo $id ?>');	
		$("#rname").val('<?php echo $rname ?>');	
		$("#name").val('<?php echo $name ?>');
		<?php echo $jstips ?>
	});
	$("#submit").click(function(){
		if(!checkNum($('#id').val())){
			alert('账号必须为数字');
			return false;
		}
	});
	function del(id)
	{
		if(confirm("确认删除账号"+id+"?"))
		{
			window.location.href = "delcustom?id="+id;
		}
		else
		{
			return;
		}
	}
</script>
</form>
</body>
</html>