<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Game extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->model ( "game_model" );
	}
	
	function index() {
	}
	
	function gameconfig() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 55 );
		
		$this->load->library ( "function_class" );
		$data ["jsCode"] = "";
		
		$data ["gamename"] = "";
		$data ["gameid"] = "";
		$data ["gameorder"] = "";
		$data ["findkey"] = "";
		$data ["hallid"] = 0;
		$data ["hallname"] = "";
		$data ["mod"] = "";
		$data ["gameuse"] = 0;
		$data ["gamestart"] = 0;
		$data ["list"] = "";
		$data ["serverList"] = "";
		$data ["serverid"] = "";
		$data ["servername"] = "";
		$data ["serverline"] = "";
		$data ["serveruse"] = 0;
		$data ["proList"] = "";
		
		if ($this->input->post ( "addsubmit" )) {
			$this->game_model->addGameInfo ( $this->input->post ( "gamename" ), $this->input->post ( "gameorder" ), $this->input->post ( "findkey" ), $this->input->post ( "gameuse" ), $this->input->post ( "hallid" ), $this->input->post ( "hallname" ), $this->input->post ( "mod" ), $this->input->post ( "gamestart" ) );
		}
		if ($this->input->post ( "editsubmit" )) {
			$this->game_model->updateGameInfo ( $this->input->post ( "gameid" ), $this->input->post ( "gamename" ), $this->input->post ( "gameorder" ), $this->input->post ( "findkey" ), $this->input->post ( "gameuse" ), $this->input->post ( "hallid" ), $this->input->post ( "hallname" ), $this->input->post ( "mod" ), $this->input->post ( "gamestart" ) );
		}
		if ($this->input->post ( "saddsubmit" )) {
			$this->game_model->addGameServer ( $this->input->post ( "gameid" ), $this->input->post ( "servername" ), $this->input->post ( "serveruse" ), $this->input->post ( "serverline" ) ? $this->input->post ( "serverline" ) : 2 );
		}
		if ($this->input->post ( "seditsubmit" )) {
			$this->game_model->updateGameServer ( $this->input->post ( "serverid" ), $this->input->post ( "servername" ), $this->input->post ( "serveruse" ), $this->input->post ( "serverline" ) ? $this->input->post ( "serverline" ) : 2 );
		}
		if ($this->input->post ( "paddsubmit" )) {
			$this->game_model->addGamePro ( $this->input->post ( "gameid" ), $this->input->post ( "proname" ) );
		}
		
		$gameList = $this->game_model->getGameInfoList ();
		for($i = 0; $i < count ( $gameList ); $i ++) {
			$data ["list"] .= "<tr><td><a href='?id=" . $gameList [$i] ["GAME_ID"] . "'>编辑</a>
			                          <a href='javascript:void(0)' onclick='gameConfigDel(" . $gameList [$i] ["GAME_ID"] . ")'>删除</a>
					      </td>
					      <td>" . $gameList [$i] ["GAME_ORDER"] . "</td>		
					      <td>" . $gameList [$i] ["GAME_NAME"] . "</td>		
					      <td>" . $gameList [$i] ["GAME_START"] . "</td>	
					      <td>" . $gameList [$i] ["GAME_MOD"] . "</td>
					      <td>" . (($gameList [$i] ["GAME_USE"] == 1) ? "使用中" : "未使用") . "</td></tr>";
		}
		
		if ($this->input->get ( "id" ) > 0 || $this->input->post ( "gameid" ) > 0) {
			$data ["gameid"] = $this->input->get ( "id" ) ? $this->input->get ( "id" ) : $this->input->post ( "gameid" );
			$node = $this->game_model->getGameInfoById ( $data ["gameid"] );
			$data ["gamename"] = $node->GAME_NAME;
			$data ["gameorder"] = $node->GAME_ORDER;
			$data ["findkey"] = $node->GAME_KEYWORD;
			$data ["hallid"] = $node->GAME_HALLID ? $node->GAME_HALLID : 0;
			$data ["hallname"] = $node->GAME_HALLNAME ? $node->GAME_HALLNAME : "";
			$data ["mod"] = $node->GAME_MOD;
			$data ["gameuse"] = $node->GAME_USE;
			$data ["gamestart"] = $node->GAME_START;
			
			$serverRst = $this->game_model->getGameServer ( $data ["gameid"] );
			for($i = 0; $i < count ( $serverRst ); $i ++) {
				$data ["serverList"] .= "<tr><td>
								<a href='?id=" . $data ["gameid"] . "&sid=" . $serverRst [$i] ["SERVER_ID"] . "'>编辑</a>
								<a href='javascript:void(0)' onclick='gameServerDel(" . $data ["gameid"] . "," . $serverRst [$i] ["SERVER_ID"] . ")'>删除</a>
							    </td>
				                            <td>" . $serverRst [$i] ["SERVER_ID"] . "</td>
							    <td>" . $serverRst [$i] ["SERVER_NAME"] . "</td>
							    <td>" . $serverRst [$i] ["SERVER_LINE"] . "</td>
							    <td>" . (($serverRst [$i] ["SERVER_USE"] == 1) ? "使用中" : "未使用") . "</td></tr>";
			}
			
			$proRst = $this->game_model->getGameProList ( $data ["gameid"] );
			for($i = 0; $i < count ( $proRst ); $i ++) {
				$data ["proList"] .= "<tr><td>
								<a href='javascript:void(0)' onclick='gameProDel(" . $data ["gameid"] . "," . $proRst [$i] ["SIGN_ID"] . ")'>删除</a>
							 </td>
							 <td>" . $proRst [$i] ["SIGN_NAME"] . "</td></tr>";
			}
			
			$data ["jsCode"] .= "$('#serverInfo').css('display','block');
				            $('#serverList').css('display','block');
				            $('#proList').css('display','block');					    
					    $('#proInfo').css('display','block');
					    $('#addsubmit').css('display','none');
					    $('#editsubmit').css('display','inline');";
		}
		
		if ($this->input->get ( "sid" ) > 0 || $this->input->post ( "serverid" ) > 0) {
			$data ["serverid"] = $this->input->get ( "sid" ) ? $this->input->get ( "sid" ) : $this->input->post ( "serverid" );
			$server = $this->game_model->getGameServerInfoById ( $data ["serverid"] );
			$data ["servername"] = $server->SERVER_NAME;
			$data ["serverline"] = $server->SERVER_LINE;
			$data ["serveruse"] = $server->SERVER_USE;
			
			$data ["jsCode"] .= "$('#saddsubmit').css('display','none');
					    $('#seditsubmit').css('display','inline');";
		}
		
		$this->load->view ( "modules/game/gameconfig_view", $data );
	}
	
	
	function gameconfigdel() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 55 );
		
		$para = "";
		
		if ($this->input->get ( "id" )) {
			if ($this->input->get ( "sid" )) {
				$this->game_model->delGameServer ( $this->input->get ( "sid" ) );
			} else if ($this->input->get ( "pid" )) {
				$this->game_model->delGamePro ( $this->input->get ( "pid" ) );
			} else {
				$this->game_model->delGameInfo ( $this->input->get ( "id" ) );
			}
			$para = "?id=" . $this->input->get ( "id" );
		}
		
		echo "<script type='text/javascript'>";
		echo "window.location.href = '/game/gameconfig$para'";
		echo "</script>";
	}
	
	
	
	function gameconfigoutput() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 55 );
				
		$this->load->library( "function_class" );
		$file_type = "xml";
		$file_ending = "xml";
		$xml = "";
		$rst = "";		
		$ikey = 24680;
		if ($this->input->get ( "type" ) == 'game') {
			//header ( "Content-Type: application/$file_type;charset=UTF-8" );
			//header ( "Content-Disposition: attachment; filename=game.$file_ending" );
			$gameList = $this->game_model->getGameInfoList ( 1 );
			for($i = 0; $i < count ( $gameList ); $i ++) {
				$proList = $this->game_model->getGameProList ( $gameList [$i] ["GAME_ID"] );
				$proStr = "";
				for($k = 0; $k < count ( $proList ); $k ++) {
					$proStr .= $proList [$k] ["SIGN_NAME"] . ",";
				}
				$proStr = substr ( $proStr, 0, - 1 );
				$xml .= '<GameInfo id="' . $gameList [$i] ["GAME_ID"] . '" start="' . $gameList [$i] ["GAME_START"] . '" name="' . $gameList [$i] ["GAME_NAME"] . '" findkey="' . $gameList [$i] ["GAME_KEYWORD"] . '" hallid="' . $gameList [$i] ["GAME_HALLID"] . '" hallname="' . $gameList [$i] ["GAME_HALLNAME"] . '" process="' . $proStr . '" vcode="" mod="' . $gameList [$i] ["GAME_MOD"] . '"/>';
			}
			$rst = $this->function_class->stringToByteArray('<?xml version="1.0" encoding="UTF-8"?><Games>' . $xml . '</Games>');
			//print_r($rst);
			for($i=0;$i<count($rst);$i++)
			{
				$rst[$i] = ($rst[$i] ^ ($ikey >> 8));
				$ikey = ($rst[$i] + $ikey) * 12345 + 54321;
			}
			//$r = $this->function_class->byteArrayToString($rst);
			print_r($rst);		
		}
		if ($this->input->get ( "type" ) == 'server') {
			header ( "Content-Type: application/$file_type;charset=UTF-8" );
			header ( "Content-Disposition: attachment; filename=server.$file_ending" );
			$gameList = $this->game_model->getGameInfoList ( 1 );
			for($i = 0; $i < count ( $gameList ); $i ++) {
				$serverList = $this->game_model->getGameServer ( $gameList [$i] ["GAME_ID"] );
				$serverStr = "";
				for($k = 0; $k < count ( $serverList ); $k ++) {
					$serverStr .= '<GameArea id="' . $serverList [$k] ["SERVER_ID"] . '" name="' . $serverList [$k] ["SERVER_NAME"] . '" line="' . ($serverList [$k] ["SERVER_LINE"] ? $serverList [$k] ["SERVER_LINE"] : 2) . '"/>';
				}
				$serverStr = str_replace ( "&", "", $serverStr );
				$xml .= '<GameID id="' . $gameList [$i] ["GAME_ID"] . '" name="' . $gameList [$i] ["GAME_NAME"] . '">
						' . $serverStr . '
					</GameID>';
			}
			echo '<?xml version="1.0" encoding="UTF-8"?><Games><GameID id="999999" name="默认游戏"><GameArea id="999999" name="所有区服"/></GameID>' . $xml . '</Games>';
		}
	}
	
	
	function gamedata()
	{
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 51 );
		$this->load->model ( "node_model" );
		$this->load->library ( "function_class" );
		
		$nodeList = $this->node_model->getNodeIdName ();
		$nodeValue = "0,";
		$ipValue = "0,";
		$searchType = "searchByNode";
		$postList = "";
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		if ($this->input->post ( "type" ))
		{
			$date = $this->input->post ( "QueryDate1" );
			$searchType = $this->input->post ( "type" );
			$nodeValue = $this->input->post ( "nodeValue" );
			$ipValue = $this->input->post ( "ipValue" );
			if ($searchType == 'searchByNode') {
				$postList = rtrim ( $nodeValue, "," );
			} else {
				$postList = rtrim ( $ipValue, "," );
			
			}
			if ($postList == "0") {
				if (strtotime ( $date ) < strtotime ( "2011-08-24" )) {
					$rsts = $this->game_model->showGameSpeedingProxy1 ( date ( "Y-m-d", strtotime ( $date ) ) );
				} else {
					$rsts = $this->game_model->showGameSpeedingProxy ( date ( "Y-m-d", strtotime ( $date ) ) );
				}
			} else {
				if (strtotime ( $date ) < strtotime ( "2011-08-24" )) {
					$rsts = $this->game_model->showGameSpeedingByNodeProxy1 ( date ( "Y-m-d", strtotime ( $date ) ), $postList );
				} else {
					$rsts = $this->game_model->showGameSpeedingByNodeProxy ( date ( "Y-m-d", strtotime ( $date ) ), $postList );
				}
			}
		}
		
		$data ["nodeList"] = "";
		$data ["ipList"] = "";
		foreach ( $nodeList as $node ) {
			$data ["nodeList"] .= "<option value='" . $node ["group_id"] . "'>" . $node ["name"] . "</option>";
			$data ["ipList"] .= "<option value='" . $node ["group_id"] . "'>" . $node ["ip"] . "</option>";
		}
		
		$data ["list"] = "";
		if ($this->input->post ( "QueryDate1" )) {
			$gameAllSum = 0;
			
			foreach ( $rsts as $record ) {
				$gameAllSum += $record ["counts"];
			}
			
			foreach ( $rsts as $record ) {
				$data ["list"] .= "<tr><td>" . $record ["game_name"] . "</td><td>" . $record ["counts"] . "</td><td>" . $record ["num"] . "</td><td>" . round ( $record ["counts"] / $gameAllSum * 100, 2 ) . "%</td></tr>";
			}
		}
		
		$data ["jsCode"] = "";
		if ($this->input->post ( "type" )) {
			if ($searchType == 'searchByNode') {
				$data ["jsCode"] .= '$("#ipSearch").attr("checked",false);$("#nodeSearch").attr("checked",true);disableBtn("ipType");';
			} else {
				$data ["jsCode"] .= '$("#nodeSearch").attr("checked",false);$("#ipSearch").attr("checked",true);disableBtn("nodeType");';
			}
		}
		
		$data ["date"] = $date;
		$data ["searchType"] = $searchType;
		$data ["nodeValue"] = $nodeValue;
		$data ["ipValue"] = $ipValue;
		$data ["postList"] = $postList;
		
		$this->load->view ( 'modules/game/gamedata_view', $data );
	}
	
	
	function gameonlinespeeding() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 53 );
		$this->load->library ( "function_class" );
		$this->load->model ( "game_model" );
		
		$data ["gameList"] = "";
		$gameList = $this->game_model->getGameInfoList (1);
		for($i = 0; $i < count ( $gameList ); $i ++) {
			$data ["gameList"] .= "<option value='" . $gameList [$i] ["GAME_ID"] . "'>" . $gameList [$i] ["GAME_NAME"] . "</option>";
		}
		
		$data ["list"] = "";
		$data ["jsCode"] = "";
		$data ["fusionCategory"] = "";
		$data ["fusionDataset"] = "";
		$data ["date"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
			$game = $this->input->post ( "game" );
			
			$data ["date"] = $date;
			$rsts = $this->game_model->getGameSpeedingOnlinePer5 ( date ( "Y-m-d H:i:s", strtotime ( $date ) ), $game );
			$rstTemp = $this->function_class->bubble_sort ( $rsts, "t2" );
			
			for($i = 0; $i < count ( $rsts ); $i ++) {
				$data ["list"] .= "<tr><td>" . $rstTemp [$i] ["t2"] . "</td><td>" . $rstTemp [$i] ["counts"] . "</td></tr>";
			}
			
			$data ["jsCode"] .= '$("#QueryDate1").val("' . $date . '");';
			$data ["jsCode"] .= '$("#game").val("' . $game . '");';
			
			$chartPotCount = 0;
			for($i = 0; $i < count ( $rsts ) - 1; $i ++) {
				$val = "";
				$tempH = date ( "H", strtotime ( $rsts [$i] ["t2"] ) );
				$tempI = date ( "i", strtotime ( $rsts [$i] ["t2"] ) );
				while ( ($tempH * 12 + $tempI / 5) != $chartPotCount ) {
					++ $chartPotCount;
					$data ["fusionDataset"] .= "<set value='' />";
				}
				++ $chartPotCount;
				$val = $rsts [$i] ["counts"];
				$data ["fusionDataset"] .= "<set value='$val'/>";
			}
		}
		
		$this->load->view ( "modules/game/gameonlinespeeding_view", $data );
	}
	
	
	function gameallnode() {
		//$this->authuser_class->checkLogin();		
		//$this->authuser_class->checkPermission(54);
		$this->load->library ( "function_class" );
		$this->load->model ( "game_model" );
		
		$data ["list"] = "";
		$data ["jsCode"] = "";
		$searchType = "searchByDate";
		if ($this->input->post ( "type" )) {
			$searchType = $this->input->post ( "type" );
			$game = $this->input->post ( "game" );
			$date1 = $this->input->post ( "QueryDate1" );
			$date2 = $this->input->post ( "QueryDate2" );
			$date3 = $this->input->post ( "QueryDate3" );
			if ($searchType == "searchByDate") {
				if ($this->input->post ( "time" )) {
					$queryTime = $date1 . " " . $this->input->post ( "hour1" ) . ":" . $this->input->post ( "minute1" ) . ":00";
					$rsts = $this->game_model->getNodeSpeedingByGame ( date ( "Y-m-d H:i:s", strtotime ( $date1 ) ), $game, $queryTime, date ( "Y-m-d H:i:s", strtotime ( $date1 ) ) );
				} else {
					$rsts = $this->game_model->getNodeSpeedingByGame ( date ( "Y-m-d H:i:s", strtotime ( $date1 ) ), $game, 0, date ( "Y-m-d H:i:s", strtotime ( $date1 ) ) );
				}
			} else {
				$rsts = $this->game_model->getNodeSpeedingByGame ( date ( "Y-m-d H:i:s", strtotime ( $date2 ) ), $game, 0, date ( "Y-m-d H:i:s", strtotime ( $date3 ) ) );
			}
			
			$gameAllSum = 0;
			
			foreach ( $rsts as $record ) {
				$gameAllSum += $record ["counts"];
			}
			
			foreach ( $rsts as $record ) {
				$data ["list"] .= "<tr><td>" . $record ["name"] . "</td><td>" . $record ["ip"] . "</td><td>" . $record ["counts"] . "</td><td>" . $record ["num"] . "</td><td>" . round ( $record ["counts"] / $gameAllSum * 100 ) . "%</td></tr>";
			}
			
			$data ["jsCode"] .= '$("#game").val("' . $game . '");';
			if ($searchType == 'searchByDate') {
				$data ["jsCode"] .= '$("#dateSearch").attr("checked",true);';
				$data ["jsCode"] .= '$("#QueryDate1").val("' . $date1 . '");';
			} else {
				$data ["jsCode"] .= '$("#periodSearch").attr("checked",true);';
				$data ["jsCode"] .= '$("#QueryDate2").val("' . $date2 . '");';
				$data ["jsCode"] .= '$("#QueryDate3").val("' . $date3 . '");';
			}
		}
		if ($this->input->post ( "time" )) {
			$data ["jsCode"] .= "$('input[name=\"time\"]').attr(\"checked\",\"true\");";
			$data ["jsCode"] .= '$("#hour1").val("' . $this->input->post ( "hour1" ) . '");';
			$data ["jsCode"] .= '$("#minute1").val("' . $this->input->post ( "minute1" ) . '");';
		}
		
		$data ["gameList"] = "";
		$gameList = $this->game_model->getGameInfoList ();
		for($i = 0; $i < count ( $gameList ); $i ++) {
			$data ["gameList"] .= "<option value='" . $gameList [$i] ["GAME_ID"] . "'>" . $gameList [$i] ["GAME_NAME"] . "</option>";
		}
		
		$data ["hour1"] = "";
		$data ["minute1"] = "";
		for($i = 0; $i < 24; $i ++) {
			$t = str_pad ( $i, 2, '0', STR_PAD_LEFT );
			$data ["hour1"] .= "<option value='$t'>$t</option>";
		}
		for($i = 0; $i < 59; $i ++) {
			$t = str_pad ( $i, 2, '0', STR_PAD_LEFT );
			$data ["minute1"] .= "<option value='$t'>$t</option>";
		}
		
		$this->load->view ( "modules/game/gameallnode_view", $data );
	}


	function downdata() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 52 );
		
		$para = time ();
		echo "<script>window.location.href='http://et.sdo.com/admins/speed/downdata.aspx?t=$para&mac=" . $this->authuser_class->getMD5Value ( $para ) . "'</script>";
	}
}
?>