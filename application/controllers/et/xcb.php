<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Xcb extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
	}
	
	function index() {
	}
	
	function logindata() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 1001 );
		
		$this->load->model ( "et/data_model", "etdata" );
		
		$date = date ( "Y-m-d" );
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		}
		$rsts = $this->etdata->getLoginDataSum ( $date, 88 );
		
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data ["fusionDataset"] = "";
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
			$val = $rsts [$i] ["usersum"];
			$data ["fusionDataset"] .= "<set value='$val'/>";
		}
		
		$date2 = date ( "Y-m-d", strtotime ( $date ) - 24 * 60 * 60 );
		$rsts2 = $this->etdata->getLoginDataSum ( $date2, 88 );
		$data ["fusionDataset2"] = "";
		$data ["fusionDataset2"] .= "<dataset seriesName='$date2' renderAs='Line' >";
		$chartPotCount2 = 0;
		for($i = 0; $i < count ( $rsts2 ); $i ++) {
			$val = "";
			$tempH = date ( "H", strtotime ( $rsts2 [$i] ["t2"] ) );
			$tempI = date ( "i", strtotime ( $rsts2 [$i] ["t2"] ) );
			while ( ($tempH * 12 + $tempI / 5) != $chartPotCount2 ) {
				++ $chartPotCount2;
				$data ["fusionDataset2"] .= "<set value='' />";
			}
			++ $chartPotCount2;
			$val = $rsts2 [$i] ["usersum"];
			$data ["fusionDataset2"] .= "<set value='$val' />";
		}
		$data ["fusionDataset2"] .= "</dataset>";
		
		$data ["date"] = $date;
		
		$this->load->view ( "modules/et/xcb/logindata_view", $data );
	}
	
	function tsdata() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 1002 );
		
		$this->load->model ( "et/data_model", "etdata" );
		
		$data ["list"] = "";
		$date = date ( "Y-m-d" );
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		}
		$rsts = $this->etdata->getTsDataSum ( $date, 88 );
		
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data ["fusionDataset"] = "";
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
			$val = $rsts [$i] ["usersum"];
			$data ["fusionDataset"] .= "<set value='$val'/>";
		}
		
		$date2 = date ( "Y-m-d", strtotime ( $date ) - 24 * 60 * 60 );
		$rsts2 = $this->etdata->getTsDataSum ( $date2, 88 );
		$data ["fusionDataset2"] = "";
		$data ["fusionDataset2"] .= "<dataset seriesName='$date2' renderAs='Line' >";
		$chartPotCount2 = 0;
		for($i = 0; $i < count ( $rsts2 ); $i ++) {
			$val = "";
			$tempH = date ( "H", strtotime ( $rsts2 [$i] ["t2"] ) );
			$tempI = date ( "i", strtotime ( $rsts2 [$i] ["t2"] ) );
			while ( ($tempH * 12 + $tempI / 5) != $chartPotCount2 ) {
				++ $chartPotCount2;
				$data ["fusionDataset2"] .= "<set value='' />";
			}
			++ $chartPotCount2;
			$val = $rsts2 [$i] ["usersum"];
			$data ["fusionDataset2"] .= "<set value='$val' />";
		}
		$data ["fusionDataset2"] .= "</dataset>";
		
		$data ["date"] = $date;
		
		$this->load->view ( "modules/et/xcb/tsdata_view", $data );
	}
	
	function commondata() {
		//$this->authuser_class->checkLogin();		
		//$this->authuser_class->checkPermission(64);
		

		$this->load->model ( "et/data_model", "etdata" );
		$rsts = $this->etdata->showXcbUserLoginData ();
		
		$data ["list"] = "";
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst ["date_id"] . "</td><td>" . $rst ["loginTimes"] . "</td><td>" . $rst ["loginPtId"] . "</td><td>" . $rst ["loginIP"] . "</td>
			                      <td>" . $rst ["avgUser"] . "</td><td>" . $rst ["maxUser"] . "</td><td>" . $rst ["avgTsUser"] . "</td><td>" . $rst ["maxTsUser"] . "</td><td>" . $rst ["createHall"] . "</td><td>" . $rst ["newuser"] . "</td></tr>";
		}
		
		$this->load->view ( "modules/et/xcb/commondata_view", $data );
	}
	
	function guildlist() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 1003 );
		
		$this->load->model ( "et/manage_model", "etmanage" );
		
		if ($this->input->post ( "name" )) {
			$name = $this->input->post ( "name" );
			$queryId = $this->input->post ( "queryId" );
			$area = $this->input->post ( "area" );
			$server = $this->input->post ( "server" );
			$rank = $this->input->post ( "rank" );
			
			$this->etmanage->addGuild ( $name, $queryId, $area, $server, $rank, 88 );
		}
		
		$rst = $this->etmanage->getGuildList ( 88 );
		
		$data ["list"] = "";
		foreach ( $rst as $eachguild ) {
			$data ["list"] .= "<tr><td><a href='javascript:void(0)' onclick='checkDel(" . $eachguild ["id"] . ",\"" . $eachguild ["name"] . "\")'>删除</a></td>
			                      <td>" . $eachguild ["rank"] . "</td><td>" . $eachguild ["name"] . "</td>
			                      <td>" . $eachguild ["queryId"] . "</td><td>" . $eachguild ["area"] . "</td>
					      <td>" . $eachguild ["server"] . "</td></tr>";
		}
		
		$this->load->view ( "modules/et/xcb/guildlist_view", $data );
	}
	
	function guilddel() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 1003 );
		
		if ($this->input->get ( "gid" )) {
			$this->load->model ( "et/manage_model", "etmanage" );
			$this->etmanage->delGuild ( $this->input->get ( "gid" ) );
		}
		redirect ( "/et/xcb/guildlist" );
	}

}
?>