<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Alert extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->model("ttmobile/data_model","TT_data_model");
		$this->load->library("function_class");
	}
	
	function index() {
	}
	
	function alertonlineuser()
	{
		header ( "content-Type: text/html; charset=Utf-8" );
		$url = "http://123.103.17.18:114/SendGMessage?name=刘刚;周森鑫;马健;刘顺;乔晓华;郑爱军;附雄道;张守凯;马剑阁&message=";
		
		$msg = "";
		$rst = $this->TT_data_model->getAlertOnlieUserCount();
		if(count($rst) > 1)
		{
			if((strtotime($rst[0]["now"]) - strtotime($rst[0]["t"])) > 1200)
			{
				$msg = "手机通通在线人数于".$rst[0]["t"]."后未上报";
				echo $url.$msg;
				$this->function_class->fetchUrl($url.$msg);
			}
			else
			{
				$n = $rst[0]["ucount"] - $rst[1]["ucount"];
				if( $n < -1000)
				{
					$msg = "手机通通在线人数从".$rst[1]["t"]."点".$rst[1]["ucount"]."人下降到".$rst[0]["t"]."点".$rst[0]["ucount"]."人，人数下跌".(-$n);
					echo $url.$msg;
					$this->function_class->fetchUrl($url.$msg);
				}
			}
		}
		
	}

}

?>