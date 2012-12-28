String.prototype.padLeft = function(str,n){
	var result = this;
	if(this.length<n){		
		for(var i=0;i<n-this.length;i++){
			result = str+result;
		}	
	}
	return result;
}

function getBirthday(year,month,day)
{
	var myDate = new Date();		
	for(var i = 1980;i<=myDate.getFullYear();i++){
		document.getElementById(year).options.add(new Option(i,i));
	}
	for(var i = 1;i<=12;i++){
		document.getElementById(month).options.add(new Option(i.toString().padLeft("0",2),i.toString().padLeft("0",2)));
	}
	for(var i = 1;i<=31;i++){
		document.getElementById(day).options.add(new Option(i.toString().padLeft("0",2),i.toString().padLeft("0",2)));
	}
}

function checkNum(value)
{
    if(/^[0-9]+$/.test(value)) {
        return true;
    }
    else
    {
        return false;
    }
}

function checkmobile(mobile) {
    if (/^1[358]\d{9}$/.test(mobile))
        return true;
    else
        return false;
}

function setCookie(name,value)
{
  var Days = 30; //此 cookie 将被保存 30 天
  var exp  = new Date();    //new Date("December 31, 9998");
  exp.setTime(exp.getTime() + Days*24*60*60*1000);
  document.cookie = name + "="+ escape(value) +";expires="+ exp.toGMTString();
}
function getCookie(name)
{
  var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
  if(arr != null) return unescape(arr[2]); return null;
}
function delCookie(name)
{
  var exp = new Date();
  exp.setTime(exp.getTime() - 1);
  var cval=getCookie(name);
  if(cval!=null) document.cookie=name +"="+cval+";expires="+exp.toGMTString();
}
/*----------------------------------------
通用的分页页码显示脚本
----------------------------------------*/
function getPageCount(records, pagesize) {
  var pages = Math.floor(records / pagesize);

  if (records % pagesize > 0)
      pages++;
  return pages;
}
function MakePageNav(recordcount, pagesize, currentpage, func, pageNavDiv) {
  if (pageNavDiv == "" || pageNavDiv == null)
      pageNavDiv = "pageNav";
  var pagecount = getPageCount(recordcount, pagesize);
  if (pagecount == 1) {
      document.getElementById(pageNavDiv).innerHTML = "";
      return;
  }
  var pagecountforshow = 10;
  var page = document.getElementById(pageNavDiv);
  page.innerHTML = "";
  var midpage = parseInt(pagecountforshow / 2);
  var startpage = 1;
  if (currentpage > midpage) {
      startpage = currentpage - midpage;
  }
  if (startpage + pagecountforshow - 1 > pagecount) {
      startpage = pagecount - pagecountforshow + 1;
  }
  if (startpage < 1) {
      startpage = 1;
  }
  var endPage = startpage + pagecountforshow - 1;
  if (endPage > pagecount) {
      endPage = pagecount;
  }
  if (startpage != 1) {
      var firstpage = document.createElement("a");
      firstpage.href = "javascript:" + func + "(1);";
      firstpage.innerHTML = "«";
      firstpage.title = "到首页";
      page.appendChild(firstpage);
  }
  for (var i = startpage; i <= endPage; i++) {
      if (i == currentpage) {
          var cpage = document.createElement("span");
          cpage.innerHTML = i;
          page.appendChild(cpage);
      }
      else {
          var other = document.createElement("a");
          other.href = "javascript:" + func + "(" + i + ");";
          other.innerHTML = i;
          page.appendChild(other);
      }
  }
  if (endPage != pagecount) {
      var lastpage = document.createElement("a");
      lastpage.href = "javascript:" + func + "(" + pagecount + ");";
      lastpage.innerHTML = "»";
      lastpage.title = "到尾页";
      page.appendChild(lastpage);
  }

  var clearDiv = document.createElement("div");
  clearDiv.style.clear = "both";
  page.appendChild(clearDiv);  
  return page;
}