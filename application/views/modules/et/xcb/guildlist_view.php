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
<form id="form1" method="post" action="/et/xcb/guildlist">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />星辰变公会列表</h1>
<table>
	<tr>
		<td>公会名称：</td>
		<td><input name="name" type="text" class="input" /></td>
	</tr>
	<tr>
		<td>公会频道ID：</td>
		<td><input name="queryId" type="text" class="input" /></td>
	</tr>
	<tr>
		<td>公会所属大区：</td>
		<td><input name="area" type="text" class="input" /></td>
	</tr>
	<tr>
		<td>公会所属服务器：</td>
		<td><input name="server" type="text" class="input" /></td>
	</tr>
	<tr>
		<td>排列等级：</td>
		<td><input name="rank" type="text" class="input" /></td>
	</tr>
</table>
<input type="button" value="添加" class="button" onclick="checkthis();" />
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>操作</th>
		<th>排列等级</th>
		<th>公会名称</th>
		<th>公会频道ID</th>
		<th>公会所属大区</th>
		<th>公会所属服务器</th>
	</tr>   
    <?php
				echo $list;
				?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
	    $("#headarea").corner();
        
	});
	function checkDel(id,name)
	{
	    if(confirm("确定要删除——"+name+"?"))
	      window.location.href="/et/xcb/guilddel?gid="+id;
	    else
	      return; 
	}
	function checkthis()
        {
            if(!checkvalue("name","请填写公会名称")){return;}  
            if(!checkvalue("queryId","请填写公会频道ID")){return;}  
            if(!checkvalue("area","请填写公会所属大区")){return;}  
            if(!checkvalue("server","请填写公会所属服务器")){return;}  
            if(!checkvalue("rank","请填写排列等级")){return;}
	    if(!checkNum($('input[name="queryId"]').val())){alert("公会频道ID只能填写数字");return;}
	    if(!checkNum($('input[name="rank"]').val())){alert("排列等级只能填写数字");return;}
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