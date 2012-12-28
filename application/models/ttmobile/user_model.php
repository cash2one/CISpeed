<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
	
	function __construct()
        {
		parent::__construct ();		
	}
	
	/*用户意见反馈————开始*/
	function addSuggest($userid,$contact,$content)
        {
		$userid = mysql_escape_string($userid);
		$contact = mysql_escape_string($contact);
		$content = mysql_escape_string($content);
		
		$sql = "insert into statistic.ttm_userSuggest(userid,contact,content,createtime) values($userid,'$contact','$content',".time().")";
		$this->db->query($sql);
	}
	
	function getSuggest($pagesize = 0, $pagecount = 0)
        {
		$sql = "select * from statistic.ttm_userSuggest order by id desc limit $pagecount,$pagesize";
		return $this->db->query($sql)->result_array();
	}
	
	function getSuggestCount()
        {
		$sql = "select count(0) c from statistic.ttm_userSuggest ";
		return $this->db->query($sql)->row()->c;
	}
	
	function getUserSuggest($id)
	{
		$id = mysql_escape_string($id);
		$sql = "select * from statistic.ttm_userSuggest where id = $id";
		return $this->db->query($sql)->result_array();
	}
	
	function updateUserSuggestRst($id,$rst)
	{
		$id = mysql_escape_string($id);
		$rst = mysql_escape_string($rst);
		
		$sql = "update statistic.ttm_userSuggest set result = '$rst' where id = $id";
		$this->db->query($sql);		
	}
	
	function delUserSuggest($id)
	{
		$id = mysql_escape_string($id);
		
		$sql = "delete from statistic.ttm_userSuggest where id = $id";
		$this->db->query($sql);		
	}	
	/*用户意见反馈————结束*/
	
	/*统计某天手机型号使用量————开始*/
	function getMobileTypeData($date)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$d = date("Y-m-d",$date);
		$d2 = date("Ymd",$date);
		
		$sql = "insert into db_background_service.t_statistic_mobiletype
                        select '$d' d,fmobileid,count(distinct fuserid) c from ".$this->function_class->fixTTLoginTable($d2,"fmobileid,fuserid")." group by fmobileid;";
		$TTdb->query($sql);
	}
	
        //按天获取手机厂商使用量
	function getMobileTypeDataByName($date)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select fdevfirm,sum(c) s from db_background_service.t_statistic_mobiletype a left join
                        db_background_service.t_mobile_version_list b on a.mobile_id = b.Fmobileid
                        where a.date_id = '$date' group by fdevfirm order by s desc";       
		return $TTdb->query($sql)->result_array();
	}
	
	//按天获取手机型号使用量
	function getMobileTypeDataByModel($date)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select Fdevfirm,Fdevmodel,sum(c) c from db_background_service.t_statistic_mobiletype a left join
                        db_background_service.t_mobile_version_list b on a.mobile_id = b.Fmobileid
                        where a.date_id = '$date' group by fdevfirm,Fdevmodel order by c desc limit 200";       
		return $TTdb->query($sql)->result_array();
	}
        
        //按天获取手机型号,系统使用量
        function getMobileTypeDataByDetail($date,$pagesize=0,$pagecount=0)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select a.c,b.* from db_background_service.t_statistic_mobiletype a
                        left join db_background_service.t_mobile_version_list b on a.mobile_id = b.Fmobileid
                        where a.date_id = '$date' order by c desc limit $pagecount,$pagesize";
                return $TTdb->query($sql)->result_array();
        }
        
        function getMobileTypeDataByDetailCount($date)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select count(0) c from db_background_service.t_statistic_mobiletype a
                        left join db_background_service.t_mobile_version_list b on a.mobile_id = b.Fmobileid
                        where a.date_id = '$date'";
                return $TTdb->query($sql)->row()->c;
        }
        
        //获取手机型号使用量总计
	function getMobileTypeDataByNameByAllDate()
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select fdevfirm,sum(c) s from db_background_service.t_statistic_mobiletype a left join
                        db_background_service.t_mobile_version_list b on a.mobile_id = b.Fmobileid
                        group by fdevfirm order by s desc";       
		return $TTdb->query($sql)->result_array();
	}
        
        //获取手机型号,系统使用量总计
        function getMobileTypeDataByAllDate()
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select sum(a.c) c,b.* from db_background_service.t_statistic_mobiletype a
                        left join db_background_service.t_mobile_version_list b on a.mobile_id = b.Fmobileid
                        group by a.mobile_id order by c desc limit $pagecount,$pagesize";
                return $TTdb->query($sql)->row()->c;
        }
        
        function getMobileTypeDataByAllDateCount()
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select count(0) c from (select sum(a.c) c,b.* from db_background_service.t_statistic_mobiletype a
                        left join db_background_service.t_mobile_version_list b on a.mobile_id = b.Fmobileid
                        group by a.mobile_id) as T";
                return $TTdb->query($sql)->row()->c;
        }
        
	/*统计某天手机型号使用量————结束*/
        
        /*网络类型使用统计————开始*/
        function getNetTypeData($date)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                $d = date("Y-m-d",($date));
                $d2 = date("Ymd",($date));
                $sql = "insert into db_background_service.t_statistic_nettype
                        select '$d' d,fnettype,count(distinct fmobile) c from ".$this->function_class->fixTTLoginTable($d2,"fnettype,fmobile")." group by fnettype";
                $TTdb->query($sql);
        }
        
        function showNetTypeData($date)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select * from db_background_service.t_statistic_nettype where date_id = '$date' order by c desc ";
                return $TTdb->query($sql)->result_array();
        }        
	/*网络类型使用统计————结束*/
                
	/*地区使用统计————开始*/
        function getRegionData($date)
        {
                $d = date("Y-m-d",($date));
                $d2 = date("Ymd",($date));
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "insert into db_background_service.t_statistic_region 
                        select '$d' d,b.region,count(distinct a.fmobile) c from 
                        (".$this->function_class->fixTTLoginTable($d2,"fmobile").") a left join db_background_service.17et_mobileregion b
                        on mid(a.fmobile,1,7) = b.code group by b.region";
                $TTdb->query($sql);
        }
        
        function showRegionDataByProvince($date)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select mid(region,1,2) a,sum(c) s from db_background_service.t_statistic_region
                        where date_id = '$date' group by mid(region,1,2) order by s desc ";
                return $TTdb->query($sql)->result_array();
        }
        
        function showRegionData($date)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select * from db_background_service.t_statistic_region where date_id = '$date' order by c desc ";
                return $TTdb->query($sql)->result_array();
        }     
	/*地区使用统计————结束*/
	
	/*默认短信设置统计————开始*/
	function getFsmsData($date)
	{
		$d = date("Y-m-d",($date));
                $d2 = date("Ymd",($date));
				
                $TTdb = $this->load->database('ttmobilelogin',TRUE);
		$sql="select '$d' d, fsms,count(distinct fuserid) c from ".$this->function_class->fixTTLoginTable($d2,"fsms,fuserid")." group by fsms";
		$rst = $TTdb->query($sql)->result_array();
		
                $TTdb = $this->load->database('ttmobile',TRUE);
		for($i=0,$c=count($rst);$i<$c;$i++)
		{			
			$sql = "insert into db_background_service.t_statistic_fsms(date_id,fsms,c) values('".$rst[$i]["d"]."','".$rst[$i]["fsms"]."',".$rst[$i]["c"].");";
			$TTdb->query($sql);
		}
		//select '$d' d, fsms,count(distinct fuserid) c from ".$this->function_class->fixTTLoginTable($d2,"fsms,fuserid")." group by fsms";
		
	}
	
	function showFsmsData($date)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from  db_background_service.t_statistic_fsms where date_id = '$date' order by c desc;";
		return $TTdb->query($sql)->result_array();
	}
	
	/*默认短信设置统计————结束*/
	
	/*默认拨号设置统计————开始*/
	function getFcallData($date)
	{
		$d = date("Y-m-d",($date));
                $d2 = date("Ymd",($date));
		
		$TTdb = $this->load->database('ttmobilelogin',TRUE);
		$sql="select '$d' d, fcall2,count(distinct fuserid) c from ".$this->function_class->fixTTLoginTable($d2,"fcall2,fuserid")." group by fcall2";
		$rst = $TTdb->query($sql)->result_array();
		
                $TTdb = $this->load->database('ttmobile',TRUE);
		for($i=0,$c=count($rst);$i<$c;$i++)
		{			
			$sql = "insert into db_background_service.t_statistic_fcall(date_id,fcall,c) values('".$rst[$i]["d"]."','".$rst[$i]["fcall2"]."',".$rst[$i]["c"].");";
			$TTdb->query($sql);
		}
		/*$sql = "insert into db_background_service.t_statistic_fcall
			select '$d' d,fcall2,count(distinct fuserid) c from ".$this->function_class->fixTTLoginTable($d2,"fcall2,fuserid")." group by fcall2";
		$TTdb->query($sql);*/
	}
	
	function showFcallData($date)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from  db_background_service.t_statistic_fcall where date_id = '$date' order by c desc;";
		return $TTdb->query($sql)->result_array();
	}	
	/*默认拨号设置统计————结束*/
	
	function getCallUserCount($date)
        {
		$TTdb = $this->load->database ('ttmobile',TRUE);
		
		$d1 = date("Ymd",strtotime($date)) ;
		$d2 = date("Y-m-d",strtotime($date));
		
		$sql = "select count(fcallid),c from (SELECT count(0) c,fcallid FROM db_call_log_$d1.t_call_log group by fcallid) as T group by c";
		$TTdb->query($sql);
	}
	
	/*按日期查询通话记录————开始*/
	private function whereStartTime($starttime,$db="",$d=20111013)
        {                
		$str = "";
                if($d < 20111013)
                {
                    switch($starttime)
                    {
                            case "0":
                                    $str = " and ".$db."Fstarttime = 0 ";
                                    break;
                            case "1":
                                    $str = " and ".$db."Fstarttime != 0 ";
                                    break;
                            default:
                                    break;
                    }
                }
		else
                {
                    switch($starttime)
                    {
                            case "0":
                                    $str = " and ".$db."fcallstate = 255 ";
                                    break;
                            case "1":
                                    $str = " and ".$db."fcallstate != 255 and Fstarttime!=0 ";/*and Fstarttime!=0 */
                                    break;
                            default:
                                    break;
                    }
                }
		return $str;
	}
	
	private function whereEndType($endtype,$db="")
        {
		$str = "";
		if($endtype != -1)
                {
			$str = " and ".$db."Fendtype = $endtype ";
		}
		
		return $str;
	}
	
	private function whereSpid($spid)
        {
		$str = " >= 600 ";
		if($spid != 600)
		{
			$str = " = $spid ";
		}
		
		return $str;
	}
	
	private function internalMobiles($includeTT=true)
        {
		$internalMobiles = "13641718531,13761198707,18621822652,18616633965,13810302456,13524335497,13585767432,18616207821,18604713003,13917171826,13611607504,13641717983,13701249860";
                if(!$includeTT)
                {
                    $internalMobiles .= ",18616211389";
                }
                
                return $internalMobiles;
        }
	
	
	/*话费统计操作————开始*/
	function createSuccessCall($date)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$d = date("Ymd",strtotime($date));
		$internalMobiles = $this->internalMobiles(false);
		
		$sql = "use db_call_log_$d";
		$TTdb->query($sql);
		
		$sql = "create table `t_call_success_list`
			select b.region sr,c.region rr,a.Fsenduid,a.Frcvuid,a.Fendtype,a.Fstarttime,a.Fendtime,(a.Fendtime-a.Fstarttime) t1,ceil((a.Fendtime-a.Fstarttime)/60) t
			from db_call_log_$d.t_call_log a
			left join db_background_service.17et_mobileregion b on mid(a.Fsenduid,1,7) = b.code                
			left join db_background_service.17et_mobileregion c on mid(a.Frcvuid,1,7) = c.code 	
			where a.Fstarttime !=0 and a.Fendtime > a.Fstarttime and a.fsenduid not in($internalMobiles) ";		
		$TTdb->query($sql);
		
		//$sql = "alter table `t_call_success_list` add primary key(Fsenduid,Fstarttime) ";
		//$TTdb->query($sql);		
	}
	
	function getMonthCallFee($month)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$month = mysql_escape_string($month);
		$d = mysql_escape_string($month."-01");
		$dt = date("Ym",strtotime($d));
		
		$sql = "use db_background_service";
		$TTdb->query($sql);
		
		$sql = "CREATE TABLE `t_statistic_callfee_$dt` (
				     `month` varchar(32) NOT NULL,
				     `mobile` bigint(20) unsigned NOT NULL,
				     `type` tinyint(3) unsigned NOT NULL,
				     `minutes` int(10) unsigned DEFAULT '0',
				     PRIMARY KEY (`month`,`mobile`,`type`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$TTdb->query($sql);		
		
		$tablelist = "";
		for($i=0;$i<31;$i++)
		{
			$t = strtotime($d)+$i*24*60*60;
			if(date("Y-m",$t) != $month)
			{
				break;
			}
			$tablelist .= " select * from db_call_log_".date("Ymd",$t).".t_call_success_list ";
			if(date("Y-m",$t+24*60*60) == $month)
			{
				$tablelist .=  " union all ";
			}
		}
		
		$sql = "insert into db_background_service.t_statistic_callfee_$dt
			select '$month' month,Fsenduid,'1' ctype,sum(t) minutes from ($tablelist) as T where sr=rr group by Fsenduid";
		$TTdb->query($sql);
		
		$sql = "insert into db_background_service.t_statistic_callfee_$dt
			select '$month' month,Fsenduid,'2' ctype,sum(t) minutes from ($tablelist) as T where sr != rr group by Fsenduid";
		$TTdb->query($sql);
		
		$sql = "insert into db_background_service.t_statistic_callfee_$dt
			select '$month' month,Fsenduid,'3' ctype,sum(t) minutes from ($tablelist) as T where (Fsenduid > 20000000000 or Frcvuid>20000000000) group by Fsenduid";
		$TTdb->query($sql);
	}
	
	function getMonthCallFeeDetail($month)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$sql = "use db_background_service";
		$TTdb->query($sql);
		
		$sql = "CREATE TABLE `t_statistic_callfee_detail_$month` (
				`mobile` bigint(20) unsigned NOT NULL,
				`type1` int(10)  unsigned NOT NULL,
				`type2` int(10)  unsigned NOT NULL,
				`type3` int(10)  unsigned NOT NULL,
				`fee1` float unsigned NOT NULL DEFAULT '0',
				`fee2` float unsigned NOT NULL DEFAULT '0',
				`fee3` float unsigned NOT NULL DEFAULT '0',
				`feetotal` float NOT NULL DEFAULT '0',
				PRIMARY KEY (`mobile`)
			      ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$TTdb->query($sql);
		
		$sql = "insert into db_background_service.t_statistic_callfee_detail_$month 
			select a.mobile,ifnull(b.t1,0) t1,ifnull(c.t2,0) t2,ifnull(d.t3,0) t3,ifnull(t1*0.1,0) f1,ifnull(t2*0.2,0) f2,ifnull(t3*2,0) f3,(ifnull(t1*0.1,0)+ifnull(t2*0.2,0)+ifnull(t3*2,0)) ft from
			(select mobile from db_background_service.t_statistic_callfee_$month  group by mobile) a 
			left join (select mobile,sum(minutes) t1 from db_background_service.t_statistic_callfee_$month  where type = 1 group by mobile) b on a.mobile = b.mobile
			left join (select mobile,sum(minutes) t2 from db_background_service.t_statistic_callfee_$month  where type = 2 group by mobile) c on a.mobile = c.mobile
			left join (select mobile,sum(minutes) t3 from db_background_service.t_statistic_callfee_$month  where type = 3 group by mobile) d on a.mobile = d.mobile;";
		$TTdb->query($sql);
	}
	
	function showMonthCallFeeDetail($month)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_callfee_detail_$month order by feetotal desc limit 500";
		return $TTdb->query($sql)->result_array();
	}
	
	function getUserMonthCallFee($mobile,$month)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$mobile = mysql_escape_string($mobile);
		$month = mysql_escape_string($month);
		$d = mysql_escape_string($month."-01");
		$dt = date("Ym",strtotime($d));
		
		$tablelist = "";
		for($i=0;$i<31;$i++)
		{
			$t = strtotime($d)+$i*24*60*60;
			if(date("Y-m",$t) != $month)
			{
				break;
			}
			$tablelist .= " select * from db_call_log_".date("Ymd",$t).".t_call_success_list where Fsenduid = $mobile";
			if(date("Y-m",$t+24*60*60) == $month)
			{
				$tablelist .=  " union all ";
			}
		}
		
		return $TTdb->query($tablelist)->result_array();
	}
	
	/*话费统计操作————结束*/
	
	/*按日期查询通话————开始*/
	function getCallLogByDate($date,$pagesize=0,$pagecount=0,$starttime=-1,$endtype=-1,$includeTT=true)
        {            
		$d = date("Ymd",strtotime($date));
		$startStr = $this->whereStartTime($starttime,"a.",$d);
		$endStr = $this->whereEndType($endtype,"a.");
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		
                $internalMobiles = $this->internalMobiles($includeTT);                
                
		$sql = "select b.region sr,c.region rr,a.Fsenduid,a.Frcvuid,a.Fendtype,a.Fstarttime,a.Fendtime from db_call_log_$d.t_call_log a
                        left join db_background_service.17et_mobileregion b on mid(a.Fsenduid,1,7) = b.code                
                        left join db_background_service.17et_mobileregion c on mid(a.Frcvuid,1,7) = c.code                        
                        where 1=1 and a.fsenduid not in($internalMobiles) and a.Frcvuid not in($internalMobiles) $startStr $endStr
                        order by Festablishtime desc,Fsenduid limit $pagecount,$pagesize";
			return $TTdb->query($sql)->result_array();
	}
	
	function getCallLogByDateCount($date,$starttime=-1,$endtype=-1,$includeTT=true,$mincalltime=-1,$maxcalltime=-1,$distinct="0")
        {            
		$d = date("Ymd",strtotime($date));
		$startStr = $this->whereStartTime($starttime,"",$d);
		$endStr = $this->whereEndType($endtype);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
                
                $internalMobiles = $this->internalMobiles($includeTT);
                
                $whereCallTime = "";
                
                if($mincalltime>0)
                {
                    $whereCallTime .= " and (Fendtime - Fstarttime) >= $mincalltime ";
                }                
                if($maxcalltime>0)
                {
                    $whereCallTime .= " and (Fendtime - Fstarttime) <= $maxcalltime ";
                }
                if($distinct != "0")
                {
                    $distinct = " distinct $distinct ";
                }
		$sql = "select count($distinct) c from db_call_log_$d.t_call_log where 1=1 and
                        fsenduid not in($internalMobiles) and Frcvuid not in($internalMobiles)   $startStr $endStr $whereCallTime";
                        /*and Frcvuid not in($internalMobiles)*/
		
                return $TTdb->query($sql)->row()->c;
	}
	
	function getCallLogByDateCountTest($date,$starttime=-1,$endtype=-1,$includeTT=true,$mincalltime=-1,$maxcalltime=-1,$distinct="0")
        {            
		$d = date("Ymd",strtotime($date));
		$startStr = $this->whereStartTime($starttime,"",$d);
		$endStr = $this->whereEndType($endtype);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
                
                $internalMobiles = $this->internalMobiles($includeTT);
                
                $whereCallTime = "";
                
                if($mincalltime>0)
                {
                    $whereCallTime .= " and (Fendtime - Fstarttime) >= $mincalltime ";
                }                
                if($maxcalltime>0)
                {
                    $whereCallTime .= " and (Fendtime - Fstarttime) <= $maxcalltime ";
                }
                if($distinct != "0")
                {
                    $distinct = " distinct $distinct ";
                }
		$sql = "select * from db_call_log_$d.t_call_log where 1=1 and
                        fsenduid not in($internalMobiles) and Frcvuid not in($internalMobiles)   $startStr $endStr $whereCallTime";
                        /*and Frcvuid not in($internalMobiles)*/
		echo $sql;
                //return $TTdb->query($sql)->row()->c;
	}
        
        function getCallLogByDateCount1($date,$starttime=-1,$endtype=-1,$includeTT=true,$mincalltime=-1,$maxcalltime=-1,$distinct="0")
        {            
		$d = date("Ymd",strtotime($date));
                $d2 = date("Y-m-d",strtotime($date));
		$startStr = $this->whereStartTime($starttime,"",$d);
		$endStr = $this->whereEndType($endtype);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
                
                $internalMobiles = $this->internalMobiles($includeTT);
                
                $whereCallTime = "";
                
                if($mincalltime>0)
                {
                    $whereCallTime .= " and (Fendtime - Fstarttime) >= $mincalltime ";
                }                
                if($maxcalltime>0)
                {
                    $whereCallTime .= " and (Fendtime - Fstarttime) <= $maxcalltime ";
                }
                if($distinct != "0")
                {
                    $distinct = " distinct $distinct ";
                }
		$sql = "select count($distinct) c from db_call_log_$d.t_call_log where 1=1 and
                        fsenduid not in($internalMobiles) and Frcvuid not in($internalMobiles)   $startStr $endStr $whereCallTime
                        and Festablishtime >= unix_timestamp('$d2 00:00:00') and Festablishtime <= unix_timestamp('$d2 ".date("H:i:s",time())."')";
                        /*and Frcvuid not in($internalMobiles)*/		
                return $TTdb->query($sql)->row()->c;
	}
        
        function getCallLogByDateCountBySp($date,$starttime=-1,$endtype=-1,$includeTT=true,$mincalltime=-1,$maxcalltime=-1,$distinct="0",$spid = 600)
        {            
		$d = date("Ymd",strtotime($date));
                $d2 = date("Y-m-d",strtotime($date));
		$startStr = $this->whereStartTime($starttime,"",$d);
		$endStr = $this->whereEndType($endtype);
		$spStr = $this->whereSpid($spid);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
                
                $internalMobiles = $this->internalMobiles($includeTT);
                
                $whereCallTime = "";
                
                if($mincalltime>0)
                {
                    $whereCallTime .= " and (Fendtime - Fstarttime) >= $mincalltime ";
                }                
                if($maxcalltime>0)
                {
                    $whereCallTime .= " and (Fendtime - Fstarttime) <= $maxcalltime ";
                }
                if($distinct != "0")
                {
                    $distinct = " distinct $distinct ";
                }
		
		
		$sql = "select count($distinct) c from
                        (select a.* from db_call_log_$d.t_call_log a inner join 
                        db_background_service.t_mobile_source_new b on a.fsenduid = b.mobile or a.Frcvuid = b.mobile
                        where b.sid $spStr) as T  where 1=1 and Frcvuid not in($internalMobiles) $startStr $endStr $whereCallTime";
                        /*and Frcvuid not in($internalMobiles)*/		
                return $TTdb->query($sql)->row()->c;
	}
        
        function getCallUserInNew($date)
        {
                $d = date("Ymd",strtotime($date));
                $d1 = strtotime($date);
		$d2 = $d1+24*60*60;
		
		$TTdb = $this->load->database('ttmobile',TRUE);                
                $internalMobiles = $this->internalMobiles(false);               
              
		$sql = "select count(0) c from (select a.fsenduid from (select distinct fsenduid from db_call_log_$d.t_call_log
                         where fsenduid not in($internalMobiles) and Frcvuid not in($internalMobiles)) as a
                         inner join (select Fuserid from db_background_service.t_new_user_login where Flogintime >= $d1  and Flogintime < $d2) as l
                         on a.fsenduid = l.Fuserid) as T";
		//echo $sql;
                return $TTdb->query($sql)->row()->c;
        }
        
        function getCallLogSucessException($date)
        {
                $d = date("Ymd",strtotime($date));
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		
                $internalMobiles = $this->internalMobiles(true);                
                
		$sql = "select a.* from (select Fsenduid,Frcvuid,Festablishtime,Fstarttime,Fendtime,Fendtype,Fcallstate,(Fstarttime-Festablishtime) t
                        from db_call_log_$d.t_call_log where fcallstate!=255 and fendtype != 0 and Fendtime-Fstarttime=0 and fsenduid not in($internalMobiles)
                        and Frcvuid not in($internalMobiles) union 
                        select Fsenduid,Frcvuid,Festablishtime,Fstarttime,Fendtime,Fendtype,Fcallstate,(Fendtime-Festablishtime) t
                        from db_call_log_$d.t_call_log  where fcallstate!=255 and fendtype != 0 and Fstarttime=0 and fsenduid not in($internalMobiles)
                        and Frcvuid not in($internalMobiles)
                        order by Festablishtime desc,Fsenduid) a
                        left join (select fuserid,fmobileid from ".$this->function_class->fixTTLoginTable($d,"fuserid,fmobileid")." group by fuserid) b on a.fsenduid = b.Fuserid
                        left join (select fuserid,fmobileid from ".$this->function_class->fixTTLoginTable($d,"fuserid,fmobileid")." group by fuserid) c on a.fsenduid = c.Fuserid ";
		//echo $sql;
		return $TTdb->query($sql)->result_array();
        }
        
	/*按日期查询通话记录————结束*/
	
	/*按手机号码查询通话记录————开始*/
	function getCallLogByDateMobile($date,$mobile,$starttime=-1,$endtype=-1,$fuid ="fsenduid",$pagesize=0,$pagecount=0)
        {            
		$d = date("Ymd",strtotime($date));
                
		$startStr = $this->whereStartTime($starttime,"a.",$d);
		$endStr = $this->whereEndType($endtype,"a.");
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$sql = "select b.region sr,c.region rr,a.Fsenduid,a.Frcvuid,a.Fendtype,a.Fstarttime,a.Fendtime from db_call_log_$d.t_call_log a
                        left join db_background_service.17et_mobileregion b on mid(a.Fsenduid,1,7) = b.code                
                        left join db_background_service.17et_mobileregion c on mid(a.Frcvuid,1,7) = c.code                        
                        where 1=1 and a.$fuid = $mobile $startStr $endStr order by Festablishtime desc,Fsenduid limit $pagecount,$pagesize";
           
		return $TTdb->query($sql)->result_array();
	}
        
        function getCallLogByDateMobileCount($date,$mobile,$starttime=-1,$endtype=-1,$fuid ="fsenduid")
        {
		$d = date("Ymd",strtotime($date));
		$startStr = $this->whereStartTime($starttime,"a.",$d);
		$endStr = $this->whereEndType($endtype,"a.");
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$sql = "select count(0) c from db_call_log_$d.t_call_log a                        
                        where 1=1 and a.$fuid = $mobile $startStr $endStr";
                //echo $sql;
		return $TTdb->query($sql)->row()->c;
	}	
	/*按手机号码查询通话记录————结束*/
	
	//按手机号码统计每天通话次数
	function getCallLogGroupByMobileByDate($date,$starttime=-1,$endtype=-1)
        {
		$d = date("Ymd",strtotime($date));
                
		$startStr = $this->whereStartTime($starttime,"",$d);
		$endStr = $this->whereEndType($endtype);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
                $internalMobiles = $this->internalMobiles(true);
		
		$sql = "select fsenduid,count(0) c from  db_call_log_$d.t_call_log
                        where fsenduid not in($internalMobiles) and Frcvuid not in($internalMobiles) $startStr $endStr
                        group by Fsenduid order by c desc";
		return $TTdb->query($sql)->result_array();
	}
        
        //获取多人通话数量
        function getMorePeopleCallCount($date)
        {
		$d = date("Ymd",strtotime($date));
                $startStr = $this->whereStartTime(1,"",$d);
                $TTdb = $this->load->database ( 'ttmobile', TRUE );
                $internalMobiles = $this->internalMobiles (false);      
		$sql = "select c p,count(c) c from (select count(fcallid) c  from db_call_log_$d.t_call_log
                        where fsenduid not in($internalMobiles) and Frcvuid not in($internalMobiles) $startStr  group by fcallid) as T group by c order by p";
		//echo $sql;
                return $TTdb->query ( $sql )->result_array ();
        }
        
        function getMorePeopleCallCountBySp($date,$spid=600)
        {
		$d = date("Ymd",strtotime($date));
                $startStr = $this->whereStartTime(1,"",$d);
		$spStr = $this->whereSpid($spid);
                $TTdb = $this->load->database ( 'ttmobile', TRUE );
                $internalMobiles = $this->internalMobiles (false);      
		$sql = "select c p,count(c) c from (select count(fcallid) c  from (select a.* from db_call_log_$d.t_call_log a inner join 
                        db_background_service.t_mobile_source_new b on a.fsenduid = b.mobile
                        where b.sid $spStr) as T
                        where Frcvuid not in($internalMobiles) $startStr  group by fcallid) as T2 group by c order by p";
		//echo $sql;
                return $TTdb->query ( $sql )->result_array ();
        }
        
        //获取多人通话记录列表
        function getMorePeopleCall($date,$count)
        {
            $TTdb = $this->load->database ( 'ttmobile', TRUE );
            $d = date("Ymd",$date);
            $internalMobiles = $this->internalMobiles (false);   
            $sql = "select fcallid,Fsenduid,Frcvuid,Fendtype,FROM_UNIXTIME(Fstarttime,'%Y-%m-%d %H:%i:%s') Fstarttime,
                    FROM_UNIXTIME(Fendtime,'%Y-%m-%d %H:%i:%s') Fendtime from db_call_log_$d.t_call_log where fcallid in(
                           select fcallid from db_call_log_$d.t_call_log where fsenduid not in($internalMobiles) and
                           Frcvuid not in($internalMobiles) and Fstarttime != 0 group by fcallid having count(fcallid)=$count);";
            return $TTdb->query ( $sql )->result_array ();
        }
        
        /*通话综合统计————开始*/
	function getCallCommonData($date)
        {
		$TTdb = $this->load->database ( 'ttmobile', TRUE );
		
		$d1 = date ("Ymd",$date);
		$d2 = date ("Y-m-d",$date);
                $usercount = $this->getCallLogByDateCount($d2,-1,-1,false,-1,-1,"Fsenduid");
                $newusercount = $this->getCallUserInNew($d2);
		
		$sql = "insert into db_background_service.t_statistic_callcommondata
                        SELECT '$d2' d, count(0) c,avg(if((fendtime<fstarttime),fstarttime,fendtime)-if((fstarttime=0),fendtime,fstarttime)) avgtime,'0' faile,'0' ac,
                        '0' usercount,'0' newusercount FROM db_call_log_$d1.t_call_log where  Fcallstate != 255;";
		$TTdb->query ( $sql );
		$sql = "update db_background_service.t_statistic_callcommondata set
                        faile = (SELECT count(0) FROM db_call_log_$d1.t_call_log where Fcallstate = 255),
                        ac = (SELECT count(0) FROM db_call_log_$d1.t_call_log where Fcallstate != 255),
                        usercount = $usercount,
                        newusercount = $newusercount
                        where date_id = '$d2';";
		$TTdb->query($sql);
	}
	
	function showCallCommonData()
        {
		$TTdb = $this->load->database ( 'ttmobile', TRUE );
		$sql = "select * from db_background_service.t_statistic_callcommondata order by date_id desc";
		return $TTdb->query ( $sql )->result_array ();
	}
	/*通话综合统计————结束*/
        
        /*通话数据统计————开始*/
        function getCallData($date)
        {
                $TTdb = $this->load->database ( 'ttmobile', TRUE );
		
		$d1 = date ( "Ymd", $date );
		$d2 = date ( "Y-m-d", $date );
		
		$sql = "delete from db_background_service.t_statistic_calldata where date_id = '$d2' ";
		$TTdb->query ( $sql );
		
		$sql = "insert into db_background_service.t_statistic_calldata(date_id) values('$d2')";
		$TTdb->query ( $sql );
                $total = $this->getCallLogByDateCount($d2,-1,-1,true);
                $totald = $this->getCallLogByDateCount($d2,-1,-1,true,-1,-1,"Fsenduid");
		$totalnokf = $this->getCallLogByDateCount($d2,-1,-1,false);
                $totalnokfd = $this->getCallLogByDateCount($d2,-1,-1,false,-1,-1,"Fsenduid");
		$successcall = $this->getCallLogByDateCount($d2,1,-1,false);
		$below20 = $this->getCallLogByDateCount($d2,1,-1,false,3,20);		
		$above20 = $this->getCallLogByDateCount($d2,1,-1,false,21);		
		$above180 = $this->getCallLogByDateCount($d2,1,-1,false,181);		
		$above300 = $this->getCallLogByDateCount($d2,1,-1,false,301);	
		$morePeople = $this->getMorePeopleCallCount($d2);                
                
		$sql = "update db_background_service.t_statistic_calldata set total = $total,
                                                                              totald = $totald,
                                                                              totalnokf = $totalnokf,
                                                                              totalnokfd = $totalnokfd,
                                                                              successcall = $successcall,
                                                                              below20 = $below20,
                                                                              above20 = $above20,
									      above180 = $above180,
                                                                              above300 = $above300 where date_id = '$d2';";
		$TTdb->query ( $sql );
                
                foreach($morePeople as $m)
                {
                    if($m["p"]>=1 && $m["p"] <=4)
                    {
                        $sql = "update db_background_service.t_statistic_calldata set ".($m["p"]+1)."people = ".$m["c"]."  where date_id = '$d2'";
                        $TTdb->query ( $sql );
                    }
                }
        }
        
        function getCallData1($date)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
		
		$d1 = date("Ymd",$date);
		$d2 = date("Y-m-d",$date);
		
		$sql = "insert into db_background_service.t_statistic_calldata_temp(date_id) values('$d2')";
		$TTdb->query ( $sql );
                $total = $this->getCallLogByDateCount1($d2,-1,-1,true);
                $totald = $this->getCallLogByDateCount1($d2,-1,-1,true,-1,-1,"Fsenduid");
		$totalnokf = $this->getCallLogByDateCount1($d2,-1,-1,false);
                $totalnokfd = $this->getCallLogByDateCount1($d2,-1,-1,false,-1,-1,"Fsenduid");
		$successcall = $this->getCallLogByDateCount1($d2,1,-1,false);		          
                
		$sql = "update db_background_service.t_statistic_calldata_temp set total = $total,
                                                                              totald = $totald,
                                                                              totalnokf = $totalnokf,
                                                                              totalnokfd = $totalnokfd,
                                                                              successcall = $successcall
                                                                              where date_id = '$d2';";
		$TTdb->query ( $sql );              
        }
        
        function getCallDataBySp($date,$spid = 600)
        {
		set_time_limit(0);
                $TTdb = $this->load->database ( 'ttmobile', TRUE );
		
		$d1 = date ( "Ymd", $date );
		$d2 = date ( "Y-m-d", $date );
		
		$spStr = $this->whereSpid($spid);
		
		$sql = "delete from db_background_service.t_statistic_calldata_sp where date_id = '$d2' and spid = $spid ";
		$TTdb->query($sql);
		
		$sql = "insert into db_background_service.t_statistic_calldata_sp(date_id,spid) values('$d2',$spid)";
		$TTdb->query ( $sql );
                $total = $this->getCallLogByDateCountBySp($d2,-1,-1,true,-1,-1,"0",$spid);
                $totald = $this->getCallLogByDateCountBySp($d2,-1,-1,true,-1,-1,"Fsenduid",$spid);
		$totalnokf = $this->getCallLogByDateCountBySp($d2,-1,-1,false,-1,-1,"0",$spid);
                $totalnokfd = $this->getCallLogByDateCountBySp($d2,-1,-1,false,-1,-1,"Fsenduid",$spid);
		$successcall = $this->getCallLogByDateCountBySp($d2,1,-1,false,-1,-1,"0",$spid);
		$below20 = $this->getCallLogByDateCountBySp($d2,1,-1,false,3,20,"0",$spid);		
		$above20 = $this->getCallLogByDateCountBySp($d2,1,-1,false,21,-1,"0",$spid);	
		$morePeople = $this->getMorePeopleCallCountBySp($d2,$spid);
		
                
		$sql = "update db_background_service.t_statistic_calldata_sp set total = $total,
                                                                              totald = $totald,
                                                                              totalnokf = $totalnokf,
                                                                              totalnokfd = $totalnokfd,
                                                                              successcall = $successcall,
                                                                              below20 = $below20,
                                                                              above20 = $above20,
									      suser = (select count(0) from db_background_service.t_mobile_source_new where sid $spStr and t<unix_timestamp('".date("Y-m-d",strtotime($d2)+24*60*60)."'))
									      where date_id = '$d2' and spid = $spid;";
		$TTdb->query($sql);
                
                foreach($morePeople as $m)
                {
                    if($m["p"]>=1 && $m["p"] <=4)
                    {
                        $sql = "update db_background_service.t_statistic_calldata_sp set ".($m["p"]+1)."people = ".$m["c"]."  where date_id = '$d2' and spid = $spid";
                        $TTdb->query ( $sql );
                    }
                }       
        }
        
        function showCallData()
        {
                $TTdb = $this->load->database('ttmobile', TRUE);
                $sql = "select * from db_background_service.t_statistic_calldata order by date_id desc";
                return $TTdb->query($sql)->result_array();
        }
        
        function showCallDataBySp($spid = 600)
        {
                $TTdb = $this->load->database('ttmobile', TRUE);
                $sql = "select * from db_background_service.t_statistic_calldata_sp where spid = $spid order by date_id desc";
                return $TTdb->query($sql)->result_array();
        }
        /*通话数据统计————结束*/
        
	//每5分钟通话查询
	function getCallDataPer5($date)
	{
		$internalMobiles1 = $this->internalMobiles(true);
		$internalMobiles2 = $this->internalMobiles(false);
		
		$d1 = date("Ymd",strtotime($date));
		$TTdb = $this->load->database('ttmobile', TRUE);
		$sql = "select a.t,IFNULL(a.c,0) c1,IFNULL(b.c,0) c2 from (select from_unixtime(floor(Festablishtime/300)*300) t,count(0) c from db_call_log_$d1.t_call_log 
			where fsenduid not in($internalMobiles1) and Frcvuid not in($internalMobiles1)
			group by t) a left join (
			select from_unixtime(floor(Festablishtime/300)*300) t,count(0) c from db_call_log_$d1.t_call_log 
			where fsenduid not in($internalMobiles2) and Frcvuid not in($internalMobiles2)
			group by t) b on a.t=b.t";
		return $TTdb->query($sql)->result_array();
	}
	
        /*按手机号查询相关信息————开始*/
	function getUserOnline($mobile)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
                $onlineTable = fmod(trim($mobile),256);
		$sql = "select count(0) c from db_background_service.t_online_user_$onlineTable where fuserid = $mobile ";
                return $TTdb->query($sql)->row()->c;
	}
        
        function getRegionByMobile($mobile)
        {
                $TTdb = $this->load->database ( 'ttmobile', TRUE );
                $sql = "select region from  db_background_service.17et_mobileregion where code =  mid($mobile,1,7)";
                return $TTdb->query($sql)->row()->region;
        }
        
        private function whereQueryType($querytype,$value,$tb="")
        {
                $queryStr = "";
                if($querytype == "fuserid")
                {
                    $queryStr = $tb.$querytype." = $value ";
                }
                else
                {
                    $queryStr = $tb.$querytype." = '$value' ";
                }
                
                return $queryStr;
        }
        
        function getRegInfoByUser($value,$querytype = "Fmobile")
        {
                $TTdb = $this->load->database ( 'ttmobile', TRUE );
                $queryStr = $this->whereQueryType($querytype,$value,"a.");
                $sql = "select a.fmobile,a.fmobileid,a.fmachinecode,from_unixtime(a.Fregistertime) t,b.Fdevfirm,b.Fdevmodel from db_background_service.t_register_list a
                        left join db_background_service.t_mobile_version_list b on a.fmobileid = b.Fmobileid
                        where $queryStr order by Fregistertime limit 1";
               
                return $TTdb->query($sql)->result_array();
        }
        
	function getUserLoginList($mobile, $date1, $date2,$pagesize = 0,$pagecount = 0,$querytype = "fuserid")
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
                $queryStr = $this->whereQueryType($querytype,$mobile);
                $d = strtotime($date1);
		$d1 = date("Ymd",$d);
		$d2 = date("Ymd",strtotime($date2));
		//$sql = "select T.*,b.fdevfirm,b.fdevmodel,b.fdevosver from (";
                $m =  gmp_mod($mobile,128);
		$sql = "select T.*,fdevfirm, fdevmodel, fdevosver from (";
		for($i = $d1; $i <= $d2; $i)
                {
			$sql .= "select fcodeid,fmachinecode,faddr,fmobile,fmobileid,fclientverid,Fnettype,Flogintime,Flogouttime from db_user_login_service_$i.t_login_list_$m where $queryStr ";
			if ($i != $d2)
                        {
				$sql .= " union all ";
			}
                        $d = $d+24*60*60;
                        $i = date("Ymd", $d);
		}
                $sql .= ") as T left join db_background_service.t_mobile_version_list b on T.fmobileid = b.fmobileid order by T.Flogintime desc limit  $pagecount,$pagesize";
                //$sql .= ") as T order by T.Flogintime desc limit  $pagecount,$pagesize";
                //echo $sql;
                return $TTdb->query($sql)->result_array();
        }
        
        function getUserLoginListCount($mobile, $date1, $date2,$querytype = "fuserid")
        {
		$TTdb = $this->load->database ( 'ttmobile', TRUE );
                $queryStr = $this->whereQueryType($querytype,$mobile);
                $d = strtotime($date1);
		$d1 = date("Ymd",$d);
		$d2 = date("Ymd",strtotime( $date2 ));
		$m = gmp_mod($mobile,128);
		$sql = "select sum(ct) c from (";
		for($i = $d1; $i <= $d2; $i){
			$sql .= "select count(0) ct from db_user_login_service_$i.t_login_list_$m where $queryStr ";
			if ($i != $d2) {
				$sql .= " union all ";
			}
                        $d = $d+24*60*60;
                        $i = date("Ymd", $d);
		}
                $sql .= ") as T";
                //echo $sql;
                return $TTdb->query($sql)->row()->c;
        }
        /*按手机号查询相关信息————结束*/
	
	/*语音服务器数据————开始*/        
        function getTalkSvrStat($date,$server,$total,$nomal,$abnomal)
        {
                $date = mysql_escape_string($date);
                $server = mysql_escape_string($server);
                $total = mysql_escape_string($total);
                $nomal = mysql_escape_string($nomal);
                $abnomal = mysql_escape_string($abnomal);
                
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "insert into db_background_service.talkSvrSatis(someday,TalkServerIP,nTotalTelephoneCalls,nNormalSessionCalls,nAbNormalSessionCalls)
                        values('$date',$server,$total,$nomal,$abnomal)";
                $TTdb->query($sql);
        }
	
	function getTalkLog($TalkServerIP,$strCallNo,$strMyWiLanIP,$nMyNetType,$nHoldOnDelay,$nDelayGT500msTimes,
			    $nMaxTransitDelay,$fSendPlcRate,$nSendAudioBytes,$fRecvPlcRate,$nRecvAudioBytes,$nBeginAcceptTime,$nTelephoneTime)
        {
                $d = date("Ymd");
		$TalkServerIP = mysql_escape_string($TalkServerIP);
		$strCallNo = mysql_escape_string($strCallNo);
		$strMyWiLanIP = mysql_escape_string($strMyWiLanIP);
		$nMyNetType = mysql_escape_string($nMyNetType);
		$nHoldOnDelay = mysql_escape_string($nHoldOnDelay);
		$nDelayGT500msTimes = mysql_escape_string($nDelayGT500msTimes);
		$nMaxTransitDelay = mysql_escape_string($nMaxTransitDelay);
		$fSendPlcRate = mysql_escape_string($fSendPlcRate);
		$nSendAudioBytes = mysql_escape_string($nSendAudioBytes);
		$fRecvPlcRate = mysql_escape_string($fRecvPlcRate);
		$nRecvAudioBytes = mysql_escape_string($nRecvAudioBytes);
		$nBeginAcceptTime = mysql_escape_string($nBeginAcceptTime);
		$nTelephoneTime  = mysql_escape_string($nTelephoneTime);
                
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "insert into db_call_log_$d.talklog(TalkServerIP,strCallNo,strMyWiLanIP,nMyNetType,nHoldOnDelay,nDelayGT500msTimes,
			                                   nMaxTransitDelay,fSendPlcRate,nSendAudioBytes,fRecvPlcRate,nRecvAudioBytes,nBeginAcceptTime,nTelephoneTime)
                        values($TalkServerIP,'$strCallNo','$strMyWiLanIP',$nMyNetType,$nHoldOnDelay,$nDelayGT500msTimes,
			       $nMaxTransitDelay,$fSendPlcRate,$nSendAudioBytes,$fRecvPlcRate,$nRecvAudioBytes,$nBeginAcceptTime,$nTelephoneTime)";
                $TTdb->query($sql);
        }
	
	function showTalkSvrStat()
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select someday,sum(nTotalTelephoneCalls) total,sum(nNormalSessionCalls) s1,sum(nAbNormalSessionCalls) s2
			from db_background_service.talkSvrSatis  group by someday order by someday desc;";
                return $TTdb->query($sql)->result_array();
	}	
	/*语音服务器接数据————结束*/ 
        
	/*手机通通用户激活信息————开始*/
	function insertDevInfoUser($Fdevfirm,$Fdevmodel,$Fdevosver,$Fcode,$Fsourceid,$Fclientid,$Fnettype,$devid,$devip,$imsi,$mobile,$mac)
	{
		$Fdevfirm = mysql_escape_string($Fdevfirm);
		$Fdevmodel = mysql_escape_string($Fdevmodel);
		$Fdevosver = mysql_escape_string($Fdevosver);
		$Fcode = mysql_escape_string($Fcode);
		$Fsourceid = mysql_escape_string($Fsourceid);
		$Fclientid = mysql_escape_string($Fclientid);
		$Fnettype = mysql_escape_string($Fnettype);
		$devid = mysql_escape_string($devid);
		$devip = mysql_escape_string($devip);
		$imsi = mysql_escape_string($imsi);
		$mobile = mysql_escape_string($mobile);
		$mac = mysql_escape_string($mac);
		
		$date = date("Ymd",time());
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "insert into db_devinfo_$date.t_devinfo_list(Fdevfirm,Fdevmodel,Fdevosver,Fcode,Fsourceid,Fclientid,Fnettype,Fdevid,Fdevip,Fimsi,Fmobile,Fmac)
			values('$Fdevfirm','$Fdevmodel','$Fdevosver','$Fcode',$Fsourceid,$Fclientid,'$Fnettype',$devid,'$devip','$imsi','$mobile','$mac')";
		$TTdb->query($sql);
	}
	
	function insertActive($mobile,$Fcode,$Fsourceid,$Fclientid,$Fnettype,$Fimsi)
	{
		$mobile = mysql_escape_string($mobile);
		$Fcode = mysql_escape_string($Fcode);
		$Fsourceid = mysql_escape_string($Fsourceid);
		$Fclientid = mysql_escape_string($Fclientid);
		$Fnettype = mysql_escape_string($Fnettype);
		$Fimsi = mysql_escape_string($Fimsi);
		
		$date = date("Ymd",time());
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "insert into db_devinfo_$date.t_active_list(fmobile,Fcode,Fsourceid,Fclientid,Fnettype,Fimsi)
			values('$mobile','$Fcode',$Fsourceid,$Fclientid,'$Fnettype','$Fimsi')";
		$TTdb->query($sql);
	}
	
	function insertRegSucc($mobile,$Fcode,$Fsourceid,$Fclientid,$Fnettype)
	{
		$mobile = mysql_escape_string($mobile);
		$Fcode = mysql_escape_string($Fcode);
		$Fsourceid = mysql_escape_string($Fsourceid);
		$Fclientid = mysql_escape_string($Fclientid);
		$Fnettype = mysql_escape_string($Fnettype);
		
		$date = date("Ymd",time());
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "insert into db_devinfo_$date.t_reg_succ_list(fmobile,Fcode,Fsourceid,Fclientid,Fnettype)
			values('$mobile','$Fcode',$Fsourceid,$Fclientid,'$Fnettype')";
		$TTdb->query($sql);
	}	
	/*手机通通用户激活信息————结束*/
	
	/*用户注册失败处理————开始*/
	function insertRegLoginFail($ctime,$etype,$emsg,$firm,$dev,$os,$mcode,$msource,$imsi,$nettype,$netinfo,$mac,$version,$content)
	{
		$ctime = mysql_escape_string($ctime);
		$etype = mysql_escape_string($etype);
		$emsg = mysql_escape_string($emsg);
		$firm = mysql_escape_string($firm);
		$dev = mysql_escape_string($dev);
		$os = mysql_escape_string($os);
		$mcode = mysql_escape_string($mcode);
		$msource = mysql_escape_string($msource);
		$imsi = mysql_escape_string($imsi);
		$nettype = mysql_escape_string($nettype);
		$netinfo = mysql_escape_string($netinfo);
		$mac = mysql_escape_string($mac);
		$version = mysql_escape_string($version);
		$content = mysql_escape_string($content);
				
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "insert into db_background_service.t_userreg_fail(ctime,etype,emsg,firm,dev,os,mcode,msource,imsi,nettype,netinfo,mac,version,content)
			values($ctime,'$etype','$emsg','$firm','$dev','$os','$mcode','$msource','$imsi','$nettype','$netinfo','$mac',$version,'$content')";
		$TTdb->query($sql);
	}
	
	function getUserRegFail($date,$pagesize = 0,$pagecount = 0)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_userreg_fail where ctime between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59') order by ctime limit  $pagecount,$pagesize";
		return $TTdb->query($sql)->result_array();
	}
	
	function getUserRegFailCount($date)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select count(0) c from db_background_service.t_userreg_fail where ctime between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59')";
		return $TTdb->query($sql)->row()->c;
	}
	
	function getUserRegFailByF($date,$f)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select count(distinct mac) dc,count(0) c,$f from db_background_service.t_userreg_fail where ctime between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59') group by $f order by dc desc";
		return $TTdb->query($sql)->result_array();
	}
	
	function getUserRegFailNullImsi($date)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select count(distinct mac) dc,count(0) c,emsg from db_background_service.t_userreg_fail where  imsi = '' and ctime between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59') group by emsg order by dc desc";
		return $TTdb->query($sql)->result_array();
	}
	/*用户注册失败处理————结束*/
	
	/*通话活跃度分析————开始*/
	function getUserCallCount($date)
	{
		$internalMobiles = $this->internalMobiles(false);
		$d = date("Ymd",strtotime($date));
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select count(0) c ,ct from
			(select count(0) ct ,u from
			 (select Fsenduid u from db_call_log_$d.t_call_log where Fcallstate != 255 and Fsenduid not in($internalMobiles) union all
			 select Frcvuid u from db_call_log_$d.t_call_log where Fcallstate != 255 and Frcvuid not in($internalMobiles))
			 as T group by u) as T2 group by ct";
		$rst = $TTdb->query($sql)->result_array();
		$ctotal = 0;
		$c1 = 0;
		$c2 = 0;
		$c3 = 0;
		$c4 = 0;
		$c5 = 0;
		$c6 = 0;
		$c10 = 0;
		for($i = 0,$j=count($rst);$i<$j;$i++)
		{
			$ctotal += $rst[$i]["c"];
			if($rst[$i]["ct"] < 6)
			{
				$c = "c".$rst[$i]["ct"];
				$$c = $rst[$i]["c"];
			}
			else if($rst[$i]["ct"] >= 6 && $rst[$i]["ct"] <= 10)
			{
				$c6 += $rst[$i]["c"];
			}
			else
			{
				$c10 += $rst[$i]["c"];
			}
		}
		$sql = "insert into db_background_service.t_statistic_usercallcount(date_id,ctotal,c1,c2,c3,c4,c5,c6,c10) 
			values('$date',$ctotal,$c1,$c2,$c3,$c4,$c5,$c6,$c10)";
		$TTdb->query($sql);
		//echo $ctotal."|".$c1."|".$c2."|".$c3."|".$c4."|".$c5."|".$c6."|".$c10;
	}
	
	function showUserCallCount()
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_usercallcount order by date_id desc";
		return  $TTdb->query($sql)->result_array();
	}
	/*通话活跃度分析————结束*/
	
	/*用户好友数量————开始*/
	function getUserFriendsData($date)
	{
		$TTbackdb = $this->load->database('ttmobileback',TRUE);
		$sql = "select count(0) c,ct from
			(select Fleft_uid u, count(0) ct from db_contact_0.t_contact_info_0 where Frelation_type = 9 and Fdelete = 0 group by Fleft_uid union all
			select Fleft_uid u, count(0) ct from db_contact_0.t_contact_info_1 where Frelation_type = 9 and Fdelete = 0 group by Fleft_uid union all
			select Fleft_uid u, count(0) ct from db_contact_0.t_contact_info_2 where Frelation_type = 9 and Fdelete = 0 group by Fleft_uid union all
			select Fleft_uid u, count(0) ct from db_contact_0.t_contact_info_3 where Frelation_type = 9 and Fdelete = 0 group by Fleft_uid union all
			select Fleft_uid u, count(0) ct from db_contact_0.t_contact_info_4 where Frelation_type = 9 and Fdelete = 0 group by Fleft_uid union all
			select Fleft_uid u, count(0) ct from db_contact_0.t_contact_info_5 where Frelation_type = 9 and Fdelete = 0 group by Fleft_uid union all
			select Fleft_uid u, count(0) c from db_contact_0.t_contact_info_6 where Frelation_type = 9 and Fdelete = 0 group by Fleft_uid union all
			select Fleft_uid u, count(0) ct from db_contact_0.t_contact_info_7 where Frelation_type = 9 and Fdelete = 0 group by Fleft_uid) as T group by ct;";
		$rst = $TTbackdb->query($sql)->result_array();
		
		$sql = "select sum(ct) s from
			(select count(distinct Fleft_uid) ct from db_contact_0.t_contact_info_0 union all
			select count(distinct Fleft_uid) ct from db_contact_0.t_contact_info_1 union all
			select count(distinct Fleft_uid) ct from db_contact_0.t_contact_info_2 union all
			select count(distinct Fleft_uid) ct from db_contact_0.t_contact_info_3 union all
			select count(distinct Fleft_uid) ct from db_contact_0.t_contact_info_4 union all
			select count(distinct Fleft_uid) ct from db_contact_0.t_contact_info_5 union all
			select count(distinct Fleft_uid) ct from db_contact_0.t_contact_info_6 union all
			select count(distinct Fleft_uid) ct from db_contact_0.t_contact_info_7) as T";
		$sum = $TTbackdb->query($sql)->row()->s;
		
		$nofri = 0;
		$f1more = 0;
		$f6more = 0; 
		$fall = 0;
		
		$f1 = 0;
		$f2 = 0;
		$f3 = 0;
		$f4 = 0;
		$f5 = 0;
		$f6 = 0;
		$f11 = 0;
		$f21 = 0;
		$f31 = 0;
		$f50 = 0;
		for($i = 0,$j=count($rst);$i<$j;$i++)
		{
			$fall += $rst[$i]["c"];
			if($rst[$i]["ct"] < 6)
			{
				$f1more += $rst[$i]["c"];
				$f = "f".$rst[$i]["ct"];
				$$f = $rst[$i]["c"];
			}
			else
			{
				$f6more += $rst[$i]["c"];
				if($rst[$i]["ct"] >= 6 && $rst[$i]["ct"] <= 10)
				{
					$f6 += $rst[$i]["c"];
				}
				else if($rst[$i]["ct"] >= 11 && $rst[$i]["ct"] <= 20)
				{
					$f11 += $rst[$i]["c"];
				}
				else if($rst[$i]["ct"] >= 21 && $rst[$i]["ct"] <= 30)
				{
					$f21 += $rst[$i]["c"];
				}
				else if($rst[$i]["ct"] >= 31 && $rst[$i]["ct"] <= 50)
				{
					$f31 += $rst[$i]["c"];
				}
				else 
				{
					$f50 += $rst[$i]["c"];
				}
			}
		}
		$nofri= $sum - $fall;
		
		$TTdb = $this->load->database ( 'ttmobile', TRUE );
		$sql = "delete from db_background_service.t_statistic_usefriends where date_id = '$date'";
		$TTdb->query($sql);
		$sql = "insert into db_background_service.t_statistic_usefriends(date_id,f1,f2,f3,f4,f5,f6,f11,f21,f31,f50) 
			values('$date',$f1,$f2,$f3,$f4,$f5,$f6,$f11,$f21,$f31,$f50)";
		$TTdb->query($sql);
				
		$sql = "delete from db_background_service.t_statistic_useallfriends where date_id = '$date'";
		$TTdb->query($sql);
		$sql = "insert into db_background_service.t_statistic_useallfriends(date_id,userall,nofri,f1more,f6more) 
			values('$date',$sum,$nofri,$f1more,$f6more)";
		$TTdb->query($sql);
	}
	
	function showUserFriendsData()
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_usefriends order by date_id desc";
		return  $TTdb->query($sql)->result_array();
	}
	
	function showUserAllFriendsData()
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_useallfriends order by date_id desc";
		return  $TTdb->query($sql)->result_array();
	}
	/*用户好友数量————结束*/
	
	/*送话费活动————开始*/
        function getSendCallFeeUser($date)
        {
		$d = date("Ymd",strtotime($date));
		$internalMobiles = $this->internalMobiles(true);
                $TTdb = $this->load->database ('ttmobile',TRUE);
                $TTbackdb = $this->load->database('ttmobileback',TRUE);
                $sql = "select u from (select Fsenduid u from db_call_log_$d.t_call_log where fcallstate != 255 and Frcvuid not in($internalMobiles) 
                        and Fsenduid in(select fuserid from db_background_service.t_new_user_login
                        where flogintime between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59')) union
                        select Frcvuid u from db_call_log_$d.t_call_log where fcallstate != 255 and Fsenduid not in($internalMobiles) 
                        and Frcvuid in(select fuserid from db_background_service.t_new_user_login
                        where flogintime between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59'))) as TT
			where u < 20000000000 and u>13000000000 order by rand() limit 100";
                
		$rst = $TTdb->query($sql)->result_array();
		/*$tempUser = "";
		foreach($rst as $u)
		{
			$tempUser .= $u["u"].",";
		}
		$tempUser = substr($tempUser,0,strlen($tempUser)-1);
		
		$sql = "select u from (select distinct Fleft_uid u from db_contact_0.t_contact_info_0 where Frelation_type = 9 and Fdelete = 0 union all
			select distinct Fleft_uid u from db_contact_0.t_contact_info_1 where Frelation_type = 9 and Fdelete = 0 union all
			select distinct Fleft_uid u from db_contact_0.t_contact_info_2 where Frelation_type = 9 and Fdelete = 0 union all
			select distinct Fleft_uid u from db_contact_0.t_contact_info_3 where Frelation_type = 9 and Fdelete = 0 union all
			select distinct Fleft_uid u from db_contact_0.t_contact_info_4 where Frelation_type = 9 and Fdelete = 0 union all
			select distinct Fleft_uid u from db_contact_0.t_contact_info_5 where Frelation_type = 9 and Fdelete = 0 union all
			select distinct Fleft_uid u from db_contact_0.t_contact_info_6 where Frelation_type = 9 and Fdelete = 0 union all
			select distinct Fleft_uid u from db_contact_0.t_contact_info_7 where Frelation_type = 9 and Fdelete = 0 ) as T where u in ($tempUser) 
			and u < 20000000000 order by rand() limit 100";
		
		$rst = $TTbackdb->query($sql)->result_array();*/
		foreach($rst as $u)
		{
			$sql = "insert into db_background_service.t_statistic_sendcallfee_no1(uid,createdate) values(".$u['u'].",'$date')";
			$TTdb->query($sql);
		}
        }
	
	function showSendCallFeeUser($date)
	{
		$TTdb = $this->load->database ('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_sendcallfee_no1 where createdate = '$date'";
		return $TTdb->query($sql)->result_array();
		
	}
        /*送话费活动————结束*/
	
	/*平均登录数据————开始*/
	function getAvgUserLogin($date)
	{
		$d = date("Ymd",strtotime($date));
		$TTdb = $this->load->database ('ttmobile',TRUE);
		
		$sql = "select count(0)/count(distinct Fuserid) avg from ".$this->function_class->fixTTLoginTable($d,"fuserid");
		$avg = round($TTdb->query($sql)->row()->avg,0);
		$sql = "select count(0) c50 from (select count(0) c from ".$this->function_class->fixTTLoginTable($d,"Fuserid")." group by Fuserid having c>=".($avg+50)." and c< ".($avg+100).") as T";
		$c50 = $TTdb->query($sql)->row()->c50;
		$sql = "select count(0) c100 from (select count(0) c from ".$this->function_class->fixTTLoginTable($d,"Fuserid")." group by Fuserid having c>=".($avg+100).") as T";
		$c100 = $TTdb->query($sql)->row()->c100;
		$sql = "select count(0) c30 from (select count(0) c from ".$this->function_class->fixTTLoginTable($d,"Fuserid")." group by Fuserid having c>=".($avg+30)." and c< ".($avg+50).") as T";
		$c30 = $TTdb->query($sql)->row()->c30;
		$sql = "select count(0) c1000 from (select count(0) c from ".$this->function_class->fixTTLoginTable($d,"Fuserid")." group by Fuserid having c>=".($avg+1000).") as T";
		$c1000 = $TTdb->query($sql)->row()->c1000;		
		$sql = "delete from db_background_service.t_statistic_avglogin where date_id='$date'";
		$TTdb->query($sql);
		$sql = "insert into db_background_service.t_statistic_avglogin(date_id,avglogin,above50,above100,above30,above1000) values('$date',$avg,$c50,$c100,$c30,$c1000)";
		$TTdb->query($sql);
	}
	
	function showAvgUserLogin()
	{
		$TTdb = $this->load->database ('ttmobile',TRUE);		
		$sql = "select * from db_background_service.t_statistic_avglogin order by date_id desc";
		return $TTdb->query($sql)->result_array();
	}
	/*平均登录数据————结束*/
	
	/*周期通话频率————开始*/
	function getCallFrequencyByDays($days,$startday)
	{
		$internalMobiles = $this->internalMobiles(true);
		$TTdb = $this->load->database ('ttmobile',TRUE);
		$sql = "delete from db_background_service.t_statistic_callrequency_30 where date_id ='$startday'";
		$TTdb->query($sql);
		$sql = "insert into db_background_service.t_statistic_callrequency_30(date_id) values('$startday')";
		$TTdb->query($sql);
		
		$sqlstr = "";
		for($i=0;$i<$days;$i++)
		{
			$d = date("Ymd",strtotime($startday)-$i*24*60*60);
			$sqlstr .= "select u from (select distinct Fsenduid u from db_call_log_$d.t_call_log
				 union select distinct Frcvuid  u from db_call_log_$d.t_call_log) as T$i where u not in ($internalMobiles)";
			if($i != ($days -1))
			{
				$sqlstr .= " union all ";
			}
		}
		
		$sql = "select count(0) c,cc from (select count(0) cc,u from ($sqlstr) as T group by u) as TT group by cc ";
		$rst = $TTdb->query($sql)->result_array();
		
		$sql = "update db_background_service.t_statistic_callrequency_30 set ";
		for($i=0,$c=count($rst);$i<$c;$i++)
		{
			$sql .= " d".$rst[$i]["cc"]."=".$rst[$i]["c"];
			if($i!=($c-1))
			{
				$sql .= ",";
			}
		}
		$sql .= " where date_id = '$startday'";
		$TTdb->query($sql);
	}
	
	function showCallFrequencyByDays($days)
	{
		$sql = "select * from db_background_service.t_statistic_callrequency_30 order by date_id desc";
		$TTdb = $this->load->database ('ttmobile',TRUE);		
		return $TTdb->query($sql)->result_array();
	}
	/*周期通话次数————结束*/
	
        function fix($date)
        {
                $TTdb = $this->load->database ( 'ttmobile', TRUE );
		
		$d1 = date("Ymd",$date);
		$d2 = date("Y-m-d",$date);
		
		$above300 = $this->getCallLogByDateCount($d2,1,-1,false,301);	            
                
		$sql = "update db_background_service.t_statistic_calldata set  above300 = $above300 where date_id = '$d2';";
		$TTdb->query ( $sql );
        }
}
?>