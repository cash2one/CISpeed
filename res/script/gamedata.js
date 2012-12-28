function AddRecordToList(type,value,text){
		var div = document.createElement("div");
		div.innerHTML = text;
		document.getElementById("valueBox").appendChild(div);
}