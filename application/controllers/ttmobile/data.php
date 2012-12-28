<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Data extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("ttmobile/data_model","TT_data_model");
	}
	
	function index(){
		$n = 0.9699;
		$temp = (46866+8000)*$n;
		for($i = 0;$i<37;$i++)
		{
			$temp = ($temp+8000)*$n;
		}
		echo $temp;
	}	
	
	function fix(){$this->TT_data_model->fix();}
	
	function commondata()
	{
		$this->authuser_class->authSession();		
		
		$data["list"] = "";
		$data["regAll"] = $this->TT_data_model->getRegCount();
		
		$data["fusionCategory"] = "";
		$data["fusionDataset"] = "";
		$data["fusionDataset2"] = "";
		$data["fusionDataset3"] = "";
		$data["fusionDataset4"] = "";
		
		$data["fusionDataset"] .= "<dataset seriesName='峰值人数' renderAs='Line' >";
		$data["fusionDataset3"] .= "<dataset seriesName='登录人数' renderAs='Line' >";
		$data["fusionDataset4"] .= "<dataset seriesName='新登人数' renderAs='Line' >";

		$rsts = $this->TT_data_model->showCommonData();
		$peakPer = "";
		$loginPer = "";
		$newLoginPer = "";
		$userCountPer = "";
		for($i = 0,$rstscount = count($rsts); $i < $rstscount ; $i++)
		{
			if($i != 0)
			{
				$peakPer = (round(($rsts[$i]["onlinePeak"]-$rsts[($i-1)]["onlinePeak"])/$rsts[$i-1]["onlinePeak"],4)*100)."%";
				$loginPer = (round(($rsts[$i]["loginCount"]-$rsts[$i-1]["loginCount"])/$rsts[$i-1]["loginCount"],4)*100)."%";
				$newLoginPer = (round(($rsts[$i]["newLoginCount"]-$rsts[$i-1]["newLoginCount"])/$rsts[$i-1]["newLoginCount"],4)*100)."%";
				$userCountPer = (round(($rsts[$i]["userCount"]-$rsts[$i-1]["userCount"])/$rsts[$i-1]["userCount"],4)*100)."%";
			}
			$data ["list"] = "<tr><td>".$rsts[$i]["date_id"]."</td>
			                      <td>".$rsts[$i]["onlinePeak"]."</td><td>$peakPer</td>
					      <td>".date("H:i:s",strtotime($rsts[$i]["onlinePeakTime"]))."</td>
					      <td>".$rsts[$i]["loginCount"]."</td><td>$loginPer</td>
					      <td>".$rsts[$i]["loginCount3"]."</td>
					      <td>".$rsts[$i]["loginCount7"]."</td>		
					      <td>".$rsts[$i]["loginCount15"]."</td>	
					      <td>".$rsts[$i]["loginCount30"]."</td>						      
					      <td>".$rsts[$i]["invitesucc"]."</td>							      
					      <td>".$rsts[$i]["activeuser"]."</td>						      
					      <td>".$rsts[$i]["newLoginCount"]."</td>	
					      <td>$newLoginPer</td>				      						      
					      <td>".$rsts[$i]["regfail"]."</td>	
					      <td>".$rsts[$i]["userCount"]."</td><td>$userCountPer</td>
					  </tr>".$data ["list"];					
			
			$showname = "showName='0'";
			if(($i % 5) == 0)
			{
				$showname = "showName='1'";
			}
			$data["fusionCategory"] .= "<category $showname name='".date("m-d",strtotime($rsts[$i]["date_id"]))."' />";
			$data["fusionDataset"] .= "<set value='".$rsts[$i]["onlinePeak"]."' />";
			$data["fusionDataset3"] .= "<set value='".$rsts[$i]["loginCount"]."' />";
			$data["fusionDataset4"] .= "<set value='".$rsts[$i]["newLoginCount"]."' />";			
		}
		$data["fusionDataset"] .= "</dataset>";
		$data["fusionDataset3"] .= "</dataset>";
		$data["fusionDataset4"] .= "</dataset>";
		
		$this->load->view("/modules/ttmobile/data/commondata_view",$data);
	}
	
	function online()
	{
		$this->authuser_class->authSession();
		
		$data["yAxisMinValue"] = 20000;
		if ($this->input->post("yAxisMinValue"))
		{
			$data["yAxisMinValue"] = $_POST["yAxisMinValue"];
		}
		
		$date = date("Y-m-d");
		if ($this->input->post ("QueryDate1"))
		{
			$date = $_POST["QueryDate1"];
		}		
		
		$rsts = $this->TT_data_model->getOnlieUserCount($date);
		$peak = $this->TT_data_model->getPeakOnline(date("Y-m-d"));
		$data["peak"] = $peak->c."人(".date("Y-m-d H:i:s",$peak->ftime).")";
		
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
		$rsts2 = $this->TT_data_model->getOnlieUserCount($date2);
		
		$data["fusionCategory"] = "";
		$this->function_class->fusionCategory($data["fusionCategory"]);
		$data ["list"] = "";
		$data ["fusionDataset"] = "";
		
		$data ["fusionDataset2"] = "";
		$data ["fusionDataset2"] .= "<dataset seriesName='$date2' renderAs='Line' >";		
		
		$chartPotCount = 0;
		$k = 0;
		for($i = 0,$rstscount = count($rsts)-1; $i < $rstscount; $i++)
		{
			$this->function_class->fusionData($rsts[$i]["t"],$data["fusionDataset"],$rsts[$i]["c"],$chartPotCount);
			
			while(date("His",strtotime($rsts[$i]["t"])) > date("His",strtotime($rsts2[$k]["t"])))
			{
				++$k;				
			}
			if(date("His",strtotime($rsts[$i]["t"])) == date("His",strtotime($rsts2[$k]["t"])))
			{
				$percent = ($rsts2[$k]["c"] == 0)?100:round(($rsts[$i]["c"]-$rsts2[$k]["c"])/$rsts2[$k]["c"]*100,2);
				$data ["list"] = "<tr><td>".$rsts[$i]["t"]."|".$rsts2[$k]["t"]."</td>
						      <td>".$rsts[$i]["c"]."|".$rsts2[$k]["c"]."($percent %)</td></tr>".$data["list"];
				++ $k;				
			}
			else
			{
				$data ["list"] = "<tr><td>".$rsts[$i]["t"]."</td>
						      <td>".$rsts[$i]["c"]."</td></tr>".$data["list"];
			}
		}
		
		if( $date == date("Y-m-d") && (strtotime($rsts[$rstscount]["t"])+600) < time())
		{
			$data ["list"] = "<tr><td><font color='red'>超过10分钟没有数据！！！</font></td><td></td></tr>".$data["list"];
		}
				
		$chartPotCount2 = 0;
		for($i = 0,$rsts2count=count($rsts2)-1; $i < $rsts2count; $i++)
		{			
			$this->function_class->fusionData($rsts2[$i]["t"],$data["fusionDataset2"],$rsts2[$i]["c"],$chartPotCount2);
		}
		$data["fusionDataset2"] .= "</dataset>";
		
		$data["date"] = $date;
		$data["date2"] = $date2;
		
		$this->load->view("/modules/ttmobile/data/online_view",$data);
	}	
	
	function regcountper5()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m-d");
		$data["date2"] = date("Y-m-d",time()-24*60*60);
		$data["list"] = "";
		$data["sum"] = "";
		
		if($this->input->post("QueryDate1"))
		{
			$data["date"] = $_POST["QueryDate1"];
		}
		
		$rsts = $this->TT_data_model->getRegCountPer5($data["date"]);
		
		$data ["compareCheck"] = "";
		if ($this->input->post("compare"))
		{
			$data["date2"] = $this->input->post("QueryDate2");
			$data["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		}
		
		$rsts2 = $this->TT_data_model->getRegCountPer5($data["date2"]);
		
		$data ["fusionCategory"] = "";
		$this->function_class->fusionCategory($data["fusionCategory"]);
		
		$data["fusionDataset2"] = "";
		$data["fusionDataset2"] .= "<dataset seriesName='" . $data ["date2"] . "' renderAs='Line' >";
		
		$data["fusionDataset"] = "";
		$chartPotCount = 0;
		$k = 0;
		$sum = 0;
		$sum2 = 0;
		for($i=0,$rstscount=count($rsts)-1;$i<$rstscount;$i++)
		{			
			$this->function_class->fusionData($rsts[$i]["t"],$data["fusionDataset"],$rsts[$i]["c"],$chartPotCount);
			
			while(date("His",strtotime($rsts[$i]["t"])) > date("His",strtotime($rsts2[$k]["t"])))
			{
				$sum2 += $rsts2[$k]["c"];
				++$k;
			}
			if(date("His",strtotime($rsts[$i]["t"])) == date("His",strtotime($rsts2[$k]["t"])))
			{
				$percent = ($rsts2[$k]["c"] == 0)?100:round(($rsts[$i]["c"]-$rsts2[$k]["c"])/$rsts2[$k]["c"]*100,2);
				$data ["list"] = "<tr><td>".$rsts[$i]["t"]."|".$rsts2[$k]["t"]."</td>
						      <td>".$rsts[$i]["c"]."|".$rsts2[$k]["c"]."($percent %)</td></tr>".$data["list"];
				$sum2 += $rsts2[$k]["c"];				
				++$k;
			}
			else
			{
				$data["list"] = "<tr><td>".$rsts[$i]["t"]."|".date("Y-m-d",strtotime($rsts2[$k]["t"]))." ".date("H:i:s",strtotime($rsts[$i]["t"]))."</td>
				 	      	     <td>".$rsts[$i]["c"]."|0</td></tr>".$data["list"];				
			}
			$sum += $rsts[$i]["c"];			
		}
		if((strtotime($rsts[$rstscount]["t"])+600) < time())
		{
			$data ["list"] = "<tr><td><font color='red'>超过10分钟没有数据！！！</font></td><td></td><td></td></tr>".$data["list"];
		}	
		
		$data["sum"] = "$sum|$sum2";		
		
		$chartPotCount2 = 0;
		for($i = 0,$rsts2count=count($rsts2)-1; $i < $rsts2count; $i++)
		{
			$this->function_class->fusionData($rsts2[$i]["t"],$data["fusionDataset2"],$rsts2[$i]["c"],$chartPotCount2);			
		}
		$data["fusionDataset2"] .= "</dataset>";		
		$this->load->view("/modules/ttmobile/data/regcountper5_view",$data);
	}
	
	function systemcall()
	{
		$this->authuser_class->authSession();
		
		$data["list"] = "";
		$rsts = $this->TT_data_model->showSystemCall();
		foreach($rsts as $r)
		{
			$data["list"] .= "<tr><td>".$r["date_id"]."</td>
					      <td>".$r["cts"]."(".(round(($r["cts"]/($r["css"]+$r["cts"])),4)*100)."%)</td>
					      <td>".$r["ttmakesyscall"]."</td>
					      <td>".$r["css"]."</td>
					      <td>".$r["sysmakesyscall"]."</td></tr>";
		}
		
		$this->load->view("/modules/ttmobile/data/systemcalldata_view",$data);
	}
	
	function devinfodata()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m-d");
		$data["date2"] = date("Y-m-d",time()-24*60*60);
		$data["list"] = "";
		$data["dev"] = -1;
		
		if($this->input->post("dev")>= -1)
		{
			$data["dev"] = $this->input->post("dev");
		}		
		if($this->input->post("QueryDate1"))
		{
			$data["date"] = $_POST["QueryDate1"];
		}
		
		$rsts = $this->TT_data_model->getDevinfoData($data["date"],$data["dev"]);
		
		$data ["compareCheck"] = "";
		if ($this->input->post("compare"))
		{
			$data["date2"] = $this->input->post("QueryDate2");
			$data["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		}
		
		$rsts2 = $this->TT_data_model->getDevinfoData($data["date2"],$data["dev"]);
		
		$data ["fusionCategory"] = "";
		$this->function_class->fusionCategory($data["fusionCategory"]);
		$data ["fusionDataset"] = "";
		$data["fusionDataset2"] = "<dataset seriesName='" . $data ["date2"] . "' renderAs='Line' >";
		
		$chartPotCount = 0;
		$k = 0;
		$sum = 0;
		$sum2 = 0;
		for($i=0,$rstscount=count($rsts)-1;$i<$rstscount;$i++)
		{			
			$this->function_class->fusionData($rsts[$i]["t"],$data["fusionDataset"],$rsts[$i]["c"],$chartPotCount);
			
			while(date("His",strtotime($rsts[$i]["t"])) > date("His",strtotime($rsts2[$k]["t"])))
			{
				$sum2 += $rsts2[$k]["c"];
				++$k;
			}
			if(date("His",strtotime($rsts[$i]["t"])) == date("His",strtotime($rsts2[$k]["t"])))
			{
				$percent = ($rsts2[$k]["c"] == 0)?100:round(($rsts[$i]["c"]-$rsts2[$k]["c"])/$rsts2[$k]["c"]*100,2);
				$data ["list"] = "<tr><td>".$rsts[$i]["t"]."|".$rsts2[$k]["t"]."</td>
						      <td>".$rsts[$i]["c"]."|".$rsts2[$k]["c"]."($percent %)</td></tr>".$data["list"];	
				$sum2 += $rsts2[$k]["c"];	
				++$k;
			}
			else
			{
				$data["list"] = "<tr><td>".$rsts[$i]["t"]."</td>
				 	      	     <td>".$rsts[$i]["c"]."</tr>".$data["list"];				
			}
			$sum += $rsts[$i]["c"];		
		}
		$data["sum"] = "$sum|$sum2";	
		$chartPotCount2 = 0;
		for($i = 0,$rsts2count=count($rsts2)-1; $i < $rsts2count; $i++)
		{			
			$this->function_class->fusionData($rsts2[$i]["t"],$data["fusionDataset2"],$rsts2[$i]["c"],$chartPotCount2);
		}
		
		$data["fusionDataset2"] .= "</dataset>";
		
		$this->load->view("/modules/ttmobile/data/devinfodata_view",$data);
	}
	
	function userlose()
	{
		$this->authuser_class->authSession();
		
		$rsts = $this->TT_data_model->showUserLose();
		
		$data ["list"] = "";
		foreach ($rsts as $rst)
		{
			$data["list"] .= "<tr><td>".$rst["date_id"]."</td><td>".$rst["login"]."</td>";
			if($rst["d3"] == 0)
			{
				$data["list"] .= "<td></td><td></td>";
			}
			else
			{
				$data["list"] .= "<td>".$rst["d3"]."</td><td>".round($rst["d3"]/$rst["login"]*100,2)."%</td>";
			}
			if($rst["d7"] == 0)
			{
				$data["list"] .= "<td></td><td></td>";
			}
			else
			{
				$data["list"] .= "<td>".$rst["d7"]."</td><td>".round($rst["d7"]/$rst["login"]*100,2)."%</td>";
			}
			if ($rst["d15"] == 0)
			{
				$data["list"] .= "<td></td><td></td>";
			}
			else
			{
				$data["list"] .= "<td>".$rst["d15"]."</td><td>".round($rst["d15"]/$rst["login"]*100,2)."%</td>";
			}
			if ($rst["d30"] == 0)
			{
				$data["list"] .= "<td></td><td></td>";
			}
			else
			{
				$data["list"] .= "<td>".$rst["d30"]."</td><td>".round($rst["d30"]/$rst["login"]*100,2)."%</td>";
			}
			$data["list"] .= "</tr>";
		}
		
		$this->load->view("/modules/ttmobile/data/userlose_view",$data);
	}
	
	function commonuserlose()
	{
		$this->authuser_class->authSession();
		
		$rsts = $this->TT_data_model->showCommonUserLose();
		
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
		
		$this->load->view("/modules/ttmobile/data/commonuserlose_view", $data );
	}
	
	function newuserlose()
	{
		$this->authuser_class->authSession();
		
		$rsts = $this->TT_data_model->showNewUserLose();
		
		$data ["list"] = "";
		foreach($rsts as $rst)
		{
			$data["list"] .= "<tr><td>".$rst["date_id"]."</td><td>".$rst["login"]."</td>";
			for($i = 1; $i <= 30; $i ++)
			{
				if ($rst ["d$i"] == 0)
				{
					$data["list"] .= "<td></td><td></td>";
				}
				else
				{
					$data["list"] .= "<td>".$rst ["d$i"]."</td><td>".round($rst["d$i"]/$rst["login"]*100,2)."%</td>";
				}
			}
			$data["list"] .= "</tr>";
		}
		
		$this->load->view("/modules/ttmobile/data/newuserlose_view", $data );
	}
	
	function newuserlosesp()
	{
		$this->authuser_class->authSession();
		$data["sp"] = 101;
		if($this->input->post("sp"))
		{
			$data["sp"] = $this->input->post("sp");			
		}
		
		$data["spselect"] = $this->function_class->getTTmMarketSelect();
		
		$rsts = $this->TT_data_model->showNewUserLoseBySp($data["sp"]);
		
		$data ["list"] = "";
		foreach($rsts as $rst)
		{
			$data["list"] .= "<tr><td>".$rst["date_id"]."</td><td>".$rst["login"]."</td>";
			for($i = 1; $i <= 30; $i ++)
			{
				if (($rst ["login"] == 0) || ((strtotime($rst["date_id"])+24*60*60*$i) >= strtotime(date("Y-m-d"))))
				{
					$data["list"] .= "<td></td><td></td>";
				}
				else
				{
					$data["list"] .= "<td>".$rst ["d$i"]."</td><td>".round($rst["d$i"]/$rst["login"]*100,2)."%</td>";
				}
			}
			$data["list"] .= "</tr>";
		}
		
		$this->load->view("/modules/ttmobile/data/newuserlosesp_view", $data );
	}
	
	function loginversion()
	{
		$this->authuser_class->authSession();
		
		$data["date"] = date("Y-m-d",time()-24*60*60);
		if($this->input->post("QueryDate1"))
		{
			$data["date"] = $this->input->post("QueryDate1");			
		}
		$rsts = $this->TT_data_model->showLoginVersion($data["date"]);
		$data["list"] = "";
		for($i=0;$i<count($rsts);$i++)
		{
			$data["list"] .= "<tr><td>".$rsts[$i]["date_id"]."</td>
			                      <td>".$rsts[$i]["ver"]."</td>
					      <td>".$rsts[$i]["u"]."</td>
					      <td>".$rsts[$i]["c"]."</td>
					      <td>".round($rsts[$i]["c"]/$rsts[$i]["u"],2)."</td></tr>";
		}
		
		$this->load->view("/modules/ttmobile/data/loginversion_view",$data);
	}
	
	function loginversionbydays()
	{
		$this->authuser_class->authSession();
		
		$data ["date"] = date("Y-m");
		$data ["list"] = "";
		if ($this->input->post("QueryDate1"))
		{
			$data["date"] = $this->input->post("QueryDate1");
		}
		
		$rsts = $this->TT_data_model->showLoginVersionByDays($data["date"]);
		foreach ($rsts as $rst)
		{
			$data ["list"] .= "<tr><td>".$rst["month_id"]."</td>
					       <td>".$rst["period"]."</td>
					       <td>".$rst["days"]."</td>
					       <td>".$rst["ver"]."</td>
					       <td>".$rst["c"]."</td>
					       <td>".$rst["u"]."</td>
					       <td>".(round($rst["c"]/$rst["u"],2))."</td>
					   </tr>";
		}
		$this->load->view ("/modules/ttmobile/data/loginversionbydays_view", $data );
	}
	
	function asyspdata()
	{
		$this->authuser_class->authSession();
		
		if($this->input->post("asy"))
		{
			$this->TT_data_model->updateSpDataDisplay();
		}
		
		$this->load->view("/modules/ttmobile/data/asyspdata_view");
	}
	
	function spdata()
	{
		$this->authuser_class->authSession();
		
		$data["QueryDate1"] = date("Y-m-d",time()-24*60*60);
		if($this->input->post("QueryDate1"))
		{
			$data["QueryDate1"] = $this->input->post("QueryDate1");
		}
		
		$TTmark = $this->function_class->TTMarket();
		
		$rst = $this->TT_data_model->showSpData($data["QueryDate1"]);
		$data["list"] = "";
		$rst_regbymcode = 0;
		$rst_regbymobile = 0;
		$rst_count = count($rst);
		
		for($i=0;$i<$rst_count;$i++)
		{
			$temp = "";
			$this->function_class->spDiscount($rst[$i]["date_id"],$rst[$i]["regbymcode"],$rst[$i]["sid"],$temp);
			
			$rst_regbymcode += $rst[$i]["regbymcode"];
			$rst_regbymobile += $rst[$i]["regbymobile"];
			
			$data["list"] .= "<tr><td>".$rst[$i]["date_id"]."</td>
					      <td>".$rst[$i]["sid"]."</td>
					      <td>".$TTmark[$rst[$i]["sid"]].($temp?"(".$temp.")":"")."</td>
					      <td>".$rst[$i]["regbymcode"]."</td>
					      <td>".$rst[$i]["regbymobile"]."</td>
					      <td>".($rst[$i]["display"]?"可见":"不可见")."</td>
					  </tr>";
			if($i<($rst_count-1))
			{
				if($rst[$i]["date_id"] != $rst[$i+1]["date_id"])
				{					
					$data["list"] .= "<tr style=\"color:red\"><td>".$rst[$i]["date_id"]."</td>
							      <td></td>
							      <td>总量</td>
							      <td>$rst_regbymcode</td>
							      <td>$rst_regbymobile</td>
							      <td></td>
							  </tr>
							  <tr style='height:22px;line-height:22px'><td></td><td></td><td></td><td></td><td></td><td></td></tr>";					
					$rst_allreg = 0;
					$rst_regbymobile = 0;
					$rst_regbymcode = 0;
				}
			}
			else
			{				
				$data["list"] .= "<tr style=\"color:red\"><td>".$rst[$i]["date_id"]."</td>
						      <td></td>
						      <td>总量</td>
						      <td>$rst_regbymcode</td>
						      <td>$rst_regbymobile</td>
						      <td></td>
						  </tr>
						  <tr style='height:22px;line-height:22px'><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
			}			
		}
		$this->load->view("/modules/ttmobile/data/spdata_view",$data);
	}
	
	function userstatisareabyip()
	{
		$this->authuser_class->authSession();
		$data["QueryDate1"] = date("Y-m-d");
		$data["list"] = "";
		$data["action"] = 0;
		$data["operator"] = 0;
		$area = $this->function_class->NtoAreaList();
		
		if($this->input->post("QueryDate1"))
		{
			$data["QueryDate1"] = $this->input->post("QueryDate1");
		}
		if($this->input->post("action"))
		{
			$data["action"] = $this->input->post("action");
		}
		if($this->input->post("operator"))
		{
			$data["operator"] = $this->input->post("operator");
		}
		$rst = $this->TT_data_model->getUserStatisAreaByIp($data["QueryDate1"],$data["action"],$data["operator"]);
		$data["list"] .= "<tr><th>时间</th><th>全部</th>";
		for($i = 1;$i<=35;$i++)
		{
			$data["list"] .= "<th>".$area["n$i"]."</th>";
		}
		$data["list"] .= "</tr>";
		for($i=0,$rstcount = count($rst);$i<$rstcount;$i++)
		{
			$tempSum = 0;
			$tempTd = "";
			
			$data["list"] .= "<tr><td>".date("H:i:s",$rst[$i]["record_time"])."</td>";
			for($j=1;$j<=35;$j++)
			{
				$tempSum += $rst[$i]["n$j"];
				$tempTd .= "<td>".$rst[$i]["n$j"]."</td>";
			}
			$data["list"].= "<td>$tempSum</td>$tempTd</tr>";
		}
				
		$rstOprator = $this->TT_data_model->getUserStatisOperatorByIp($data["QueryDate1"],$data["action"]);
		$data["fusionData"] =  "<set name='移动' value='".$rstOprator[0]["Mobile"]."' />".
				       "<set name='电信' value='".$rstOprator[0]["Telcom"]."' />".
				       "<set name='联通' value='".$rstOprator[0]["Unicom"]."' />".
				       "<set name='其他' value='".$rstOprator[0]["Other"]."' />";
		
		$this->load->view("/modules/ttmobile/data/userstatisareabyip_view",$data);
	}
	
	function userstatisareabymobile()
	{
		$this->authuser_class->authSession();
		$data["QueryDate1"] = date("Y-m-d");
		$data["list"] = "";
		$data["action"] = 0;
		$data["operator"] = 0;
		$area = $this->function_class->NtoAreaList();
		
		if($this->input->post("QueryDate1"))
		{
			$data["QueryDate1"] = $this->input->post("QueryDate1");
		}
		if($this->input->post("action"))
		{
			$data["action"] = $this->input->post("action");
		}
		if($this->input->post("operator"))
		{
			$data["operator"] = $this->input->post("operator");
		}
		$rst = $this->TT_data_model->getUserStatisAreaByMobile($data["QueryDate1"],$data["action"],$data["operator"]);		
		$data["list"] .= "<tr><th>时间</th><th>全部</th>";
		for($i = 1;$i<=35;$i++)
		{
			$data["list"] .= "<th>".$area["n$i"]."</th>";
		}
		$data["list"] .= "</tr>";
		for($i=0,$rstcount = count($rst);$i<$rstcount;$i++)
		{
			$tempSum = 0;
			$tempTd = "";
			
			$data["list"] .= "<tr><td>".date("H:i:s",$rst[$i]["record_time"])."</td>";
			for($j=1;$j<=35;$j++)
			{
				$tempSum += $rst[$i]["n$j"];
				$tempTd .= "<td>".$rst[$i]["n$j"]."</td>";
			}
			$data["list"].= "<td>$tempSum</td>$tempTd</tr>";
		}
		
		$rstOprator = $this->TT_data_model->getUserStatisOperatorByMobile($data["QueryDate1"],$data["action"]);
		$data["fusionData"] =  "<set name='移动' value='".$rstOprator[0]["Mobile"]."' />".
				       "<set name='电信' value='".$rstOprator[0]["Telcom"]."' />".
				       "<set name='联通' value='".$rstOprator[0]["Unicom"]."' />".
				       "<set name='其他' value='".$rstOprator[0]["Other"]."' />";
		
		$this->load->view("/modules/ttmobile/data/userstatisareabymobile_view",$data);
	}

}
?>