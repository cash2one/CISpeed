<?php if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Manage_model extends CI_Model
{
	
	function __construct()
        {
		parent::__construct ();
	}
	
	/*手机通通激活码申请管理————开始*/
	function getApplyKeyList($approve = 0, $pagesize = 0, $pagecount = 0, $sort = "") {
		$orderby = " id desc ";
		if ($sort != "") {
			$orderby = $sort;
		}
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "select id,name,applytime,mobile,inet_ntoa(ip) ip  from ttmobiledb.17et_keyapply   where approve = $approve  order by $orderby limit " . ($pagecount) . ",$pagesize";
		return $TTdb->query ( $sql )->result_array ();
	}
	
	function getApplyKeyCount($approve = 0) {
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "select count(0) c from ttmobiledb.17et_keyapply where approve = $approve ";
		return $TTdb->query ( $sql )->row ()->c;
	}
	
	function applyApprove($id) {
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "update ttmobiledb.17et_keyapply set approve = 2,approvetime = " . time () . " where id = $id ";
		$TTdb->query ( $sql );
	}
	
	function sendKey($mobile, $key, $appid = 0) {
		$table = "ttmobiledb.t_user_active_info_" . fmod ( $mobile, 8 );
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "select count(0) c from $table where Fleft_uid = $mobile";
		$c = $TTdb->query ( $sql )->row ()->c;
		if ($c == 0) {
			$sql = "insert into $table(Fleft_uid,Factivecode,Factivestatus,Factivetime,appid) values($mobile,'$key',2," . time () . ",$appid)";
			$TTdb->query ( $sql );
			return 0;
		} else {
			return - 1;
		}
	}
	
	function delApplyKey($appid) {
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "delete from ttmobiledb.17et_keyapply where id = $appid";
		$TTdb->query ( $sql );
	}
	
	function queryKey($mobile) {
		$table = "ttmobiledb.t_user_active_info_" . fmod ( $mobile, 8 );
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "select a.Fleft_uid,a.Factivecode,a.Factivestatus,b.name,c.region,b.applytime,a.Factivetime,inet_ntoa(b.ip) ip from $table a left join ttmobiledb.17et_keyapply b on a.appid = b.id
                left join ttmobiledb.17et_mobileregion c  on mid(a.fleft_uid,1,7) = c.code where a.Fleft_uid = $mobile;";
		return $TTdb->query ( $sql )->row ();
	}
	
	function queryActivByName($name) {
		$name = mysql_escape_string ( $name );
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "select b.name,a.fleft_uid,a.factivecode,a.factivestatus,a.factivetime,a.fstatustime,b.applytime,inet_ntoa(b.ip) ip,c.region from 
                (SELECT * FROM ttmobiledb.t_user_active_info_0 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_1 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_2 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_3 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_4 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_5 union all
                SELECT * FROM ttmobiledb.t_user_active_info_6 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_7) as a left join ttmobiledb.17et_keyapply b		
                on a.appid = b.id left join ttmobiledb.17et_mobileregion c  on mid(a.fleft_uid,1,7) = c.code where b.name like '%$name%';";
		
		return $TTdb->query ( $sql )->result_array ();
	
	}
	
	function getActiveList($pagesize = 0, $pagecount = 0, $status = -1) {
		$approve = "";
		
		if ($status == 1 || $status == 2) {
			$approve = " where a.factivestatus = $status ";
		}
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "select b.name,a.fleft_uid,a.factivecode,a.factivestatus,a.factivetime,a.fstatustime,b.applytime,inet_ntoa(b.ip) ip,c.region from 
                (SELECT * FROM ttmobiledb.t_user_active_info_0 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_1 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_2 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_3 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_4 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_5 union all
                SELECT * FROM ttmobiledb.t_user_active_info_6 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_7) as a left join ttmobiledb.17et_keyapply b		
                on a.appid = b.id left join ttmobiledb.17et_mobileregion c  on mid(a.fleft_uid,1,7) = c.code $approve
                order by a.factivetime desc limit " . ($pagecount) . ",$pagesize";
		
		return $TTdb->query ( $sql )->result_array ();
	}
	
	function getActiveListCount($status = -1) {
		$approve = "";
		
		if ($status == 1 || $status == 2) {
			$approve = " where factivestatus = $status ";
		}
		
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "select count(0) c from 
                (SELECT * FROM ttmobiledb.t_user_active_info_0 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_1 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_2 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_3 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_4 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_5 union all
                SELECT * FROM ttmobiledb.t_user_active_info_6 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_7) as T $approve ";
		return $TTdb->query ( $sql )->row ()->c;
	}
	
	function getActiveStatusCount()
        {
		$TTdb = $this->load->database ( 'et', TRUE );
		$sql = "select count(0) c,factivestatus from 
                (SELECT * FROM ttmobiledb.t_user_active_info_0 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_1 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_2 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_3 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_4 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_5 union all
                SELECT * FROM ttmobiledb.t_user_active_info_6 union all 
                SELECT * FROM ttmobiledb.t_user_active_info_7) as T group by factivestatus";
		return $TTdb->query ( $sql )->result_array ();
	}
	
	function sendSysMsg($S_StartTime,$S_EndTime,$S_Mode,$S_Receiver,$S_Content,$S_SysType,$S_Title,$S_CreateTime,$S_Creater,$S_ShowType,$S_Link,$S_VoicePrompt,$S_SendType)
	{
		$S_StartTime = mysql_escape_string($S_StartTime);
		$S_EndTime = mysql_escape_string($S_EndTime);
		$S_Type = 2;
		$S_Receiver = mysql_escape_string($S_Receiver);
		$S_Content = mysql_escape_string($S_Content);
		$S_Mode = mysql_escape_string($S_Mode);
		$S_Status = 1;
		$S_SysType = mysql_escape_string($S_SysType);
		$S_Title= mysql_escape_string($S_Title);
		$S_CreateTime = date("Y-m-d H:i:s");
		$S_Creater = mysql_escape_string($S_Creater);
		$S_ShowType = mysql_escape_string($S_ShowType);
		$S_Link = mysql_escape_string($S_Link);
		$S_VoicePrompt = mysql_escape_string($S_VoicePrompt);
		$S_SendType = mysql_escape_string($S_SendType);
		
		$TTsysdb = $this->load->database('ttmobilesysmsg',TRUE);
		$sql = "INSERT INTO db_msg.m_sysmessage(S_StartTime,S_EndTime,S_Type,S_Receiver,S_Content,S_Mode,S_Status,
						 S_SysType,S_Title,S_CreateTime,S_Creater,S_ShowType,S_Link,S_VoicePrompt,S_SendType)
			                  VALUES('$S_StartTime','$S_EndTime',$S_Type,'$S_Receiver','$S_Content',$S_Mode,$S_Status,
						 $S_SysType,'$S_Title','$S_CreateTime','$S_Creater',$S_ShowType,'$S_Link',$S_VoicePrompt,$S_SendType)";
		
		$TTsysdb->query($sql);
	}
	
	function showSysMsgList($S_Status)
	{
		$S_Status = mysql_escape_string($S_Status);
		$TTsysdb = $this->load->database('ttmobilesysmsg',TRUE);
		$sql = "select * from db_msg.m_sysmessage where S_Status = $S_Status order by s_id desc limit 90";
		return $TTsysdb->query($sql)->result_array();
	}
	
	function cancelSysMsg($id)
	{
		$id = mysql_escape_string($id);
		$TTsysdb = $this->load->database('ttmobilesysmsg',TRUE);
		$sql = "update db_msg.m_sysmessage set S_Status = 3 where S_ID = $id ";
		$TTsysdb->query($sql);
	}
	
	function cancelSysMsgMode($id)
	{
		$id = mysql_escape_string($id);
		$TTsysdb = $this->load->database('ttmobilesysmsg',TRUE);
		$sql = "update db_msg.m_sysmessage set S_Mode = 3 where S_ID = $id ";
		$TTsysdb->query($sql);
	}
        /*手机通通激活码申请管理————结束*/
        
        
}
?>