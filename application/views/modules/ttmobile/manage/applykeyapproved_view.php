<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
<script src="/res/script/common.js?v=20110818" type="text/javascript"></script>
</head>
<body>
<form id="form1" method="post" action="/ttmobile/manage/approvedkeylist">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />手机通通已发放激活码用户</h1>
<div>查询手机号：<input type="text" id="mobile" name="mobile" class="input" /><input
	type="button" value="查询" class="button" onclick="checkthis();" /></div>
<div style="margin-top: 5px; line-height: 26px;"><?php
echo $queryMobile?></div>
<input type="hidden" name="key" value="<?php
echo $key?>" />
<div>查询申请人：<input type="input" id="applyname" name="applyname"
	class="input" /><input type="submit" name="queryname" value="查询"
	class="button"></div>
</div>
<div><a href="/ttmobile/manage/approvedkeylist?stat=1">已激活(<?php
echo $count1?>)</a>
<a href="/ttmobile/manage/approvedkeylist?stat=2">未激活(<?php
echo $count2?>)</a>
<a href="/ttmobile/manage/approvedkeylist?stat=3">所有用户(<?php
echo $allcount?>)</a>
</div>
<table class="table" style="margin-top: 0px" cellspacing="0"
	cellpadding="0">
	<tr>
		<th>申请人</th>
		<th>申请手机号</th>
		<th>手机归属地</th>
		<th>激活码</th>
		<th>激活</th>
		<th>激活时间</th>
		<th>申请者IP</th>
		<th>申请时间</th>
		<th>审核时间</th>
	</tr>
    <?php
				echo $list;
				?> 
</table>
<div>
    <?php
				echo $page;
				?>
</div>

<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#mobile").val("<?php
		echo $mobile?>");
		$("#applyname").val("<?php
		echo $applyname?>");
	});
	function checkthis()
	{
		if(checkmobile($("#mobile").val()))
		{
			$("#form1").submit();
		}
		else
		{
			alert("请填写正确的手机号");
		}
	}
</script></form>
</body>
</html>