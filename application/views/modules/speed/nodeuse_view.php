<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
<script src="/res/script/common.js" type="text/javascript"></script>
<script type="text/javascript"
	src="/res/script/My97DatePicker/WdatePicker.js"></script>
<style type="text/css">
#headarea {
	line-height: 30px;
}
</style>
</head>
<body>
<form id="form1" method="post" action="/speed/nodeuse">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />节点使用情况统计 <input
	type="button" onclick="window.location.href = location;" class="button"
	value="刷新" /></h1>
<div><input type="radio" name="type" value="searchByNode"
	id="nodeSearch" checked />节点名： <select id="node" name="node"><?php
	echo $eachNode?></select>
<input type="radio" name="type" value="searchByIP" id="ipSearch" />节点IP：
<select id="ip" name="ip"><?php
echo $eachIp?></select></div>
<div style="padding-left: 10px">选择日期：<input name="QueryDate1"
	id="QueryDate1" type="text" size="20"
	onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="input" />
时间点&nbsp;&nbsp;<select id="hour1" name="hour1"><?php
echo $hourSelect?></select>时
<select id="minute1" name="minute1"><?php
echo $minuteSelect?></select>分
</div>
<div style="padding-left: 205px"><input type="checkbox" name="compare"
	value="1" style="vertical-align: middle">对比</input> <select id="hour2"
	name="hour2"><?php
	echo $hourSelect?></select>时 <select id="minute2"
	name="minute2"><?php
	echo $minuteSelect?></select>分 <input
	type="button" value="查询" class="button" onclick="checkthis();" /></div>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>序号</th>
		<th>snda_id</th>
		<th>加速游戏</th>
	</tr>
        <?php
								echo $list?>
</table>

<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php
		echo $date;
		?>");
                <?php
																echo $jsCode?>
	});
	
	function checkthis()
	{
		if(!checkvalue("QueryDate1","请填写要查询的日期")){return;}
		$("#form1").submit();
	}
	function checkvalue(id,msg){
		if(!$('input[name="'+id+'"]').val()){
			alert(msg);
			return false;
		}
		return true;
	}
</script></form>
</body>
</html>