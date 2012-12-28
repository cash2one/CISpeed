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
    <form id="form1" method="post" action="/im/manage/sysmsg">
    <div id="headarea" class="Corner">
        <h1><img src="/res/images/admin/icon/11.gif" alt="" />IM系统消息</h1>
        <div>
            <select id="systype" name="systype">
                <option value="1">系统消息</option>
            </select>
            <select id="type" name="type">
                <option value="0">在线系统消息</option>
                <option value="1">上线系统消息</option>
            </select>
            <select ID="mode" name="mode">
                <option value="1">任务栏闪动</option>
                <option value="2">任务栏浮起框</option>
                <option value="4">主面板吸附条</option>
                <option value="8">聊天对话框吸附条</option>
            </select>
            开始时间<input id="stime" name="stime" type="text" size="20" style="width: 150px" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00'})" class="input" />
            结束时间<input id="etime" name="etime" type="text" size="20" style="width: 150px" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00'})" class="input">
        </div>
        <div style="margin-top:10px">
            标题：<input type="text" class="input" id="title" name="title" style="width:300px" /><br/>
            内容：<input type="text" class="input" id="content" name="content" style="width:300px" /><br/>
            链接：<input type="text" class="input" id="url" name="url" style="width:300px" />
        </div>
        <div style="margin-top:10px">
            <input id="Radio1" name="radApprove" runat="server" type="radio" value="1" checked="true" onclick="checkchanged(1);" />针对所有用户
            <input id="Radio2" name="radApprove" runat="server" type="radio" value="0" onclick="checkchanged(2);" />指定ID发送
            <span id="Receiver" style="display: none; padding-top: 5px;">
                <input id="txtReceiver" name="txtReceiver" value="0" style="width:300px" class="input"/>(手工输入ID,多个ID用";"分隔)
            </span>
        </div>
        <div>
            <input type="submit" class="button" value="发送" name="submit" />
        </div>
    </div>
    
    <script type="text/javascript">
            $(document).ready(function() {
                $("#headarea").corner();                  
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