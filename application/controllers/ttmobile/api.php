<?php if (! defined ( 'BASEPATH' )) 	exit ( 'No direct script access allowed' );

class Api extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct ();
	}
	
	function index() {}
	
	function test()
	{
		//phpinfo();
	}
	
	/*用户意见操作————开始*/
	function addUserSuggest()
	{
		$key = "!QA@WS";
		$content = $this->input->get_post("content");
		$mac = $this->input->get_post("mac");
		if($content && $mac)
		{			
			$userid = $this->input->get_post("userid");
			$mail = $this->input->get_post("mail");
			if (strtolower(md5($userid.$mail.$content.$key)) == strtolower($mac))
			{
				$this->load->model("ttmobile/user_model","TT_user_model");
				$this->TT_user_model->addSuggest($userid, $mail, $content);			
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
	
	function getUserSuggest()
	{
		$id = $this->input->post("id");
		$this->load->model("ttmobile/user_model", "TT_user_model");
		$rst = $this->TT_user_model->getUserSuggest($id);
		$data = "";
		foreach($rst as $r)
		{
			$data .= '{"id":"'.$r["id"].'","contact":"'.$r["contact"].'","content":"'.$r["content"].'","result":"'.$r["result"].'"},';
		}
		$data = substr($data,0,-1);
		$json = '{"Table":['.$data.']}';
		echo $json;
	}
	
	function updateUserSuggestRst()
	{
		$id = $this->input->post("id");
		$rst = $this->input->post("rst");
		$this->load->model("ttmobile/user_model", "TT_user_model");
		$this->TT_user_model->updateUserSuggestRst($id,$rst);
	}
	
	function delUserSuggest()
	{
		$id = $this->input->post("id");
		$this->load->model("ttmobile/user_model", "TT_user_model");
		$this->TT_user_model->delUserSuggest($id);
		
	}	
	/*用户意见操作————结束*/
	
	function getMorePeopleCallList()
	{
		$date = $this->input->post("date");
		$count = $this->input->post("count");
		$this->load->model("ttmobile/user_model", "TT_user_model");
		$rst = $this->TT_user_model->getMorePeopleCall(strtotime($date),$count);
		$data = "";
		foreach($rst as $r)
		{
			$data .= '{"callid":"'.$r["fcallid"].'","senduid":"'.$r["Fsenduid"].'","rcvid":"'.$r["Frcvuid"].'","start":"'.$r["Fstarttime"].'","end":"'.$r["Fendtime"].'"},';
		}
		$data = substr($data,0,-1);
		$json = '{"Table":['.$data.']}';
		echo $json;
	}
		
        /*有你提供手机号与通通用户匹配————开始*/
	function import()
	{
		$temp = file("./res/1.csv");
		$this->load->model("ttmobile/data_model", "TT_data_model");
		for ($i=0;$i<count($temp);$i++) 
		{ 
			$string=explode(",",$temp[$i]);
			//echo $string[0]."/";
			$this->TT_data_model->import($string[0]);
		}
	}
	
	function showimport()
	{
		$this->load->model("ttmobile/data_model", "TT_data_model");
		$rst = $this->TT_data_model->showimport();
		foreach($rst as $r)
		{
			echo $r["mobile"]."</br>";
		}
		
	}	
        /*有你提供手机号与通通用户匹配————结束*/
	
	/*手机通通用户激活信息————开始*/
	function insertDevInfoUser()
	{
		$Fdevfirm = $this->input->get_post("firm");
		$Fdevmodel = $this->input->get_post("model");
		$Fdevosver = $this->input->get_post("os");
		$Fcode = $this->input->get_post("code");
		$Fsourceid = $this->input->get_post("sid");
		$Fclientid = $this->input->get_post("cid");
		$Fnettype = $this->input->get_post("net");
		$dev = $this->input->get_post("devid")?$this->input->get_post("devid"):0;
		$Fimsi = $this->input->get_post("imsi")?$this->input->get_post("imsi"):0;
		$Fmobile = $this->input->get_post("mobile")?$this->input->get_post("mobile"):0;
		$Fmac = $this->input->get_post("fmac")?$this->input->get_post("fmac"):0;
		$mac = $this->input->get_post("mac");
		$key = "%TG^YH";
		
		$this->load->library("function_class");
		$devip = $this->function_class->GetIP();
		
		if($Fdevfirm && $Fdevmodel && $Fdevosver && $Fcode && $Fsourceid && $Fclientid && $Fnettype && $mac)
		{
			//echo strtolower(md5($Fdevfirm.$Fdevmodel.$Fdevosver.$Fcode.$Fsourceid.$Fclientid.$Fnettype.$key));
			if(strtolower(md5($Fdevfirm.$Fdevmodel.$Fdevosver.$Fcode.$Fsourceid.$Fclientid.$Fnettype.$key)) == strtolower($mac))
			{				
				$this->load->model("ttmobile/user_model", "TT_user_model");
				$this->TT_user_model->insertDevInfoUser($Fdevfirm,$Fdevmodel,$Fdevosver,$Fcode,$Fsourceid,$Fclientid,$Fnettype,$dev,$devip,$Fimsi,$Fmobile,$Fmac);
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
	
	function insertActive()
	{
		$mobile = $this->input->get_post("mobile");
		$Fcode = $this->input->get_post("code");
		$Fsourceid = $this->input->get_post("sid");
		$Fclientid = $this->input->get_post("cid");
		$Fnettype = $this->input->get_post("net");
		$Fimsi = $this->input->get_post("imsi")?$this->input->get_post("imsi"):"0";
		$mac = $this->input->get_post("mac");
		$key = "BGTHY^";
		
		if($mobile && $Fcode && $Fsourceid && $Fclientid && $Fnettype && $mac)
		{
			if(strtolower(md5($mobile.$Fcode.$Fsourceid.$Fclientid.$Fnettype.$key)) == strtolower($mac))
			{				
				$this->load->model("ttmobile/user_model", "TT_user_model");
				$this->TT_user_model->insertActive($mobile,$Fcode,$Fsourceid,$Fclientid,$Fnettype,$Fimsi);
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
	
	function insertRegSucc()
	{
		$mobile = $this->input->get_post("mobile");
		$Fcode = $this->input->get_post("code");
		$Fsourceid = $this->input->get_post("sid");
		$Fclientid = $this->input->get_post("cid");
		$Fnettype = $this->input->get_post("net");
		$mac = $this->input->get_post("mac");
		$key = "^YHUJM";
		
		if($mobile && $Fcode && $Fsourceid && $Fclientid && $Fnettype && $mac)
		{			
			if(strtolower(md5($mobile.$Fcode.$Fsourceid.$Fclientid.$Fnettype.$key)) == strtolower($mac))
			{				
				$this->load->model("ttmobile/user_model", "TT_user_model");
				$this->TT_user_model->insertRegSucc($mobile,$Fcode,$Fsourceid,$Fclientid,$Fnettype);
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
	/*手机通通用户激活信息————结束*/
	
	/*Iphone验证————开始*/
	function authForIphone()
	{
		$dev = base64_decode($this->input->get("dev"));
		$mobile = base64_decode($this->input->get("mobile"));
		$authcode = $this->input->get("authcode");
		$mac = base64_decode($this->input->get("mac"));
		$carrierName = $this->input->get("carrier")?base64_decode($this->input->get("carrier")):"";
		$authTime = $this->input->get("authtime")?$this->input->get("authtime"):0;
		if($dev && $mobile && $mac)
		{
			$this->load->model("ttmobile/data_model", "TT_data_model");
			$id = "0";
			$rst = "-1";
			if(!$authcode)
			{
				if($mobile == 18686868686868686 || $mobile==18787878787878787)
				{
					$rst = "0";
				}
			}
			else
			{				
				$id  = $this->TT_data_model->insertIphoneAuth($dev,$mobile,$authcode,$mac,$carrierName,$authTime);
				if(($mobile == 13521540201 && $authcode== 135135)||
				       ($mobile == 18686868686868686 && $authcode==868686)||
				       ($mobile==18686868686868686 && $authcode==383838) ||
				       ($mobile==18787878787878787 && $authcode==747474)||
				       ($mobile==13521540201  && $authcode==135135))
				{
					
					$rst = "0";				
				}
				else
				{
					$rst = "-1";
				}
			}
			echo '{"Result": "'.$rst.'","id":"'.$id.'"}';
		}
		else
		{
			echo '{"Result":"-999"}';
		}
	}
	
	function authForIphoneRst()
	{
		$authid = $this->input->get("authid");
		$rst = $this->input->get("rst");
		
		if($authid)
		{
			$this->load->model("ttmobile/data_model", "TT_data_model");
			$id = $this->TT_data_model->insertIphoneAuthRst($authid,$rst);
			echo '{"Result": "0"}';
		}
		else
		{
			echo '{"Result":"-1"}';
		}
	}
	
	/*Iphone验证————结束*/
	
	/*手机通通下载统计API————开始*/
	function insertDownloadData()
	{
		$date = $this->input->get("date");
		$clientver = $this->input->get("clientver");
		$num = $this->input->get("num")?$this->input->get("num"):0;
		
		if($date && $clientver && ($num>=0))
		{
			$this->load->model("ttmobile/data_model", "TT_data_model");
			$id = $this->TT_data_model->insertDownloadData($date,$clientver,$num);			
			echo '{"Result": "0","id":"'.$id.'"}';
		}
		else
		{
			echo '{"Result":"-999"}';
		}
	}
	/*手机通通下载统计API————j结束*/
	
}

?>