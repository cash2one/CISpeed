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
		
		$sql = "select * from s_admin where name = '".$name."'and pwd = '".md5($pwd)."'";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	function getAdminName($name)
	{
		$name = mysql_escape_string($name);
		
		$sql = "select * from s_admin where name = '".$name."'";
		$query = $this->db->query($sql);
		
		return $query->row();
	}
	
	function getList()
	{
		$sql = "select * from s_admin order by id desc";
		$query = $this->db->query ( $sql );
		return $query->result ();
	}
	
	function insertLogin($adminId, $loginIP)
	{
		$adminId = mysql_escape_string($adminId);
		$loginIP = mysql_escape_string($loginIP);
		
		$sql = "insert into s_adminLogin(adminId,loginIP) values($adminId,'$loginIP')";
		$this->db->query ( $sql );
	}
	
	function getLoginById($adminId)
	{
		$adminId = mysql_escape_string ($adminId);
		$sql = "select a.*,b.name from s_adminLogin a left join s_admin b on b.id = a.adminId
		        where a.adminId = $adminId order by a.id desc limit 10 ";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function getFlag($adminid)
	{
		$adminid = mysql_escape_string ( $adminid );
		
		$sql = "select flag from s_admin where id = $adminid";
		$query = $this->db->query ( $sql );
		return $query->row ();
	}
	
	function updateFlag($flag, $adminid)
	{
		$adminid = mysql_escape_string ( $adminid );
		$flag = mysql_escape_string ( $flag );
		
		$sql = "update s_admin set flag = '$flag' where id = $adminid ";
		$this->db->query ( $sql );
	}
	
	function delAdmin($adminid) {
		$adminid = mysql_escape_string ( $adminid );
		
		$sql = "delete from s_admin where id = $adminid";
		$this->db->query ( $sql );
	}
	
	function insertAdmin($name, $pwd, $group)
	{
		$name = mysql_escape_string($name);
		$pwd = mysql_escape_string($pwd);
		
		$sql = "select count(0) a from s_admin where name = '$name'";
		if ($this->db->query($sql)->row()->a == 0)
		{
			$sql = "insert into s_admin(name,pwd,groupid) values('$name','".md5($pwd)."',$group)";
			$this->db->query($sql);
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
		
		$sql = "select count(0) c from s_admin where id = $adminid and pwd = '".md5($oldpwd). "'";
		$row = $this->db->query($sql)->row();
		if($row->c > 0)
		{
			$sql = "update s_admin set pwd = '".md5($newpwd)."' where id = $adminid";
			$this->db->query($sql);
			return 0;
		}
		else
		{
			return - 1;
		}
	}
}

?>