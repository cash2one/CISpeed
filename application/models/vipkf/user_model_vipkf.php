<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model_vipkf extends CI_Model {
	
	function __construct()
        {
		parent::__construct ();		
	}
	
	function getCallRecByCustom($pagesize,$pagecount,$cutomid)
	{
		$cutomid = mysql_escape_string($cutomid);
		$sql = "select a.*,b.fgatename,c.Fuid from db_call_rec.t_call_rec a left join db_call_rec.t_gate_sort b
		        on a.Fgateid = b.Fgateid left join db_call_rec.t_service_rec c on a.Fcallid = c.Fcallid  
			where a.Fcustomid = $cutomid  and a.Festbtime != 0 order by a.Festbtime desc limit $pagecount,$pagesize";
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		return $VIPKFdb->query($sql)->result_array();
	}
	
	function getCallRecByCustomCount($cutomid)
	{		
		$cutomid = mysql_escape_string($cutomid);
		$sql = "select count(0) c from db_call_rec.t_call_rec a  where a.Fcustomid = $cutomid  and a.Festbtime != 0";
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		return $VIPKFdb->query($sql)->row()->c;
	}
	
	function getCallRecByPhone($pagesize,$pagecount,$uid)
	{
		$uid = mysql_escape_string($uid);
		$sql = "select a.Fuid,b.*,c.Frealname,d.Fbigquestionid,d.Fsmallquestionid,d.Fcallqos,d.Fqosdetail from db_call_rec.t_service_rec a
		        left join db_call_rec.t_call_rec b on a.fcallid = b.fcallid
		        left join db_customer_info.t_custom_info c on b.Fcustomid = c.Fucustomid
			left join db_call_rec.t_call_question_rec d on a.fcallid = d.fcallid 
			where a.fuid = $uid and b.Festbtime != 0
			order by b.Festbtime desc limit $pagecount,$pagesize";
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		return $VIPKFdb->query($sql)->result_array();
	}
	
	function getCallRecByPhoneCount($uid)
	{
		$uid = mysql_escape_string($uid);
		$sql = "select count(0) c from db_call_rec.t_service_rec a left join db_call_rec.t_call_rec b on a.fcallid = b.fcallid
		         where a.fuid = $uid and b.Festbtime != 0";
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		return $VIPKFdb->query($sql)->row()->c;
	}
	
	function checkTokenUid($token,$uid)
	{
		$uid = mysql_escape_string($uid);
		$token = mysql_escape_string($token);
		$sql = "select count(0) c from db_customer_info.t_usr_token where fusrid = $uid and ftoken = $token";
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		return $VIPKFdb->query($sql)->row()->c;		
	}
	
	function getCustomData($date,$cid)
	{
		$date =  mysql_escape_string($date);
		$cid =  mysql_escape_string($cid);
		
		$sql = "select a.fagentid,a.fstate,sum(a.fendtime)-sum(a.fbegintime) stime,count(0) c,b.floginid,
			from_unixtime(b.flogintime) intime,from_unixtime(b.flogouttime) outtime from db_op_log.t_agentstate_log a
			left join db_op_log.t_agentlogin_log b on a.floginid=b.floginid
			where a.fagentid = $cid and b.flogintime between unix_timestamp('$date 00:00:00') and  unix_timestamp('$date 23:59:59')
			group by a.floginid,a.fstate order by a.floginid desc,fstate";
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		return $VIPKFdb->query($sql)->result_array();	
	}
	
	function insertCustomInfo($Fucustomid,$Fpassword,$Frealname,$Fname,$Frole)
	{
		$Fucustomid = mysql_escape_string($Fucustomid);
		$Fpassword = mysql_escape_string($Fpassword);
		$Frealname = mysql_escape_string($Frealname);
		$Fname = mysql_escape_string($Fname);
		$Frole = mysql_escape_string($Frole);
		
		$sql = "select count(0) c from db_customer_info.t_custom_info where Fucustomid = $Fucustomid";		
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		if($VIPKFdb->query($sql)->row()->c > 0)
		{
			return -1;
		}
		else
		{
			$sql = "insert into db_customer_info.t_custom_info(Fucustomid,Fpassword,Frealname,Fname,Frole) 
				values($Fucustomid,'$Fpassword','$Frealname','$Fname',$Frole)";
			$VIPKFdb->query($sql);
			return 0;
		}		
	}
	
	function getCustomInfoList()
	{
		$sql = "select * from db_customer_info.t_custom_info ";		
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		return $VIPKFdb->query($sql)->result_array();
	}
	
	function delCustom($id)
	{
		$id = mysql_escape_string($id);
		$sql = "delete from db_customer_info.t_custom_info where fucustomid = $id";		
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		$VIPKFdb->query($sql);
	}
	
	function insertQuestion($callid,$bigq,$smallq,$qos,$qosdetail,$content=" ")
	{
		$callid = mysql_escape_string($callid);
		$bigq = mysql_escape_string($bigq);
		$smallq = mysql_escape_string($smallq);
		$qos = mysql_escape_string($qos);
		$qosdetail = mysql_escape_string($qosdetail);
		$content = mysql_escape_string($content);
		
		$sql = "insert into db_call_rec.t_call_question_rec(Fcallid,Fbigquestionid,Fsmallquestionid,Fcallqos,Fqosdetail,Fquestiondetail)
		        values($callid,$bigq,$smallq,$qos,$qosdetail,'$content')";	
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		$VIPKFdb->query($sql);		
	}
}