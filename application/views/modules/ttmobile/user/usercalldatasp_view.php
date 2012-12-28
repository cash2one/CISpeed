<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css?v=20110919" />
	<link type="text/css" rel="stylesheet" href="/res/css/tinybox.css" />
        <style type="text/css">
            #headarea div{line-height:28px}
	    #morePeople{position:absolute;display:none}
	    .table a{padding-right:50px}
        </style>        
</head>

<body>
<form id="form1" method="post" action="/ttmobile/user/calldatabysp">
<div id="headarea" class="Corner">
	<h1><img src="/res/images/admin/icon/9.gif" alt="" />通话记录数据
	    <input type="submit" name="queryByDate" value="刷新" class="button" />
	</h1>
	<div>渠道：<select name="sp" id="sp">
			<?php echo $spselect; ?>
		   </select>
		<input name="query" type="submit" value="查询" class="button" />
	</div>
	用户通话数据不包含客服主叫和客服被叫
</div>
<table class="table" cellspacing="0" cellpadding="0">
	<tr><th>日期</th><th>渠道人数</th><th>总通话人次</th><th>总通话人数</th><th>用户通话人次</th><th>用户通话人数</th><th>用户接通量</th><th>用户接通率</th>
	    <th>3-20秒通话量</th><th>20秒以上通话量</th><th>2人通话量</th><th>3人通话量</th><th>4人通话量</th><th>5人通话量</th></tr>
	<tr><td>今天当前</td><td></td>
		<td><?php echo $total ?></td>
		<td><?php echo $totald ?></td>
		<td><?php echo $totalnokf ?></td>
		<td><?php echo $totalnokfd ?></td>
		<td><?php echo $successcall ?></td>
		<td><?php echo ($totalnokf==0)?"":(round($successcall/$totalnokf,4)*100)."%" ?></td>
		<td><?php echo $below20 ?></td>
		<td><?php echo $above20 ?></td>
		<?php
			$p2 = 0;
			$p3 = 0;
			$p4 = 0;
			$p5 = 0;
			foreach($morePeople as $m)
			{
				$a = "p".($m["p"]+1);
				$$a = $m["c"];
			}
			$str = "<td>$p2</td><td>$p3</td><td>$p4</td><td>$p5</td>";
			echo $str;
		?>
	</tr>	
	<?php echo $list;?>
</table>
<div id="morePeople"></div>
<?php echo $scriptjs?>
<script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#sp").val(<?php echo $sp ?>);		
	});
</script>
</form>
</body>
</html>