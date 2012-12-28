<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Manage extends CI_Controller
{
	function __construct()
        {
		parent::__construct ();
		$this->load->model("im/manage_model", "IM_manage_model" );
	}
	
	function index() {}
        
        private function sysmsg_getContent($content)
        {
            return str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",$content)));
        }
        
        function sysmsg()
        {
            $this->authuser_class->checkLogin();
	    $this->authuser_class->checkPermission(1101);
                
            if($this->input->post("submit"))
            {
                $S_StartTime = $this->input->post("stime");
                $S_EndTime = $this->input->post("etime");
                $S_Type = $this->input->post("type");
                $S_Receiver = $this->input->post("txtReceiver");
                $S_Mode = $this->input->post("mode");
                $S_SysType = $this->input->post("systype");
                $S_Title = $this->input->post("title");
                $S_Describ = $this->input->post("content");
                $S_CreateTime = date("Y-m-d H:i:s");
                $S_Creater = $_SESSION['adminname'];
                $url = $this->input->post("url");                
                $S_Content = "<msg><msgitem><type>5</type><etime>".strtotime($S_EndTime).
                             "</etime><subtype id=\"$S_SysType\" name=\"系统消息\"/>".
                             "<displaymode>$S_Mode</displaymode><linkurl>$url</linkurl>".
                             "<title fname=\"宋体\" fcolor=\"13463317\" fsize=\"12\" fflag=\"1\">".$this->sysmsg_getContent($S_Title)."</title>".
                             "<txt fname=\"宋体\" fcolor=\"0\" fsize=\"12\" fflag=\"0\">".$this->sysmsg_getContent($S_Describ)."</txt></msgitem></msg>";                
                if($S_StartTime && $S_EndTime && $S_Title && $S_Describ)
                {
                    $this->IM_manage_model->AddSysMsg($S_StartTime,$S_EndTime,$S_Type,$S_Receiver,$S_Content,$S_Mode,$S_SysType,$S_Title,$S_Describ,$S_CreateTime,$S_Creater);
                }
            }
            $this->load->view("/modules/im/manage/sysmsg_view");
        }
}
?>
