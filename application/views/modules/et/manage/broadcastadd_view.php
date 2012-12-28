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
<style>
#headarea div {
	margin-right: 5px;
}
</style>
</head>

<body>
<form id="form1" method="post" action="/et/manage/broadcastadd">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />发布广播</h1>
<div>发送时间 <input type="text" name="time" size="20" style="width: 150px"
	onFocus="WdatePicker({minDate:'%y-%M-%d %H:#{%m+1}:00',dateFmt:'yyyy-MM-dd HH:mm:00'})"
	class="input" /></div>
<div>发送次数 <input type="text" name="count" class="input" value="1" /></div>
<div>时间间隔 <input type="text" name="span" class="input" />分钟</div>
<div>消息类型 <select name="type">
	<option value="3">官方公告</option>
	<option value="4">活动公告</option>
</select></div>
<div>消息标题 <input type="text" name="title" class="input"
	style="width: 400px" /></div>
<div><span style="float: left">消息内容</span> <span
	style="margin-left: 4px"><textarea class="input" name="content"
	style="width: 400px; height: 100px"></textarea></span></div>
<input type="button" value="提交" class="button" onclick="checkthis();" />
</div>
<div>当前有效消息
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>标题</th>
		<th>内容</th>
		<th>类型</th>
		<th>发起时间</th>
		<th>发送次数</th>
		<th>发送间隔</th>
		<th>创建者</th>
	</tr>   
	<?php
	echo $recentMsg;
	?>
    </table>
</div>
<div>历史消息
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>标题</th>
		<th>内容</th>
		<th>类型</th>
		<th>发起时间</th>
		<th>发送次数</th>
		<th>发送间隔</th>
		<th>创建者</th>
	</tr>   
	<?php
	echo $historyMsg;
	?>
    </table>
</div>

<script type="text/javascript">
	$(document).ready(function() {
	    $("#headarea").corner();
            <?php
												echo $jsCode;
												?>
	});
	
	function checkthis()
        {
            if(!checkvalue("time","请填写发送时间")){return;}
            if(!checkvalue("count","请填写发送次数")){return;}
            if(!checkvalue("span","请填写时间间隔")){return;}
            if(!checkvalue("title","请填写消息标题")){return;}

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