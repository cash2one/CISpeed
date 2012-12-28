<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
<script src="/res/script/common.js" type="text/javascript"></script>
</head>

<body>
<form id="form1" method="post" action="/node/speednodepush">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />节点推送</h1>
地区<select name="arealist" id="arealist">
	<option value="-1">所有</option>
	    <?php
					echo $areaL?>
	</select> 网络<select name="netlist" id="netlist">
	<option value="-1">所有</option>
	<option value="0">电信</option>
	<option value="1">网通</option>
	<option value="2">其他</option>
</select> 游戏<select name="gamelist" id="gamelist">
	<option value="-1">所有</option>
	    <?php
					echo $gameL?>
	</select> <input type="submit" name="query" class="button" value="查询">
<input type="submit" name="generatenode" class="button" value="生成推送数据" />
<input type="submit" name="generatefile" class="button" value="生成节点文件" /></div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>用户所在地</th>
		<th>用户线路</th>
		<th>游戏</th>
		<th>游戏区服</th>
		<th>适用节点</th>
	</tr>	
        <?php
								echo $list?>
</table>
<?php
echo $page?>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#arealist").val("<?php
		echo $arealist?>");
		$("#netlist").val("<?php
		echo $netlist?>");
		$("#gamelist").val("<?php
		echo $gamelist?>");
	});
</script></form>
</body>
</html>