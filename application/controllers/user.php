<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class User extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->model ( "user_model" );
	}
	
	function index() {
	}
	
	function userlose() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 35 );
		
		$rsts = $this->user_model->showUserLose ();
		
		$data ["list"] = "";
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst ["date_id"] . "</td><td>" . $rst ["login"] . "</td>";
			for($i = 1; $i <= 30; $i ++) {
				if ($rst ["d$i"] == 0) {
					$data ["list"] .= "<td></td><td></td>";
				} else {
					$data ["list"] .= "<td>" . $rst ["d$i"] . "</td><td>" . round ( $rst ["d$i"] / $rst ["login"] * 100, 2 ) . "%</td>";
				}
			}
			$data ["list"] .= "</tr>";
		}
		
		$this->load->view ( "modules/user/userlose_view", $data );
	}
	
	function userloseversion1() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 38 );
		
		$rsts = $this->user_model->showUserLoseVersion1 ();
		
		$data ["list"] = "";
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst ["date_id"] . "</td><td>" . $rst ["login"] . "</td>";
			for($i = 1; $i <= 30; $i ++) {
				if ($rst ["d$i"] == 0) {
					$data ["list"] .= "<td></td><td></td>";
				} else {
					$data ["list"] .= "<td>" . $rst ["d$i"] . "</td><td>" . round ( $rst ["d$i"] / $rst ["login"] * 100, 2 ) . "%</td>";
				}
			}
			$data ["list"] .= "</tr>";
		}
		
		$this->load->view ( "modules/user/userloseversion1_view", $data );
	}
	
	function userloseversion2() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 39 );
		
		$rsts = $this->user_model->showUserLoseVersion2 ();
		
		$data ["list"] = "";
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst ["date_id"] . "</td><td>" . $rst ["login"] . "</td>";
			for($i = 1; $i <= 30; $i ++) {
				if ($rst ["d$i"] == 0) {
					$data ["list"] .= "<td></td><td></td>";
				} else {
					$data ["list"] .= "<td>" . $rst ["d$i"] . "</td><td>" . round ( $rst ["d$i"] / $rst ["login"] * 100, 2 ) . "%</td>";
				}
			}
			$data ["list"] .= "</tr>";
		}
		
		$this->load->view ( "modules/user/userloseversion2_view", $data );
	}
	
	function userLoginIp() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 311 );
		
		$data ["date"] = "";
		if ($this->input->get ( "QueryDate1" )) {
			$data ["date"] = $this->input->get ( "QueryDate1" );
			$this->load->library ( "function_class" );
			$rsts = $this->user_model->getLoginUserIp ( $data ["date"] );
			for($i = 0; $i < count ( $rsts ); $i ++) {
				$rsts [$i] ["ip"] = $this->function_class->IntToIP ( $rsts [$i] ["ip"] );
				$rsts [$i] ["a"] = str_replace ( "-", " ", $this->function_class->convertip ( $rsts [$i] ["ip"] ) );
			}
			$savename = $data ["date"];
			$file_type = "vnd.ms-excel";
			$file_ending = "CSV";
			header ( "Content-Type: application/$file_type;charset=GBK" );
			header ( "Content-Disposition: attachment; filename=" . $savename . ".$file_ending" );
			
			foreach ( $rsts as $item ) {
				echo iconv ( "utf-8", "gb2312//IGNORE", '"' . implode ( '","', $item ) . '"' ); //转换字符编码，防止中文乱码				
				echo "\n";
			}
			return;
		}
		
		$this->load->view ( "modules/user/userloginip_view", $data );
	}
	
	function newuserretain() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 310 );
		
		$rsts = $this->user_model->showNewUserRetain ();
		
		$data ["list"] = "";
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst ["date_id"] . "</td><td>" . $rst ["new"] . "</td>";
			for($i = 1; $i <= 30; $i ++) {
				if ($rst ["d$i"] == 0) {
					$data ["list"] .= "<td></td><td></td>";
				} else {
					$data ["list"] .= "<td>" . $rst ["d$i"] . "</td><td>" . round ( $rst ["d$i"] / $rst ["new"] * 100, 2 ) . "%</td>";
				}
			}
			$data ["list"] .= "</tr>";
		}
		
		$this->load->view ( "modules/user/newuserretain_view", $data );
	}
	
	function newuserip() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 36 );
		
		$data ["date"] = "";
		$data ["list"] = "";
		$data ["page"] = "";
		if ($this->input->get ( "QueryDate1" )) {
			$data ["date"] = $this->input->get ( "QueryDate1" );
			$this->load->library ( "function_class" );
			/*$this->load->library('pagination');
			$config['base_url'] = "/user/newuserip?QueryDate1=".$this->input->get("QueryDate1");			
						
			$config['total_rows'] = $this->user_model->getNewUserIpCount($data["date"]);
			$config['per_page'] = '500';
			$config['num_links'] = 5;
			$config['page_query_string'] = TRUE;
			$config['first_link'] = '首页';
			$config['last_link'] = '末页';
			$config['full_tag_open'] = '<div>';
			$config['full_tag_close'] = '</div>';
			$this->pagination->initialize($config);		
			$data["page"] = $this->pagination->create_links();
			
			$rsts = $this->user_model->getNewUserIp($data["date"],500,$this->input->get("per_page")?$this->input->get("per_page"):0);
			
			
			$data["list"] = "";			
			for($i=0;$i<count($rsts);$i++)
			{
				$rsts[$i]["last_login_ip"] = $this->function_class->IntToIP($rsts[$i]["last_login_ip"]);
				$rsts[$i]["a"] = $this->function_class->convertip($rsts[$i]["last_login_ip"]);				
			}
			$rstTemp = $this->function_class->bubble_sort($rsts,"a");
			
			foreach($rstTemp as $rst)
			{
				$data["list"] .= "<tr><td>".$rst["snda_id"]."</td><td>".$rst["snda_passport"]."</td><td>".$rst["last_login_ip"]."</td><td>".$rst["a"]."</td></tr>";
			}*/
			$rsts = $this->user_model->getNewUserIp ( $data ["date"] );
			for($i = 0; $i < count ( $rsts ); $i ++) {
				$rsts [$i] ["last_login_ip"] = $this->function_class->IntToIP ( $rsts [$i] ["last_login_ip"] );
				$rsts [$i] ["a"] = str_replace ( "-", " ", $this->function_class->convertip ( $rsts [$i] ["last_login_ip"] ) );
			}
			$savename = $data ["date"];
			$file_type = "vnd.ms-excel";
			$file_ending = "CSV";
			header ( "Content-Type: application/$file_type;charset=GBK" );
			header ( "Content-Disposition: attachment; filename=" . $savename . ".$file_ending" );
			
			foreach ( $rsts as $item ) {
				echo iconv ( "utf-8", "gb2312//IGNORE", '"' . implode ( '","', $item ) . '"' ); //转换字符编码，防止中文乱码				
				echo "\n";
			}
			return;
		}
		
		$this->load->view ( "modules/user/newuserip_view", $data );
	}
	
	function newusercount() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 37 );
		
		$this->load->library ( "function_class" );
		
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		} else {
			$date = date ( "Y-m-d" );
		}
		
		$rsts = $this->user_model->getNewUserCount ( $date );
		$rstTemp = $this->function_class->bubble_sort ( $rsts, "t" );
		
		$data ["list"] = "";
		$data ["sum"] = 0;
		$data ["sum2"] = "";
		for($i = 0; $i < count ( $rsts ); $i ++) {
			$data ["sum"] = $data ["sum"] + $rstTemp [$i] ["c"];
			$data ["list"] .= "<tr><td>" . $rstTemp [$i] ["t"] . "</td><td>" . $rstTemp [$i] ["c"] . "</td></tr>";
		}
		if ($this->input->post ( "compare" )) {
			$data ["sum2"] = 0;
			$date2 = $this->input->post ( "QueryDate2" );
			$rsts2 = $this->user_model->getNewUserCount ( $date2 );
			for($i = 0; $i < count ( $rsts ); $i ++) {
				if (date ( "Hi", strtotime ( $rsts2 [$i] ["t"] ) ) <= date ( "Hi", strtotime ( $rsts [count ( $rsts ) - 1] ["t"] ) )) {
					$data ["sum2"] = $data ["sum2"] + $rsts2 [$i] ["c"];
				} else {
					break;
				}
			}
		} else {
			$date2 = date ( "Y-m-d" );
		}
		
		$data ["compareCheck"] = "";
		
		if ($this->input->post ( "compare" )) {
			$data ["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		}
		
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
			$tempH = date ( "H", strtotime ( $rsts [$i] ["t"] ) );
			$tempI = date ( "i", strtotime ( $rsts [$i] ["t"] ) );
			while ( ($tempH * 12 + $tempI / 5) != $chartPotCount ) {
				++ $chartPotCount;
				$data ["fusionDataset"] .= "<set value='' />";
			}
			++ $chartPotCount;
			$val = $rsts [$i] ["c"];
			$data ["fusionDataset"] .= "<set value='$val'/>";
		}
		
		$data ["fusionDataset2"] = "";
		if ($this->input->post ( "compare" )) {
			$data ["fusionDataset2"] .= "<dataset seriesName='$date2' renderAs='Line' >";
			$chartPotCount2 = 0;
			for($i = 0; $i < count ( $rsts2 ); $i ++) {
				$val = "";
				$tempH = date ( "H", strtotime ( $rsts2 [$i] ["t"] ) );
				$tempI = date ( "i", strtotime ( $rsts2 [$i] ["t"] ) );
				while ( ($tempH * 12 + $tempI / 5) != $chartPotCount2 ) {
					++ $chartPotCount2;
					$data ["fusionDataset2"] .= "<set value='' />";
				}
				++ $chartPotCount2;
				$val = $rsts2 [$i] ["c"];
				$data ["fusionDataset2"] .= "<set value='$val' />";
			}
			$data ["fusionDataset2"] .= "</dataset>";
		}
		
		$data ["date"] = $date;
		$data ["date2"] = $date2;
		
		$this->load->view ( "/modules/user/newusercount_view", $data );
	}
	
	function usertimedata() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 312 );
		
		$this->load->library ( "function_class" );
		
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		} else {
			$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		}
		
		$rsts = $this->user_model->showUserTimeData ( $date );
		$rstsSum = $this->user_model->showUserTimeDataSum ( $date );
		$data ["list"] = "";
		
		$distinctSum = $rstsSum->distinctSum == 0 ? 1 : $rstsSum->distinctSum;
		$singleSum = $rstsSum->singleSum == 0 ? 1 : $rstsSum->singleSum;
		$distinctSpeedSum = $rstsSum->distinctSpeedSum == 0 ? 1 : $rstsSum->distinctSpeedSum;
		$singleSpeedSum = $rstsSum->singleSpeedSum == 0 ? 1 : $rstsSum->singleSpeedSum;
		
		$data ["list"] .= "<tr><td></td><td></td><td>$distinctSum</td><td>$singleSum</td><td>$distinctSpeedSum</td><td>$singleSpeedSum</td></tr>";
		for($i = 0; $i < count ( $rsts ); $i ++) {
			$distinctSum = ($distinctSum + $rsts [$i] ["distinctOnlineTime"]);
			$singleSum = ($singleSum + $rsts [$i] ["singleOnlineTime"]);
			$data ["list"] .= "<tr><td>" . $rsts [$i] ["date_id"] . "</td><td>" . $this->function_class->getFormattime2 ( $rsts [$i] ["minute_id"] ) . "</td>
			                  <td>" . $rsts [$i] ["distinctOnlineTime"] . "(" . round ( $rsts [$i] ["distinctOnlineTime"] / $distinctSum * 100, 2 ) . "%)</td>
					  <td>" . $rsts [$i] ["singleOnlineTime"] . "(" . round ( $rsts [$i] ["singleOnlineTime"] / $singleSum * 100, 2 ) . "%)</td>
			                  <td>" . $rsts [$i] ["distinctSpeedTime"] . "(" . round ( $rsts [$i] ["distinctSpeedTime"] / $distinctSpeedSum * 100, 2 ) . "%)</td>
					  <td>" . $rsts [$i] ["singleSpeedTime"] . "(" . round ( $rsts [$i] ["singleSpeedTime"] / $singleSpeedSum * 100, 2 ) . "%)</td></tr>";
		}
		
		$data ["date"] = $date;
		$this->load->view ( "/modules/user/usertimedata_view", $data );
	}
	
	
}

?>