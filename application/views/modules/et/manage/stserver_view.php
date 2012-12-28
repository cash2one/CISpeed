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
<form id="form1" method="post" action="/et/manage/stserver">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />登录服务器管理</h1>
<div style="float: left">编号：<input class="input" name="serverid"
	id="serverid" type="text" /><br />
ip&nbsp;&nbsp;&nbsp;：<input class="input" name="ip" id="ip" type="text" /><br />
机房：<input class="input" name="address" id="address" type="text" /><br />
状态：<select id=stat name="stat">
	<option value="1">正常</option>
	<option value="0">停用</option>
</select></div>
<div style="clear: both"><input class="button" type="button"
	onclick="checkthis()" id="submitbtn" name="add" value="添加" /> <input
	class="button" type="button"
	onclick="window.location.href='/et/manage/stserver'"
	style="display: none" id="cancel" value="取消" /></div>
<input type="hidden" id="flag" name="flag" value="add"></input> <input
	type="hidden" id="id" name="id" value="0"></input> <input type="hidden"
	id="oldserverid" name="oldserverid" value="0"></input></div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>操作</th>
		<th>服务器编号</th>
		<th>IP</th>
		<th>机房</th>
		<th>状态</th>
		<th>最近更新时间</th>
	</tr>	
        <?php
								echo $list?>
</table>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		var isadd = <?php
		echo $isadd;
		?>;
		if(isadd == 0){
			$("#submitbtn").attr("name","add");
			$("#submitbtn").attr("value","添加");
			$("#flag").attr("value","add");
		}
		else{
			$("#submitbtn").attr("name","edit");
			$("#submitbtn").attr("value","编辑");
			$("#cancel").css("display","inline");
			$("#flag").attr("value","edit");
                        <?php
																								if ($server != "") {
																									?>                        
                            $("#oldserverid").attr("value","<?php
																									echo $server ["si_serverid"];
																									?>");
                            $("#id").attr("value","<?php
																									echo $server ["si_id"];
																									?>");    
                            $("#serverid").val('<?php
																									echo $server ["si_serverid"];
																									?>');
                            $("#ip").val('<?php
																									echo $server ["si_ip"];
																									?>');
                            $("#address").val('<?php
																									echo $server ["si_address"];
																									?>');
                            $("#stat").val('<?php
																									echo $server ["si_valid"];
																									?>');
                        <?php
																								}
																								?>
		}
                <?php
																echo $jsCode?>
	});	
	function checkthis()
	{
		if(!checkvalue("serverid","请填写id")){return;}	
		if(!checkvalue("ip","请填写ip")){return;}	
		if(!checkvalue("address","请填写机房")){return;}		
		$("#form1").submit();
	}
	function checkvalue(id,msg){
		if(!$('input[name="'+id+'"]').val()){
			alert(msg);
			$('input[name="'+id+'"]').focus();
			return false;
		}
		return true;
	}
</script></form>
</body>
</html>