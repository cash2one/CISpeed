<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Data extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct ();
	}
	
	function index() {}
	
	function logindata()
	{
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 61 );
		
		$this->load->model ( "et/data_model", "etdata" );
		$this->load->model ( "et/manage_model", "etmanage" );
		$this->load->library ( "function_class" );
		
		$data ["serverList"] = "";
		$serverList = $this->etmanage->getServerList ( 1 );
		for($i = 0; $i < count ( $serverList ); $i ++) {
			$data ["serverList"] .= "<option value='" . $serverList [$i] ["si_serverid"] . "'>" . $serverList [$i] ["si_ip"] . " " . $serverList [$i] ["si_serverid"] . $serverList [$i] ["si_address"] . "</option>";
		}
		
		$stserver = 0;
		$date = date ( "Y-m-d" );
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
			$stserver = $this->input->post ( "stserver" );
		}
		$rsts = $this->etdata->getLoginDataSum ( $date, 0, $stserver );
		
		$data ["compareCheck"] = "";
		$compare = 0;
		if ($this->input->post ( "compare" )) {
			$compare = 1;
			$date2 = $this->input->post ( "QueryDate2" );
			$data ["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		} else {
			$date2 = date ( "Y-m-d", time () - 24 * 60 * 60 );
		}
		
		$rsts2 = $this->etdata->getLoginDataSum ( $date2, 0, $stserver );
		
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data ["list"] = "";
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
			$data ["list"] = "<tr><td>" . $rsts [$i] ["t2"] . "</td>
			                     <td>" . $rsts [$i] ["usersum"] . "</td></tr>" . $data ["list"];
			/*$percent = round(($rsts[$i]["usersum"]-$rsts2[$i]["usersum"])/$rsts2[$i]["usersum"]*100,2);
			$data["list"] = "<tr><td>".$rsts[$i]["t2"]."|".$rsts2[$i]["t2"]."</td>
				             <td>".$rsts[$i]["usersum"]."|".$rsts2[$i]["usersum"]."($percent %)</td></tr>".$data["list"];*/
		}
		
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
		$data ["date2"] = $date2;
		$data ["stserver"] = $stserver;
		
		$this->load->view ( "modules/et/data/logindata_view", $data );
	}
	
	function logindataall() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 610 );
		
		$this->load->model ( "et/data_model", "etdata" );
		$this->load->model ( "et/manage_model", "etmanage" );
		$this->load->library ( "function_class" );
		
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		} else {
			$date = date ( "Y-m-d" );
		}
		
		$nodes = $this->etmanage->getServerList ( 1 );
		$data ["chartDiv"] = "";
		$data ["chartDiv"] .= '<div id="chartdiv0" align="left" style="margin-top:5px;">T</div>';
		foreach ( $nodes as $node ) {
			$data ["chartDiv"] .= '<div id="chartdiv' . $node ["si_serverid"] . '" align="left" style="margin-top:5px;">T</div>';
		}
		
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data ["scriptFusion"] = "";
		$data ["fusionDataset2"] = "";
		
		$nodesRst = $this->etdata->getLoginDataSum ( $date, 0, 0 );
		$data ["fusionDataset2"] .= "<dataset seriesName='" . $date . ",总登录' renderAs='Line' >";
		$chartPotCount2 = 0;
		for($i = 0; $i < count ( $nodesRst ); $i ++) {
			$val = "";
			$tempH = date ( "H", strtotime ( $nodesRst [$i] ["t2"] ) );
			$tempI = date ( "i", strtotime ( $nodesRst [$i] ["t2"] ) );
			while ( ($tempH * 12 + $tempI / 5) != $chartPotCount2 ) {
				++ $chartPotCount2;
				$data ["fusionDataset2"] .= "<set value='' />";
			}
			++ $chartPotCount2;
			$val = $nodesRst [$i] ["usersum"];
			$data ["fusionDataset2"] .= "<set value='$val' />";
		}
		$data ["fusionDataset2"] .= "</dataset>";
		$data ["scriptFusion"] .= "myChart = new FusionCharts(\"/res/fusion/charts/MSCombi2D.swf\", \"myChartId\", \"100%\", \"200\");
			 str=
			\"<chart palette='3' drawAnchors='0' caption='每5分钟分时曲线' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>\"+
			\"<categories>" . $data ["fusionCategory"] . "</categories>\"+
			\"" . $data ["fusionDataset2"] . "\"+
			\"</chart>\";
			myChart.setDataXML(str);
			myChart.render(\"chartdiv0\");
			$(\"#chartdiv0\").append(\"总登录\");";
		
		foreach ( $nodes as $node ) {
			$data ["fusionDataset2"] = "";
			$id = $node ["si_serverid"];
			$ip = $node ["si_ip"];
			$name = $node ["si_address"];
			$nodesRst = $this->etdata->getLoginDataSum ( $date, 0, $id );
			$data ["fusionDataset2"] .= "<dataset seriesName='" . $date . ",节点" . $name . "(" . $ip . ")' renderAs='Line' >";
			$chartPotCount2 = 0;
			for($i = 0; $i < count ( $nodesRst ); $i ++) {
				$val = "";
				$tempH = date ( "H", strtotime ( $nodesRst [$i] ["t2"] ) );
				$tempI = date ( "i", strtotime ( $nodesRst [$i] ["t2"] ) );
				while ( ($tempH * 12 + $tempI / 5) != $chartPotCount2 ) {
					++ $chartPotCount2;
					$data ["fusionDataset2"] .= "<set value='' />";
				}
				++ $chartPotCount2;
				$val = $nodesRst [$i] ["usersum"];
				$data ["fusionDataset2"] .= "<set value='$val' />";
			}
			$data ["fusionDataset2"] .= "</dataset>";
			
			$data ["scriptFusion"] .= "myChart = new FusionCharts(\"/res/fusion/charts/MSCombi2D.swf\", \"myChartId\", \"100%\", \"200\");
			 str=
			\"<chart palette='3' drawAnchors='0' caption='每5分钟分时曲线' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>\"+
			\"<categories>" . $data ["fusionCategory"] . "</categories>\"+
			\"" . $data ["fusionDataset2"] . "\"+
			\"</chart>\";
			myChart.setDataXML(str);
			myChart.render(\"chartdiv" . $id . "\");
			$(\"#chartdiv" . $id . "\").append(\"" . $name . "(" . $ip . ")\");";
		}
		
		$data ["date"] = $date;
		$this->load->view ( "/modules/et/data/logindataall_view", $data );
	}
	
	function online2point0() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 27 );
		
		$this->load->model ( "et/data_model", "etdata" );
		$this->load->library ( "function_class" );
		
		$date = date ( "Y-m-d" );
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		}
		$rsts = $this->etdata->getLoginDataSum ( $date, 43, 0, 2, 1 );
		
		$data ["compareCheck"] = "";
		$compare = 0;
		if ($this->input->post ( "compare" )) {
			$compare = 1;
			$date2 = $this->input->post ( "QueryDate2" );
			$rsts2 = $this->etdata->getLoginDataSum ( $date2, 43, 0, 2, 1 );
			$data ["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		} else {
			$date2 = date ( "Y-m-d", time () - 24 * 60 * 60 );
			$rsts2 = $this->etdata->getLoginDataSum ( $date2, 43, 0, 2, 1 );
		}
		
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data ["list"] = "";
		$data ["fusionDataset"] = "";
		$chartPotCount = 0;
		$k = 0;
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
			
			while ( date ( "His", strtotime ( $rsts [$i] ["t2"] ) ) > date ( "His", strtotime ( $rsts2 [$k] ["t2"] ) ) ) {
				++ $k;
			}
			
			if (date ( "His", strtotime ( $rsts [$i] ["t2"] ) ) == date ( "His", strtotime ( $rsts2 [$k] ["t2"] ) )) {
				$percent = round ( ($rsts [$i] ["usersum"] - $rsts2 [$k] ["usersum"]) / $rsts2 [$k] ["usersum"] * 100, 2 );
				$data ["list"] = "<tr><td>" . $rsts [$i] ["t2"] . "|" . $rsts2 [$k] ["t2"] . "</td>
						     <td>" . $rsts [$i] ["usersum"] . "|" . $rsts2 [$k] ["usersum"] . "($percent %)</td></tr>" . $data ["list"];
				++ $k;
			} else {
				$data ["list"] = "<tr><td>" . $rsts [$i] ["t2"] . "</td>
						     <td>" . $rsts [$i] ["usersum"] . "</td></tr>" . $data ["list"];
			}
		
		}
		
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
		$data ["date2"] = $date2;
		
		$this->load->view ( "modules/et/data/online2point0_view", $data );
	}
	
	function tsdata() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 62 );
		
		$this->load->model ( "et/data_model", "etdata" );
		$this->load->model ( "et/manage_model", "etmanage" );
		$this->load->library ( "function_class" );
		
		$data ["serverList"] = "";
		$serverList = $this->etmanage->getServerList ( 0 );
		for($i = 0; $i < count ( $serverList ); $i ++) {
			$data ["serverList"] .= "<option value='" . $serverList [$i] ["si_serverid"] . "'>" . $serverList [$i] ["si_ip"] . " " . $serverList [$i] ["si_serverid"] . $serverList [$i] ["si_address"] . "</option>";
		}
		
		$tsserver = 0;
		$date = date ( "Y-m-d" );
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
			$tsserver = $this->input->post ( "tsserver" );
		}
		$rsts = $this->etdata->getTsDataSum ( $date, 0, $tsserver );
		$rstTemp = $this->function_class->bubble_sort ( $rsts, "t2" );
		
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data ["list"] = "";
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
			
			$data ["list"] .= "<tr><td>" . $rstTemp [$i] ["t2"] . "</td><td>" . $rstTemp [$i] ["usersum"] . "</td></tr>";
		}
		
		$date2 = date ( "Y-m-d", strtotime ( $date ) - 24 * 60 * 60 );
		$rsts2 = $this->etdata->getTsDataSum ( $date2, 0, $tsserver );
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
		$data ["tsserver"] = $tsserver;
		
		$this->load->view ( "modules/et/data/tsdata_view", $data );
	}
	
	function tsdataall() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 67 );
		
		$this->load->model ( "et/data_model", "etdata" );
		$this->load->model ( "et/manage_model", "etmanage" );
		$this->load->library ( "function_class" );
		
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		} else {
			$date = date ( "Y-m-d" );
		}
		
		$nodes = $this->etmanage->getServerList ( 0 );
		$data ["chartDiv"] = "";
		foreach ( $nodes as $node ) {
			$data ["chartDiv"] .= '<div id="chartdiv' . $node ["si_serverid"] . '" align="left" style="margin-top:5px;">T</div>';
		}
		
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data ["scriptFusion"] = "";
		
		foreach ( $nodes as $node ) {
			$data ["fusionDataset2"] = "";
			$id = $node ["si_serverid"];
			$ip = $node ["si_ip"];
			$name = $node ["si_address"];
			$nodesRst = $this->etdata->getTsDataSum ( $date, 0, $id );
			$data ["fusionDataset2"] .= "<dataset seriesName='" . $date . ",节点" . $name . "(" . $ip . ")' renderAs='Line' >";
			$chartPotCount2 = 0;
			for($i = 0; $i < count ( $nodesRst ); $i ++) {
				$val = "";
				$tempH = date ( "H", strtotime ( $nodesRst [$i] ["t2"] ) );
				$tempI = date ( "i", strtotime ( $nodesRst [$i] ["t2"] ) );
				while ( ($tempH * 12 + $tempI / 5) != $chartPotCount2 ) {
					++ $chartPotCount2;
					$data ["fusionDataset2"] .= "<set value='' />";
				}
				++ $chartPotCount2;
				$val = $nodesRst [$i] ["usersum"];
				$data ["fusionDataset2"] .= "<set value='$val' />";
			}
			$data ["fusionDataset2"] .= "</dataset>";
			
			$data ["scriptFusion"] .= "myChart = new FusionCharts(\"/res/fusion/charts/MSCombi2D.swf\", \"myChartId\", \"100%\", \"200\");
			 str=
			\"<chart palette='3' drawAnchors='0' caption='每5分钟分时曲线' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' formatNumberScale='0'>\"+
			\"<categories>" . $data ["fusionCategory"] . "</categories>\"+
			\"" . $data ["fusionDataset2"] . "\"+
			\"</chart>\";
			myChart.setDataXML(str);
			myChart.render(\"chartdiv" . $id . "\");
			$(\"#chartdiv" . $id . "\").append(\"" . $name . "(" . $ip . ")\");";
		}
		
		$data ["date"] = $date;
		$this->load->view ( "/modules/et/data/tsdataall_view", $data );
	}
	
	function userlose() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 63 );
		
		$this->load->model ( "et/data_model", "etdata" );
		$rsts = $this->etdata->showUserLose ();
		
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
		
		$this->load->view ( "modules/et/data/userlose_view", $data );
	}
	
	function userlose2point0() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 313 );
		
		$this->load->model ( "et/data_model", "etdata" );
		$rsts = $this->etdata->showUserLose2point0 ();
		
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
		
		$this->load->view ( "modules/et/data/userlose2point0_view", $data );
	}
	
	function commondata() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 64 );
		
		$this->load->model ( "et/data_model", "etdata" );
		$rsts = $this->etdata->showUserLoginData ();
		
		$data ["list"] = "";
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst ["date_id"] . "</td><td>" . $rst ["loginTimes"] . "</td><td>" . $rst ["loginPtId"] . "</td><td>" . $rst ["loginIP"] . "</td>
			                      <td>" . $rst ["avgUser"] . "</td><td>" . $rst ["maxUser"] . "</td><td>" . $rst ["avgTsUser"] . "</td><td>" . $rst ["maxTsUser"] . "</td><td>" . $rst ["createHall"] . "</td><td>" . $rst ["newuser"] . "</td></tr>";
		}
		
		$this->load->view ( "modules/et/data/commondata_view", $data );
	}
	
	function commondata2point0()
	{
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 210 );
		
		$this->load->model ( "et/data_model", "etdata" );
		$rsts = $this->etdata->showET2point0Data();
		
		$data ["list"] = "";
		
		$data ["fusionCategory"] = "";
		$data ["fusionDataset"] = "";
		$data ["fusionDataset2"] = "";
		$data ["fusionDataset3"] = "";
		$data ["fusionDataset4"] = "";
		
		$data ["fusionDataset"] .= "<dataset seriesName='平均在线' renderAs='Line' >";
		$data ["fusionDataset2"] .= "<dataset seriesName='最高在线' renderAs='Line' >";
		$data ["fusionDataset3"] .= "<dataset seriesName='登录账号' renderAs='Line' >";
		$data ["fusionDataset4"] .= "<dataset seriesName='新增用户' renderAs='Line' >";
		
		for($i=0;$i<count($rsts);$i++)
		{
			$data["list"] = "<tr><td>".$rsts[$i]["date_id"]."</td>
					     <td>".$rsts[$i]["proxyStartCount"]."</td><td>".$rsts[$i]["proxyIpCount"]."</td><td>".$rsts[$i]["proxyIdCount"]."</td>
					     <td>".$rsts[$i]["loginTimes"]."</td><td>".$rsts[$i]["loginPtId"]."</td><td>".$rsts[$i]["loginIP"]."</td>
			                     <td>".$rsts[$i]["avgUser"]."</td><td>".$rsts[$i]["maxUser"]."</td><td>".$rsts[$i]["newuser"]."</td></tr>".$data["list"];
			if($i>0)
			{
				$showname = "showName='0'";
				if(($i % 5) == 0)
				{
					$showname = "showName='1'";
				}
				$data ["fusionCategory"] .= "<category $showname name='".date("m-d",strtotime($rsts[$i]["date_id"]))."' />";
				$data ["fusionDataset"] .= "<set value='".$rsts[$i]["avgUser"]."' />";
				$data ["fusionDataset2"] .= "<set value='".$rsts[$i]["maxUser"]."' />";
				$data ["fusionDataset3"] .= "<set value='".$rsts[$i]["loginPtId"]."' />";
				$data ["fusionDataset4"] .= "<set value='".$rsts[$i]["newuser"]."' />";
			}
		}
		$data ["fusionDataset"] .= "</dataset>";
		$data ["fusionDataset2"] .= "</dataset>";
		$data ["fusionDataset3"] .= "</dataset>";
		$data ["fusionDataset4"] .= "</dataset>";
		
		$this->load->view ( "modules/et/data/commondata2point0_view", $data );
	}
	
	function guild() {
		$this->load->model ( "et/data_model", "etdata" );
		$rsts = $this->etdata->guild ();
		echo count ( $rsts );
	}
	
	function hallmax() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 65 );
		
		$this->load->model ( "et/data_model", "etdata" );
		
		$date = date ( "Y-m-d" );
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		}
		$rsts = $this->etdata->getHallMaxCount ( $date );
		
		$data ["list"] = "";
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst->m . "</td><td>" . $rst->hallid . "</td></tr>";
		}
		$data ["date"] = $date;
		$this->load->view ( "modules/et/data/hallmaxdata_view", $data );
	}
	
	function hallavg() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 66 );
		
		$this->load->model ( "et/data_model", "etdata" );
		
		$date = date ( "Y-m-d" );
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		}
		$rsts = $this->etdata->getHallAvgCount ( $date );
		
		$data ["list"] = "";
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst->a . "</td><td>" . $rst->hallid . "</td></tr>";
		}
		$data ["date"] = $date;
		$this->load->view ( "modules/et/data/hallavgdata_view", $data );
	}
	
	function singlehallonline() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 69 );
		
		$data ["hallid"] = 0;
		$data ["list"] = "";
		$data ["date"] = date ( "Y-m-d" );
		;
		$data ["fusionDataset"] = "";
		$data ["fusionDataset2"] = "";
		$data ["fusionCategory"] = "";
		
		if ($this->input->post ( "QueryDate1" ) && $this->input->post ( "hallid" )) {
			$data ["date"] = $this->input->post ( "QueryDate1" );
			$data ["hallid"] = $this->input->post ( "hallid" );
			
			$this->load->model ( "et/data_model", "etdata" );
			$rsts = $this->etdata->getSingleHallOnline ( $data ["date"], $data ["hallid"] );
			
			for($i = 0; $i < 12 * 24; $i ++) {
				$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
				$show = ($i % 12) == 0 ? "1" : "0";
				$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
			}
			
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
				$val = $rsts [$i] ["usercount"];
				$data ["fusionDataset"] .= "<set value='$val'/>";
				$data ["list"] = "<tr><td>" . $rsts [$i] ["t2"] . "</td><td>" . $rsts [$i] ["usercount"] . "</td></tr>" . $data ["list"];
			}
		}
		
		$this->load->view ( "modules/et/data/singlehallonline_view", $data );
	}
	
	function singlehalldata() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 66 );
		
		$data ["hallid"] = 0;
		$data ["list"] = "";
		$data ["date1"] = "";
		$data ["date2"] = "";
		
		if ($this->input->post ( "hallid" ) && $this->input->post ( "QueryDate1" ) && $this->input->post ( "QueryDate2" )) {
			$this->load->model ( "et/data_model", "etdata" );
			$data ["hallid"] = $this->input->post ( "hallid" );
			$data ["date1"] = $this->input->post ( "QueryDate1" );
			$data ["date2"] = $this->input->post ( "QueryDate2" );
			
			$rsts = $this->etdata->showSingleHallData ( $data ["hallid"], $data ["date1"], $data ["date2"] );
			$avg1 = 0;
			$avg2 = 0;
			$avg3 = 0;
			$c = count ( $rsts );
			if ($c > 0) {
				foreach ( $rsts as $rst ) {
					$avg1 += $rst->avgcount;
					$avg2 += $rst->maxcount;
					$avg3 += $rst->ipcount;
					$data ["list"] .= "<tr><td>" . $rst->date_id . "</td><td>" . $rst->avgcount . "</td><td>" . $rst->maxcount . "</td><td>" . $rst->ipcount . "</td></tr>";
				}
				$avg1 = round ( $avg1 / $c, 2 );
				$avg2 = round ( $avg2 / $c, 2 );
				$avg3 = round ( $avg3 / $c, 2 );
				$data ["list"] .= "<tr><td>平均</td><td>$avg1</td><td>$avg2</td><td>$avg3</td></tr>";
			}
		}
		
		$this->load->view ( "modules/et/data/singlehalldata_view", $data );
	}
	
	function userloginversion() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 611 );
		
		$data ["date"] = date ( "Y-m-d", time () - 24 * 60 * 60 );
		if ($this->input->post ( "QueryDate1" )) {
			$data ["date"] = $this->input->post ( "QueryDate1" );
		}
		
		$this->load->model ( "et/data_model", "etdata" );
		$this->load->library ( "function_class" );
		$rsts = $this->etdata->showUserLoginVersion ( $data ["date"] );
		$data ["list"] = "";
		foreach ( $rsts as $rst ) {
			$v = $this->function_class->formatEtVersion ( $rst ["v"] );
			$data ["list"] .= "<tr><td>" . $rst ["date_id"] . "</td><td>$v</td>
			                      <td>" . $rst ["dcount"] . "</td><td>" . $rst ["count"] . "</td></tr>";
		}
		$this->load->view ( "modules/et/data/userloginversion_view", $data );
	}
	
	function activeuserdata() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 612 );
		
		$data ["date"] = date ( "Y-m" );
		$data ["list"] = "";
		if ($this->input->post ( "QueryDate1" )) {
			$data ["date"] = $this->input->post ( "QueryDate1" );
		}
		
		$this->load->model ( "et/data_model", "etdata" );
		$rsts = $this->etdata->showUserActiveData ( $data ["date"] );
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst ["month_id"] . "</td>
					      <td>" . $rst ["peroid"] . "</td>
					      <td>" . $rst ["days"] . "</td>
					      <td>" . $rst ["users"] . "</td></tr>";
		}
		$this->load->view ( "modules/et/data/activeuserdata_view", $data );
	}
	
	function fix() {
	}
}
?>