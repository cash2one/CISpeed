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
<style type="text/css">
#headarea td {
	line-height: 30px;
	vertical-align: top
}

#headarea td select {
	margin-top: 5px
}

.r {
	float: right;
}

.selectType {
	height: 120px;
	border: 1px solid gray;
	padding: 2px 5px
}

.box {
	width: 150px;
	height: 120px;
	border: 1px solid black;
	padding: 2px 5px;
	line-height: 18px;
	background-color: white;
	overflow-y: scroll
}
</style>
</head>

<body>
<form id="form1" method="post" action="/game/gamedata">
<div id="headarea" class="Corner">
<h1><img src="/res/images/admin/icon/9.gif" alt="" />游戏加速数据 <input
	type="button" onclick="window.location.href = location;" class="button"
	value="刷新" /></h1>
<table>
	<tr>
		<td>
		<div class="selectType"><input type="radio" name="type"
			value="searchByNode" id="nodeSearch"
			onchange="clearBox();disableBtn('ipType')" checked />节点： <select
			id="node" name="node">
			<option value="0">全部</option><?php
			echo $nodeList?></select> <input
			type="button" class="button" value="添加" id="nodeType"
			onclick="addRecordToList(1);" /><br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：<input
			name="QueryDate1" id="QueryDate1" type="text" size="20"
			onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'%y-%M-#{%d-1}'})"
			class="input" /> <input type="button" value="查询" class="button"
			onclick='submitSearch()' /> <!--<input type="radio" name="type" disabled="true" value="searchByIp" id="ipSearch" onchange="clearBox();disableBtn('nodeType')"/>IP：
			<select id="ip" name="ip"><option value="0">全部</option><--?php echo $ipList ?></select><br />
                        <input type="button" class="button r"  id="ipType" style="background-color:gray" value="添加" onclick="addRecordToList(2);" />-->
		</div>
		</td>
		<td>
		<div class="box" id="valueBox"></div>
		<input type="button" class="button r" onclick="clearBox()" value="清空" />
		</td>
		<td></td>
	</tr>
</table>
<input type="hidden" name="nodeValue" id="nodeValue" value="0," /> <input
	type="hidden" name="ipValue" id="ipValue" value="0," /></div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>游戏名称</th>
		<th>人数</th>
		<th>人次</th>
		<th>比率</th>
	</tr>
        <?php
								echo $list?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$("#headarea").corner();
		$("#QueryDate1").val("<?php
		echo $date;
		?>");
		document.getElementById("ipType").disabled = true;
		document.getElementById("valueBox").appendChild(createValueDiv("0","全部",document.getElementById("nodeValue")));
                <?php
																echo $jsCode?>
                
		var searchType = "<?php
		echo $searchType?>";
		$("#nodeValue").val("<?php
		echo $nodeValue?>");
		$("#ipValue").val("<?php
		echo $ipValue?>");
		var postList = "<?php
		echo $postList;
		?>";
		if(postList)
		{
			var obj,postValueObj;
			document.getElementById("valueBox").innerHTML = "";
			if(searchType == "searchByNode")
			{
				obj = document.getElementById("node");
				postValueObj = document.getElementById("nodeValue");
			}
			else
			{
				obj = document.getElementById("ip");
				postValueObj = document.getElementById("ipValue");
			}
			var temp = postList.split(",");
			for(var i=0;i<temp.length;i++)
			{
				for(var j=0;j<obj.options.length;j++)
				{
					if(temp[i] == obj.options[j].value)
					{
						document.getElementById("valueBox").appendChild(createValueDiv(obj.options[j].value,obj.options[j].text,postValueObj));
					}
				}
			}
		}
	});	
	function createValueDiv(value,text,postValueObj)
	{
		var div = document.createElement("div");
		div.innerHTML = text;
		div.setAttribute("val",value);
		div.setAttribute("title","双击移除");
		div.style.cursor = "pointer";		
		div.ondblclick = function(){
			postValueObj.value = postValueObj.value.replace(value+",","");
			document.getElementById("valueBox").removeChild(this);
		}; 

		return div;
	}
	function addRecordToList(type){
		var obj,value,text,post;
		if(type == 1)
		{
			obj = document.getElementById("node");
			post = document.getElementById("nodeValue");
		}
		if(type == 2)
		{
			obj = document.getElementById("ip");
			post = document.getElementById("ipValue");
		}
		
		value = obj.value;
		text = obj.options[obj.selectedIndex].text;
		if(value == "0"||post.value == "0," )
		{
			post.value = "";
			document.getElementById("valueBox").innerHTML = "";
		}
		var list = document.getElementById("valueBox").childNodes;
		
		for(var i=0;i<list.length;i++)
		{
			if(list.item(i).innerHTML == text)
			{
				return;
			}
		}
		var div = createValueDiv(value,text,post);
		
		document.getElementById("valueBox").appendChild(div);
		post.value += value+",";
	}
	function clearBox()
	{
		document.getElementById("ipValue").value = "";
		document.getElementById("nodeValue").value = "";
		document.getElementById("valueBox").innerHTML = "";
	}
	function disableBtn(obj)
	{
		if(obj == "nodeType")
		{
			document.getElementById("nodeType").disabled = true;
			document.getElementById("ipType").disabled = false;
			document.getElementById("nodeType").style.backgroundColor = "gray"; 
			document.getElementById("ipType").style.backgroundColor = ""; 
		}
		else
		{
			document.getElementById("nodeType").disabled = false;
			document.getElementById("ipType").disabled = true;
			document.getElementById("ipType").style.backgroundColor = "gray"; 
			document.getElementById("nodeType").style.backgroundColor = ""; 
		}
	}
	function submitSearch()
	{
		if($('#valueBox').html() == "")
		{
			alert("请选择查询内容");
			return;
		}
		$("#form1").submit();
	}
	
</script></form>
</body>
</html>