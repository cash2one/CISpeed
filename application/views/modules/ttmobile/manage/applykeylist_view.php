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
<form id="form1" method="post" action="/ttmobile/manage/applykeylist">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />手机通通用户申请激活码 <select
	id="sltAutoReload" onchange="setAutoReload(this.value);"
	name="sltAutoReload" title="自动刷新设置">
	<option value="0" selected="selected">关闭刷新</option>
	<option value="30">30秒</option>
	<option value="60">1分钟</option>
</select></h1>
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th width="30px">审核</th>
		<th>申请人</th>
		<th>申请时间</th>
		<th>申请手机号</th>
		<th>ip</th>
		<th>申请ID</th>
	</tr>
    <?php
				echo $list;
				?>
</table>
<div><input type="button" class="button" style="float: left"
	id="selectall" value="全选"> <input type="button" class="button"
	style="float: left" id="selectop" value="反选"> <input type="submit"
	name="submit" value="审批" class="button" style="float: left;" /> <input
	type="submit" name="del" value="删除" onclick="return confirm('确定删除?');"
	class="button" style="float: left;" />
    <?php
				echo $page;
				?>
</div>

<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
                $(function(){
                    $("#selectall").click(function() {
                        $("input[name='check[]']").each(function() {
                            $(this).attr("checked", true);
                        });
                    });
                    $("#selectop").click(function() {
                        $("input[name='check[]']").each(function() {
                            if($(this).attr("checked")){$(this).attr("checked", false);}
                            else{$(this).attr("checked", true);}
                        });
                    });
                });
		autoReload();
	});
	
	var autoReloadRel = null;

	function setAutoReload(defValue){ 
		var intvalue = parseInt(defValue); 
		if(0==intvalue){
			window.clearTimeout(autoReloadRel); 
		}
		else{
			autoReloadRel = window.setTimeout(function(){window.location.reload();}, intvalue*1000);
		}
		setCookie('autoRefresh', intvalue);
		$("#sltAutoReload").val(intvalue);
	}
	
	function autoReload(){
		if(getCookie("autoRefresh")!=''){
			var autoReloadVal = parseInt(getCookie("autoRefresh"));
			if(autoReloadVal>0){
				setAutoReload(autoReloadVal);
			}else{
				setAutoReload(0);
			}
		}
	}
</script></form>
</body>
</html>