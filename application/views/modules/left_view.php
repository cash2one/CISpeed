<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="/res/images/admin/page.css" />
<script src="/res/script/jquery.js" type="text/javascript"></script>
<script src="/res/script/jquery.corner.js" type="text/javascript"></script>
<style>
#listUL {
	margin: 10px 0px 10px 10px; #
	margin-top: 15px
}

#listUL div {
	height: 20px;
}

#listUL ul a {
	color: gray;
}

a img {
	border: 0;
}

#listUL ul {
	margin-left: 20px;
}
</style>
</head>
<body>
<div id="headarea" class="Corner">
<ul id="listUL">
            <?=$modulesList?>
	</ul>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#headarea").corner();
    });

    function Options(id) {
        if ($("#op" + id).css("display") == "none") {
            $("#img" + id).attr({ src: "/res/images/admin/icon/img-.gif" });
            $("#op" + id).slideUp(1);
            $("#img" + id).attr({ src: "/res/images/admin/icon/img-.gif" });
            $("#op" + id).slideUp(1);
            $("#op" + id).slideDown(500, function () { $("#op" + id).css("display", "block"); });

        }
        else {
            $("#img" + id).attr({ src: "/res/images/admin/icon/img+.gif" });
            $("#op" + id).slideUp(500);
            $("#op" + id).css("display", "block");
        }
    }   
</script>
</body>
</html>
