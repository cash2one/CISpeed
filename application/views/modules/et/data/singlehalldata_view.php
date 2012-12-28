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
</head>

<body>
<form id="form1" method="post" action="/et/data/singlehalldata">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />单频道数据统计 <input
	type="button" onclick="window.location.href = location;" class="button"
	value="刷新" /></h1>
频道号:<input name="hallid" id="hallid" type="text" /> 选择日期:<input
	name="QueryDate1" id="QueryDate1" type="text" size="20"
	onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="input" /> 至 <input
	name="QueryDate2" id="QueryDate2" type="text" size="20"
	onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="input" /> <input
	type="button" value="查询" class="button" onclick="checkthis();" /></div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>日期</th>
		<th>平均在线</th>
		<th>峰值在线</th>
		<th>进入独立IP</th>
	</tr>
        <?php
								echo $list?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
                $("#hallid").val("<?php
																echo $hallid;
																?>");
                $("#QueryDate1").val("<?php
																echo $date1;
																?>");
                $("#QueryDate2").val("<?php
																echo $date2;
																?>");
                
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