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
<form id="form1" method="post" action="/et/manage/changechannelserver">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />更换频道服务器</h1>
<table>
	<tr>
		<td>频道ID：</td>
		<td><input id="cid" name="cid" type="text" class="input" /></td>
		<td><input type="button" value="查询服务器" class="button"
			onclick="queryServer()" /></td>
	</tr>
	<tr>
		<td>当前的服务器：</td>
		<td colspan="2"><?php
		echo $server?></td>
	</tr>
	<tr>
		<td>转移到服务器：</td>
		<td colspan="2"><select name="tsserver" id="tsserver">
			<option value="0">[请选择]</option>
			<?php
			echo $serverList?>
		    </select></td>
	</tr>
</table>
<input type="button" value="提交" class="button" onclick="checkthis();" />
</div>

<script type="text/javascript">
	$(document).ready(function() {
	    $("#headarea").corner();
	    $("#cid").val("<?php echo $cid;?>");
            <?php echo $jsCode?>
	});
	function queryServer()
	{
	    var cid = $("#cid").val();
	    window.location.href = "/et/manage/changechannelserver"+"?cid="+cid;
	}
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
</script></form>
</body>
</html>