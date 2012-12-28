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
<form id="form1" method="post" action="/et/manage/faqlist">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />FAQ列表</h1>
<table>
	<tr>
		<td>类型：</td>
		<td><select name="type" id="type" class="select">
			<option value="1">普通</option>
		</select></td>
	</tr>
	<tr>
		<td>标题：</td>
		<td><input id="title" name="title" type="text" class="input"
			style="width: 300px;" /></td>
	</tr>
	<tr>
		<td>内容：</td>
		<td><textarea id="content" name="content" class="input"
			style="width: 300px; height: 100px"></textarea></td>
	</tr>
	<tr>
		<td>显示：</td>
		<td><select name="list" id="list" class="select">
			<option value="0">否</option>
			<option value="1">是</option>
		</select></td>
	</tr>
</table>
<input type="button" value="添加" id="submitbtn" class="button"
	onclick="checkthis();" /> <input class="button" type="button"
	onclick="window.location.href='/et/manage/faqlist'"
	style="display: none" id="cancel" value="取消" /> <input type="hidden"
	id="id" name="id" value="0"></input> <input type="hidden" id="flag"
	name="flag" value="add"></input></div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>操作</th>
		<th>显示</th>
		<th>标题</th>
		<th>内容</th>
	</tr>   
    <?php
				echo $list;
				?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
	    $("#headarea").corner();
	    var isadd = <?php
					echo $isadd;
					?>;
	    if(isadd == 0){
		$("#submitbtn").attr("name","add");
		$("#submitbtn").attr("value","添加");
		$("#flag").attr("value","add");
	    }
	    else{
		$("#submitbtn").attr("name","edit");
		$("#submitbtn").attr("value","编辑");
		$("#cancel").css("display","inline");
		$("#flag").attr("value","edit");
                <?php
																if ($faq != "") {
																	?>    
                    $("#id").val('<?php
																	echo $faq ["id"];
																	?>');
                    $("#type").val('<?php
																	echo $faq ["type"];
																	?>');
                    $("#title").val('<?php
																	echo $faq ["title"];
																	?>');
                    $("#content").val('<?php
																	echo $faq ["content"];
																	?>'.replace(/<br\/>/img,"\n"));
                    $("#list").val('<?php
																	echo $faq ["list"];
																	?>');
                <?php
																}
																?>
		}
	});
	function checkDel(id,name)
	{
	    if(confirm("确定要删除——"+name+"?"))
	      window.location.href="/et/manage/faqdel?fid="+id;
	    else
	      return; 
	}
	function checkthis()
        {
            if(!checkvalue("title","请填写title")){return;}
            $("#content").val($("#content").val().replace(/\n/img,"<br/>"));
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