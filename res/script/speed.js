$('input[name="compare"]').click(function() {
		if ($(this).attr("checked") == true) { 
			$("#vs").css("display","inline");
		}
		else{
			$("#vs").css("display","none");
		}
			
	});
function checkthis()
{
	if(!checkvalue("QueryDate1","请填写要查询的日期")){return;}
	if($('input[name="compare"]').attr("checked") == true)
	{
		if(!checkvalue("QueryDate2","请填写要比较的日期")){return;}
	}
	$("#form1").submit();
}
function checkvalue(id,msg){
	if(!$('input[name="'+id+'"]').val()){
		alert(msg);
		return false;
	}
	return true;
}