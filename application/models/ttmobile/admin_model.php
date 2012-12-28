<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

class Admin_model extends CI_Model
{	
	function __construct()
	{
		parent::__construct ();
	}
	
	function getAdmin($name, $pwd)
	{
		$name = mysql_escape_string($name);
		$pwd = mysql_escape_string($pwd);
                
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_admin.s_admin where name = '".$name."'and pwd = '".md5($pwd)."'";
		return $TTdb->query($sql)->row();		 
	}
	
	function getAdminName($name)
	{
		$name = mysql_escape_string($name);
		
                $TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_admin.s_admin where name = '".$name."'";
		return $TTdb->query($sql)->row();		 
	}
	
	function getList()
	{
                $TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_admin.s_admin order by id desc";
		return $TTdb->query($sql)->result();		 
	}
	
	function insertLogin($adminId, $loginIP)
	{
		$adminId = mysql_escape_string($adminId);
		$loginIP = mysql_escape_string($loginIP);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "insert into db_admin.s_adminLogin(adminId,loginIP) values($adminId,'$loginIP')";
		$TTdb->query($sql);
	}
	
	function getLoginById($adminId)
	{
		$adminId = mysql_escape_string($adminId);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select a.*,b.name from db_admin.s_adminLogin a left join db_admin.s_admin b on b.id = a.adminId
		        where a.adminId = $adminId order by a.id desc limit 10 ";
		return $TTdb->query($sql)->result();
	}
	
	function getFlag($adminid)
	{
		$adminid = mysql_escape_string($adminid);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select flag from db_admin.s_admin where id = $adminid";
		return $TTdb->query($sql)->row();
	}
	
	function getSpFlag($adminid)
	{
		$adminid = mysql_escape_string($adminid);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select spflag from db_admin.s_admin where id = $adminid";
		return $TTdb->query($sql)->row();
	}
	
	function updateFlag($flag, $adminid)
	{
		$adminid = mysql_escape_string($adminid);
		$flag = mysql_escape_string($flag);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "update db_admin.s_admin set flag = '$flag' where id = $adminid ";
		$TTdb->query ($sql);
	}
	
	function updateSpFlag($spflag, $adminid)
	{
		$adminid = mysql_escape_string($adminid);
		$spflag = mysql_escape_string($spflag);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "update db_admin.s_admin set spflag = '$spflag' where id = $adminid ";
		$TTdb->query ($sql);
	}
	
	function delAdmin($adminid)
	{
		$adminid = mysql_escape_string($adminid);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "delete from db_admin.s_admin where id = $adminid";
		$TTdb->query($sql);
	}
	
	function insertAdmin($name, $pwd, $group)
	{
		$name = mysql_escape_string($name);
		$pwd = mysql_escape_string($pwd);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select count(0) a from db_admin.s_admin where name = '$name'";
		if($TTdb->query($sql)->row()->a == 0)
		{
			$sql = "insert into db_admin.s_admin(name,pwd,groupid) values('$name','".md5($pwd)."',$group)";
			$TTdb->query($sql);
			return 0;
		}
		else
		{
			return - 1;
		}
	}
	
	function changePwd($adminid, $oldpwd, $newpwd)
	{
		$adminid = mysql_escape_string($adminid);
		$oldpwd = mysql_escape_string($oldpwd);
		$newpwd = mysql_escape_string($newpwd);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select count(0) c from db_admin.s_admin where id = $adminid and pwd = '".md5($oldpwd). "'";
		$row = $TTdb->query($sql)->row();
		if($row->c > 0)
		{
			$sql = "update db_admin.s_admin set pwd = '".md5($newpwd)."' where id = $adminid";
			$TTdb->query($sql);
			return 0;
		}
		else
		{
			return - 1;
		}
	}
	
	function addSp($name,$detail)
	{
		$name = mysql_escape_string($name);
		$detail = mysql_escape_string($detail);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "insert into db_admin.s_sp(name,detail) values('$name','$detail')";
		$TTdb->query($sql);
	}
	
	function getSpList()
	{		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_admin.s_sp";
		return $TTdb->query($sql)->result_array();
	}
	
	function getSp($sid)
	{
		$sid = mysql_escape_string($sid);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_admin.s_sp where id = $sid";
		return $TTdb->query($sql)->row();
	}
	
	function updateSp($sid,$name,$detail)
	{
		$sid = mysql_escape_string($sid);
		$name = mysql_escape_string($name);
		$detail = mysql_escape_string($detail);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "update db_admin.s_sp set name = '$name',detail = '$detail' where id = $sid";
		$TTdb->query($sql);
	}
	
	function delSp($sid)
	{
		$adminid = mysql_escape_string($sid);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "delete from db_admin.s_sp where id = $sid";
		$TTdb->query($sql);
	}
}

?>