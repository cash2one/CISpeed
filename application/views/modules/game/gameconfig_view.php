<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
<script src="/res/script/common.js" type="text/javascript"></script>
<style type="text/css">
.fl {
	float: left
}

.fl26 {
	display: none;
	margin-left: 26px
}

.fl15 {
	display: none;
	margin-left: 15px
}

.fl130 {
	display: none;
	margin-left: 130px
}

.clear {
	clear: both
}
</style>
</head>
<body>
<form id="form1" method="post" action="/game/gameconfig">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />游戏配置 <a
	href="/game/gameconfigoutput?type=game" target="_blank">导出游戏</a>|<a
	href="/game/gameconfigoutput?type=server" target="_blank">导出区服</a></h1>
<div class="fl">
<div>游戏编号<input type="input" name="gameid" class="input" readonly
	value="<?php
	echo $gameid?>" /> 游戏排序<input type="input"
	name="gameorder" class="input" value="<?php
	echo $gameorder?>" /><br />
游戏名称<input type="input" name="gamename" class="input"
	value="<?php
	echo $gamename?>" /> 搜索信息<input type="input"
	name="findkey" class="input" value="<?php
	echo $findkey?>" /><br />
频道编号<input type="input" name="hallid" class="input"
	value="<?php
	echo $hallid?>" /> 频道名称<input type="input"
	name="hallname" class="input" value="<?php
	echo $hallname?>" /><br />
优选模式<input type="input" name="mod" class="input"
	value="<?php
	echo $mod?>" /> 游戏启动<select id="gamestart"
	name="gamestart">
	<option value="0">不启动</option>
	<option value="1">启动</option>
</select><br />
游戏状态<select id="gameuse" name="gameuse">
	<option value="0">未使用</option>
	<option value="1">使用中</option>
</select></div>
<div><input type="submit" class="button" name="addsubmit" id="addsubmit"
	value="添加"> <input type="submit" class="button" name="editsubmit"
	id="editsubmit" value="修改" style="display: none"> <input class="button"
	type="button" onclick="window.location.href='/game/gameconfig'"
	id="cancel" value="取消" /></div>
</div>
<div id="serverInfo" class="fl fl15">
<div>区服编号<input type="input" name="serverid" class="input" readonly
	value="<?php
	echo $serverid?>" /><br />
区服名称<input type="input" name="servername" class="input"
	value="<?php
	echo $servername?>" /><br />
区服线路<input type="input" name="serverline" class="input"
	value="<?php
	echo $serverline?>" /><br />
区服状态<select id="serveruse" name="serveruse">
	<option value="0">未使用</option>
	<option value="1">使用中</option>
</select></div>
<div><input type="submit" class="button" name="saddsubmit"
	id="saddsubmit" value="添加"> <input type="submit" class="button"
	name="seditsubmit" id="seditsubmit" value="修改" style="display: none"> <input
	class="button" type="button"
	onclick="window.location.href='/game/gameconfig?id=<?php
	echo $gameid?>'"
	id="cancel" value="取消" /></div>
</div>
<div class="fl fl15" id="proInfo">
<div>进程<input type="input" class="input" id="proname" name="proname"
	onclick="$('#profile').click()" /> <input type="submit" class="button"
	name="paddsubmit" id="saddsubmit" value="添加"> <input type="file"
	id="profile" onchange="getFileName()" style="display: none" /></div>
<div id="proList" class="fl">
<table class="table2" cellspacing="0" cellpadding="0">
		    <?php
						echo $proList?>
		</table>
</div>
</div>
<div class="clear"></div>
</div>
<div class="fl">
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>操作</th>
		<th>排序</th>
		<th>游戏名称</th>
		<th>游戏启动</th>
		<th>优选模式</th>
		<th>当前状态</th>
	</tr>
        <?php
								echo $list?>
    </table>
</div>
<div id="serverList" class="fl fl26">
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>操作</th>
		<th>编号</th>
		<th>名称</th>
		<th>线路</th>
		<th>当前状态</th>
	</tr>
        <?php
								echo $serverList?>
    </table>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
		$("#gameuse").val("<?php
		echo $gameuse?>");
		$("#gamestart").val("<?php
		echo $gamestart?>");
		$("#serveruse").val("<?php
		echo $serveruse?>");
		<?php
		echo $jsCode;
		?>
	});
	
	var gameConfigDel = function(id){
	    if(confirm("确认删除？"))
	    {
		window.location.href = "/game/gameconfigdel?id="+id;
	    }
	    else
	    {
		return false;
	    }
	};	
	var gameServerDel = function(id,sid){
	    if(confirm("确认删除？"))
	    {
		window.location.href = "/game/gameconfigdel?id="+id+"&sid="+sid;
	    }
	    else
	    {
		return false;
	    }
	};
	var gameProDel = function(id,pid){
	    if(confirm("确认删除？"))
	    {
		window.location.href = "/game/gameconfigdel?id="+id+"&pid="+pid;
	    }
	    else
	    {
		return false;
	    }
	};
	
	var getFileName = function(){
	    var temp = $("#profile").val().split("\\");
	    $('#proname').val(temp[temp.length-1]);
	}
</script></form>
</body>
</html>