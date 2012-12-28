<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Data extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("ttmobile/data_model","TT_data_model");
	}
	
	function index(){}
	
	function spdata()
	{
		$this->authuser_class->checkTTmOutLogin();
		$this->authuser_class->checkTTmOutPermission(96);
		
		$data["name"] = $_SESSION['adminname'];
		$data["spcheck"] = "";
		$spflag = $_SESSION['adminspflag'];
		$spflag_array = explode(",",$spflag);
		foreach($spflag_array as $temp)
		{
			$data["spcheck"] .= '<input type="checkbox" name="check[]" value="'.$temp.'">'.$temp.'</input>';
		}	
		
		$data["spflag"] = $spflag;
		if($this->input->post("query"))
		{
			$data["spflag"] = implode(",",$this->input->post("check"));
		}
		
		$data["QueryDate1"] = date("Y-m-d",time()-24*60*60);
		if($this->input->post("QueryDate1"))
		{
			$data["QueryDate1"] = $this->input->post("QueryDate1");
		}
		
		$data["QueryDate2"] = date("Y-m-d",time()-24*60*60);
		if($this->input->post("QueryDate2"))
		{
			$data["QueryDate2"] = $this->input->post("QueryDate2");
		}
		
		$TTmark = $this->function_class->TTMarket();
		
		$rst = $this->TT_data_model->showSpData($data["QueryDate1"],$data["spflag"],1,$data["QueryDate2"]);		
		$data["list"] = "";
		$rst_regbymcode = 0;
		$rst_regbymobile = 0;
		$rst_count = count($rst);
		for($i=0;$i<$rst_count;$i++)
		{
			$this->function_class->spDiscount($rst[$i]["date_id"],$rst[$i]["regbymcode"],$rst[$i]["sid"],$rst[$i]["regbymcode"]);
			$this->function_class->spDiscount($rst[$i]["date_id"],$rst[$i]["regbymobile"],$rst[$i]["sid"],$rst[$i]["regbymobile"]);
						
			$rst_regbymcode += $rst[$i]["regbymcode"];
			$rst_regbymobile += $rst[$i]["regbymobile"];
			
			$data["list"] .= "<tr><td>".$rst[$i]["date_id"]."</td>
					      <td>".$rst[$i]["sid"]."</td>
					      <td>".$TTmark[$rst[$i]["sid"]]."</td>
					      <td>".$rst[$i]["regbymcode"]."</td>
					  </tr>";
			if($i<($rst_count-1))
			{
				if($rst[$i]["date_id"] != $rst[$i+1]["date_id"])
				{					
					$data["list"] .= "<tr style=\"color:red\"><td>".$rst[$i]["date_id"]."</td>
							      <td></td>
							      <td>总量</td>
							      <td>$rst_regbymcode</td>
							  </tr>
							  <tr style='height:22px;line-height:22px'><td></td><td></td><td></td><td></td></tr>";	
					$rst_regbymcode = 0;
					$rst_regbymobile = 0;
				}
			}
			else
			{				
				$data["list"] .= "<tr style=\"color:red\"><td>".$rst[$i]["date_id"]."</td>
						      <td></td>
						      <td>总量</td>
						      <td>$rst_regbymcode</td>
						  </tr>
						  <tr style='height:22px;line-height:22px'><td></td><td></td><td></td><td></td></tr>";
			}			
		}
		$this->load->view("/modules/ttmobileout/data/spdata_view",$data);
	}
}

?>