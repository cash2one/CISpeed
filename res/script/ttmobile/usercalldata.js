function GetMorePeopleCallList(date,count,e) {     
    TINY.box.show('<img style="float:right" src="/res/images/admin/Loading.gif" align="absmiddle" /> Loading......');
    $.ajax({
        type: "POST",
        cache: false,
        url: "/ttmobile/api/getMorePeopleCallList",
        data: "date="+date+"&count="+count,
        dataType: "json",
        success: function (data) {
            var list = data.Table;
            var temp = "";           
            temp += '<table class="table" id="morelist" cellspacing="0" cellpadding="0">'+
                    '    <tr><th>呼叫编号</th><th>主叫</th><th>被叫</th><th>开始</th><th>结束</th>';           	
            
            $.each(list, function (i, item) {
                temp += '<tr><td>'+item.callid+'</td><td>'+item.senduid+'</td><td>'+item.rcvid+'</td><td>'+item.start+'</td><td>'+item.end+'</td></tr>';
            });
            temp += "</tr></table>";
            TINY.box.show(temp);
        }
    });
}