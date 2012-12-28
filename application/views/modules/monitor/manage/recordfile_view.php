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
    <form id="form1" method="post" action="/monitor/manage/recordfile">
    <div id="headarea" class="Corner">
        <h1><img src="/res/images/admin/icon/11.gif" alt="" />历史录音</h1>
        <a href="javascript:history.go(-1)">返回</a>
    </div>    
    <table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>序号</th>
		<th>手机</th>
		<th>呼叫id</th>
		<th>开始时间</th>
		<th>结束时间</th>
		<th>录音</th>
	</tr>
        <?php echo $list;?>
    </table>
    <?php echo $page; ?>
    <script type="text/javascript">
            $(document).ready(function() {
                $("#headarea").corner();
                <?php echo $jstips ?>
            });            
    </script>
    </form>
</body>
</html>