<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
    <script src="/res/script/jquery.js" type="text/javascript"></script>
    <script src="/res/script/jquery.corner.js" type="text/javascript"></script>
    <script src="/res/script/common.js?v=20110818" type="text/javascript"></script>
</head>

<body>
<form id="form1" enctype="multipart/form-data" method="post" action="/ttmobile/manage/uploadapk">
    <div id="headarea" class="Corner">
        <h1><img src="/res/images/admin/icon/9.gif" alt="" />上传APK</h1>
	选择渠道:<select name="sp" id="sp"><?php echo $spselect ?></select><br/>
        选择文件:<input name="file" type="file" ><br/>
	<input type="submit" name="upload" class="button" value="上传">
        <div><?php echo $content ?></div>
    </div>
    <script type="text/javascript">	
	$(document).ready(function() {
		$("#headarea").corner();
		$("#sp").val(<?php echo $spselected ?>);
	});
    </script>
</form>
</body>
</html>