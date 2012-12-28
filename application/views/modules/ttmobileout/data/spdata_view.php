<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css?v=20110919" />
        <script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
	<script type="text/javascript" src="/res/script/My97DatePicker/WdatePicker.js"></script>
</head>

<body>
<form id="form1" method="post" action="/ttmobileout/data/spdata">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />渠道数据(<?php echo $name ?>)
	    <input type="submit" name="queryByDate" value="刷新" class="button" />
	</h1>
	开始日期:<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-10-20',maxDate:'%y-%M-#{%d-1}'})" class="input" />
	结束日期:<input name="QueryDate2" id="QueryDate2" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-10-20',maxDate:'%y-%M-#{%d-1}'})" class="input" />
	<div>
		渠道编号:<?php echo $spcheck ?>
		<script>
		var flags =  '<?php echo $spflag?>'.split(",");
		$(":checkbox").each(function(){	
			for(var i = 0;i<flags.length;i++)
			{
				if($(this).attr("value")===flags[i])
				{
					$(this).attr("checked",true);
				}
			}
		});
		</script>
	</div>
	<div><input type="submit" name="query" value="查询" class="button" /></div>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr><th>日期</th><th>渠道编号</th><th>渠道名称</th><th>渠道IMEI数</th></tr>	
	<?php echo $list;?>
</table>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php echo $QueryDate1 ?>");
		$("#QueryDate2").val("<?php echo $QueryDate2 ?>");	
	});
</script>
</form>
</body>
</html>