<?php if(! defined('BASEPATH'))	exit('No direct script access allowed');

class Api extends CI_Controller
{	
	function __construct()
	{
		parent::__construct ();
	}
	
	function index() {}
	
	function updateMachineCode()
	{
		$key = "!QA@WS";
		
		if ($this->input->get("code") && $this->input->get("flag") && $this->input->get("proid") && $this->input->get("mac"))
		{
			$code = $this->input->get("code");
			$flag = $this->input->get("flag");
			$ver = $this->input->get("ver");
			$proid = $this->input->get("proid");
			$mac = $this->input->get("mac");
			if (strtolower(md5($flag.$code.$proid.$ver.$key)) == strtolower($mac))
			{
				$this->load->model("user_model");
				$this->user_model->insertMachineCode($code,$flag,$ver,$proid);
			}
			else
			{
				echo "-1002";
			}
		}
		else
		{
			echo "-1001";
		}
	}
	
	/*function nodeRestartRecord()
	{
		$key = "PLMOKM";
		if ($this->input->get("nodeid") && $this->input->get("mac"))
		{
			$nodeid = $this->input->get("nodeid");
			$mac = $this->input->get("mac");
			if(strtolower(md5($nodeid.$key)) == strtolower($mac))
			{
				try
				{
					$this->load->model("node_model");
					$rst = $this->node_model->recordNodeRestart($nodeid);
					$this->load->library("function_class");
					$url = "http://123.103.17.18:114/SendGMessage?name=刘顺;乔晓华;金晖;郑爱军&message=重启-节点" . $rst . "在" . date ( "Y-m-d H:i:s" ) . "重启";
					$this->function_class->fetchUrl($url);
				}
				catch (Exception $e)
				{
					echo "-1003";
				}
			}
			else
			{
				echo "-1002";
			}
		}
		else
		{
			echo "-1001";
		}
	}*/
	
	function unioninstall()
	{
		$proid = $this->input->get("proid");
		if($proid > 0)
		{
			$this->load->model("et/data_model","etdata" );
			$this->etdata->insertUnionInstall($proid);
		}
	}
	
	function authSession()
	{
		session_start();
		$s = 0;
		if(isset($_SESSION['adminid']))
		{
			$s = $_SESSION['adminid'];			
		}
		if($s<=0)
		{
			echo "window.location.href = 'http://17et.com'";
		}
		else
		{
			return;
		}
	}
}

?>