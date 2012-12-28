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
<form id="form1" method="post" action="/game/gameallnode">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />游戏在各节点中的加速情况 <input
	type="button" onclick="window.location.href = location;" class="button"
	value="刷新" /></h1>
<div>游戏名<select id="game" name="game"><?php
echo $gameList?></select></div>
<div>时间<input type="radio" name="type" value="searchByDate"
	id="dateSearch" checked />日期点： <input name="QueryDate1" id="QueryDate1"
	type="text" size="20"
	onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'%y-%M-#{%d-1}'})"
	class="input" />&nbsp;&nbsp; <input type="checkbox" name="time"
	value="1" style="vertical-align: middle">时间点:</input>&nbsp;&nbsp; <select
	id="hour1" name="hour1"><?php
	echo $hour1?></select>时 <select
	id="minute1" name="minute1"><?php
	echo $minute1?></select>分</div>
<div style="padding-left: 24px"><input type="radio" name="type"
	value="searchByPeriod" id="periodSearch" />日期：&nbsp;&nbsp;&nbsp; <input
	name="QueryDate2" id="QueryDate2" type="text" size="20"
	onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-04-24',maxDate:'%y-%M-#{%d-1}'})"
	class="input" /> &nbsp;&nbsp;至&nbsp;&nbsp; <input name="QueryDate3"
	id="QueryDate3" type="text" size="20"
	onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-04-24',maxDate:'%y-%M-#{%d-1}'})"
	class="input" />&nbsp;&nbsp;</div>
<div><input type="button" value="查询" class="button"
	onclick='submitSearch()' /></div>

</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>节点名称</th>
		<th>节点IP</th>
		<th>人数</th>
		<th>人次</th>
		<th>比率</th>
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

	function submitSearch()
	{
		/*if($('#valueBox').html() == "")
		{
			alert("请选择查询内容");
			return;
		}*/
		$("#form1").submit();
	}
</script></form>
</body>
</html>