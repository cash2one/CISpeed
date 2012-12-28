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
    <form id="form1" method="post" action="/monitor/manage/managemonitor">
    <div id="headarea" class="Corner">
        <h1><img src="/res/images/admin/icon/11.gif" alt="" />监控列表</h1>
        <div style="margin-top:10px">
            <table>
                <tr style='display:<?php echo $adddisplay ?>'><td>监控者ID：</td><td><input type="text" class="input" id="manageid" name="manageid" style="width:300px" /></td></tr>
                <tr style='display:inline'><td>监控手机：</td><td><input type="text" class="input" id="phone" name="phone" style="width:300px" /></td></tr>
                <tr style='display:inline'><td>通通短号：</td><td><input type="text" class="input" id="imid" name="imid" style="width:300px" value="0"/></td></tr>
            </table>
        </div>       
        <div>
            <input type="submit" class="button" value="添加" name="add" />
        </div>
    </div>    
    <table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>操作</th>
		<th>序号</th>
		<th>监控者</th>
		<th>手机</th>
	</tr >
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