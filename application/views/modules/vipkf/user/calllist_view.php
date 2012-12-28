<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css?v=20110919" />
	<script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
	<script src="/res/script/common.js?v=20110818" type="text/javascript"></script>
	<script type="text/javascript" src="/res/script/My97DatePicker/WdatePicker.js"></script>
</head>

<body>
<form id="form1" method="post" action="/vipkf/user/calllist">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />来电记录</h1>
        <select name="type" id="type">
            <option value="1">用户电话</option>
            <option value="2">坐席工号</option>
        </select>
        <input name="uid" id="uid" type="text" class="input"  />
        <input type="submit" name="queryByDate" value="查询" class="button" />
	<!--div>查询日期：<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-08-28',maxDate:'%y-%M-%d'})" class="input" />
	<input type="submit" name="queryByDate" value="查询" class="button" /></div-->
</div>
<div>
	<table class="table" cellspacing="0" cellpadding="0">
		<tr><th>来电编号</th><th>来电号码</th><th>接听工号</th><th>设备</th><th>通话时长</th><th>呼入时间</th><th>结束时间</th>
		    <th>呼叫等待时长</th><th>问题大类</th><th>问题小类</th><th>通话质量</th><th>音质</th><th>客服记录</th></tr>
		<?php echo $list;?>
	</table>
	<div><?php echo $page; ?></div>
</div>

<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#type").val("<?php echo $type ?>");
		$("#uid").val("<?php echo $uid ?>");
	});	
</script>
</form>
</body>
</html>