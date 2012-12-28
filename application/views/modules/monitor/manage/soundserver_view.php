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
    <form id="form1" method="post" action="/monitor/manage/soundserver">
    <div id="headarea" class="Corner">
        <h1><img src="/res/images/admin/icon/11.gif" alt="" />语音服务器</h1>
        <div></div>
        <div style="margin-top:10px">
            <table>
                <tr><td>语音服务器ID：</td><td><input type="text" class="input" id="serverid" name="serverid" style="width:300px" /></td></tr>
                <tr><td>密码：</td><td><input type="text" class="input" id="pwd" name="pwd" style="width:300px" /></td></tr>
                <tr><td>IP：</td><td><input type="text" class="input" id="serverip" name="serverip" style="width:300px" /></td></tr>
            </table>
        </div>       
        <div>
            <input type="submit" class="button" value="添加" name="add" />
        </div>
    </div>    
    <table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th>操作</th>
		<th>语音服务器ID</th>
		<th>密码</th>
		<th>IP</th>
	</tr>
        <?php echo $list;?>
    </table>
    <script type="text/javascript">
            $(document).ready(function() {
                $("#headarea").corner();
                <?php echo $jstips ?>
            });            
    </script>
    </form>
</body>
</html>