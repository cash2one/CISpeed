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
    <style type="text/css">
    td {
	    line-height: 22px;
    }
    </style>
</head>

<body>
    <form id="form1" method="post" action="/speed/data">
    <div id="headarea" class="Corner" style="width: 2200px">
    <h1><img src="/res/images/admin/icon/11.gif" alt="" />统计</h1>
    </div>
    <table class="table" cellspacing="0" cellpadding="0"
	    style="width: 2200px">
	    <tr>
		    <th>日期</th>
		    <!--th>新增用户数</th>
	    <th>新增用户独立IP数</th>
	    <th>登录独立IP数</th>
	    <th>登录独立账号数</th>
	    <th>平均在线</th>
	    <th>在线峰值人数</th-->
		    <!--th>点击加速总数</th>
	    <th>点击停止总数</th>
	    <th>点击加速独立IP数</th>
	    <th>点击加速独立账号数</th>
	    <!--th>登录点击加速率</th-->
		    <th>加速独立IP数</th>
		    <th>加速独立账号数</th>
		    <th>加速峰值人数</th>
		    <!--th>加速成功率</th-->
		    <th>流量(上行/下行)</th>
		    <th>0-3点流量(上行/下行)</th>
		    <th>3-9点流量(上行/下行)</th>
		    <th>9-15点流量(上行/下行)</th>
		    <th>15-19点流量(上行/下行)</th>
		    <th>19-24点流量(上行/下行)</th>
	    </tr>
	<?php echo $list; ?>
    </table>
    <script type="text/javascript">
	    $(document).ready(function() {
		    $("#headarea").corner();
	    });
    </script>
    </form>
</body>
</html>