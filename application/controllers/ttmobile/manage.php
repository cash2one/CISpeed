<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Manage extends CI_Controller
{	
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "ttmobile/manage_model", "TT_manage_model" );
		$this->load->model ( "ttmobile/user_model", "TT_user_model" );
	}
	
	function index() {}
	
	function userinfo()
	{
		$data ["date"] = date ( "Y-m-d" );
		$data ["date2"] = date ( "Y-m-d" );
		
		$this->load->view( "modules/ttmobile/manage/userinfo_view", $data );
	}
	
	/*手机通通激活码管理————开始*/
	function applykeylist()
	{
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 81 );		
		
		if ($this->input->post ( "submit" )) {
			if ($this->input->post ( "check" )) {
				$list = $this->input->post ( "check" );
				for($i = 0; $i < count ( $list ); $i ++) {
					$t = explode ( "|", $list [$i] );
					$id = $t [1];
					//$name = $t[2];
					$mobiles = explode ( ";", $t [0] );
					for($k = 0; $k < count ( $mobiles ); $k ++) {
						$key = $this->function_class->genRandomString ( 4 );
						$checkrst = $this->TT_manage_model->sendKey ( $mobiles [$k], $key, $id );
						if ($checkrst == 0) {
							$msgheader = "";
							if ($k != 0) {
								$msg = "您的朋友" . $mobiles [0] . "邀请您使用手机通通,邀请码是$key,下载地址http://17et.com/down .";
							} else {
								$msg = "您的邀请码是:$key,请下载通通后使用邀请码激活;http://17et.com/down .";
							}
							$this->function_class->sendTTkey ( $msg, $mobiles [$k] );
						
						}
					}
					$this->TT_manage_model->applyApprove ( $id );
				}
			}
		}
		if ($this->input->post ( "del" )) {
			if ($this->input->post ( "check" )) {
				$list = $this->input->post ( "check" );
				for($i = 0; $i < count ( $list ); $i ++) {
					$t = explode ( "|", $list [$i] );
					$id = $t [1];
					$this->TT_manage_model->delApplyKey ( $id );
				}
			}
		}
		
		$pagesize = 50;
		
		$this->load->library ( 'pagination' );
		$config ['base_url'] = "/ttmobile/manage/applykeylist?v=1";
		$config ['total_rows'] = $this->TT_manage_model->getApplyKeyCount ( 0 );
		$config ['per_page'] = $pagesize;
		$config ['num_links'] = 5;
		$config ['page_query_string'] = true;
		$config ['first_link'] = '<<';
		$config ['last_link'] = '>>';
		$config ['full_tag_open'] = '<div style="float:left;margin-top:4px" class="pagination">';
		$config ['full_tag_close'] = '</div>';
		$this->pagination->initialize ( $config );
		$data ["page"] = $this->pagination->create_links ();
		
		$data ["list"] = "";
		$rst = $this->TT_manage_model->getApplyKeyList ( 0, $pagesize, $this->input->get ( "per_page" ) ? $this->input->get ( "per_page" ) : 0 );
		for($i = 0; $i < count ( $rst ); $i ++) {
			$data ["list"] .= "<tr><td>
						<input type='checkbox' name='check[]' value='" . $rst [$i] ["mobile"] . "|" . $rst [$i] ["id"] . "'></input>
					      </td>					      
					      <td>" . $rst [$i] ["name"] . "</td>
					      <td>" . date ( "Y-m-d H:i:s", $rst [$i] ["applytime"] ) . "</td>
					      <td>" . $rst [$i] ["mobile"] . "</td>
					      <td>" . iconv ( "GB2312", "UTF-8", str_replace ( "-", " ", $this->function_class->convertip ( $rst [$i] ["ip"] ) ) ) . "(" . $rst [$i] ["ip"] . ")</td>	
					      <td>" . $rst [$i] ["id"] . "</td>
					      </tr>";
		}
		$this->load->view ( "modules/ttmobile/manage/applykeylist_view", $data );
	}
	
	function approvedkeylist()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission( 82 );
		
		$data ["queryMobile"] = "";
		$data ["mobile"] = "";
		$data ["key"] = "";
		
		if ($this->input->post ( "mobile" )) {
			if ($this->input->post ( "resend" )) {
				$this->load->library ( 'function_class' );
				$msg = "";
				$mobile = $this->input->post ( "mobile" );
				if ($this->input->post ( "key" )) {
					$msg = "您的邀请码是:" . $this->input->post ( "key" ) . ",请下载通通后使用邀请码激活;http://17et.com/down .";
				} else {
					$key = $this->function_class->genRandomString ( 4 );
					$checkrst = $this->TT_manage_model->sendKey ( $mobile, $key );
					if ($checkrst == 0) {
						$msg = "您的邀请码是:" . $key . ",请下载通通后使用邀请码激活;http://17et.com/down .";
					}
				}
				if ($msg != "") {
					$this->function_class->sendTTkey ( $msg, $mobile );
				}
			}
			
			$data ["mobile"] = $this->input->post ( "mobile" );
			$queryRst = $this->TT_manage_model->queryKey ( $data ["mobile"] );
			if (count ( $queryRst ) > 0) {
				$this->load->library ( 'function_class' );
				$status = $queryRst->Factivestatus == 1 ? "已激活" : "<font color='red'>未激活</font>";
				$data ["queryMobile"] = "姓名：" . $queryRst->name . "，手机号：" . $queryRst->Fleft_uid . "，手机归属地：" . $queryRst->region . "，激活码：" . $queryRst->Factivecode . "，状态：" . $status . "<br/>申请时间：" . date ( "Y-m-d H:i:s", $queryRst->applytime ) . "，审核时间：" . date ( "Y-m-d H:i:s", $queryRst->Factivetime ) . "<br/>申请者IP：" . iconv ( "GB2312", "UTF-8", str_replace ( "-", " ", $this->function_class->convertip ( $queryRst->ip ) ) ) . "(" . $queryRst->ip . ")";
				$data ["key"] = $queryRst->Factivecode;
			} else {
				$data ["queryMobile"] = "无激活码";
			}
			$data ["queryMobile"] .= '<input type="submit" value="重发激活码" name="resend" class="button" />';
		}
		
		$queryStat = 0;
		if ($this->input->get ( "stat" )) {
			$queryStat = $this->input->get ( "stat" );
		}
		$pagesize = 50;
		
		$this->load->library ( 'function_class' );
		
		$allcount = $this->TT_manage_model->getActiveStatusCount ();
		$data ["count1"] = $allcount [0] ["c"];
		$data ["count2"] = $allcount [1] ["c"];
		$data ["allcount"] = $allcount [0] ["c"] + $allcount [1] ["c"] . "，激活率:" . (round ( $data ["count1"] / ($allcount [0] ["c"] + $allcount [1] ["c"]), 4 ) * 100) . "%";
		
		$data ["list"] = "";
		$data ["page"] = "";
		$data ["applyname"] = $this->input->post ( "applyname" );
		if ($this->input->post ( "applyname" )) {
			$rst = $this->TT_manage_model->queryActivByName ( $data ["applyname"] );
		} else {
			$rst = $this->TT_manage_model->getActiveList ( $pagesize, $this->input->get ( "per_page" ) ? $this->input->get ( "per_page" ) : 0, $queryStat );
			$this->load->library ( 'pagination' );
			$config ['base_url'] = "/ttmobile/manage/approvedkeylist?stat=$queryStat";
			$config ['total_rows'] = $this->TT_manage_model->getActiveListCount ( $queryStat );
			$config ['per_page'] = $pagesize;
			$config ['num_links'] = 5;
			$config ['page_query_string'] = true;
			$config ['first_link'] = '<<';
			$config ['last_link'] = '>>';
			$config ['full_tag_open'] = '<div class="pagination">';
			$config ['full_tag_close'] = '</div>';
			$this->pagination->initialize ( $config );
			$data ["page"] = $this->pagination->create_links ();
		}
		for($i = 0; $i < count ( $rst ); $i ++) {
			$statusTime = "";
			if ($rst [$i] ["fstatustime"]) {
				$statusTime = date ( "Y-m-d H:i:s", $rst [$i] ["fstatustime"] );
			}
			$s = $rst [$i] ["factivestatus"] == 1 ? "已激活" : "<font color='red'>未激活</font>";
			$data ["list"] .= "<tr><td>" . $rst [$i] ["name"] . "</td>		
					      <td>" . $rst [$i] ["fleft_uid"] . "</td>	
					      <td>" . $rst [$i] ["region"] . "</td>			      
			                      <td>" . $rst [$i] ["factivecode"] . "</td>				      
					      <td>$s</td>
			                      <td>" . $statusTime . "</td>				      
			                      <td>" . iconv ( "GB2312", "UTF-8", str_replace ( "-", " ", $this->function_class->convertip ( $rst [$i] ["ip"] ) ) ) . "(" . $rst [$i] ["ip"] . ")</td>					       
			                      <td>" . date ( "Y-m-d H:i:s", $rst [$i] ["applytime"] ) . "</td>	
			                      <td>" . date ( "Y-m-d H:i:s", $rst [$i] ["factivetime"] ) . "</td>	
					      </tr>";
		}
		$this->load->view ( "modules/ttmobile/manage/applykeyapproved_view", $data );
	}
	/*手机通通激活码管理————结束*/
	
	function uploadapk()
	{
		$this->authuser_class->authSession();
		$data["content"] = "";
		$data["spselect"] = "";
		$data["spselected"] = 101;
		$this->load->library('function_class');
		$sparray = $this->function_class->TTMarket();
		foreach($sparray as $spkey => $spvalue)
		{
			$data["spselect"] .= "<option value='$spkey'>$spkey $spvalue</option>";
		}
		
		if($this->input->post("upload"))
		{
			$endfile = end(explode(".",$_FILES["file"]["name"]));
			$fname = "";
			if($endfile == "apk")
			{
				$fname = "SndaTT.apk";
			}
			else if($endfile == "sisx")
			{
				$fname = "SYBTT.sisx";
			}
			$sp = $this->input->post("sp");
			$data["spselected"] = $sp; 
			if ($_FILES["file"]["error"] > 0)
			{
				$data["content"] .= "错误: " . $_FILES["file"]["error"] . "<br />";
			}
			else if($endfile != "apk" && $endfile != "sisx")
			{
				$data["content"] .= "错误: 上传的不是apk或者sisx文件<br />";
			}
			else
			{
				$data["content"] .= "文件名: " . $_FILES["file"]["name"] . "<br />";
				$data["content"] .= "类型: " . $_FILES["file"]["type"] . "<br />";
				$data["content"] .= "大小: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
				$data["content"] .= "临时位置: " . $_FILES["file"]["tmp_name"]."<br />";
			    
				if (!file_exists("/www/17et/res/down/$sp/"))
				{
					mkdir("/www/17et/res/down/$sp/",0777);
					chmod("/www/17et/res/down/$sp/",0777);
				}
				if (!file_exists("/www/17et/down/$sp/"))
				{
					mkdir("/www/17et/down/$sp/",0777);
					chmod("/www/17et/down/$sp/",0777);
					
					$fp=fopen("/www/17et/down/$sp/index.html",'w+');//fopen()的其它开关请参看相关函数
					$str= "<script type='text/javascript'>
						   window.location.href ='/res/down/$sp/$fname';
					       </script>";
					fputs($fp,$str);
					fclose($fp); 
				}
				if(move_uploaded_file($_FILES["file"]["tmp_name"],"/www/17et/res/down/$sp/".$_FILES["file"]["name"]) &&
				   copy("/www/17et/res/down/$sp/".$_FILES["file"]["name"],"/www/17et/res/down/$sp/$fname"))
				{
					$data["content"] .= "文件位置:/www/17et/res/down/$sp/".$_FILES["file"]["name"].
					                    "和/www/17et/res/down/$sp/$fname<br />";
					$data["content"] .= "下载地址:<a href='http://17et.com/down/$sp' target='_blank'>http://17et.com/down/$sp</a>";
				}
			}			
		}
		
		$this->load->view("modules/ttmobile/manage/uploadapk_view.php",$data);
	}	
	
	function sendcallfee()
	{
		$this->authuser_class->authSession();
		$data["date"] = date("Y-m-d",time()-24*60*60);
		if($this->input->post("QueryDate1"))
		{
			$data["date"] = $this->input->post("QueryDate1");
		}
		$rsts = $this->TT_user_model->showSendCallFeeUser($data["date"]);		
		$this->load->library('function_class');
		$data["list"] = "";
		foreach($rsts as $r)
		{
			$m = substr($r["uid"],0,3);
			$tel = $this->function_class->getTelCom($m);
			$data["list"] .= "<tr><td>".$r["uid"]."</td><td>$tel</td></tr>";
		}
		$this->load->view("modules/ttmobile/manage/sendcallfee_view.php",$data);
	}
	
	function sysmsg()
	{
		$this->authuser_class->authSession();
		$data["stime"] = date("Y-m-d H:i:s");
		$data["etime"] = date("Y-m-d H:i:s",time()+60*60);
		$data["queryType"] = 1;
		$data["list"] = "";
		
		if($this->input->get("type") && $this->input->get("id"))
		{
			if($this->input->get("type") == "cancel")
			{
				$this->TT_manage_model->cancelSysMsg($this->input->get("id"));
			}
			if($this->input->get("type") == "cancelmode")
			{
				$this->TT_manage_model->cancelSysMsgMode($this->input->get("id"));
			}
		}
		
		if($this->input->post("submit"))
		{
		    $S_StartTime = $this->input->post("stime");
		    $S_EndTime = $this->input->post("etime");
		    $S_Mode = $this->input->post("mode");
		    $S_Receiver = $this->input->post("txtReceiver");
		    $S_SysType = $this->input->post("type");
		    $S_Title = $this->input->post("title");
		    $S_Content = $this->input->post("content");
		    $S_CreateTime = date("Y-m-d H:i:s");
		    $S_Creater = "";//$_SESSION['adminname'];
		    $S_Link = $this->input->post("url");
		    $S_ShowType =  $this->input->post("show");
		    $S_VoicePrompt = $this->input->post("voice");
		    $S_SendType  =  $this->input->post("sendtype");
		    if($S_StartTime && $S_EndTime && $S_Title && $S_Content)
		    {
			$this->TT_manage_model->sendSysMsg($S_StartTime,$S_EndTime,$S_Mode,$S_Receiver,$S_Content,$S_SysType,$S_Title,$S_CreateTime,$S_Creater,$S_ShowType,$S_Link,$S_VoicePrompt,$S_SendType);
		    }
		}
		
		if($this->input->post("querySubmit"))
		{
			$data["queryType"] = $this->input->post("query");
		}
		$rsts = $this->TT_manage_model->showSysMsgList($data["queryType"]);
		$data["status"] = $this->function_class->TTSysMsg_Stauts();		
		$data["mode"] = $this->function_class->TTSysMsg_Mode();	
		$data["systype"] = $this->function_class->TTSysMsg_Type();
		$data["showtype"] = $this->function_class->TTSysMsg_ShowType();
		$data["voicetype"] = $this->function_class->TTSysMsg_VoiceType();
		foreach($rsts as $rst)
		{
			$data["list"]  .= "<tr>
						<td>".$rst["S_ID"].(($data["queryType"]==1||$data["queryType"]==2)?"(<a href='/ttmobile/manage/sysmsg?type=cancel&id=".$rst["S_ID"]."'>取消</a>)":"").
								   (($data["queryType"]==1)?"(<a href='/ttmobile/manage/sysmsg?type=cancelmode&id=".$rst["S_ID"]."'>取消定时</a>)":"")."</td>
						<td>".$rst["S_StartTime"]."</td>
						<td>".$rst["S_EndTime"]."</td>
						<td>".$rst["S_Title"]."</td>
						<td>".$rst["S_Content"]."</td>
						<td>".$rst["S_Link"]."</td>
						<td>".$data["systype"][$rst["S_SysType"]]."</td>
						<td>".$data["showtype"][$rst["S_ShowType"]]."</td>
						<td>".$data["voicetype"][$rst["S_VoicePrompt"]]."</td>
					   </tr>";
		}
		
		$this->load->view("/modules/ttmobile/manage/sysmsg_view",$data);
	}
}
?>