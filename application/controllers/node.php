<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Node extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("node_model");
	}
	
	function index(){}
	
	function nodelist()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(42);
		
		$data["node"] = "";
		$data["isadd"] = "";
		if($this->input->get("id"))
		{
			$isadd = 1;
			$node = $this->node_model->getNodeById( $this->input->get("id"));
			$data ["node"] = $node;
		}
		else
		{
			$isadd = 0;
		}
		$data["isadd"] = $isadd;
		
		$data ["jsCode"] = "";
		if ($this->input->post ( "flag" )) {
			$flag = $this->input->post ( "flag" );
			$id = $this->input->post ( "id" );
			$ip = $this->input->post ( "ip" );
			$name = $this->input->post ( "name" );
			$port = $this->input->post ( "port" );
			$pingport = $this->input->post ( "ping_port" );
			$stat = $this->input->post ( "stat" );
			$group = $this->input->post ( "group" );
			if ($flag == "add") {
				$update_rst = $this->node_model->insertNode ( $id, $ip, $name, $port, $pingport, $group, $stat );
				if ($update_rst == - 1) {
					$data ["jsCode"] .= "alert('编号已存在，请修改');";
				}
			} else if ($flag == "edit") {
				$oldid = $_POST ["oldid"];
				$update_rst = $this->node_model->updateNode ( $id, $ip, $name, $port, $pingport, $group, $stat, $oldid );
				if ($update_rst == - 1) {
					$data ["jsCode"] .= "alert('编号已存在，请修改');";
				}
			}
		}
		
		$data ["groupList"] = "";
		$groupList = $this->node_model->getGroupList ();
		foreach ( $groupList as $group1 ) {
			$data ["groupList"] .= "<option value='" . $group1 ["id"] . "'>" . $group1 ["name"] . "</option>";
		}
		
		$data ["list"] = "";
		$rsts = $this->node_model->getNodeList ();
		for($i = 0; $i < count ( $rsts ); $i ++) {
			$data ["list"] .= "<tr><td><a href='/node/nodelist?id=" . $rsts [$i] ["id"] . "'>编辑</a></td><td>" . $rsts [$i] ["id"] . "</td><td>" . $rsts [$i] ["ip"] . "</td>" . "<td>" . $rsts [$i] ["name"] . "</td><td>" . $rsts [$i] ["groupname"] . "</td><td>" . $rsts [$i] ["port"] . "</td><td>" . $rsts [$i] ["ping_port"] . "</td>" . "<td>" . ($rsts [$i] ["valid"] == 1 ? "正常" : "<font color='red'>停用</font>") . "</td><td>" . $rsts [$i] ["last_update_time"] . "</td></tr>";
		}
		
		$this->load->view ( 'modules/node/nodelist_view', $data );
	}
	
	function grouplist() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 41 );
		
		$data ["group"] = "";
		$data ["isadd"] = "";
		if ($this->input->get ( "id" )) {
			$isadd = 1;
			$group = $this->node_model->getGroupById ( $this->input->get ( "id" ) );
			$data ["group"] = $group;
		} else {
			$isadd = 0;
		}
		$data ["isadd"] = $isadd;
		
		$data ["jsCode"] = "";
		if ($this->input->post ( "flag" )) {
			$flag = $this->input->post ( "flag" );
			$id = $this->input->post ( "id" );
			$name = $this->input->post ( "name" );
			$stat = $this->input->post ( "stat" );
			if ($flag == "add") {
				$update_rst = $this->node_model->insertGroup ( $id, $name, $stat );
				if ($update_rst == - 1) {
					$data ["jsCode"] .= "alert('编号已存在，请修改');";
				}
			} else if ($flag == "edit") {
				$oldid = $_POST ["oldid"];
				$update_rst = $this->node_model->updateGroup ( $id, $name, $stat, $oldid );
				if ($update_rst == - 1) {
					$data ["jsCode"] .= "alert('编号已存在，请修改');";
				}
			}
		}
		
		$data ["list"] = "";
		$rsts = $this->node_model->getGroupList ();
		for($i = 0; $i < count ( $rsts ); $i ++) {
			$data ["list"] .= "<tr><td><a href='/node/grouplist?id=" . $rsts [$i] ["id"] . "'>编辑</a></td><td>" . $rsts [$i] ["id"] . "</td><td>" . $rsts [$i] ["name"] . "</td><td>" . ($rsts [$i] ["valid"] == 1 ? "正常" : "<font color='red'>停用</font>") . "</td>" . "<td>" . $rsts [$i] ["last_update_time"] . "</td></tr>";
		}
		
		$this->load->view ( 'modules/node/grouplist_view', $data );
	}
	
	function speednodepush()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(56);
		
		$this->load->library("function_class");
		ini_set ( "memory_limit",-1 );
		if ($this->input->post ( "generatenode" ))
		{
			$this->node_model->generateGameNodeDataList ( date ( "Y-m-d", time () - 24 * 60 * 60 ) );
		}
		if ($this->input->post ( "generatefile" ))
		{
			$this->function_class->clearDir('./res/speednode/');
			$rsts = $this->node_model->getGameNodePushData ();
			$content = "";
			$file = "";
			$nodecount = 0;
			for($i = 0; $i < count ( $rsts ); $i ++)
			{
				if ($i == 0)
				{
					$file = "node_" . $rsts [$i] ["areaid"] . "_" . $rsts [$i] ["netmode"] . ".xml";
					$content = '<?xml version="1.0" encoding="UTF-8"?>
						    <Nodes>
							<GameInfo id="' . $rsts [$i] ["gameid"] . '">
								<GameArea id="' . $rsts [$i] ["gameareaid"] . '">
									<Node id="' . $rsts [$i] ["nodeid"] . '" />';
				}
				else
				{
					if ($rsts [$i] ["areaid"] == $rsts [$i - 1] ["areaid"] && $rsts [$i] ["netmode"] == $rsts [$i - 1] ["netmode"])
					{
						if ($rsts [$i] ["gameid"] == $rsts [$i - 1] ["gameid"])
						{
							if ($rsts [$i] ["gameareaid"] == $rsts [$i - 1] ["gameareaid"]) {
								if($nodecount <2) {
									$content .= '<Node id="' . $rsts [$i] ["nodeid"] . '" />';
									++$nodecount;
								}
							} else {
								$nodecount=0;
								$content .= '</GameArea>									
									     <GameArea id="' . $rsts [$i] ["gameareaid"] . '">
										<Node id="' . $rsts [$i] ["nodeid"] . '" />';
							}
						} else {
							$nodecount=0;
							$content .= '</GameArea></GameInfo>
								     <GameInfo id="' . $rsts [$i] ["gameid"] . '">
									<GameArea id="' . $rsts [$i] ["gameareaid"] . '">
										<Node id="' . $rsts [$i] ["nodeid"] . '" />';
						}
					
					} else {
						$nodecount=0;
						$content .= "</GameArea></GameInfo></Nodes>";
						$this->function_class->saveFile('./res/speednode/', $file, $content );
						
						$file = "node_" . $rsts [$i] ["areaid"] . "_" . $rsts [$i] ["netmode"] . ".xml";
						$content = '<?xml version="1.0" encoding="UTF-8"?>
							    <Nodes>
								<GameInfo id="' . $rsts [$i] ["gameid"] . '">
									<GameArea id="' . $rsts [$i] ["gameareaid"] . '">
										<Node id="' . $rsts [$i] ["nodeid"] . '" />';
					}
				}
			}
		}
		
		$data ["arealist"] = - 1;
		$data ["netlist"] = - 1;
		$data ["gamelist"] = - 1;
		
		$data ["areaL"] = "";
		$data ["gameL"] = "";
		
		$areaL = $this->node_model->getEtAreaInfo ();
		foreach ( $areaL as $area ) {
			$data ["areaL"] .= "<option value='" . $area ["areaid"] . "'>" . $area ["areaname"] . "</option>";
		}
		
		$this->load->model ( "game_model" );
		$gameL = $this->game_model->getGameInfoList ( - 1, "et" );
		foreach ( $gameL as $game ) {
			$data ["gameL"] .= "<option value='" . $game ["GAME_ID"] . "'>" . $game ["GAME_NAME"] . "</option>";
		}
		
		if ($this->input->get_post ( "arealist" ) > - 2) {
			$data ["arealist"] = $this->input->get_post ( "arealist" );
		}
		if ($this->input->get_post ( "netlist" ) > - 2) {
			$data ["netlist"] = $this->input->get_post ( "netlist" );
		}
		if ($this->input->get_post ( "gamelist" ) > - 2) {
			$data ["gamelist"] = $this->input->get_post ( "gamelist" );
		}
		
		$pagesize = 50;
		$this->load->library ( 'pagination' );
		$config ['base_url'] = "/node/speednodepush?v=1&arealist=" . $data ["arealist"] . "&netlist=" . $data ["netlist"] . "&gamelist=" . $data ["gamelist"];
		$config ['total_rows'] = $this->node_model->getGameNodePushDataCount ( $data ["arealist"], $data ["netlist"], $data ["gamelist"] );
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
		
		$list = $this->node_model->getGameNodePushData ( $pagesize, $this->input->get ( "per_page" ) ? $this->input->get ( "per_page" ) : 0, $data ["arealist"], $data ["netlist"], $data ["gamelist"] );
		for($i = 0; $i < count ( $list ); $i ++) {
			$net = "";
			switch ($list [$i] ["netmode"]) {
				case 0 :
					$net = "电信";
					break;
				case 1 :
					$net = "网通";
					break;
				case 2 :
					$net = "其他";
					break;
				default :
					break;
			}
			$data ["list"] .= "<tr><td>" . $list [$i] ["areaname"] . "</td>
					      <td>$net</td>
					      <td>" . $list [$i] ["game_name"] . "</td>
					      <td>" . $list [$i] ["server_name"] . "</td>
					      <td>" . $list [$i] ["nodename"] . "</td>
					  </tr>";
		}
		
		$this->load->view ( 'modules/node/speednodepush_view', $data );
	}
	
	function noderestart() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 44 );
		
		$data ["list"] = "";
		$rst = $this->node_model->showNodeRestart ();
		
		for($i = 0; $i < count ( $rst ); $i ++) {
			$data ["list"] .= "<tr><td>" . $rst [$i] ["nodeid"] . "</td>
			                      <td>" . $rst [$i] ["ip"] . "</td>
					      <td>" . $rst [$i] ["name"] . "</td>
					      <td>" . date ( "Y-m-d H:i:s", $rst [$i] ["restarttime"] ) . "</td>
					      <td>" . $rst [$i] ["restartcount"] . "</td></tr>	";
		}
		
		$this->load->view ( "modules/node/restart_view", $data );
	}
}
?>