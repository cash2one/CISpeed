<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
    <script src="/res/script/jquery.js" type="text/javascript"></script>
    <script src="/res/script/jquery.corner.js" type="text/javascript"></script>
</head>

<body>
    <form id="form1" method="post" action="/ttmobileout/admin/addsp">
    <div id="headarea" class="Corner">
    <h1><img src="/res/images/admin/icon/11.gif" alt="" />添加渠道</h1>
    <div>名字：<input class="input" name="name" type="text" /></div>    
    <div>详细：<input class="input" name="detail" type="text" />(多个之间用逗号分隔)</div>
    <div><input class="button" name="submit" type="submit" value="确定" /></div>
    </div>
    <script type="text/javascript">
            $(document).ready(function() {
                    $("#headarea").corner();
                    <?php echo $tips?>
            });
    </script>
    </form>
</body>
</html>