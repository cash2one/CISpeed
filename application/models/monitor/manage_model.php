<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Manage_model extends CI_Model {
	        
	function __construct()
        {
		parent::__construct ();
		$this->load->library('function_class');
	}
        
	function getAdmin($name, $pwd)
	{
		$name = mysql_escape_string($name);
		$pwd = mysql_escape_string($pwd);
                
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "select * from db_recorder_monitor.t_monitor_manager  where UserName = '".$name."'and Passwd = '".md5($pwd)."'";
		return $Monitordb->query($sql)->row();		 
	}
	
        function showSoundServer()
        {
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "select * from db_recorder_monitor.t_sound_server";		
		return $Monitordb->query($sql)->result_array();
        }
	
	function addSoundServer($id,$pwd,$ip)
	{
		$id = mysql_escape_string($id);
		$pwd = mysql_escape_string($pwd);
		$ip = mysql_escape_string($ip);
		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "insert into db_recorder_monitor.t_sound_server(soundid,passwd,ipaddr) values('$id','$pwd','$ip') ";
		$Monitordb->query($sql);
	}
	
	function delSoundServer($id)
	{
		$id = mysql_escape_string($id);
		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "delete from db_recorder_monitor.t_sound_server where soundid = '$id' ";
		$Monitordb->query($sql);
	}
	
	function getManageMonitor($role=0,$manageid="0",$pagesize=0,$pagecount=0)
	{
		$manageid = mysql_escape_string($manageid);
		
		$where = "";
		if($role == 1)
		{
			$where .= " where ManagerID = '$manageid' ";
		}
		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "select * from db_recorder_monitor.t_manager_monitor_list $where limit $pagecount,$pagesize";
		return $Monitordb->query($sql)->result_array();
	}
	
	function getManageMonitorCount($role=0,$manageid="0")
	{
		$manageid = mysql_escape_string($manageid);
		
		$where = "";
		if($role == 1)
		{
			$where .= " where ManagerID = '$manageid' ";
		}
		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "select count(0) c from db_recorder_monitor.t_manager_monitor_list $where ";
		return $Monitordb->query($sql)->row()->c;
	}
	
	function addManageMonitor($manageid,$phone,$imid)
	{		
		$manageid = mysql_escape_string($manageid);
		$phone = mysql_escape_string($phone);
		$imid = mysql_escape_string($imid);
		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "insert into db_recorder_monitor.t_manager_monitor_list(managerid,phoneid,imid,createtime) 
			values('$manageid',$phone,$imid,".time().")";
		$Monitordb->query($sql);
		
		$sql = "insert into db_recorder_monitor.t_sound_server_userid_list(phoneid,imid,createtime) 
			values($phone,$imid,".time().")";
		$Monitordb->query($sql);
	}
	
	function delManageMonitor($id)
	{
		$id = mysql_escape_string($id);
		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "delete from db_recorder_monitor.t_manager_monitor_list where id = $id";
		$Monitordb->query($sql);
	}
	
	function getRecordFile($phoneid,$state,$pagesize=0,$pagecount=0)
	{
		$phoneid = mysql_escape_string($phoneid);
		
		$wherephone = "";
		
		//if($phoneid == "0")
		if($phoneid>0) //modified by gary 20120208
		{
			$wherephone = " and a.userid = $phoneid ";
		}
		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "select a.id,a.callid,a.userid,b.fileurl,b.starttime,b.endtime from db_recorder_monitor.t_callid_member a 
			left join db_recorder_monitor.t_recoder_file_rec b on a.callid = b.callid
		        where b.state = $state $wherephone limit $pagecount,$pagesize";
		return $Monitordb->query($sql)->result_array();
	}
	
	function getRecordFileCount($phoneid,$state)
	{
		$phoneid = mysql_escape_string($phoneid);
		
		$wherephone = "";
		
		//if($phoneid == "0")
		if($phoneid>0) //modified by gary 20120208
		{
			$wherephone = " and a.userid = $phoneid ";
		}
		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "select count(0) c from db_recorder_monitor.t_callid_member a 
			left join db_recorder_monitor.t_recoder_file_rec b on a.callid = b.callid
		        where b.state = $state $wherephone";
		return $Monitordb->query($sql)->row()->c;
	}
	
	function addUser($username,$pwd,$role)
	{
		$username = mysql_escape_string($username);
		$pwd = md5(mysql_escape_string($pwd));
		$role = mysql_escape_string($role);		
		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "insert into db_recorder_monitor.t_monitor_manager(username,passwd,role,fromip)
			values('$username','$pwd',$role,'127.0.0.1')";
		$Monitordb->query($sql);
	}
	
	function getUserList()
	{			
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "select * from db_recorder_monitor.t_monitor_manager ";
		return $Monitordb->query($sql)->result_array();
	}
	
	function delUser($id)
	{
		$id = mysql_escape_string($id);		
		$Monitordb = $this->load->database('monitor',TRUE);
		$sql = "delete from db_recorder_monitor.t_monitor_manager  where username = '$id'";
		$Monitordb->query($sql);
	}
	
	
	
}
?>