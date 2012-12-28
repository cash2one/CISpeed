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
<form id="form1" method="post" action="/vipkf/user/savereason">
<div id="headarea" class="Corner">
    <select id="reason" name="reason"></select>
    <select id="childs" name="childs"></select><br/>
    通话质量：
    <select id="qos" name="qos">
	<option value="1">好</option>
	<option value="2">一般</option>
	<option value="3">差</option>
	<option value="4">无声</option>
    </select><br/>
    音质情况：
    <select id="qosdetail" name="qosdetail">
	<option value="1">有杂音</option>
	<option value="2">有断续</option>
	<option value="3">杂音断续都有</option>
	<option value="4">没有杂音断续</option>
    </select><br/>
    详情：<br/><textarea id="content" name="content" class="input" style="width:240px; height: 100px"></textarea><br/>
    <input type="submit" name="submit" value="保存" class="button" />
    <input type="hidden" name="callid" value="<?php echo $callid ?> " />
    <input type="hidden" name="uid" value="<?php echo $uid ?>" />
    <input type="hidden" name="token" value="<?php echo $token ?>" />
</div>
<script type="text/javascript">
        var reasonJson = <?php echo $reasonJson ?>;
	$(document).ready(function() {
		$("#headarea").corner();
		$.each(reasonJson.Reasons, function(index, value) { 
			var op = document.createElement("option");
			op.value = value.id; 	
			op.innerHTML = value.name;
			$("#reason").append(op);
		});
		changeReason($("#reason").val());
		
		$("#reason").change(function(){
			changeReason($(this).val());
		});
	});
	
	function changeReason(id){
		$("#childs").html("");
		$.each(reasonJson.Reasons, function(index, value) {
			if(value.id == id){
				$.each(value.children, function(i, v){					
					var cop = document.createElement("option");
					cop.value = v.id; 	
					cop.innerHTML = v.name;
					$("#childs").append(cop);
				});
			}
		});
	}
</script>
</form>
</body>
</html>