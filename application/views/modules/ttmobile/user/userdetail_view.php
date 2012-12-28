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
<form id="form1" method="post" action="/ttmobile/user/userdetail">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />用户资料查询</h1>	
	<select id="querytype" name="querytype">
		<option value="fuserid">手机号码</option>
		<option value="fmachinecode" >机器码</option>
	</select>:
	<input name="mobile" class="input" id="mobile" type="text" /><?php echo $online;?>
        <!--input type="submit" name="query" value="查询" class="button" /--> <br/>
        开始日期:<input name="QueryDate1" id="QueryDate1" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-08-28',maxDate:'%y-%M-%d'})" class="input" />
        截止日期:<input name="QueryDate2" id="QueryDate2" type="text" size="20" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2011-08-28',maxDate:'%y-%M-%d'})" class="input" />
	<input type="submit" name="queryLogin"  value="查询上线记录" class="button" />
	<div><?php echo $regInfo?></div>
	<!--input type="submit" name="queryCall"  value="查询通话记录" class="button" /-->
</div>

<?php echo $list;?>
<div><?php echo $page; ?></div>

<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#mobile").val("<?php echo $mobile?>");
		$("#QueryDate1").val("<?php echo $queryDate1?>");
		$("#QueryDate2").val("<?php echo $queryDate2?>");
		$("#querytype").val("<?php echo $querytype ?>");
                /*$(":submit").bind("click",function(){
                    if(checkmobile($("#mobile").val()))
                    {
                        return true;
                    }
                    else
                    {
                        alert("请填写正确的手机号");
                        return false;
                    }
                });*/
	});	
</script>
</form>
</body>
</html>