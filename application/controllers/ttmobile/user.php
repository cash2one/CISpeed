<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class User extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("ttmobile/user_model","TT_user_model");
	}
	
	function index() {
		$this->TT_user_model->getCallLogByDateCountTest("2011-12-10",1,-1,false,21);	
	}
	
	/*用户意见反馈————开始*/
	function suggestlist()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(83);
		
		$pagesize = 50;
		
		$this->load->library('pagination');
		$config['base_url'] = "/ttmobile/user/suggestlist?v=1";
		$config['total_rows'] = $this->TT_user_model->getSuggestCount();
		$config['per_page'] = $pagesize;
		$config['num_links'] = 5;
		$config['page_query_string'] = true;
		$config['first_link'] = '<<';
		$config['last_link'] = '>>';
		$config['full_tag_open'] = '<div class="pagination">';
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$data["page"] = $this->pagination->create_links();
		
		$data["list"] = "";
		$rst = $this->TT_user_model->getSuggest($pagesize,$this->input->get("per_page")?$this->input->get("per_page"):0);
		for($i=0;$i<count($rst);$i++)
		{
			$data ["list"] .= "<tr><td>".$rst[$i]["userid"]."</td>					      
					       <td style='width:35%;word-break:break-all'>".$this->function_class->autowrapstring($rst[$i]["content"],50)."</td>
					       <td>".$rst[$i]["contact"]."</td>
					       <td>".date("Y-m-d H:i:s",$rst[$i]["createtime"])."</td>
					       <td style='width:15%;word-break:break-all'>".$rst[$i]["result"]."</td>
					       <td><a href='javascript:void(0)' onclick='DelResult(".$rst[$i]["id"].")'>删除</a></td>
					       <td><a href='javascript:void(0)' onclick='GetUserSuggest(".$rst[$i]["id"].")'>操作</a></td>
					       <td>".$rst[$i]["id"]."</td>
					   </tr>";
		}
		
		$data["scriptjs"] = '<script src="/res/script/jquery.js" type="text/javascript"></script>
				     <script src="/res/script/jquery.corner.js" type="text/javascript"></script>				     
				     <script src="/res/script/common.js?v='.rand().'" type="text/javascript"></script>
				     <script src="/res/script/ttmobile/usersuggest.js?v='.rand().'" type="text/javascript"></script>
				     <script src="/res/script/tinybox.js" type="text/javascript"></script>';
				     
		$this->load->view("modules/ttmobile/user/getusersuggest_view",$data);
	}
	/*用户意见反馈————结束*/
	
	function mobiletypedata()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m-d",time()-24*60*60);	
		if($this->input->get_post("QueryDate1"))
		{
			$data["date"] = $this->input->get_post("QueryDate1");			
		}
		
		$rsts = $this->TT_user_model->getMobileTypeDataByName($data["date"]);
		
		$data["fusionData"] = "";
		foreach($rsts as $rst)
		{
			$rst["fdevfirm"] = $rst["fdevfirm"]?$rst["fdevfirm"]:"未记录";
			$data["fusionData"] .= "<set name='".$rst["fdevfirm"]."' value='".$rst["s"]."' />";
		} 
		
		$pagesize = 100;
		$list = $this->TT_user_model->getMobileTypeDataByModel($data["date"]);
		/*$list = $this->TT_user_model->getMobileTypeDataByDetail($data["date"],$pagesize,$this->input->get("per_page")?$this->input->get("per_page"):0);
		$this->load->library('pagination');
		$config['base_url'] = "/ttmobile/user/mobiletypedata?QueryDate1=".$data["date"]."&v=1";
		$config['total_rows'] = $this->TT_user_model->getMobileTypeDataByDetailCount($data["date"]);
		$config['per_page'] = $pagesize;
		$config['num_links'] = 5;
		$config['page_query_string'] = true;
		$config['first_link'] = '<<';
		$config['last_link'] = '>>';
		$config['full_tag_open'] = '<div class="pagination">';
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);*/
		$data["page"] = "";//$this->pagination->create_links();
		$data["list"] = "";
		for($i = 0; $i < count($list); $i++) {			
			$data["list"] .= "<tr><td>".$list[$i]["Fdevfirm"]." ".$list[$i]["Fdevmodel"]."</td>
					      <td>all"./*$list[$i]["Fdevosver"].*/"</td>
					      <td>".$list[$i]["c"]."</td>
					  </tr>";
		}
		
		$this->load->view("modules/ttmobile/user/mobiletypedata_view",$data);
	}
	
	function nettypedata()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m-d",time()-24*60*60);
		if ($this->input->get_post("QueryDate1"))
		{
			$data["date"] = $this->input->get_post("QueryDate1");
		}
		$rsts = $this->TT_user_model->showNetTypeData($data["date"]);
		$data["fusionData"] = "";
		$data["list"] = "";
		foreach ($rsts as $rst )
		{
			$rst["nettype"] = $rst["nettype"]?$rst["nettype"]:"未记录";
			$data["fusionData"] .= "<set name='".$rst["nettype"]."' value='".$rst["c"]."' />";
			$data["list"] .= "<tr><td>".$rst["nettype"]."</td>
					      <td>".$rst["c"]."</td>
					  </tr>";
		}
		
		$this->load->view("modules/ttmobile/user/nettypedata_view",$data);
	}
	
	function regiondata()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m-d",time()-24*60*60);
		if ($this->input->get_post("QueryDate1"))
		{
			$data["date"] = $this->input->get_post("QueryDate1");
		}
		$provinces = $this->TT_user_model->showRegionDataByProvince($data["date"]);
		$data ["fusionData"] = "";
		foreach ($provinces as $province ) {
			$province["a"] = $province["a"]?$province["a"]:"未记录";
			$data["fusionData"] .= "<set name='".$province["a"]."' value='".$province["s"]."' />";
		}
		
		$data["list"] = "";
		$rsts = $this->TT_user_model->showRegionData($data["date"]);
		for($i=0;$i<count($rsts);$i++)
		{			
			$data["list"] .= "<tr><td>".$rsts[$i]["region"]."</td>
					      <td>".$rsts[$i]["c"]."</td>
					  </tr>";
		}
		
		$this->load->view("modules/ttmobile/user/regiondata_view", $data);
	}
	
	function fsmsdata()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m-d",time()-24*60*60);
		if ($this->input->get_post("QueryDate1"))
		{
			$data["date"] = $this->input->get_post("QueryDate1");
		}
		$rsts = $this->TT_user_model->showFsmsData($data["date"]);
		$data["fusionData"] = "";
		$data["list"] = "";
		foreach ($rsts as $rst )
		{
			$rst["fsms"] = $rst["fsms"]?$rst["fsms"]:"默认";
			$data["fusionData"] .= ($rst["fsms"]=="默认")?"":"<set name='".$rst["fsms"]."' value='".$rst["c"]."' />";
			$data["list"] .= "<tr><td>".$rst["fsms"]."</td>
					      <td>".$rst["c"]."</td>
					  </tr>";
		}
		
		$this->load->view("modules/ttmobile/user/fsmsdata_view",$data);
	}
	
	function fcalldata()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m-d",time()-24*60*60);
		if ($this->input->get_post("QueryDate1"))
		{
			$data["date"] = $this->input->get_post("QueryDate1");
		}
		$rsts = $this->TT_user_model->showFcallData($data["date"]);
		$data["fusionData"] = "";
		$data["list"] = "";
		foreach ($rsts as $rst )
		{
			$rst["fcall"] = $rst["fcall"]?$rst["fcall"]:"默认";
			$data["fusionData"] .= ($rst["fcall"]=="默认")?"":"<set name='".$rst["fcall"]."' value='".$rst["c"]."' />";
			$data["list"] .= "<tr><td>".$rst["fcall"]."</td>
					      <td>".$rst["c"]."</td>
					  </tr>";
		}
		
		$this->load->view("modules/ttmobile/user/fcalldata_view",$data);
	}
	
	function callcommondata()
	{
		$this->authuser_class->authSession();
		
		$data["list"] = "";
		$rst = $this->TT_user_model->showCallCommonData();
		for($i=0;$i<count($rst);$i++)
		{
			$newuserper = 0;
			if($rst[$i]["usercount"] != 0)
			{
				$newuserper = round(($rst[$i]["newusercount"]/$rst[$i]["usercount"])*100,2);
			}
			$callrate = round($rst[$i]["c"]/($rst[$i]["c"]+$rst[$i]["faile"])*100,2);
			$data["list"] .= "<tr><td>".$rst[$i]["date_id"]."</td>
					      <td>".$rst[$i]["usercount"]."</td>
					      <td>".$rst[$i]["newusercount"]."($newuserper%)</td>
			                      <td>".$rst[$i]["c"]."</td>
			                      <td>".$rst[$i]["faile"]."</td>
			                      <td>".$callrate."%</td>
					      <td>".$this->function_class->getFormattime3($rst[$i]["avgtime"])."</td></tr>";
		}
		$this->load->view("modules/ttmobile/user/callcommondata_view",$data);
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
	
	function calllog()
	{
		$this->authuser_class->authSession();
		
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
		if (!$this->input->get_post("mobile"))
		{
			$rst = $this->TT_user_model->getCallLogByDate($data["date"],$pagesize,$pagecount,$data["starttime"],$data["endtype"]);
			
			$config['base_url'] = "/ttmobile/user/calllog?starttime=".$data["starttime"]."&endtype=".$data["endtype"]."&QueryDate1=".$data["date"]."&v=1";
			$config['total_rows'] = $this->TT_user_model->getCallLogByDateCount($data["date"],$data["starttime"],$data["endtype"]);
			$config['per_page'] = $pagesize;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['first_link'] = '<<';
			$config['last_link'] = '>>';
			$config['full_tag_open'] = '<div class="pagination">';
			$config['full_tag_close'] = '</div>';
			$this->pagination->initialize($config);
			$data["page"] = $this->pagination->create_links();
		}
		else
		{			
			$data["mobile"] = $this->input->get_post("mobile");
			$rst = $this->TT_user_model->getCallLogByDateMobile($data["date"],$data["mobile"],$data["starttime"],$data["endtype"],$data["fuid"],$pagesize,$pagecount);
			
			$config['base_url'] = "/ttmobile/user/calllog?mobile=".$data["mobile"]."&fuid=".$data["fuid"]."&starttime=".$data["starttime"]."&endtype=".$data["endtype"]."&QueryDate1=".$data["date"]."&v=1";
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
		}
		
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
					       /*(".$rst[$i]["fdevfirm"]."/".$rst[$i]["Fdevmodel"].")*/
		}
		
		$data ["list2"] = "";
		$allcount = 0;
		$rst2 = $this->TT_user_model->getCallLogGroupByMobileByDate($data["date"],$data["starttime"],$data["endtype"]);		
		for($i=0;$i<10 && $i<count($rst2);$i++)
		{
			$allcount += $rst2[$i]["c"];
			$data["list2"] .= "<tr><td>".$rst2[$i]["fsenduid"]."</td>
		                               <td>".$rst2[$i]["c"]."</td></tr>";
		}
		
		$data ["list2"] = "<tr><td>总计</td><td>$allcount</td></tr>".$data["list2"];	
		$this->load->view("modules/ttmobile/user/usercalllog_view",$data);
	}
	
	function calllogsucessexception()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = $this->input->get_post("QueryDate1")? $this->input->get_post("QueryDate1"):date("Y-m-d");
		$data["list"] = "";
		/*$rsts = $this->TT_user_model->getCallLogSucessException($data["date"]);
		for($i=0;$i<count($rsts);$i++)
		{
			$data["list"] .= "<tr><td>".$rsts[$i]["Fsenduid"]."</td>
					      <td>".$rsts[$i]["Frcvuid"]."</td>
					      <td>".date("H:i:s",$rsts[$i]["Festablishtime"])."</td>
					      <td>".date("H:i:s",$rsts[$i]["Fstarttime"]?$rsts[$i]["Fstarttime"]:$rsts[$i]["Fendtime"])."</td>
					      <td>".$rsts[$i]["t"]."秒</td>
					      <td>".$this->getEndType($rsts[$i]["Fendtype"])."</td>
					  </tr>";
		}*/
		$this->load->view("modules/ttmobile/user/calllogsuccessexception_view",$data);
	}
	
	function calldata()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m-d");		
			
		$data["total"] = $this->TT_user_model->getCallLogByDateCount($data["date"],-1,-1,true);
		$data["totalnokf"] = $this->TT_user_model->getCallLogByDateCount($data["date"],-1,-1,false);
		$data["totald"] = $this->TT_user_model->getCallLogByDateCount($data["date"],-1,-1,true,-1,-1,"Fsenduid");
		$data["totalnokfd"] = $this->TT_user_model->getCallLogByDateCount($data["date"],-1,-1,false,-1,-1,"Fsenduid");
		$data["successcall"] = $this->TT_user_model->getCallLogByDateCount($data["date"],1,-1,false);
		$data["below20"] = $this->TT_user_model->getCallLogByDateCount($data["date"],1,-1,false,3,20);		
		$data["above20"] = $this->TT_user_model->getCallLogByDateCount($data["date"],1,-1,false,21);		
		$data["above180"] = $this->TT_user_model->getCallLogByDateCount($data["date"],1,-1,false,181);		
		$data["above300"] = $this->TT_user_model->getCallLogByDateCount($data["date"],1,-1,false,301);	
		$data["morePeople"] = $this->TT_user_model->getMorePeopleCallCount($data["date"]);
		
		$data["total1"] = $this->TT_user_model->getCallLogByDateCount1(date("Y-m-d",strtotime($data["date"])-24*60*60),-1,-1,true);
		$data["totalnokf1"] = $this->TT_user_model->getCallLogByDateCount1(date("Y-m-d",strtotime($data["date"])-24*60*60),-1,-1,false);
		$data["totald1"] = $this->TT_user_model->getCallLogByDateCount1(date("Y-m-d",strtotime($data["date"])-24*60*60),-1,-1,true,-1,-1,"Fsenduid");
		$data["totalnokfd1"] = $this->TT_user_model->getCallLogByDateCount1(date("Y-m-d",strtotime($data["date"])-24*60*60),-1,-1,false,-1,-1,"Fsenduid");
		$data["successcall1"] = $this->TT_user_model->getCallLogByDateCount1(date("Y-m-d",strtotime($data["date"])-24*60*60),1,-1,false);
		$data["below201"] = $this->TT_user_model->getCallLogByDateCount1(date("Y-m-d",strtotime($data["date"])-24*60*60),1,-1,false,3,20);
		$data["above201"] = $this->TT_user_model->getCallLogByDateCount1(date("Y-m-d",strtotime($data["date"])-24*60*60),1,-1,false,21);
		$data["above1801"] = $this->TT_user_model->getCallLogByDateCount1(date("Y-m-d",strtotime($data["date"])-24*60*60),1,-1,false,181);
		$data["above3001"] = $this->TT_user_model->getCallLogByDateCount1(date("Y-m-d",strtotime($data["date"])-24*60*60),1,-1,false,301);
		
		$data["scriptjs"] = '<script src="/res/script/jquery.js" type="text/javascript"></script>
				     <script src="/res/script/jquery.corner.js" type="text/javascript"></script>				     
				     <script src="/res/script/common.js?v='.rand().'" type="text/javascript"></script>
				     <script src="/res/script/ttmobile/usercalldata.js?v='.rand().'" type="text/javascript"></script>
				     <script src="/res/script/tinybox.js" type="text/javascript"></script>';
		
		$rst = $this->TT_user_model->showCallData();
		$data["list"] = "";
		foreach($rst as $r)
		{
			$data["list"] .= "<tr><td>".$r["date_id"]."</td>
			                      <td>".$r["total"]."</td>
			                      <td>".$r["totald"]."</td>
					      <td>".$r["totalnokf"]."</td>
					      <td>".$r["totalnokfd"]."</td>
					      <td>".$r["successcall"]."</td>
					      <td>".(round($r["successcall"]/$r["totalnokf"],4)*100)."%"."</td>
					      <td>".round($r["totalnokf"]/$r["totalnokfd"],2)."</td>
					      <td>".$r["below20"]."</td>
					      <td>".$r["above20"]."</td>
					      <td>".$r["above180"]."</td>
					      <td>".$r["above300"]."</td>
					      <td>".$r["2people"]."</td>
					      <td>".(($r["3people"]==0)?$r["3people"]:("<a href='javascript:void(0)' onclick='GetMorePeopleCallList(\"".$r["date_id"]."\",2,event)'>".$r["3people"]."</a>"))."</td>
					      <td>".(($r["4people"]==0)?$r["4people"]:("<a href='javascript:void(0)' onclick='GetMorePeopleCallList(\"".$r["date_id"]."\",3,event)'>".$r["4people"]."</a>"))."</td>
					      <td>".(($r["5people"]==0)?$r["5people"]:("<a href='javascript:void(0)' onclick='GetMorePeopleCallList(\"".$r["date_id"]."\",4,event)'>".$r["5people"]."</a>"))."</td></tr>";
		}
		$this->load->view ( "modules/ttmobile/user/usercalldata_view", $data );
	}	
			
	function calldatabysp()
	{
		$this->authuser_class->authSession();
		
		$data["spselect"] = $this->function_class->getTTmMarketSelect();
		
		$data["date"] = date("Y-m-d");			
		$data["suser"] = "(渠道人数)";
		$data["sp"] = 600;
		if($this->input->post("sp"))
		{
			$data["sp"] = $this->input->post("sp");
		}
		
		$data["total"] = $this->TT_user_model->getCallLogByDateCountBySp($data["date"],-1,-1,true,-1,-1,"0",$data["sp"]);
		$data["totalnokf"] = $this->TT_user_model->getCallLogByDateCountBySp($data["date"],-1,-1,false,-1,-1,"0",$data["sp"]);
		$data["totald"] = $this->TT_user_model->getCallLogByDateCountBySp($data["date"],-1,-1,true,-1,-1,"Fsenduid",$data["sp"]);
		$data["totalnokfd"] = $this->TT_user_model->getCallLogByDateCountBySp($data["date"],-1,-1,false,-1,-1,"Fsenduid",$data["sp"]);
		$data["successcall"] = $this->TT_user_model->getCallLogByDateCountBySp($data["date"],1,-1,false,-1,-1,"0",$data["sp"]);
		$data["below20"] = $this->TT_user_model->getCallLogByDateCountBySp($data["date"],1,-1,false,3,20,"0",$data["sp"]);		
		$data["above20"] = $this->TT_user_model->getCallLogByDateCountBySp($data["date"],1,-1,false,21,-1,"0",$data["sp"]);	
		$data["morePeople"] = $this->TT_user_model->getMorePeopleCallCountBySp($data["date"],$data["sp"]);		
		
		$data["scriptjs"] = '<script src="/res/script/jquery.js" type="text/javascript"></script>
				     <script src="/res/script/jquery.corner.js" type="text/javascript"></script>				     
				     <script src="/res/script/common.js?v='.rand().'" type="text/javascript"></script>
				     <script src="/res/script/ttmobile/usercalldata.js?v='.rand().'" type="text/javascript"></script>
				     <script src="/res/script/tinybox.js" type="text/javascript"></script>';
		
		$rst = $this->TT_user_model->showCallDataBySp($data["sp"]);
		$data["list"] = "";
		foreach($rst as $r)
		{
			$per = "";
			if($r["totalnokf"] != 0)
			{
				$per = (round($r["successcall"]/$r["totalnokf"],4)*100)."%";
			}
			$data["list"] .= "<tr><td>".$r["date_id"]."</td>
					      <td>".$r["suser"]."</td>
			                      <td>".$r["total"]."</td>
			                      <td>".$r["totald"]."</td>
					      <td>".$r["totalnokf"]."</td>
					      <td>".$r["totalnokfd"]."</td>
					      <td>".$r["successcall"]."</td>
					      <td>$per</td>
					      <td>".$r["below20"]."</td>
					      <td>".$r["above20"]."</td>
					      <td>".$r["2people"]."</td>
					      <td>".$r["3people"]."</td>
					      <td>".$r["4people"]."</td>
					      <td>".$r["5people"]."</td></tr>";
		}
		$this->load->view("modules/ttmobile/user/usercalldatasp_view", $data );
	}
	
	function calldataper5()
	{
		$this->authuser_class->authSession();
		
		if ($this->input->post ("QueryDate1"))
		{
			$date = $_POST["QueryDate1"];
		}
		else
		{
			$date = date("Y-m-d");
		}
		
		$rsts = $this->TT_user_model->getCallDataPer5($date);
		
		$data ["compareCheck"] = "";
		if ($this->input->post("compare"))
		{
			$date2 = $this->input->post("QueryDate2");
			$data["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		}
		else
		{
			$date2 = date("Y-m-d",time()-24*60*60);				
		}		
		$rsts2 = $this->TT_user_model->getCallDataPer5($date2);
		
		$data["fusionCategory"] = "";		
		$this->function_class->fusionCategory($data["fusionCategory"]);		
		$data ["list"] = "";
		$data ["fusionDataset"] = "";		
		$data ["fusionDataset2"] = "";
		$data ["fusionDataset2"] .= "<dataset seriesName='$date2' renderAs='Line' >";
		
		$chartPotCount = 0;
		$k = 0;
		for($i = 0,$rstscount=count($rsts)-1; $i < $rstscount; $i++)
		{			
			$this->function_class->fusionData($rsts[$i]["t"],$data["fusionDataset"],$rsts[$i]["c1"],$chartPotCount);
			while(date("His",strtotime($rsts[$i]["t"])) > date("His",strtotime($rsts2[$k]["t"])))
			{
				++$k;
			}
			if(date("His",strtotime($rsts[$i]["t"])) == date("His",strtotime($rsts2[$k]["t"])))
			{
				$percent = ($rsts2[$k]["c1"] == 0)?100:round(($rsts[$i]["c1"]-$rsts2[$k]["c1"])/$rsts2[$k]["c1"]*100,2);
				$percent2 = ($rsts2[$k]["c2"] == 0)?100:round(($rsts[$i]["c2"]-$rsts2[$k]["c2"])/$rsts2[$k]["c2"]*100,2);
				$data ["list"] = "<tr><td>".$rsts[$i]["t"]."|".$rsts2[$k]["t"]."</td>
						      <td>".$rsts[$i]["c1"]."|".$rsts2[$k]["c1"]."($percent %)</td>
						      <td>".$rsts[$i]["c2"]."|".$rsts2[$k]["c2"]."($percent2 %)</td>
						  </tr>".$data["list"];
				++$k;
				
			}
			else
			{
				$data ["list"] = "<tr><td>".$rsts[$i]["t"]."</td>
						      <td>".$rsts[$i]["c1"]."</td><td>".$rsts[$i]["c2"]."</td></tr>".$data["list"];				
			}			
		}
		if((strtotime($rsts[$rstscount]["t"])+600) < time())
		{
			$data ["list"] = "<tr><td><font color='red'>超过10分钟没有数据！！！</font></td><td></td><td></td></tr>".$data["list"];
		}
		
		$chartPotCount2 = 0;
		for($i = 0,$rsts2count=count($rsts2)-1; $i < $rsts2count; $i++)
		{
			$this->function_class->fusionData($rsts2[$i]["t"],$data["fusionDataset2"],$rsts2[$i]["c1"],$chartPotCount2);
		}
		
		$data["fusionDataset2"] .= "</dataset>";
		
		$data["date"] = $date;
		$data["date2"] = $date2;
		
		$this->load->view("modules/ttmobile/user/calldataper5_view",$data);
	}
	
	function talkSvrstat()
	{
		$this->authuser_class->authSession();
		
		$data["list"] = "";
		$rst = $this->TT_user_model->showTalkSvrStat();
		for($i=0;$i<count($rst);$i++)
		{
			$callrate = round($rst[$i]["s2"]/$rst[$i]["s1"]*100,2);
			$data["list"] .= "<tr><td>".$rst[$i]["someday"]."</td>
			                      <td>".$rst[$i]["s1"]."</td>
			                      <td>".$rst[$i]["s2"]."</td>
			                      <td>".$callrate."%</td></tr>";
		}
		$this->load->view("modules/ttmobile/user/talksvrstat_view",$data);
	}
	
	function userdetail()
	{
		//$this->authuser_class->authSession();
		
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
			$config ['base_url'] = "/ttmobile/user/userdetail?querytype=".$data["querytype"]."&queryLogin=queryLogin&mobile=".$data ["mobile"]."&QueryDate1=".$data["queryDate1"]."&QueryDate2=".$data["queryDate2"];	
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
		
		$this->load->view("modules/ttmobile/user/userdetail_view",$data);
	}
	
	function userregfail()
	{
		$this->authuser_class->authSession();
		
		$data ["page"] = "";
		$data["queryDate1"] = date("Y-m-d",time()-24*60*60);
		if($this->input->get_post("QueryDate1"))
		{
			$data["queryDate1"] = $this->input->get_post("QueryDate1");
		}
		$datalist = "";
		
		if($this->input->get_post("queryEtype"))
		{
			$rst = $this->TT_user_model->getUserRegFailByF($data["queryDate1"],"etype");
			$datalist .= "<tr><th>mac数</th><th>总数</th><th>错误类型</th></tr>";
			foreach($rst as $r)
			{
				$datalist .= "<tr><td>".$r["dc"]."</td>					  
						  <td>".$r["c"]."</td>					  
						  <td>".$r["etype"]."</td></tr>";	
			}
		}
		else if($this->input->get_post("queryDev"))
		{
			$rst = $this->TT_user_model->getUserRegFailByF($data["queryDate1"],"dev");
			$datalist .= "<tr><th>mac数</th><th>总数</th><th>机器型号</th></tr>";
			foreach($rst as $r)
			{
				$datalist .= "<tr><td>".$r["dc"]."</td>					  
						  <td>".$r["c"]."</td>					  
						  <td>".$r["dev"]."</td></tr>";	
			}
		}
		else if($this->input->get_post("queryNullImsi"))
		{			
			$rst = $this->TT_user_model->getUserRegFailNullImsi($data["queryDate1"]);
			$datalist .= "<tr><th>mac数</th><th>总数</th><th>错误信息</th></tr>";
			foreach($rst as $r)
			{
				$datalist .= "<tr><td>".$r["dc"]."</td>					  
						  <td>".$r["c"]."</td>					  
						  <td>".$r["emsg"]."</td></tr>";	
			}
		}
		else if($this->input->get_post("querySource"))
		{
			$rst = $this->TT_user_model->getUserRegFailByF($data["queryDate1"],"msource");
			$datalist .= "<tr><th>mac数</th><th>总数</th><th>来源</th></tr>";
			foreach($rst as $r)
			{
				$datalist .= "<tr><td>".$r["dc"]."</td>					  
						  <td>".$r["c"]."</td>					  
						  <td>".$r["msource"]."</td></tr>";	
			}
		}
		else
		{
			$pagesize = 50;
			$this->load->library('pagination');
			$rst = $this->TT_user_model->getUserRegFail($data["queryDate1"],$pagesize, $this->input->get("per_page") ? $this->input->get("per_page") : 0);
			$datalist .= "<tr><th>详细</th><th>创建时间</th><th>类型</th><th>消息</th><th>厂商</th><th>设备</th>
					  <th>系统</th><th>来源</th><th>imsi</th><th>网络</th><th>版本</th></tr>";
			
			foreach($rst as $r)
			{
				$datalist .= "<tr><td><a href='".$r["content"]."' target='_blank'>详细</a></td>
						  <td>".date("H:i:s",$r["ctime"])."</td>
						  <td>".$r["etype"]."</td>
						  <td>".$r["emsg"]."</td>
						  <td>".$r["firm"]."</td>
						  <td>".$r["dev"]."</td>
						  <td>".$r["os"]."</td>						  
						  <td>".$r["msource"]."</td>					  
						  <td>".$r["imsi"]."</td>					  
						  <td>".$r["netinfo"]."</td>					  
						  <td>".$r["version"]."</td></tr>";	
			}
			$config ['base_url'] = "/ttmobile/user/userregfail?QueryDate1=".$data["queryDate1"];	
			$config ['total_rows'] = $this->TT_user_model->getUserRegFailCount($data["queryDate1"]);			
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
		
		$this->load->view("modules/ttmobile/user/userregfail_view",$data);
	}
	
	function monthcallfeedetail()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m",strtotime("-1 month"));
		$data["list"] = "";
		if ($this->input->post("QueryDate1"))
		{
			$data["date"] = $this->input->post("QueryDate1");
		}		
		$rsts = $this->TT_user_model->showMonthCallFeeDetail(str_replace("-","",$data["date"]));
		foreach ($rsts as $rst)
		{
			$data ["list"] .= "<tr><td>".$rst["mobile"]."</td>
					       <td>".$rst["type1"]."分钟</td>
					       <td>".$rst["fee1"]."元</td>
					       <td>".$rst["type2"]."分钟</td>
					       <td>".$rst["fee2"]."元</td>
					       <td>".$rst["type3"]."分钟</td>
					       <td>".$rst["fee3"]."元</td>
					       <td>".$rst["feetotal"]."元</td>
					   </tr>";
		}
		$this->load->view ("/modules/ttmobile/user/monthcallfeedetail_view", $data );
	}
	
	function usermonthcallfee()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m",strtotime("-1 month"));
		$data["mobile"] = "";
		$data["list"] = "";
		if ($this->input->post("QueryDate1") && $this->input->post("mobile"))
		{
			$data["date"] = $this->input->post("QueryDate1");
			$data["mobile"] = $this->input->post("mobile");
			$rsts = $this->TT_user_model->getUserMonthCallFee($data["mobile"],$data["date"]);
			for($i=0;$i<count($rsts);$i++)
			{
				$data["list"] .= "<tr>
							<td>".$rsts[$i]["Fsenduid"]."(".$rsts[$i]["sr"].")</td>
							<td>".$rsts[$i]["Frcvuid"]."(".$rsts[$i]["rr"].")</td>
							<td>".date("Y-m-d H:i:s",$rsts[$i]["Fstarttime"])."</td>
							<td>".date("Y-m-d H:i:s",$rsts[$i]["Fendtime"])."</td>
							<td>".$rsts[$i]["t"]."分钟</td>
							<td>".$rsts[$i]["t1"]."秒</td>
						</tr>";
			}
		}		
		
		$this->load->view ("/modules/ttmobile/user/usermonthcallfee_view", $data );
	}
	
	function usercallcount()
	{		
		$this->authuser_class->authSession();
		$rsts = $this->TT_user_model->showUserCallCount();
		$data["list"] = "";
		foreach($rsts as $rst)
		{
			$data["list"] .= "<tr><td>".$rst["date_id"]."</td>
					      <td>".$rst["ctotal"]."</td>
					      <td>".$rst["c1"]."</td>
					      <td>".$rst["c2"]."</td>
					      <td>".$rst["c3"]."</td>
					      <td>".$rst["c4"]."</td>
					      <td>".$rst["c5"]."</td>
					      <td>".$rst["c6"]."</td>
					      <td>".$rst["c10"]."</td></tr>";
		}
		$this->load->view ("/modules/ttmobile/user/usercallcount_view", $data );
	}
	
	function userfriend()
	{		
		$this->authuser_class->authSession();
		$rsts = $this->TT_user_model->showUserFriendsData();
		$data["list"] = "";
		foreach($rsts as $rst)
		{
			$data["list"] .= "<tr><td>".$rst["date_id"]."</td>
					      <td>".$rst["f1"]."</td>
					      <td>".$rst["f2"]."</td>
					      <td>".$rst["f3"]."</td>
					      <td>".$rst["f4"]."</td>
					      <td>".$rst["f5"]."</td>
					      <td>".$rst["f6"]."</td>
					      <td>".$rst["f11"]."</td>
					      <td>".$rst["f21"]."</td>
					      <td>".$rst["f31"]."</td>
					      <td>".$rst["f50"]."</td></tr>";
		}
		$this->load->view ("/modules/ttmobile/user/userfriend_view", $data );
	}
	
	function userallfriend()
	{		
		$this->authuser_class->authSession();
		$rsts = $this->TT_user_model->showUserAllFriendsData();
		$data["list"] = "";
		foreach($rsts as $rst)
		{
			$data["list"] .= "<tr><td>".$rst["date_id"]."</td>
					      <td>".$rst["userall"]."</td>
					      <td>".$rst["nofri"]."</td>
					      <td>".$rst["f1more"]."</td>
					      <td>".(round($rst["f1more"]/$rst["userall"],4)*100)."%</td>
					      <td>".$rst["f6more"]."</td>
					      <td>".(round($rst["f6more"]/$rst["userall"],4)*100)."%</td>
					      </tr>";
		}
		$this->load->view ("/modules/ttmobile/user/userallfriend_view", $data );
	}
	
	function useravglogin()
	{
		$this->authuser_class->authSession();
		$rsts = $this->TT_user_model->showAvgUserLogin();
		$data["list"] = "";
		foreach($rsts as $rst)
		{
			$data["list"] .= "<tr><td>".$rst["date_id"]."</td>
					      <td>".$rst["avglogin"]."</td>
					      <td>".$rst["above30"]."</td>
					      <td>".$rst["above50"]."</td>
					      <td>".$rst["above100"]."</td>
					      <td>".$rst["above1000"]."</td>
					      </tr>";
		}
		$this->load->view ("/modules/ttmobile/user/useravglogin_view", $data );
	}
	
	function callfrequency30()
	{
		$this->authuser_class->authSession();
		$rsts = $this->TT_user_model->showCallFrequencyByDays(30);
		$data["list"] = "";
		foreach($rsts as $rst)
		{
			$data["list"] .= "<tr><td>".$rst["date_id"]."</td>";
			for($i=0;$i<30;$i++)
			{
				$v = "d".($i+1);
				$data["list"] .= "<td>".$rst[$v]."</td>";
			}
			$data["list"] .= "</tr>";
		}
		$this->load->view ("/modules/ttmobile/user/callfrequency30_view", $data );
	}
	
}
?>