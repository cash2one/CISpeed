<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
	<script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
	<script src="/res/script/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/res/script/My97DatePicker/WdatePicker.js"></script>
</head>

<body>
<form id="form1" method="post" action="/vipkf/user/customdata">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />��ϯ״̬��¼</h1>
	<div>��ѯ���ڣ�<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-12-21',maxDate:'%y-%M-#{%d-1}'})" class="input" />
	��ϯ�ţ�<input name="cid" id="cid" type="text" class="input" />
        <input type="submit" name="submit" value="��ѯ" class="button" />
        </div>
</div>
<div>
	<table class="table" cellspacing="0" cellpadding="0">
		<tr><th>��ϯ��</th><th>ǩ��ʱ��</th><th>ǩ��ʱ��</th><th>״̬</th><th>����</th><th>ʱ��</th></tr>
		<?php echo $list;?>
	</table>
</div>

<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php echo $date?>");
	});	
</script>
</form>
</body>
</html>