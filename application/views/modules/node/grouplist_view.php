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
<form id="form1" method="post" action="/node/grouplist">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />Group管理</h1>
<div>编号：<input class="input" name="id" id="id" type="text" /></div>
<div>名称：<input class="input" name="name" id="name" type="text" /></div>
<div>状态：<select id=stat name="stat">
	<option value="1">正常</option>
	<option value="0">停用</option>
</select></div>
<div class="c"><input class="button" type="button" onclick="checkthis()"
	id="submitbtn" name="add" value="添加" /> <input class="button"
	type="button" onclick="window.location.href='/node/grouplist'"
	style="display: none" id="cancel" value="取消" /></div>
<input type="hidden" id="flag" name="flag" value="add"></input> <input
	type="hidden" id="oldid" name="oldid" value="0"></input></div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>操作</th>
		<th>Group编号</th>
		<th>Group名称</th>
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
																								if ($group != "") {
																									?>
                            $("#oldid").attr("value","<?php
																									echo $group ["id"];
																									?>");
    
                            $("#id").val('<?php
																									echo $group ["id"];
																									?>');
                            $("#name").val('<?php
																									echo $group ["name"];
																									?>');
                            $("#stat").val('<?php
																									echo $group ["valid"];
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
		if(!checkvalue("id","请填写id")){return;}	
		if(!checkvalue("name","请填写名称")){return;}	
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