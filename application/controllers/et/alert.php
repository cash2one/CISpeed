<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Alert extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
	}
	
	function index() {
	}
	
	function alertloginserverusersum()
	{
		header ( "content-Type: text/html; charset=Utf-8" );
		$url = "http://123.103.17.18:114/SendGMessage?name=刘顺;乔晓华;金晖;郑爱军&message=";
		
		$this->load->model ( "et/data_model", "etdata" );
		$this->load->model ( "et/manage_model", "etmanage" );
		$this->load->library ( "function_class" );
		
		$serverList = $this->etmanage->getServerListValid ( 1, 1 );
		
		for($i = 0; $i < count($serverList ); $i ++)
		{
			$id = $serverList [$i] ["si_serverid"];
			$ip = $serverList [$i] ["si_ip"];
			$name = $serverList [$i] ["si_address"];
			$rst = $this->etdata->getAlertLoginData ( $id );
			$msg = "";
			if (count($rst) > 1)
			{
				if((time() - strtotime($rst[0]["logtime"])) > 1800)
				{
					$msg = "登录-" . $name . "(" . $ip . ")人数数据于 " . $rst [0] ["logtime"] . " 后未上报";
					$this->function_class->fetchUrl ( $url . $msg );
				}
				else
				{
					$t = 0;
					if ($rst [1] ["usercount"] != 0)
					{
						$t = ($rst[0]["usercount"] - $rst[1]["usercount"]) / $rst[1]["usercount"]*100;
					}
					if ($t < -5)
					{
						$msg = "登录-" . $name . "(" . $ip . ") " . $rst [0] ["logtime"] . "-人数由" . $rst [1] ["usercount"] . "降至" . $rst [0] ["usercount"] . "跌幅" . $t . urlencode ( "%" );
						$this->function_class->fetchUrl($url.$msg);
					}
				}
			}
		}
	}

}

?>