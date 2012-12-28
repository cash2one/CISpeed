<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Statistic extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("vipkf/data_model_vipkf","TT_vipkf_model");
	}
	
	function index() {}
	
	function getCommonData()
        {
            $this->TT_vipkf_model->getCommondata(date("Y-m-d",time()-24*60*60));
        }
}