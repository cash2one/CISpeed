<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Speed extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->model ( "speed_model" );
	}
	
	function index() {
	}
	
	function data() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 21 );
		
		$this->load->library ( "function_class" );
		
		$data ["list"] = "";
		$rsts = $this->speed_model->showSpeedDataList ();
		foreach ( $rsts as $rst ) {
			/*<td>".$rst->newUser."</td><td>".$rst->newUserIP."</td>
			                      <td>".$rst->loginIPcount."</td><td>".$rst->loginIDcount."</td><td>".$rst->onlineAvg."</td>
					      <td>".$rst->onlinePeak."</td>*/
			$data ["list"] .= "<tr><td>" . $rst->date_id . "</td>
					      <td>" . $rst->speedIPcount . "</td><td>" . $rst->speedIDcount . "</td>
					      <td>" . $rst->speedPeak . "</td>
					      <td>" . $this->function_class->getByteToMb ( $rst->uploadAll ) . "Mb/" . $this->function_class->getByteToMb ( $rst->downloadAll ) . "Mb</td>
					      <td>" . $this->function_class->getByteToMb ( $rst->upload1 ) . "Mb/" . $this->function_class->getByteToMb ( $rst->download1 ) . "Mb</td>
					      <td>" . $this->function_class->getByteToMb ( $rst->upload2 ) . "Mb/" . $this->function_class->getByteToMb ( $rst->download2 ) . "Mb</td>
					      <td>" . $this->function_class->getByteToMb ( $rst->upload3 ) . "Mb/" . $this->function_class->getByteToMb ( $rst->download3 ) . "Mb</td>
					      <td>" . $this->function_class->getByteToMb ( $rst->upload4 ) . "Mb/" . $this->function_class->getByteToMb ( $rst->download4 ) . "Mb</td>
					      <td>" . $this->function_class->getByteToMb ( $rst->upload5 ) . "Mb/" . $this->function_class->getByteToMb ( $rst->download5 ) . "Mb</td></tr>";
		}
		$this->load->view ( 'modules/speed/data_view', $data );
	}
	
	function speeding() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 22 );
		
		$this->load->library ( "function_class" );
		
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
		} else {
			$date = date ( "Y-m-d" );
		}
		
		$rsts = $this->speed_model->getSpeedingListPer5 ( date ( "ymd", strtotime ( $date ) ) );
		
		$data ["compareCheck"] = "";
		if ($this->input->post ( "compare" )) {
			$date2 = $this->input->post ( "QueryDate2" );
			$rsts2 = $this->speed_model->getSpeedingListPer5 ( date ( "ymd", strtotime ( $date2 ) ) );
			$data ["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		} else {
			$date2 = date ( "Y-m-d", time () - 24 * 60 * 60 );
			$rsts2 = $this->speed_model->getSpeedingListPer5 ( date ( "ymd", strtotime ( $date2 ) ) );
		}
		$rsts2 = array();
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data ["list"] = "";
		$data ["fusionDataset"] = "";
		$data ["fusionDataset3"] = "";
		$data ["fusionDataset5"] = "";
		$chartPotCount = 0;
		for($i = 0; $i < count ( $rsts ) - 1; $i ++) {
			$tempH = date ( "H", strtotime ( $rsts [$i] ["t"] ) );
			$tempI = date ( "i", strtotime ( $rsts [$i] ["t"] ) );
			while ( ($tempH * 12 + $tempI / 5) != $chartPotCount ) {
				++ $chartPotCount;
				$data ["fusionDataset"] .= "<set value='' />";
			}
			++ $chartPotCount;
			$val = $rsts [$i] ["sum"];
			$val3 = $rsts [$i] ["sock_sum"];
			$val5 = $rsts [$i] ["vpn_sum"];
			$data ["fusionDataset"] .= "<set value='$val'/>";
			$data ["fusionDataset3"] .= "<set value='$val3'/>";
			$data ["fusionDataset5"] .= "<set value='$val5'/>";
			
			$data ["list"] = "<tr><td>" . $rsts [$i] ["t"] . "|" . /*$rsts2 [$i] ["t"] .*/ "</td>
					      <td>" . $rsts [$i] ["sum"] . "|" . /*$rsts2 [$i] ["sum"] .*/ "(" ./* round ( ($rsts [$i] ["sum"] - $rsts2 [$i] ["sum"]) / $rsts2 [$i] ["sum"] * 100, 2 ) . */"%)</td>
				              <td>" . $rsts [$i] ["sock_sum"] . "|" . /*$rsts2 [$i] ["sock_sum"] .*/ "(" . /*round ( ($rsts [$i] ["sock_sum"] - $rsts2 [$i] ["sock_sum"]) / $rsts2 [$i] ["sock_sum"] * 100, 2 ) . */"%)</td>
					      <td>" . $rsts [$i] ["vpn_sum"] . "|" . /*$rsts2 [$i] ["vpn_sum"] .*/ "(" . /*round ( ($rsts [$i] ["vpn_sum"] - $rsts2 [$i] ["vpn_sum"]) / $rsts2 [$i] ["vpn_sum"] * 100, 2 ) .*/ "%)</td>
					      <td>" . $rsts [$i] ["nodes"] . "|" . /*$rsts2 [$i] ["nodes"] . */"</td></tr>" . $data ["list"];
		
		}
		
		$data ["fusionDataset2"] = "";
		$data ["fusionDataset4"] = "";
		$data ["fusionDataset6"] = "";
		
		$data ["fusionDataset2"] .= "<dataset seriesName='$date2' renderAs='Line' >";
		$data ["fusionDataset4"] .= "<dataset seriesName='$date2' renderAs='Line' >";
		$data ["fusionDataset6"] .= "<dataset seriesName='$date2' renderAs='Line' >";
		$chartPotCount2 = 0;
		for($i = 0; $i < count ( $rsts2 ); $i ++) {
			$tempH = date ( "H", strtotime ( $rsts2 [$i] ["t"] ) );
			$tempI = date ( "i", strtotime ( $rsts2 [$i] ["t"] ) );
			while ( ($tempH * 12 + $tempI / 5) != $chartPotCount2 ) {
				++ $chartPotCount2;
				$data ["fusionDataset2"] .= "<set value='' />";
			}
			++ $chartPotCount2;
			$val2 = $rsts2 [$i] ["sum"];
			$val4 = $rsts2 [$i] ["sock_sum"];
			$val6 = $rsts2 [$i] ["vpn_sum"];
			$data ["fusionDataset2"] .= "<set value='$val2' />";
			$data ["fusionDataset4"] .= "<set value='$val4' />";
			$data ["fusionDataset6"] .= "<set value='$val6' />";
		}
		$data ["fusionDataset2"] .= "</dataset>";
		$data ["fusionDataset4"] .= "</dataset>";
		$data ["fusionDataset6"] .= "</dataset>";
		
		$data ["date"] = $date;
		$data ["date2"] = $date2;
		
		$this->load->view ( "/modules/speed/speeding_view", $data );
	}
	
	function online() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 24 );
		
		$this->load->library ( "function_class" );
		
		if ($this->input->post ( "QueryDate1" )) {
			$date = $_POST ["QueryDate1"];
		} else {
			$date = date ( "Y-m-d" );
		}
		
		$rsts = $this->speed_model->getUserOnlineSumPer5 ( date ( "ymd", strtotime ( $date ) ) );
		
		$data ["compareCheck"] = "";
		$compare = 0;
		if ($this->input->post ( "compare" )) {
			$compare = 1;
			$date2 = $this->input->post ( "QueryDate2" );
			$rsts2 = $this->speed_model->getUserOnlineSumPer5 ( date ( "ymd", strtotime ( $date2 ) ) );
			$data ["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		} else {
			$date2 = date ( "Y-m-d" );
			$rsts2 = $this->speed_model->getSpeedingListPer5 ( date ( "ymd", strtotime ( $date ) ) );
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
		for($i = 0; $i < count ( $rsts ) - 1; $i ++) {
			$val = "";
			$tempH = date ( "H", strtotime ( $rsts [$i] ["t2"] ) );
			$tempI = date ( "i", strtotime ( $rsts [$i] ["t2"] ) );
			while ( ($tempH * 12 + $tempI / 5) != $chartPotCount ) {
				++ $chartPotCount;
				$data ["fusionDataset"] .= "<set value='' />";
			}
			++ $chartPotCount;
			$val = $rsts [$i] ["user_sum"];
			$data ["fusionDataset"] .= "<set value='$val'/>";
			if ($compare == 1) {
				$data ["list"] = "<tr><td>" . $rsts [$i] ["t2"] . "|" . $rsts2 [$i] ["t2"] . "</td>
				                      <td>" . $rsts [$i] ["user_sum"] . "|" . $rsts2 [$i] ["user_sum"] . "</td></tr>" . $data ["list"];
			} else {
				$data ["list"] = "<tr><td>" . $rsts [$i] ["t2"] . "</td><td>" . $rsts [$i] ["user_sum"] . "</td></tr>" . $data ["list"];
			}
		}
		
		$data ["fusionDataset2"] = "";
		if ($this->input->post ( "compare" )) {
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
				$val = $rsts2 [$i] ["user_sum"];
				$data ["fusionDataset2"] .= "<set value='$val' />";
			}
			$data ["fusionDataset2"] .= "</dataset>";
		} else {
			$data ["fusionDataset2"] .= "<dataset seriesName='加速人数' renderAs='Line' >";
			$chartPotCount2 = 0;
			for($i = 0; $i < count ( $rsts2 ) - 1; $i ++) {
				$val = "";
				$tempH = date ( "H", strtotime ( $rsts2 [$i] ["t"] ) );
				$tempI = date ( "i", strtotime ( $rsts2 [$i] ["t"] ) );
				while ( ($tempH * 12 + $tempI / 5) != $chartPotCount2 ) {
					++ $chartPotCount2;
					$data ["fusionDataset2"] .= "<set value='' />";
				}
				++ $chartPotCount2;
				$val = $rsts2 [$i] ["sum"];
				$data ["fusionDataset2"] .= "<set value='$val' />";
			}
			$data ["fusionDataset2"] .= "</dataset>";
		}
		
		$data ["date"] = $date;
		$data ["date2"] = $date2;
		
		$this->load->view ( "/modules/speed/online_view", $data );
	}
	
	function node()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(23);
		
		$this->load->library("function_class");
		$this->load->model("node_model");
		
		$nodeSpeedingList = $this->speed_model->getSpeedingNodeListRec(date('ymd'));
		
		$data["nodeList"] = "";
		$nodes = $this->node_model->getNodeList(1);
		foreach($nodes as $node)
		{
			$id = $node ["id"];
			$ip = $node ["ip"];
			$name = $node ["name"];
			$str = '<option value='.$id.'>'.$name.'('.$ip.')</option>';
			$data ["nodeList"] .= $str;
		}
		
		$data["nodeSpeedingList"] = "";
		for($i = 0; $i<count($nodeSpeedingList);$i++)
		{
			$style = "";
			if ((time() - strtotime($nodeSpeedingList[$i]["t2"])) > 600)
			{
				$style = "style='color:red'";
			}
			$data ["nodeSpeedingList"] .= "<tr $style><td>".$nodeSpeedingList[$i]["nodeid"]."</td><td>".$nodeSpeedingList[$i]["ip"]."</td><td>".$nodeSpeedingList[$i]["name"]."</td>"."<td>".$nodeSpeedingList [$i] ["usum"] . "</td><td>" . $nodeSpeedingList [$i] ["sock_user_sum"] . "</td><td>" . $nodeSpeedingList [$i] ["vpn_user_sum"] . "</td><td>" . $nodeSpeedingList [$i] ["t2"] . "</td></tr>";
		}
		
		if($this->input->post("QueryDate1"))
		{
			$date = $this->input->post("QueryDate1");
			$nodeid = $this->input->post("nodeList");
		}
		else
		{
			$date = date("Y-m-d");
			$nodeid = 3002001;
		}
		
		$rsts = $this->speed_model->getSpeedingListPer5ByNode(date("ymd",strtotime($date)),$nodeid);
		$rstTemp = $this->function_class->bubble_sort ( $rsts, "t2" );
		
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++)
		{
			$text = floor($i/12).":".str_pad(($i%12)*5,2,0,STR_PAD_LEFT);
			$show = ($i%12)==0?"1":"0";
			$data["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data["list"] = "";
		$data["fusionDataset"] = "";
		$chartPotCount = 0;
		for($i = 0; $i < count($rsts); $i++)
		{
			$val = "";
			$tempH = date("H",strtotime($rsts[$i]["t2"]));
			$tempI = date("i",strtotime($rsts[$i]["t2"]));
			while(($tempH*12 + $tempI/5) != $chartPotCount)
			{
				++ $chartPotCount;
				$data ["fusionDataset"] .= "<set value='' />";
			}
			++ $chartPotCount;
			$val = $rsts[$i]["sum"];
			$data["fusionDataset"] .= "<set value='$val' />";
			
			$data["list"] .= "<tr><td>" . $rstTemp [$i] ["nodeid"] . "</td><td>" . $rstTemp [$i] ["name"] . "</td><td>" . $rstTemp [$i] ["t2"] . "</td>
			                      <td>" . $rstTemp [$i] ["sum"] . "</td><td>" . $rstTemp [$i] ["sock_user_sum"] . "</td><td>" . $rstTemp [$i] ["vpn_user_sum"] . "</td></tr>";
		
		}
		
		if ($this->input->post("compare"))
		{
			$date2 = $this->input->post ( "QueryDate2" );
			$rsts2 = $this->speed_model->getSpeedingListPer5ByNode ( date ( "ymd", strtotime ( $date2 ) ), $nodeid );
		} else {
			$date2 = date ( "Y-m-d", time () - 24 * 60 * 60 );
		}
		
		$data ["compareCheck"] = "";
		if ($this->input->post ( "compare" )) {
			$data ["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		}
		
		$data ["fusionDataset3"] = "";
		if ($this->input->post ( "compare" )) {
			$nodesRst = $rsts2;
			$data ["fusionDataset3"] .= "<dataset seriesName='" . $date2 . ",节点" . $nodeid . "' renderAs='Line' >";
			$chartPotCount3 = 0;
			for($i = 0; $i < count ( $nodesRst ); $i ++) {
				$val = "";
				$tempH = date ( "H", strtotime ( $nodesRst [$i] ["t2"] ) );
				$tempI = date ( "i", strtotime ( $nodesRst [$i] ["t2"] ) );
				while ( ($tempH * 12 + $tempI / 5) != $chartPotCount3 ) {
					++ $chartPotCount3;
					$data ["fusionDataset3"] .= "<set value='' />";
				}
				++ $chartPotCount3;
				$val = $nodesRst [$i] ["sum"];
				$data ["fusionDataset3"] .= "<set value='$val' />";
			}
			$data ["fusionDataset3"] .= "</dataset>";
		}
		
		$data ["fusionDataset2"] = "";
		/*foreach ($nodes as $node)
		{
			$id = $node["id"];
			$name = $node["ip"];
			$nodesRst = $this->speed_model->getSpeedingListPer5ByNode(date("ymd",strtotime($date)),$id);
			$data["fusionDataset2"] .= "<dataset seriesName='".$date.",节点".$name."' renderAs='Line' >";
			$chartPotCount2 = 0;
			for($i=0;$i<count($nodesRst);$i++)
			{
				$val = "";
				$tempH = date("H",strtotime($nodesRst[$i]["t2"]));
				$tempI = date("i",strtotime($nodesRst[$i]["t2"]));
				while(($tempH*12+$tempI/5)!=$chartPotCount2)
				{
					++$chartPotCount2;
					$data["fusionDataset2"] .= "<set value='' />";
				}
				++$chartPotCount2;
				$val = $nodesRst[$i]["sum"];
				$data["fusionDataset2"] .= "<set value='$val' />";
			}		
			$data["fusionDataset2"] .= "</dataset>";
		}*/
		
		$data ["date"] = $date;
		$data ["date2"] = $date2;
		$data ["nodeid"] = $nodeid;
		
		$this->load->view ( "/modules/speed/node_view", $data );
	}
	
	function node2() {
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(28);
		
		$this->load->model("node_model");
		if ($this->input->post("QueryDate1"))
		{
			$date = $this->input->post("QueryDate1");
		}
		else
		{
			$date = date("Y-m-d");
		}
		
		$nodes = $this->node_model->getNodeList(1);
		$data["chartDiv"] = "";
		foreach($nodes as $node)
		{
			$data ["chartDiv"] .= '<div id="chartdiv'.$node["id"].'" align="left" style="margin-top:5px;">T</div>';
		}
		
		$data ["fusionCategory"] = "";
		for($i = 0; $i < 12 * 24; $i ++) {
			$text = floor ( $i / 12 ) . ":" . str_pad ( ($i % 12) * 5, 2, 0, STR_PAD_LEFT );
			$show = ($i % 12) == 0 ? "1" : "0";
			$data ["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		
		$data ["scriptFusion"] = "";
		
		foreach ($nodes as $node ) {
			$data ["fusionDataset2"] = "";
			$id = $node ["id"];
			$ip = $node ["ip"];
			$name = $node ["name"];
			$nodesRst = $this->speed_model->getSpeedingListPer5ByNode(date("ymd",strtotime($date)),$id);
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
				$val = $nodesRst [$i] ["sum"];
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
		$this->load->view ( "/modules/speed/node2_view", $data );
	}
	
	function nodepeakdata()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(211);
		
		$data["date"] = date("Y-m-d",time()-24*60*60);
		if($this->input->post("QueryDate1"))
		{
			$data["date"] = $this->input->post("QueryDate1");
		}
		$rsts = $this->speed_model->showNodesPeakCount($data["date"]);
		$data["list"] = "";
		foreach($rsts as $rst)
		{
			$data["list"] .= "<tr><td>".$rst["nodeid"]."</td>
					      <td>".$rst["name"]."</td>
					      <td>".$rst["ip"]."</td>
					      <td>".$rst["c"]."(".$rst["peakc"].")</td>
					      <td>".$rst["t"]."</td>
					  </tr>";
		}
		$this->load->view("/modules/speed/nodepeakdata_view",$data);
	}
	
	function loadsize()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(25);
		
		$this->load->library("function_class");
		$this->load->model("node_model");
		
		$data["nodeList"] = "";
		$nodes = $this->node_model->getNodeList ();
		foreach ( $nodes as $node ) {
			$id = $node ["id"];
			$name = $node ["ip"];
			$str = '<option value=' . $id . '>' . $name . '</option>';
			$data ["nodeList"] .= $str;
		}
		
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
			$nodeid = $this->input->post ( "nodeList" );
		} else {
			$date = date ( "Y-m-d" );
			$nodeid = 3002001;
		}
		$rsts = $this->speed_model->getSpeedingLoadSize ( date ( "ymd", strtotime ( $date ) ), $nodeid );
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
		for($i = 0; $i < count ( $rsts ); $i ++) {
			$val = "";
			$tempH = date ( "H", strtotime ( $rsts [$i] ["t2"] ) );
			$tempI = date ( "i", strtotime ( $rsts [$i] ["t2"] ) );
			while ( ($tempH * 12 + $tempI / 5) != $chartPotCount ) {
				++ $chartPotCount;
				$data ["fusionDataset"] .= "<set value='' />";
			}
			++ $chartPotCount;
			$val = $this->function_class->getByteToKbPerS ( $rsts [$i] ["upload"] );
			$data ["fusionDataset"] .= "<set value='$val' />";
			
			$data ["list"] .= "<tr><td>" . $rstTemp [$i] ["nodeid"] . "</td><td>" . $rstTemp [$i] ["name"] . "</td><td>" . $rstTemp [$i] ["t2"] . "</td>" . "    <td>" . $this->function_class->getByteToKbPerS ( $rstTemp [$i] ["upload"] ) . "KB</td><td>" . $this->function_class->getByteToKbPerS ( $rstTemp [$i] ["download"] ) . "KB</td></tr>";
		}
		
		$data ["date"] = $date;
		$data ["nodeid"] = $nodeid;
		
		$this->load->view ( "/modules/speed/loadsize_view", $data );
	}
	
	function nodeuse() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 26 );
		
		$this->load->library ( "function_class" );
		$this->load->model ( "node_model" );
		
		$nodeList = $this->node_model->getNodeList ();
		$data ["eachNode"] = "";
		$data ["eachIp"] = "";
		foreach ( $nodeList as $eachNode ) {
			$data ["eachNode"] .= "<option value='" . $eachNode ["id"] . "'>" . $eachNode ["name"] . "</option>";
			$data ["eachIp"] .= "<option value='" . $eachNode ["id"] . "'>" . $eachNode ["ip"] . "</option>";
		}
		
		$data ["hourSelect"] = "";
		$data ["minuteSelect"] = "";
		for($i = 0; $i < 24; $i ++) {
			$t = str_pad ( $i, 2, '0', STR_PAD_LEFT );
			$data ["hourSelect"] .= "<option value='$t'>$t</option>";
		}
		for($i = 0; $i < 59; $i ++) {
			$t = str_pad ( $i, 2, '0', STR_PAD_LEFT );
			$data ["minuteSelect"] .= "<option value='$t'>$t</option>";
		}
		
		$searchType = "searchByNode";
		$node = "";
		$date = "";
		if ($this->input->post ( "QueryDate1" )) {
			if ($this->input->post ( "type" ) == "searchByNode") {
				$node = $this->input->post ( "node" );
			} else {
				$searchType = "searchByIP";
				$node = $this->input->post ( "ip" );
			}
			$date = $this->input->post ( "QueryDate1" );
			$time = $date . " " . $this->input->post ( "hour1" ) . ":" . $this->input->post ( "minute1" );
			$time2 = $date . " " . $this->input->post ( "hour2" ) . ":" . $this->input->post ( "minute2" );
			if ($this->input->post ( "compare" )) {
				$userGameList = $this->speed_model->getUserSpeedingGameByNodeCompare ( $date, $time, $node, $time2 );
			} else {
				$userGameList = $this->speed_model->getUserSpeedingGameByNode ( $date, $time, $node );
			}
		}
		
		$data ["list"] = "";
		if ($this->input->post ( "QueryDate1" )) {
			$idx = 1;
			
			foreach ( $userGameList as $user ) {
				$data ["list"] .= "<tr><td>$idx</td><td>" . $user ["snda_id"] . "</td><td>" . $user ["game_name"] . "</td></tr>";
				++ $idx;
			}
		}
		
		$data ["jsCode"] = "";
		if ($this->input->post ( "type" )) {
			if ($searchType == 'searchByNode') {
				$data ["jsCode"] .= '$("#nodeSearch").attr("checked",true);';
				$data ["jsCode"] .= '$("#node").val("' . $node . '");';
			} else {
				$data ["jsCode"] .= '$("#ipSearch").attr("checked",true);';
				$data ["jsCode"] .= '$("#ip").val("' . $node . '");';
			}
			$data ["jsCode"] .= '$("#hour1").val("' . $this->input->post ( "hour1" ) . '");';
			$data ["jsCode"] .= '$("#minute1").val("' . $this->input->post ( "minute1" ) . '");';
		}
		if ($this->input->post ( "compare" )) {
			$data ["jsCode"] .= "$('input[name=\"compare\"]').attr(\"checked\",\"true\");";
			$data ["jsCode"] .= '$("#hour2").val("' . $this->input->post ( "hour2" ) . '");';
			$data ["jsCode"] .= '$("#minute2").val("' . $this->input->post ( "minute2" ) . '");';
		}
		
		$data ["date"] = $date;
		
		$this->load->view ( "/modules/speed/nodeuse_view", $data );
	}
	
	function machinecode() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 29 );
		
		$this->load->model ( "user_model" );
		$this->load->library ( "function_class" );
		
		$date = date ( "Y-m", time () );
		$proList = 43;
		$installType = 1;
		$type = "install";
		if ($this->input->post ( "QueryDate1" )) {
			$date = $this->input->post ( "QueryDate1" );
			$proList = $this->input->post ( "proList" );
			$installType = $this->input->post ( "installType" );
			if ($installType == 2) {
				$type = "uninstall";
			}
		}
		
		$data ["installSum"] = "";
		$data ["uninstallSum"] = "";
		
		$allSum = $this->user_model->showMachineCodeAllSum ( $proList );
		/*$data["installSum"] .= "<td>".$allSum["install"]."</td>";
		$data["uninstallSum"] .= "<td>".$allSum["uninstall"]."</td>";*/
		$data ["installSum"] .= "<td>" . $allSum [0] ["sum"] . "</td>";
		$data ["uninstallSum"] .= "<td>" . $allSum [1] ["sum"] . "</td>";
		
		$monthSum = $this->user_model->showMachineCodeSum ( 2011, $proList );
		
		$monthSumIndex = 0;
		$monthSumCount = count ( $monthSum );
		$yinstallSum = 0;
		$yuninstallSum = 0;
		
		for($i = 1; $i <= 12; $i ++) {
			if ($monthSumCount >= ($monthSumIndex + 1) && ($i == $monthSum [$monthSumIndex] ["month"])) {
				$data ["installSum"] .= ("<td>" . $monthSum [$monthSumIndex] ["install"] . "</td>");
				$data ["uninstallSum"] .= ("<td>" . $monthSum [$monthSumIndex] ["uninstall"] . "</td>");
				$yinstallSum += $monthSum [$monthSumIndex] ["install"];
				$yuninstallSum += $monthSum [$monthSumIndex] ["uninstall"];
				++ $monthSumIndex;
			} else {
				$data ["installSum"] .= ("<td>0</td>");
				$data ["uninstallSum"] .= ("<td>0</td>");
			}
		}
		$data ["installSum"] .= "<td>" . $yinstallSum . "</td>";
		$data ["uninstallSum"] .= "<td>" . $yuninstallSum . "</td>";
		
		$rsts = $this->user_model->showMachineCodeData ( $date, $proList );
		
		$data ["list"] = "";
		$data ["fusionCategory"] = "";
		for($i = 1; $i < 31; $i ++) {
			$data ["fusionCategory"] .= "<category label='$i' />";
		}
		
		$data ["fusionDataset"] = "";
		$data ["fusionDataset2"] = "";
		$chartPotCount = 1;
		
		foreach ( $rsts as $rst ) {
			$data ["list"] .= "<tr><td>" . $rst ["date_id"] . "</td><td>" . $rst ["install"] . "</td><td>" . $rst ["uninstall"] . "</td></tr>";
			$val = "";
			$tempD = date ( "d", strtotime ( $rst ["date_id"] ) );
			while ( $tempD != $chartPotCount ) {
				++ $chartPotCount;
				$data ["fusionDataset"] .= "<set value='' />";
			}
			++ $chartPotCount;
			$val = $rst [$type];
			$data ["fusionDataset"] .= "<set value='$val' />";
		}
		
		$data ["date"] = $date;
		$data ["proList"] = $proList;
		$data ["installType"] = $installType;
		$this->load->view ( "/modules/speed/machinecode_view", $data );
	}
	
	function fix() {
		$this->speed_model->fix4 ();
	}
}

?>