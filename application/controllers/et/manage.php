<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Manage extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->model ( "et/manage_model", "etmanage" );
	}
	
	function index() {
	}
	
	function nodelist() {
		$data ["list"] = "";
		$data ["jsCode"] = "";
		$this->load->view ( "modules/et/manage/nodelist_view", $data );
	}
	
	function stserver() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 43 );
		
		$this->load->model ( "et/manage_model", "etmanage" );
		
		$data ["server"] = "";
		$data ["isadd"] = "";
		
		if ($this->input->get ( "id" )) {
			$isadd = 1;
			$node = $this->etmanage->getServerById ( $this->input->get ( "id" ) );
			$data ["server"] = $node;
		} else {
			$isadd = 0;
		}
		
		$data ["isadd"] = $isadd;
		$data ["jsCode"] = "";
		
		if ($this->input->post ( "flag" )) {
			$flag = $this->input->post ( "flag" );
			$id = $this->input->post ( "id" );
			$serverid = $this->input->post ( "serverid" );
			$ip = $this->input->post ( "ip" );
			$address = $this->input->post ( "address" );
			$stat = $this->input->post ( "stat" );
			if ($flag == "add") {
				$update_rst = $this->etmanage->addServer ( $serverid, $ip, $address, 1, $stat );
				if ($update_rst == - 1) {
					$data ["jsCode"] .= "alert('编号已存在，请修改');";
				}
			} else if ($flag == "edit") {
				$oldserverid = $this->input->post ( "oldserverid" );
				;
				$update_rst = $this->etmanage->updateServer ( $serverid, $ip, $address, 1, $stat, $id, $oldserverid );
				if ($update_rst == - 1) {
					$data ["jsCode"] .= "alert('编号已存在，请修改');";
				}
			}
		}
		
		$data ["list"] = "";
		$rsts = $this->etmanage->getServerList ( 1 );
		for($i = 0; $i < count ( $rsts ); $i ++) {
			$data ["list"] .= "<tr><td><a href='/et/manage/stserver?id=" . $rsts [$i] ["si_id"] . "'>编辑</a></td><td>" . $rsts [$i] ["si_serverid"] . "</td><td>" . $rsts [$i] ["si_ip"] . "</td>" . "<td>" . $rsts [$i] ["si_address"] . "</td><td>" . ($rsts [$i] ["si_valid"] == 1 ? "正常" : "<font color='red'>停用</font>") . "</td><td>" . $rsts [$i] ["si_updatetime"] . "</td></tr>";
		}
		
		$this->load->view ( 'modules/et/manage/stserver_view', $data );
	}
	
	function faqlist() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 71 );
		
		$data ["faq"] = "";
		$data ["isadd"] = "";
		if ($this->input->get ( "id" )) {
			$isadd = 1;
			$node = $this->etmanage->getFaqById ( $this->input->get ( "id" ) );
			$data ["faq"] = $node;
		} else {
			$isadd = 0;
		}
		$data ["isadd"] = $isadd;
		
		if ($this->input->post ( "title" )) {
			$flag = $this->input->post ( "flag" );
			$id = $this->input->post ( "id" );
			$type = $this->input->post ( "type" );
			$title = $this->input->post ( "title" );
			$content = $this->input->post ( "content" );
			$list = $this->input->post ( "list" );
			
			if ($flag == "add") {
				$this->etmanage->addFaq ( $type, $title, $content, $list );
			} else if ($flag == "edit") {
				$this->etmanage->editFaq ( $id, $type, $title, $content, $list );
			}
		}
		
		$rst = $this->etmanage->getFaqList ();
		
		$data ["list"] = "";
		foreach ( $rst as $eachfaq ) {
			$data ["list"] .= "<tr><td><a href='/et/manage/faqlist?id=" . $eachfaq ["id"] . "'>编辑</a> |
			                          <a href='javascript:void(0)' onclick='checkDel(" . $eachfaq ["id"] . ",\"" . $eachfaq ["title"] . "\")'>删除</a></td>
			                      <td>" . $eachfaq ["list"] . "</td><td>" . $eachfaq ["title"] . "</td>
			                      <td style='width:55%'>" . $eachfaq ["content"] . "</td></tr>";
		}
		
		$this->load->view ( "modules/et/manage/faqlist_view", $data );
	}
	
	function faqdel() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 71 );
		
		if ($this->input->get ( "fid" )) {
			$this->etmanage->delFaq ( $this->input->get ( "fid" ) );
		}
		redirect ( "/et/manage/faqlist" );
	}
	
	function changechannelid() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 72 );
		
		$jsCode = "";
		if ($this->input->post ( "old" ) && $this->input->post ( "new" )) {
			$old = $this->input->post ( "old" );
			$new = $this->input->post ( "new" );
			$ciId = $this->etmanage->changeChannelId_send ( $old, $new );
			$rst = $this->changechannelid_receive ( $ciId );
			if ($rst == "操作成功") {
				//session_start();   
				$account = $_SESSION ["adminname"];
				$this->etmanage->changeChannel_addLog ( $account, "更换频道ID", "更换" . $old . "改为" . $new );
			}
			$jsCode = "alert('$rst')";
		}
		
		$list = $this->etmanage->etserver_getLog ( 50, "更换频道ID" );
		
		$data ["list"] = "";
		for($i = 0; $i < count ( $list ); $i ++) {
			$data ["list"] .= "<tr><td>" . $list [$i] ["CA_Time"] . "</td><td>" . $list [$i] ["CA_UserAccount"] . "</td><td>" . $list [$i] ["CA_CONTENT"] . "</td></tr>";
		}
		$data ["jsCode"] = $jsCode;
		$this->load->view ( "modules/et/manage/changechannelid_view", $data );
	}
	
	function changechannelserver() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 73 );
		
		$data ["cid"] = "";
		$data ["server"] = "";
		$data ["serverList"] = "";
		$data ["changeServer"] = 0;
		
		$serverList = $this->etmanage->getServerList ( 0 );
		for($i = 0; $i < count ( $serverList ); $i ++) {
			$data ["serverList"] .= "<option value='" . $serverList [$i] ["si_serverid"] . "'>" . $serverList [$i] ["si_serverid"] . " " . $serverList [$i] ["si_address"] . " " . $serverList [$i] ["si_ip"] . "</option>";
		}
		
		if ($this->input->get ( "cid" )) {
			$data ["cid"] = $this->input->get ( "cid" );
			$ciId = $this->etmanage->getChannelInfo_send ( $data ["cid"] );
			$rst = $this->getChannelInfo_receive ( $ciId );
			if (count ( $rst ) > 0) {
				$data ["server"] = $rst ["ServerID"];
				for($i = 0; $i < count ( $serverList ); $i ++) {
					if ($serverList [$i] ["si_serverid"] == $rst ["ServerID"]) {
						$data ["server"] .= "/" . $serverList [$i] ["si_address"] . "/" . $serverList [$i] ["si_ip"];
						break;
					}
				}
			}
		}
		
		$jsCode = "";
		if ($this->input->post ( "tsserver" ) && $this->input->post ( "cid" )) {
			$data ["changeServer"] = $this->input->post ( "tsserver" );
			$data ["cid"] = $this->input->post ( "cid" );
			
			$ciId = $this->etmanage->changeChannelServer_send ( $data ["cid"], $data ["changeServer"] );
			$rst = $this->changechannelserver_receive ( $ciId );
			if ($rst == "操作成功") {
				//session_start();   
				$account = $_SESSION ["adminname"];
				$this->etmanage->changeChannel_addLog ( $account, "更换频道服务器", "频道ID" . $data ["cid"] . "服务器更换到" . $data ["changeServer"] );
			}
			$jsCode = "alert('$rst')";
		}
		
		$data ["jsCode"] = $jsCode;
		$this->load->view ( "modules/et/manage/changechannelserver_view", $data );
	}
	
	function broadcastadd() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 74 );
		
		$data ["title"] = "";
		$data ["content"] = "";
		$data ["span"] = "";
		$data ["time"] = "";
		$data ["type"] = "";
		$data ["count"] = "";
		$data ["jsCode"] = "";
		
		if ($this->input->post ( "time" ) && $this->input->post ( "count" ) && $this->input->post ( "title" )) {
			$data ["title"] = $this->input->post ( "title" );
			$data ["content"] = $this->input->post ( "content" );
			$data ["span"] = $this->input->post ( "span" );
			$data ["time"] = $this->input->post ( "time" );
			$data ["count"] = $this->input->post ( "count" );
			$data ["type"] = $this->input->post ( "type" );
			
			$msId = $this->etmanage->broadcastAdd_send ( $data ["title"], $data ["content"], $data ["time"], $data ["count"], $data ["span"], $data ["type"] );
			$rst = $this->broadcastadd_receive ( $msId, $data ["title"], $data ["content"], $data ["time"], $data ["count"], $data ["span"], $data ["type"] );
			if ($rst) {
				$data ["jsCode"] = "alert('发送成功')";
			} else {
				$data ["jsCode"] = "alert('发送失败')";
			}
		}
		
		$data ["recentMsg"] = "";
		$recentList = $this->etmanage->getCurrentBroadList ();
		if (count ( $recentList ) > 0) {
			for($i = 0; $i < count ( $recentList ); $i ++) {
				$data ["recentMsg"] .= "<tr><td>" . $recentList [$i] ["RM_TITLE"] . "</td>
							   <td width='50%'>" . $recentList [$i] ["RM_CONTENT"] . "</td>
							   <td>" . $this->getBroadcastType ( $recentList [$i] ["RM_TYPE"] ) . "</td>
							   <td>" . $recentList [$i] ["RM_TIME"] . "</td>
							   <td>" . $recentList [$i] ["RM_COUNT"] . "</td>
							   <td>" . $recentList [$i] ["RM_SPAN"] . "</td>
							   <td>" . $recentList [$i] ["RM_CREATER"] . "</td>
						       </tr>";
			}
		} else {
			$data ["recentMsg"] = "<tr><td>无</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		}
		
		$data ["historyMsg"] = "";
		$historyList = $this->etmanage->getHistoryBroadList ();
		if (count ( $historyList ) > 0) {
			for($i = 0; $i < count ( $historyList ); $i ++) {
				$data ["historyMsg"] .= "<tr>
							   <td>" . $historyList [$i] ["RM_TITLE"] . "</td>
							   <td width='50%'>" . $historyList [$i] ["RM_CONTENT"] . "</td>
				                           <td>" . $this->getBroadcastType ( $historyList [$i] ["RM_TYPE"] ) . "</td>
							   <td>" . $historyList [$i] ["RM_TIME"] . "</td>
							   <td>" . $historyList [$i] ["RM_COUNT"] . "</td>
							   <td>" . $historyList [$i] ["RM_SPAN"] . "</td>
							   <td>" . $historyList [$i] ["RM_CREATER"] . "</td>
						       </tr>";
			}
		} else {
			$data ["historyMsg"] = "<tr><td>无</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		}
		$this->load->view ( "modules/et/manage/broadcastadd_view", $data );
	}
	
	/*相关的私有方法供Control调用————开始*/
	private function changechannelid_receive($ciId) //修改频道ID，接收结果
	{
		$temp = time ();
		while ( (time () - $temp) < 2 ) {
			$rst = $this->etmanage->changeChannelId_receive ( $ciId );
			switch ($rst) {
				case 1 :
					return "操作成功";
				case 2 :
					return "新id已存在";
				case 3 :
					return "旧id不存在";
				case 4 :
					return "操作暂时不可用";
				case 5 :
					return "内部错误";
				default :
					break;
			}
			sleep ( 2 );
		}
		$this->etmanage->changeChannelId_del ( $ciId );
		return "";
	}
	
	private function getChannelInfo_receive($ciId) //获取频道服务器，接收结果
	{
		$temp = time ();
		while ( (time () - $temp) < 2 ) {
			$rst = $this->etmanage->getChannelInfo_receive ( $ciId );
			switch ($rst) {
				case 1 :
					return $this->etmanage->getChannelInfo_detail ( $ciId );
				default :
					break;
			}
			sleep ( 2 );
		}
		$this->etmanage->getChannelInfo_del ( $ciId );
		return "";
	}
	
	private function changechannelserver_receive($ciId) //修改频道Server，接收结果
{
		$temp = time ();
		while ( (time () - $temp) < 2 ) {
			$rst = $this->etmanage->changeChannelServer_receive ( $ciId );
			switch ($rst) {
				case 1 :
					return "操作成功";
				case 2 :
					return "服务器不存在";
				case 3 :
					return "频道不存在";
				case 4 :
					return "操作暂时不可用";
				case 5 :
					return "内部错误";
				default :
					break;
			}
			sleep ( 2 );
		}
		$this->etmanage->changeChannelServer_del ( $ciId );
		return "";
	}
	
	private function broadcastadd_receive($msId, $title, $content, $time, $count, $span, $type) {
		$temp = time ();
		while ( (time () - $temp) < 2 ) {
			$rst = $this->etmanage->broadcastAdd_receive ( $msId );
			if ($rst == 1) {
				$creater = $_SESSION ["adminname"];
				$createdate = date ( "Y-m-d H:i:s" );
				return $this->etmanage->broadcastAdd_insert ( $title, $content, $time, $count, $span, $creater, $createdate, $type, $msId );
			} else if ($rst == 0) {
				return false;
			}
			sleep ( 2 );
		}
		$this->etmanage->broadcastAdd_del ( $msId );
		return false;
	}
	
	private function getBroadcastType($id) {
		$type = "";
		switch ($id) {
			case 3 :
				$type = "官方公告";
				break;
			case 4 :
				$type = "活动公告";
				break;
			default :
				break;
		}
		
		return $type;
	}
	/*相关的私有方法供Control调用————结束*/
	
	function sysmsg_send() {
		$this->load->view ( "modules/et/manage/sysmsg_send_view" );
	}
}

?>