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
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />�����¼</h1>
        <select name="type" id="type">
            <option value="1">�û��绰</option>
            <option value="2">��ϯ����</option>
        </select>
        <input name="uid" id="uid" type="text" class="input"  />
        <input type="submit" name="queryByDate" value="��ѯ" class="button" />
	<!--div>��ѯ���ڣ�<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-08-28',maxDate:'%y-%M-%d'})" class="input" />
	<input type="submit" name="queryByDate" value="��ѯ" class="button" /></div-->
</div>
<div>
	<table class="table" cellspacing="0" cellpadding="0">
		<tr><th>������</th><th>�������</th><th>��������</th><th>�豸</th><th>ͨ��ʱ��</th><th>����ʱ��</th><th>����ʱ��</th>
		    <th>���еȴ�ʱ��</th><th>�������</th><th>����С��</th><th>ͨ������</th><th>����</th><th>�ͷ���¼</th></tr>
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