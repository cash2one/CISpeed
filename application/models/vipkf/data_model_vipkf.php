<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Data_model_vipkf extends CI_Model {
	
	function __construct()
        {
		parent::__construct ();		
	}
        
        function getCommondata($date)
        {
		$date = mysql_escape_string($date);
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		$sql = "select count(0) c from db_call_rec.t_call_rec where Festbtime != 0 and Festbtime between
			unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59')";
		$succall = $VIPKFdb->query($sql)->row()->c;
		
		$sql = "select count(0) c,count(distinct fuid) dc from db_op_log.t_usr_queue_log
			where Fenquetime between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59')";
		$rst = $VIPKFdb->query($sql)->row();
		$allcall = $rst->c;
		$allmobiles = $rst->dc;
		
		$sql = "delete from db_call_rec.t_statistic_commondata where date_id = '$date'";
		$VIPKFdb->query($sql);
		
		$sql = "insert into db_call_rec.t_statistic_commondata(date_id,allcall,succall,allmobiles) values('$date',$allcall,$succall,$allmobiles)";
		$VIPKFdb->query($sql);
        }
        
        function showCommondata()
        {
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		$sql = "select * from db_call_rec.t_statistic_commondata order by date_id desc";
		return $VIPKFdb->query($sql)->result_array();
        }
	
	function showIncomingCallData($date)
	{
		$VIPKFdb = $this->load->database('vipkf',TRUE);
		$sql = "select from_unixtime(fstattime) t,Fqueuelen c,Facceptcallnum,Fonlineagentnum,Fcallingagentnum from db_vip_stat.t_incoming_call_stat
		        where fstattime between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59')";
		return $VIPKFdb->query($sql)->result_array();
	}
}