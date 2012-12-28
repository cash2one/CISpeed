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
    <form id="form1" method="post" action="/ttmobile/manage/sysmsg">
    <div id="headarea" class="Corner">
        <h1><img src="/res/images/admin/icon/11.gif" alt="" />手机通通系统消息</h1>
        <div>
            <select id="type" name="type">
                <?php
                    foreach ($systype as $k => $v)
                    {
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }
                ?>
            </select>
            <select id="show" name="show">
               <?php
                    foreach ($showtype as $k => $v)
                    {
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }
                ?>
            </select>
            <select ID="voice" name="voice">
                <?php
                    foreach ($voicetype as $k => $v)
                    {
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }
                ?>
            </select>
            <select ID="mode" name="mode">
                <?php
                    foreach ($mode as $k => $v)
                    {
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }
                ?>
            </select>
            开始时间<input id="stime" value="<?php echo $stime?>" name="stime" type="text" size="20" style="width: 150px" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00'})" class="input" />
            结束时间<input id="etime" value="<?php echo $etime?>" name="etime" type="text" size="20" style="width: 150px" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00'})" class="input">
        </div>
        <div style="margin-top:10px">
            <table>
                <tr><td>标题：</td><td><input type="text" class="input" id="title" name="title" style="width:300px" /></td></tr>
                <tr><td>内容：</td><td><textarea type="text" class="input" id="content" name="content" style="width:300px;height:100px"></textarea></td></tr>
                <tr><td>链接：</td><td><input type="text" class="input" id="url" name="url" style="width:300px" /></td></tr>
            </table>
        </div>
        <div style="margin-top:10px">
            <input id="Radio1" name="sendtype" type="radio" value="1" checked="true" onclick="checkchanged(1);" />所有在线用户<br/>
            <input id="Radio2" name="sendtype" type="radio" value="2" onclick="checkchanged(2);" />指定ID发送<br/>
            <span id="Receiver" style="display: none; padding-top: 5px;">
                <textarea id="txtReceiver" name="txtReceiver" value="0" style="width:300px;height:100px" class="input"></textarea>(手工输入ID,多个ID用"|"分隔)
            </span>
        </div>
        <div>
            <input type="submit" class="button" value="发送" name="submit" />
        </div>
    </div>
    <div>
        <select ID="query" name="query">
                 <?php
                    foreach ($status as $k => $v)
                    {
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }
                ?>
        </select>
        <input type="submit" class="button" value="查询" name="querySubmit" />
    </div>
    <table class="table" cellspacing="0" cellpadding="0">
	<tr>
		<th style="width:100px">id</th>
		<th style="width:136px">开始时间</th>
		<th style="width:136px">结束时间</th>
		<th style="width:100px">标题</th>
		<th>内容</th>
		<th style="width:100px">链接</th>
		<th style="width:56px">消息类型</th>
		<th style="width:75px">提示方式</th>
		<th style="width:76px">提示标记</th>
	</tr>
        <?php echo $list;?>
    </table>
    <script type="text/javascript">
            $(document).ready(function() {
                $("#headarea").corner();
                $("#query").val("<?php echo $queryType ?>");
            });
            function checkchanged(checkid)
            {
                if(checkid==1)
                {
                    $("#Receiver").css("display","none");
                     $("#txtReceiver").val("0");
                }
                else
                {
                    $("#Receiver").css("display","");
                }
            }    
    </script>
    </form>
</body>
</html>