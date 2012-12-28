<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css?v=20110919" />
	<script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
	<script src="/res/script/common.js?v=20110818" type="text/javascript"></script>
	<script type="text/javascript" src="/res/script/My97DatePicker/WdatePicker.js"></script>
</head>

<body>
<form id="form1" method="post" action="/ttmobile/user/userregfail">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />用户登录失败</h1>	
        日期:<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-11-16',maxDate:'%y-%M-#{%d-1}'})" class="input" />
        <input type="submit" name="queryDetail"  value="查询详情" class="button" />
        <input type="submit" name="queryEtype"  value="按错误类型查询" class="button" />
        <input type="submit" name="queryDev"  value="按机器型号查询" class="button" />
        <input type="submit" name="querySource"  value="按来源查询" class="button" />
        <input type="submit" name="queryNullImsi"  value="按空imsi查询" class="button" />	
</div>

<?php echo $list;?>
<div><?php echo $page; ?></div>

<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php echo $queryDate1?>");		
	});	
</script>
</form>
</body>
</html>