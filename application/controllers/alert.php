<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Alert extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
	}
	
	function index() {
	}
	
	function alertspeedingnodeusersum()
	{
		header ( "content-Type: text/html; charset=Utf-8" );
		$url = "http://123.103.17.18:114/SendGMessage?name=刘顺;乔晓华;金晖;郑爱军&message=";
		
		$this->load->model("speed_model");
		$this->load->model("node_model");
		$this->load->library("function_class");
		$nodes = $this->node_model->getNodeList(1);
		$msg1 = "";
		$msg2 = "";
		foreach ($nodes as $node)
		{
			$id = $node ["id"];
			$ip = $node ["ip"];
			$name = $node ["name"];
			$rst = $this->speed_model->getAlertSpeedData($id);
			$msg = "";
			if (count ( $rst ) > 1)
			{
				if ((time () - strtotime ($rst[0]["last_update_time"])) > 1800)
				{
					$msg = "节点-".$name."(".$ip.")人数数据于 ".$rst[0]["last_update_time"]." 后未上报";
					$this->function_class->fetchUrl($url.$msg );
				}
				else
				{
					$t = 0;
					if ($rst[1]["user_sum"] > 100)
					{
						$t = ($rst[0]["user_sum"] - $rst[1]["user_sum"]) / $rst[1]["user_sum"]*100;
					}
					if($id == 3011055 || $id == 3011034 || $id == 3011033)
					{
						if($t < -10)
						{
							$msg = "节点-" . $name . "(" . $ip . ") " . $rst [0] ["last_update_time"] . "-人数由" . $rst [1] ["user_sum"] . "将至" . $rst [0] ["user_sum"] . "-跌幅" . $t . urlencode ( "%" );
							$this->function_class->fetchUrl($url.$msg);
						}
					}
					else
					{
						if($t < - 10)
						{
							$msg = "节点-" . $name . "(" . $ip . ") " . $rst [0] ["last_update_time"] . "-人数由" . $rst [1] ["user_sum"] . "将至" . $rst [0] ["user_sum"] . "-跌幅" . $t . urlencode ( "%" );
							$this->function_class->fetchUrl($url.$msg);
						}
					}
				}
			}
		}
	}
}

?>