<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class User extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("ttmobile/user_model","TT_user_model");
		$this->load->model("vipkf/user_model_vipkf","VIPKF_user_model");
	}
        
        private function getEndType($type)
	{
		$str = "";
		switch ($type) {
			case 0 :
				$str = "正在通话";
				break;
			case 1 :
				$str = "正常挂断";
				break;
			case 2 :
				$str = "占线";
				break;
			case 3 :
				$str = "拒绝接听";
				break;
			case 4 :
				$str = "无人应答";
				break;
			case 6 :
				$str = "不在线或不在服务区";
				break;
			case 7 :
				$str = "掉线";
				break;
			case 8 :
				$str = "连接超时";
				break;
			case 9 :
				$str = "被踢出";
				break;
			case 10 :
				$str = "频道超过最大用户数";
				break;
			case 11 :
				$str = "未登录";
				break;
			case 12 :
				$str = "语音频道连接断开";
				break;
			case 13 :
				$str = "频道连接超时时间";
				break;
			case 14 :
				$str = "服务器异常";
				break;
			default :
				break;
		}
		return $str;
	}
        
        function userdetail()
	{/*
		$data["mobile"] = "";
		$data["online"] = "";
		$data["regInfo"] = "";
		$data["queryDate1"] = date("Y-m-d");
		$data["queryDate2"] = date("Y-m-d");
		$data["page"] = "";
		$data["querytype"] = $this->input->get_post("querytype");
		$datalist = "";
			
		$pagesize = 50;
		$this->load->library ( 'pagination' );
		if($this->input->get_post("queryLogin"))
		{
			$data["mobile"] = $this->input->get_post("mobile");
			$data["queryDate1"] = $this->input->get_post("QueryDate1");
			$data["queryDate2"] = $this->input->get_post("QueryDate2");			
			
			if($data["querytype"] == "fuserid")
			{
				$online = $this->TT_user_model->getUserOnline($data["mobile"]);
				if($online){$data["online"] = "当前在线";}
				else{$data["online"] = "当前不在线";}
				$region = $this->TT_user_model->getRegionByMobile($data["mobile"]);
				$data["online"] .= "/".($region);
				
				$regInfo = $this->TT_user_model->getRegInfoByUser($data["mobile"]);
			}
			else
			{				
				$regInfo = $this->TT_user_model->getRegInfoByUser($data["mobile"],$data["querytype"]);
			}
			
			if(count($regInfo)>0)
			{				
				$data["regInfo"] .= "<br/>注册信息——号码:".$regInfo[0]["fmobile"]."/
						    型号:".$regInfo[0]["Fdevfirm"]."(".$regInfo[0]["Fdevmodel"].")/
						    机器码:".$regInfo[0]["fmachinecode"]."/
						    注册时间:".$regInfo[0]["t"]; 
			}
			
			$rst = $this->TT_user_model->getUserLoginList($data["mobile"], $data["queryDate1"], $data["queryDate2"],$pagesize, $this->input->get("per_page") ? $this->input->get("per_page") : 0,$data["querytype"]);
			$datalist .= "<tr><th>手机</th><th>机器码</th><th>厂商</th><th>类型</th><th>系统</th><th>版本</th><th>IP</th><th>渠道</th><th>网络</th><th>登录时间</th><th>退出时间</th></tr>";
			
			foreach($rst as $r)
			{
				$datalist .= "<tr><td>".$r["fmobile"]."</td>
						  <td>".$r["fmachinecode"]."</td>
				                  <td>".$r["fdevfirm"]."</td>
						  <td>".$r["fdevmodel"]."</td>
						  <td>".$r["fdevosver"]."</td>
						  <td>".$r["fclientverid"]."</td>
						  <td>".$r["faddr"]."</td>
						  <td>".$r["fcodeid"].$this->function_class->getTTmMarket($r["fcodeid"])."</td>						  
						  <td>".$r["Fnettype"]."</td>
						  <td>".($r["Flogintime"]?date("Y-m-d H:i:s",$r["Flogintime"]):$r["Flogintime"])."</td>
						  <td>".($r["Flogouttime"]?date("Y-m-d H:i:s",$r["Flogouttime"]):$r["Flogouttime"])."</td></tr>";	
			}
			$config ['base_url'] = "/vipkf/user/userdetail?querytype=".$data["querytype"]."&queryLogin=queryLogin&mobile=".$data ["mobile"]."&QueryDate1=".$data["queryDate1"]."&QueryDate2=".$data["queryDate2"];	
			$config ['total_rows'] = $this->TT_user_model->getUserLoginListCount($data["mobile"], $data["queryDate1"], $data["queryDate2"],$data["querytype"]);			
			$config ['per_page'] = $pagesize;
			$config ['num_links'] = 5;
			$config ['page_query_string'] = true;
			$config ['first_link'] = '<<';
			$config ['last_link'] = '>>';
			$config ['full_tag_open'] = '<div class="pagination">';
			$config ['full_tag_close'] = '</div>';
			$this->pagination->initialize ($config);
			$data ["page"] = $this->pagination->create_links ();
		}
		$data["list"] = '<table class="table" cellspacing="0" cellpadding="0">'.$datalist.'</table>';	
		
		$this->load->view("modules/vipkf/user/userdetail_view",$data);*/
	}
        
        function calllog()
	{	/*	
		$data["list"] = "";
		$data["page"] = "";
		$data["mobile"] = "";
		$data["fuid"] = $this->input->get_post("fuid");
		
		$data["date"] = date("Y-m-d");
		$data["starttime"] = -1;
		$data["endtype"] = -1;
		if($this->input->get_post("QueryDate1"))
		{
			$data["date"] = $this->input->get_post("QueryDate1");
		}
		if($this->input->get_post("starttime")>-2)
		{
			$data["starttime"] = $this->input->get_post("starttime");
		}
		if($this->input->get_post("endtype")>-2)
		{
			$data["endtype"] = $this->input->get_post("endtype");
		}
		
		$pagesize = 50;
		$pagecount = $this->input->get("per_page")?$this->input->get("per_page"):0;
		$this->load->library('pagination');
		if ($this->input->get_post("mobile"))
		{			
			$data["mobile"] = $this->input->get_post("mobile");
			$rst = $this->TT_user_model->getCallLogByDateMobile($data["date"],$data["mobile"],$data["starttime"],$data["endtype"],$data["fuid"],$pagesize,$pagecount);
			
			$config['base_url'] = "/vipkf/user/calllog?mobile=".$data["mobile"]."&fuid=".$data["fuid"]."&starttime=".$data["starttime"]."&endtype=".$data["endtype"]."&QueryDate1=".$data["date"]."&v=1";
			$config['total_rows'] = $this->TT_user_model->getCallLogByDateMobileCount($data["date"],$data["mobile"],$data["starttime"],$data["endtype"],$data["fuid"]);
			$config['per_page'] = $pagesize;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['first_link'] = '<<';
			$config['last_link'] = '>>';
			$config['full_tag_open'] = '<div class="pagination">';
			$config['full_tag_close'] = '</div>';
			$this->pagination->initialize($config);
			$data["page"] = $this->pagination->create_links();
			
			for($i=0;$i<count($rst);$i++)
			{
				$endtime = ($rst[$i]["Fendtime"]<$rst[$i]["Fstarttime"])?$rst[$i]["Fstarttime"]:$rst[$i]["Fendtime"];
				$starttime = ($rst[$i]["Fstarttime"]?$rst[$i]["Fstarttime"]:$rst[$i]["Fendtime"]);
				if ($endtime==0 && $starttime==0)
				{
					continue;
				}
				$data ["list"] .= "<tr><td>".$rst[$i]["Fsenduid"]."(".$rst[$i]["sr"].")</td>
						       <td>".$rst[$i]["Frcvuid"]."(".$rst[$i]["rr"].")</td>
						       <td>".$this->getEndType($rst[$i]["Fendtype"])."</td>
						       <td>".$this->function_class->getFormattime3($endtime-$starttime)."</td>
						       <td>".date("H:i:s",$starttime)."</th>
						       <td>".date("H:i:s",$endtime)."</td></tr>";
						       /*(".$rst[$i]["fdevfirm"]."/".$rst[$i]["Fdevmodel"].")
			}	
		}		
		
		$this->load->view("modules/vipkf/user/usercalllog_view",$data);*/
	}
	
	function callrecbycustom()
	{/*
		$data["date"] = "";
		$data["page"] = "";
		$data["list"] = "";
		$uid = $this->input->get("uid");
		$token = $this->input->get("token");
		if(!$token || !$uid)
		{
			echo iconv("UTF-8", "gb2312//IGNORE","非法请求-1");
			return;
		}
		else
		{
			if($this->VIPKF_user_model->checkTokenUid($token,$uid)<=0)
			{
				echo iconv("UTF-8", "gb2312//IGNORE","非法请求-2");
				return;
			}
			else
			{			
				$pagesize = 50;
				$pagecount = $this->input->get("per_page")?$this->input->get("per_page"):0;
				$this->load->library('pagination');
				
				$rst = $this->VIPKF_user_model->getCallRecByCustom($pagesize,$pagecount,$uid);			
				$config['base_url'] = "/vipkf/user/callrecbycustom?v=1&token=$token&uid=$uid";
				$config['total_rows'] = $this->VIPKF_user_model->getCallRecByCustomCount($uid);
				$config['per_page'] = $pagesize;
				$config['num_links'] = 5;
				$config['page_query_string'] = true;
				$config['first_link'] = '<<';
				$config['last_link'] = '>>';
				$config['full_tag_open'] = '<div class="pagination">';
				$config['full_tag_close'] = '</div>';
				$this->pagination->initialize($config);
				$data["page"] = $this->pagination->create_links();		
				
				for($i=0; $i<count($rst);$i++)
				{			
					$data["list"] .= "<tr><td>".$rst[$i]["Fuid"]."</td>
							      <td>".date("Y-m-d H:i:s",$rst[$i]["Festbtime"])."</td>
							      <td>".date("Y-m-d H:i:s",$rst[$i]["Fleavetime"])."</td>
							      <td>".iconv("UTF-8", "gb2312//IGNORE" ,$this->function_class->getFormattime3($rst[$i]["Fleavetime"]-$rst[$i]["Festbtime"]))."</td>
							      <td>".(($rst[$i]["Foutbound"]==1)?"IN":"OUT")."</td>
							  </tr>";
				}
			}
		}
		
		$this->load->view("modules/vipkf/user/callrecbycustom_view",$data);*/
	}
	
	function callrecbyphone()
	{/*
		$data["date"] = "";
		$data["page"] = "";
		$data["list"] = "";
		$userphone = $this->input->get("userphone");			
		$uid = $this->input->get("uid");
		$token = $this->input->get("token");
		if(!$token || !$uid)
		{			
			echo iconv("UTF-8", "gb2312//IGNORE","非法请求-1");
			return;
		}
		else
		{
			if($this->VIPKF_user_model->checkTokenUid($token,$uid)<=0)
			{			
				echo iconv("UTF-8", "gb2312//IGNORE","非法请求-2");
				return;
			}
			
			else if($userphone)
			{
				$pagesize = 50;
				$pagecount = $this->input->get("per_page")?$this->input->get("per_page"):0;
				$this->load->library('pagination');
				
				$rst = $this->VIPKF_user_model->getCallRecByPhone($pagesize,$pagecount,$userphone);			
				$config['base_url'] = "/vipkf/user/callrecbycustom?v=1&token=$token&uid=$uid&userphone=$userphone";
				$config['total_rows'] = $this->VIPKF_user_model->getCallRecByPhoneCount($userphone);
				$config['per_page'] = $pagesize;
				$config['num_links'] = 5;
				$config['page_query_string'] = true;
				$config['first_link'] = '<<';
				$config['last_link'] = '>>';
				$config['full_tag_open'] = '<div class="pagination">';
				$config['full_tag_close'] = '</div>';
				$this->pagination->initialize($config);
				$data["page"] = $this->pagination->create_links();		
				
				$qos = $this->function_class->vipQos();
				$qosDetail = $this->function_class->vipQosDetail();
				
				for($i=0; $i<count($rst);$i++)
				{			
					$data["list"] .= "<tr>
							      <td>".date("Y-m-d H:i:s",$rst[$i]["Festbtime"])."</td>
							      <td>".$rst[$i]["Frealname"]."</td>
							      <td>".$qos[$rst[$i]["Fcallqos"]]."</td>
							      <td>".$qosDetail[$rst[$i]["Fqosdetail"]]."</td>
							  </tr>";
				}
			}
		}
		
		$this->load->view("modules/vipkf/user/callrecbyphone_view",$data);*/
	}
	
	function callrecbyphone1()
	{
		/*$data["date"] = "";
		$data["page"] = "";
		$data["list"] = "";
		$userphone = $this->input->get("userphone");	
		if($userphone)
			{
				$pagesize = 50;
				$pagecount = $this->input->get("per_page")?$this->input->get("per_page"):0;				
				$this->load->library('pagination');
				
				$rst = $this->VIPKF_user_model->getCallRecByPhone($pagesize,$pagecount,$userphone);			
				$config['base_url'] = "/vipkf/user/callrecbycustom?v=1&token=$token&uid=$uid&userphone=$userphone";
				$config['total_rows'] = $this->VIPKF_user_model->getCallRecByPhoneCount($userphone);
				$config['per_page'] = $pagesize;
				$config['num_links'] = 5;
				$config['page_query_string'] = true;
				$config['first_link'] = '<<';
				$config['last_link'] = '>>';
				$config['full_tag_open'] = '<div class="pagination">';
				$config['full_tag_close'] = '</div>';
				$this->pagination->initialize($config);
				$data["page"] = $this->pagination->create_links();		
				
				for($i=0; $i<count($rst);$i++)
				{			
					$data["list"] .= "<tr>
							      <td>".date("Y-m-d H:i:s",$rst[$i]["Festbtime"])."</td>
							      <td>".$rst[$i]["Frealname"]."</td>
							      <td>".$rst[$i]["Fcallqos"]."</td>
							      <td>".$rst[$i]["Fqosdetail"]."</td>
							  </tr>";
				}
			}
		
		
		$this->load->view("modules/vipkf/user/callrecbyphone_view",$data);*/
	}
	
	function calllist()
	{
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission(1202);
	    
		$data["list"] = "";
		$data["page"] = "";
		$data["type"] = $this->input->get_post("type");
		$data["uid"] = $this->input->get_post("uid");
		
		if($data["uid"])
		{
			$pagesize = 50;
			$pagecount = $this->input->get("per_page")?$this->input->get("per_page"):0;
			$this->load->library('pagination');
			
			if($data["type"] == 2)
			{				
				$rst = $this->VIPKF_user_model->getCallRecByCustom($pagesize,$pagecount,$data["uid"]);
				$config['total_rows'] = $this->VIPKF_user_model->getCallRecByCustomCount($data["uid"]);
			}
			else
			{				
				$rst = $this->VIPKF_user_model->getCallRecByPhone($pagesize,$pagecount,$data["uid"]);				
				$config['total_rows'] = $this->VIPKF_user_model->getCallRecByPhoneCount($data["uid"]);
			}		
			$config['base_url'] = "/vipkf/user/calllist?v=1&type=".$data["type"]."&uid=".$data["uid"];
			$config['per_page'] = $pagesize;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['first_link'] = '<<';
			$config['last_link'] = '>>';
			$config['full_tag_open'] = '<div class="pagination">';
			$config['full_tag_close'] = '</div>';
			$this->pagination->initialize($config);
			$data["page"] = $this->pagination->create_links();		
			$qos = $this->function_class->vipQos();
			$qosDetail = $this->function_class->vipQosDetail();
			$question = $this->function_class->vipQuestion();
			
			for($i=0; $i<count($rst);$i++)
			{			
				$data["list"] .= "<tr><td>".$rst[$i]["Fcallid"]."</td>
					      <td>".$rst[$i]["Fuid"]."</td>
					      <td>".$rst[$i]["Fcustomid"]."</td>
					      <td>".$rst[$i]["Fremark"]."</td>
					      <td>".iconv("UTF-8", "gb2312//IGNORE" ,$this->function_class->getFormattime3($rst[$i]["Fleavetime"]-$rst[$i]["Festbtime"]))."</td>
					      <td>".date("Y-m-d H:i:s",$rst[$i]["Festbtime"])."</td>
					      <td>".date("Y-m-d H:i:s",$rst[$i]["Fleavetime"])."</td>
					      <td>".iconv("UTF-8", "gb2312//IGNORE" ,$this->function_class->getFormattime3($rst[$i]["Festbtime"]-$rst[$i]["Fentertime"]))."</td>
					      <td>".($rst[$i]["Fbigquestionid"]?$qos[$rst[$i]["Fbigquestionid"]]:$rst[$i]["Fbigquestionid"])."</td>
					      <td>".($rst[$i]["Fsmallquestionid"]?$qosDetail[$rst[$i]["Fsmallquestionid"]]:$rst[$i]["Fsmallquestionid"])."</td>
					      <td>".($rst[$i]["Fcallqos"]?$qos[$rst[$i]["Fcallqos"]]:$rst[$i]["Fcallqos"])."</td>
					      <td>".($rst[$i]["Fqosdetail"]?$qosDetail[$rst[$i]["Fqosdetail"]]:$rst[$i]["Fqosdetail"])."</td>
					      <td>".iconv("UTF-8", "gb2312//IGNORE",$rst[$i]["Fremark"])."</td></tr>";
			}			
		}
		$this->load->view("modules/vipkf/user/calllist_view",$data);		
	}
	
	function insertcustom()
	{
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission(1203);
		$data["role"] = $this->input->post("role");
		$data["id"] = $this->input->post("id")?$this->input->post("id"):"";
		$data["pwd"] = $this->input->post("pwd")?$this->input->post("pwd"):"";
		$data["rname"] = $this->input->post("rname")?$this->input->post("rname"):"";
		$data["name"] = $this->input->post("name")?$this->input->post("name"):"";
		$data["jstips"] = "";		
		$str = "";
		
		if($this->input->post("submit"))
		{
			if($data["id"] && $data["pwd"])
			{
				
				$rst = $this->VIPKF_user_model->insertCustomInfo($data["id"],$data["pwd"],$data["rname"],$data["name"],$data["role"]);
				if($rst == -1)
				{
					$str = "添加失败，此账号已存在";
				}
				else if($rst == 0)
				{
					$str = "添加成功";
					
				}
				else
				{
					$str = "未知$rst";
				}
			}
			else
			{
				$str = '账号密码不能为空';
			}
			$data["jstips"] = "alert('$str')";
		}
		
		$data["list"] = "";
		$userList = $this->VIPKF_user_model->getCustomInfoList();
		foreach($userList as $user)
		{
			$data["list"] .= "<tr><td><a href='javascript:void(0)' onclick='del(".$user["Fucustomid"].")'>删除</a></td>
					      <td>".$user["Fucustomid"]."</td>
					      <td>".$user["Frealname"]."</td>
					      <td>".$user["Fname"]."</td>
					      <td>".($user["Frole"]==8?"管理员":"坐席")."</td></tr>";
		}	
			
		$this->load->view("modules/vipkf/user/insertcustom_view",$data);
	}
	
	function delcustom()
	{
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission(1203);
		$id =  $this->input->get("id");
		if($id>=0)
		{
			$this->VIPKF_user_model->delCustom($id);
			echo "<script>window.location.href = 'insertcustom';</script>";
		}
		
	}
	
	function customdata()
	{
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission(1204);
		$data["list"] = "";
		$data["cid"] = "";
		$data["date"] = date("Y-m-d",time()-24*60*60);
		if($this->input->post("submit"))
		{
			$date["cid"] = $this->input->post("cid");
			$date["date"] = $this->input->post("QueryDate1");
			
			if($date["cid"] && $date["date"])
			{
				$states = $this->function_class->vipkfAgentState();
				$rsts = $this->VIPKF_user_model->getCustomData($date["date"],$date["cid"]);
				for($i=0,$rstcount=count($rsts);$i<$rstcount;$i++)
				{
					$data["list"] .= "<tr><td>".$rsts[$i]["fagentid"]."</td>
							      <td>".$rsts[$i]["intime"]."</td>
							      <td>".$rsts[$i]["outtime"]."</td>
							      <td>".iconv("UTF-8","gb2312//IGNORE",$states[$rsts[$i]["fstate"]])."</td>
							      <td>".$rsts[$i]["c"]."</td>
							      <td>".iconv("UTF-8", "gb2312//IGNORE" ,$this->function_class->getFormattime3($rsts[$i]["stime"]))."</td></tr>";
				}
				
			}
		}
		$this->load->view("modules/vipkf/user/customdata_view",$data);
	}
	
	
	function savereason()
	{
		$data["callid"] = $this->input->get("callid");			
		$data["uid"] = $this->input->get("uid");
		$data["token"] = $this->input->get("token");
		
		if($this->input->post("submit"))
		{
			$callid = $this->input->post("callid");			
			$uid = $this->input->post("uid");
			$token = $this->input->post("token");
			if(!$token || !$uid)
			{			
				echo iconv("UTF-8", "gb2312//IGNORE" ,"非法请求-1");
				return;
			}
			else
			{
				if($this->VIPKF_user_model->checkTokenUid($token,$uid)<=0)
				{			
					echo iconv("UTF-8", "gb2312//IGNORE" ,"非法请求-2");
					return;
				}
				else if($callid)
				{
					$this->VIPKF_user_model->insertQuestion($callid,$this->input->post("reason"),$this->input->post("childs"),
										$this->input->post("qos"),$this->input->post("qosdetail"),$this->input->post("content"));
				}
				else
				{
					echo iconv("UTF-8", "gb2312//IGNORE" ,"非法请求-3");
					return;
				}
			}
		}		
		
		$data["reasonJson"] = "";		
		$doc = new DOMDocument ();
		$doc->load($_SERVER['DOCUMENT_ROOT'].'/res/config/vipkf/TtReasons.xml');
		$bigReasons = $doc->getElementsByTagName("reasons")->item(0)->getElementsByTagName("reason");
				
		$data["reasonJson"] .= "{'Reasons':[";
		foreach($bigReasons as $bigReason)
		{
			$childs = $bigReason->getElementsByTagName("childs")->item(0)->getElementsByTagName("child");
			$childJson = "";
			foreach($childs as $child)
			{
				$childJson .= "{'id':'".$child->getElementsByTagName("id")->item(0)->nodeValue."',".
					      " 'name':'".$child->getElementsByTagName("reasonName")->item(0)->nodeValue."'},";
			}
			$childJson = substr($childJson,0, strlen($childJson)-1);
					
			$data["reasonJson"] .= "{'id':'".$bigReason->getElementsByTagName("id")->item(0)->nodeValue."',".
					       " 'name':'".$bigReason->getElementsByTagName("reasonName")->item(0)->nodeValue."',".
					       " 'children':[$childJson]},";
		}
		$data["reasonJson"] = substr($data["reasonJson"],0, strlen($data["reasonJson"])-1);
		$data["reasonJson"] .= "]}";
						
		$this->load->view("modules/vipkf/user/savereason_view",$data);		
	}
}