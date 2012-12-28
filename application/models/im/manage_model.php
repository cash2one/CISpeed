<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Manage_model extends CI_Model
{
	
	function __construct()
        {
            parent::__construct ();
        }
	
        /*系统消息操作————开始*/
	function AddSysMsg($S_StartTime,$S_EndTime,$S_Type,$S_Receiver,$S_Content,$S_Mode,$S_SysType,$S_Title,$S_Describ,$S_CreateTime,$S_Creater)
        {
		$S_StartTime = mysql_escape_string($S_StartTime);
		$S_EndTime = mysql_escape_string($S_EndTime);
		$S_Type = mysql_escape_string($S_Type);
		$S_Receiver = mysql_escape_string($S_Receiver);
		$S_Content = mysql_escape_string($S_Content);
		$S_Mode = mysql_escape_string($S_Mode);
		$S_SysType = mysql_escape_string($S_SysType);
		$S_Title= mysql_escape_string($S_Title);
		$S_Describ = mysql_escape_string($S_Describ);
		$S_CreateTime = mysql_escape_string($S_CreateTime);
		$S_Creater = mysql_escape_string($S_Creater);
		
		$ETserverdb = $this->load->database('etserver',TRUE);
		$sql = "INSERT INTO M_SysMessage(S_StartTime,S_EndTime,S_Type,S_Receiver,S_Content,S_Mode,
						 S_SysType,S_Title,S_Describ,S_CreateTime,S_Creater)
			                  VALUES('$S_StartTime','$S_EndTime',$S_Type,'$S_Receiver','$S_Content',$S_Mode,
						 $S_SysType,'$S_Title','$S_Describ','$S_CreateTime','$S_Creater')";
		$ETserverdb->query($sql);
	}
	
		
        /*系统消息操作————结束*/
}
?>