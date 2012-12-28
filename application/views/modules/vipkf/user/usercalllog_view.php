<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css?v=20110919" />
	<script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
	<script src="/res/script/common.js?v=20110818" type="text/javascript"></script>
	<script type="text/javascript" src="/res/script/My97DatePicker/WdatePicker.js"></script>
</head>

<body>
<form id="form1" method="post" action="/vipkf/user/calllog">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />用户通话记录</h1>
	<div>查询日期：<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-08-28',maxDate:'%y-%M-%d'})" class="input" />
		<select id="starttime" name="starttime">
			<option value="-1">所有通话</option>
			<option value="0">未接通通话</option>
			<option value="1">接通通话</option>
		</select>
		<select id="endtype" name="endtype">
			<option value="-1">所有类型</option>
			<option value="0">正在通话</option>
			<option value="1">正常挂断</option>
			<option value="2">占线</option>
			<option value="3">拒绝接听</option>
			<option value="4">无人应答</option>
			<option value="6">不在线或不在服务区</option>
			<option value="7">掉线</option>
			<option value="8">连接超时</option>
			<option value="9">被踢出</option>
			<option value="10">频道超过最大用户数</option>
			<option value="11">未登录</option>
			<option value="12">语音频道连接断开</option>
			<option value="13">频道连接超时时间</option>
			<option value="14">服务器异常</option>
		</select><br />
	<select id="fuid" name="fuid">
		<option value="Fsenduid">主叫号码</option>
		<option value="Frcvuid">被叫号码</option>
	</select>：
	<input name="mobile" class="input" value="0" id="mobile" type="text" />
	<input type="submit" name="queryByDate" value="查询" class="button" /></div>
</div>
<div>
	<table class="table" cellspacing="0" cellpadding="0">
		<tr><th>主叫手机号</th><th>接收手机号</th><th>通话类型</th><th>通话时长</th><th>开始时间</th><th>结束时间</th></tr>
		<?php echo $list;?>
	</table>
	<div><?php echo $page; ?></div>
</div>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php echo $date?>");
		$("#starttime").val("<?php echo $starttime?>");
		$("#endtype").val("<?php echo $endtype?>");
		$("#mobile").val("<?php echo $mobile?>");
		$("#fuid").val("<?php echo $fuid?>");
	});	
</script>
</form>
</body>
</html>