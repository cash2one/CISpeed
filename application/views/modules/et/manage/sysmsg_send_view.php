<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
	<script src="/res/script/jquery.js" type="text/javascript"></script>
	<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
	<script src="/res/script/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/res/script/My97DatePicker/WdatePicker.js"></script>
</head>

<body>
	<form id="form1" method="post" action="/et/manage/changechannelserver">
	<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />发布系统消息</h1>
	<div>系统消息种类 <select class="select"></select> 消息类型 <select class="select"></select></div>
	<div>接收对象 <input type="radio" name="txt" value="1" checked="checked" />所有用户
	<input type="radio" name="txt" value="2" />指定节点
	<input type="radio" name="txt" value="3" />指定游戏 <input type="radio" name="txt" value="4" />指定ID用户(多用户用分号隔开)
	</div>
	<div>发布形式 <input type="radio" name="kind" value="1" checked="checked" />系统托盘闪烁
		      <input type="radio" name="kind" value="2" />右下角自动浮起信息框
		      <input type="radio" name="kind" value="3" />语音频道系统广播
	</div>
	<div>开始日期 <input type="text" size="20" style="width: 150px"
		onFocus="WdatePicker({minDate:'%y-%M-%d %H:#{%m+1}:00',dateFmt:'yyyy-MM-dd HH:mm:00'})"
		class="input"> 失效时间 <input type="text" value="1">小时 </div>
	<div>消息标题 <input type="text" class="input" style="width: 400px"></div>
	<div><span style="float: left">消息内容</span> <span
		style="margin-left: 4px"><textarea class="input"
		style="width: 400px; height: 100px"></textarea></span></div>
	<div>链接地址 <input type="text" class="input" style="width: 400px"></div>
	<input type="button" value="提交" class="button" onclick="checkthis();" />
	</div>
	
	<script type="text/javascript">
		$(document).ready(function() {
		    $("#headarea").corner();
		});
		
		function checkthis()
		{
		    if(!checkvalue("cid","请填写频道ID")){return;}
		    if($("#tsserver").val() == 0)
		    {
			alert("请选择要转移的服务器");
			return;
		    }
		    $("#form1").submit();
		}
		function checkvalue(id,msg){
			if(!$('input[name="'+id+'"]').val()){
				alert(msg);
				return false;
			}
			return true;
		}
	</script>
	</form>
</body>
</html>