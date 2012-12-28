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
<form id="form1" method="post" action="/et/manage/changechannelid">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />更换频道ID</h1>
<table>
	<tr>
		<td>原频道ID：</td>
		<td><input id="old" name="old" type="text" class="input" /></td>
	</tr>
	<tr>
		<td>新频道ID：</td>
		<td><input id="new" name="new" type="text" class="input" /></td>
	</tr>
</table>
<input type="button" value="提交" class="button" onclick="checkthis();" />
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>时间</th>
		<th>操作人</th>
		<th>内容</th>
	</tr>   
    <?php
				echo $list;
				?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
	    $("#headarea").corner();
            <?php
												echo $jsCode?>
	});	
	function checkthis()
        {
            if(!checkvalue("old","请填写原频道ID")){return;}
            if(!checkvalue("new","请填写新频道ID")){return;}
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