function GetUserSuggest(id) {     
    //TINY.box.show('<img style="float:right" src="/res/images/admin/Loading.gif" align="absmiddle" /> Loading......');
    $.ajax({
        type: "POST",
        cache: false,
        url: "/ttmobile/api/getUserSuggest",
        data: "id="+id,
        dataType: "json",
        success: function (data) {
            var list = data.Table;
            var temp = "";                      
            $.each(list, function (i, item) {
                temp += '<div class="Corner" style="width:500px;line-height:22px">'+
                        '编号:'+item.id+'<br/>'+
                        '电话:'+item.contact+'<br/>'+
                        '意见:'+item.content+'<br/>'+
                        '处理:<br/><textarea style="width:490px" id="result" />'+item.result+'</textarea><br/>'+                        
                        '<input type="button" onclick="UpdateResult('+id+')" value="处理" class="button"/>'+
                        '</div>';
            });
            TINY.box.show(temp);
        },
        error:function(request, status) {
            alert(request.responseText);
        }
    });
}

function UpdateResult(id)
{
    $.ajax({
        type: "POST",
        cache: false,
        url: "/ttmobile/api/updateUserSuggestRst",
        data: "id="+id+"&rst="+$("#result").val(),
        dataType: "json",
        success: function (data) {
            window.location.href = window.location;
        }
    });
}

function DelResult(id)
{
    if(confirm("确定删除？"))
    {
        $.ajax({
            type: "POST",
            cache: false,
            url: "/ttmobile/api/delUserSuggest",
            data: "id="+id,
            dataType: "json",
            success: function (data) {
                window.location.href = window.location;
            }
        });
    }
}