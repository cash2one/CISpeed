<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Authuser_class
{	
	function __construct()
        {
		session_save_path($_SERVER['DOCUMENT_ROOT'].'/session');
	}
	
	function checkLogin()
        {
		session_start();
		if (isset($_SESSION['adminid']))
                {
			if((time()-$_SESSION['adminTimestamp']) < (21600))
                        {
				$_SESSION['adminTimestamp'] = time();
				return true;
			}
                        else
                        {
				echo "<script>window.top.location.href='/admin/logout'</script>";
			}
		}
		echo "<script>window.top.location.href='/admin/oalogin'</script>";
	}
        
        function checkTTmOutLogin()
        {
		session_start();
		if(isset($_SESSION['adminid']))
                {
			if((time()-$_SESSION['adminTimestamp']) < (21600))
                        {
				$_SESSION['adminTimestamp'] = time();
				return true;
			}
                        else
                        {
				echo "<script>window.top.location.href='/ttmobileout/admin/logout'</script>";
			}
		}
		echo "<script>window.top.location.href='/ttmobileout/admin/login'</script>";
	}
	
	function checkMonitorLogin()
        {
		session_start();
		if(isset($_SESSION['adminid']))
                {
			if((time()-$_SESSION['adminTimestamp']) < (21600))
                        {
				$_SESSION['adminTimestamp'] = time();
				return true;
			}
                        else
                        {
				echo "<script>window.top.location.href='/monitor/admin/logout'</script>";
			}
		}
		echo "<script>window.top.location.href='/monitor/admin/login'</script>";
	}
	
	function checkPermission($permissionid)
        {
		$flags = $_SESSION ["adminflag"];
		if(!in_array($permissionid,$flags))
                {
			echo "<script>window.location.href='/admin/nopermission'</script>";
		}
	}
        
        function checkTTmOutPermission($permissionid)
        {
		$flags = $_SESSION["adminflag"];
		if(!in_array($permissionid,$flags))
                {
			echo "<script>window.location.href='/ttmobileout/admin/nopermission'</script>";
		}
	}
        	
	function safeQuit()
        {		
		session_start ();
		session_unset ();
		session_destroy ();
	}
	
	function getMD5Value($para) {
		$key = "speedadminphp!@#";
		return md5 ( $key . $para );
	}
        
        function authSession()
        {
            echo "<script type=\"text/javascript\" src=\"http://123.103.17.22:8081/api/authsession\"></script>";            
        }
}
?>