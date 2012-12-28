<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Data extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("vipkf/data_model_vipkf","VIPKF_data_model");
	}
        
        function commondata()
        {
            $this->authuser_class->checkLogin ();
	    $this->authuser_class->checkPermission(1201);
                
            $data["list"] = "";
            $rst = $this->VIPKF_data_model->showCommondata();
            for($i=0,$c=count($rst);$i<$c;$i++)
            {
                $data["list"] .= "<tr><td>".$rst[$i]["date_id"]."</td>
                                      <td>".$rst[$i]["allmobiles"]."</td>
                                      <td>".$rst[$i]["allcall"]."</td>
                                      <td>".$rst[$i]["succall"]."</td>
                                      <td>".(round($rst[$i]["succall"]/$rst[$i]["allcall"],2)*100)."%</td></tr>";
            }
            $this->load->view("/modules/vipkf/data/commondata_view",$data);
        }
	
	function incomingcall()
	{
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission(1205);
	    
		$data["date"] = date("Y-m-d");
		$data["date2"] = date("Y-m-d",time()-24*60*60);
		$data["list"] = "";
		
		if($this->input->post("QueryDate1"))
		{
			$data["date"] = $_POST["QueryDate1"];
		}
		
		$rsts = $this->VIPKF_data_model->showIncomingCallData($data["date"]);
		
		$data ["compareCheck"] = "";
		if ($this->input->post("compare"))
		{
			$data["date2"] = $this->input->post("QueryDate2");
			$data["compareCheck"] = "$('input[name=\"compare\"]').attr(\"checked\",\"true\");$(\"#vs\").css(\"display\",\"inline\");";
		}
		
		$rsts2 = $this->VIPKF_data_model->showIncomingCallData($data["date2"]);
		
		$data ["fusionCategory"] = "";
		
		$data["fusionDataset2"] = "";
		$data["fusionDataset2"] .= "<dataset seriesName='" . $data ["date2"] . "' renderAs='Line' >";
		
		for($i=0;$i<12*24;$i++)
		{
			$text = floor($i/12).":".str_pad(($i%12)*5,2,0,STR_PAD_LEFT);
			$show = ($i%12)==0?"1":"0";
			$data["fusionCategory"] .= "<category label='$text' showName='$show'/>";
		}
		$data ["fusionDataset"] = "";
		$chartPotCount = 0;
		$k = 0;
		for($i=0,$rstscount=count($rsts)-1;$i<$rstscount;$i++)
		{
			$val = "";
			$tempH = date("H",strtotime($rsts[$i]["t"]));
			$tempI = date("i",strtotime($rsts[$i]["t"]));
			while(($tempH*12 + $tempI/5) > $chartPotCount)
			{
				++$chartPotCount;
				$data ["fusionDataset"] .= "<set value='' />";
			}
			++$chartPotCount;
			$val = $rsts[$i]["c"];
			$data["fusionDataset"] .= "<set value='$val' />";
			
			while(date("His",strtotime($rsts[$i]["t"])) > date("His",strtotime($rsts2[$k]["t"])))
			{
				++$k;
			}
			if(date("His",strtotime($rsts[$i]["t"])) == date("His",strtotime($rsts2[$k]["t"])))
			{
				$percent = ($rsts2[$k]["c"] == 0)?100:round(($rsts[$i]["c"]-$rsts2[$k]["c"])/$rsts2[$k]["c"]*100,2);
				$data ["list"] = "<tr><td>".$rsts[$i]["t"]."|".$rsts2[$k]["t"]."</td>
						      <td>".$rsts[$i]["c"]."|".$rsts2[$k]["c"]."($percent %)</td>
						      <td>".$rsts[$i]["Facceptcallnum"]."|".$rsts2[$k]["Facceptcallnum"]."</td>
						      <td>".$rsts[$i]["Fonlineagentnum"]."|".$rsts2[$k]["Fonlineagentnum"]."</td>
						      <td>".$rsts[$i]["Fcallingagentnum"]."|".$rsts2[$k]["Fcallingagentnum"]."</td>
						  </tr>".$data["list"];
								
				++$k;
			}
			else
			{
				$data["list"] = "<tr><td>".$rsts[$i]["t"]."|".date("Y-m-d",strtotime($rsts2[$k]["t"]))." ".date("H:i:s",strtotime($rsts[$i]["t"]))."</td>
				 	      	     <td>".$rsts[$i]["c"]."|0</td>
						     <td>".$rsts[$i]["Facceptcallnum"]."|".$rsts2[$k]["Facceptcallnum"]."</td>
						      <td>".$rsts[$i]["Fonlineagentnum"]."|".$rsts2[$k]["Fonlineagentnum"]."</td>
						      <td>".$rsts[$i]["Fcallingagentnum"]."|".$rsts2[$k]["Fcallingagentnum"]."</td>
						</tr>".$data["list"];				
			}		
		}
		
		$chartPotCount2 = 0;
		for($i = 0,$rsts2count=count($rsts2)-1; $i < $rsts2count; $i++)
		{
			$val = "";
			$tempH = date("H",strtotime($rsts2[$i]["t"]));
			$tempI = date("i",strtotime($rsts2[$i]["t"]));
			while(($tempH*12 + $tempI/5) > $chartPotCount2)
			{
				++$chartPotCount2;
				$data ["fusionDataset2"] .= "<set value='' />";
			}
			++$chartPotCount2;
			$val = $rsts2[$i]["c"];
			$data["fusionDataset2"] .= "<set value='$val' />";
		}
		$data["fusionDataset2"] .= "</dataset>";

		$this->load->view("/modules/vipkf/data/incomingcall_view",$data);		
	}
}