<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

	//加速器用户统计类
class User_model extends CI_Model {
	
	function __construct() {
		parent::__construct ();
	}
	
	/*所有版本用户流失每日定时统计——开始*/
	function getUserLose() {
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d = date ( "ymd", time () - 24 * 60 * 60 );
		
		$sql = "delete from statistic.user_tempSndaId";
		$this->db->query ( $sql );
		
		$sql = "insert into statistic.user_lose(date_id) values('$date')";
		$this->db->query ( $sql );
		
		$sql = "insert into statistic.user_tempSndaId(snda_id) select distinct snda_id FROM ns_$d.s_cs_user s where operate = 'login' ";
		$this->db->query ( $sql );
		
		$sql = "update statistic.user_lose set login =
                (select count(*) counts from statistic.user_tempSndaId)
                where date_id='$date'; ";
		$this->db->query ( $sql );
		
		for($i = 1; $i <= 30; $i ++) {
			$date2 = date ( "Y-m-d", strtotime ( $date ) - $i * 24 * 60 * 60 );
			$d2 = date ( "ymd", strtotime ( $date ) - $i * 24 * 60 * 60 );
			
			$sql = "update statistic.user_lose set d$i = login -
                    (select count(*) counts from statistic.user_tempSndaId as T
                        inner join (select distinct snda_id from ns_$d2.s_cs_user s where operate = 'login') as T2 on T.snda_id = T2.snda_id)
                    where date_id='$date2'; ";
			$this->db->query ( $sql );
		}
		
		$sql = "delete from statistic.user_tempSndaId";
		$this->db->query ( $sql );
	}
	
	function showUserLose() {
		$sql = "select * from statistic.user_lose order by date_id desc";
		return $this->db->query ( $sql )->result_array ();
	}
	/*所有版本用户流失每日定时统计——结束*/
	
	/*独立版本(1.1.3.11及以前版本)用户流失每日定时统计——开始*/
	function getUserLoseVersion1() {
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d = date ( "ymd", time () - 24 * 60 * 60 );
		
		$sql = "delete from statistic.user_tempSndaId";
		$this->db->query ( $sql );
		
		$sql = "insert into statistic.user_lose_version1(date_id) values('$date')";
		$this->db->query ( $sql );
		
		$sql = "insert into statistic.user_tempSndaId(snda_id) select distinct snda_id FROM ns_$d.s_cs_user s where operate = 'login' and version <= '1.1.3.11' ";
		$this->db->query ( $sql );
		
		$sql = "update statistic.user_lose_version1 set login =
                (select count(*) counts from statistic.user_tempSndaId)
                where date_id='$date'; ";
		$this->db->query ( $sql );
		
		for($i = 1; $i <= 30; $i ++) {
			$date2 = date ( "Y-m-d", strtotime ( $date ) - $i * 24 * 60 * 60 );
			$d2 = date ( "ymd", strtotime ( $date ) - $i * 24 * 60 * 60 );
			
			$sql = "update statistic.user_lose_version1 set d$i = login -
                    (select count(*) counts from statistic.user_tempSndaId as T
                        inner join (select distinct snda_id from ns_$d2.s_cs_user s where operate = 'login' and version <= '1.1.3.11') as T2 on T.snda_id = T2.snda_id)
                    where date_id='$date2'; ";
			$this->db->query ( $sql );
		}
		
		$sql = "delete from statistic.user_tempSndaId";
		$this->db->query ( $sql );
	}
	
	function showUserLoseVersion1() {
		$sql = "select * from statistic.user_lose_version1 order by date_id desc";
		return $this->db->query ( $sql )->result_array ();
	}
	/*独立版本(1.1.3.11及以前版本)用户流失每日定时统计——结束*/
	
	/*独立版本(2.3.3.1及以后版本)用户流失每日定时统计——开始*/
	function getUserLoseVersion2() {
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d = date ( "ymd", time () - 24 * 60 * 60 );
		
		$sql = "delete from statistic.user_tempSndaId";
		$this->db->query ( $sql );
		
		$sql = "insert into statistic.user_lose_version2(date_id) values('$date')";
		$this->db->query ( $sql );
		
		$sql = "insert into statistic.user_tempSndaId(snda_id) select distinct snda_id FROM ns_$d.s_cs_user s where operate = 'login' and version >= '2.3.3.1' ";
		$this->db->query ( $sql );
		
		$sql = "update statistic.user_lose_version2 set login =
                (select count(*) counts from statistic.user_tempSndaId)
                where date_id='$date'; ";
		$this->db->query ( $sql );
		
		for($i = 1; $i <= 30; $i ++) {
			$date2 = date ( "Y-m-d", strtotime ( $date ) - $i * 24 * 60 * 60 );
			$d2 = date ( "ymd", strtotime ( $date ) - $i * 24 * 60 * 60 );
			
			$sql = "update statistic.user_lose_version2 set d$i = login -
                    (select count(*) counts from statistic.user_tempSndaId as T
                        inner join (select distinct snda_id from ns_$d2.s_cs_user s where operate = 'login' and version >= '2.3.3.1') as T2 on T.snda_id = T2.snda_id)
                    where date_id='$date2'; ";
			$this->db->query ( $sql );
		}
		
		$sql = "delete from statistic.user_tempSndaId";
		$this->db->query ( $sql );
	}
	
	function showUserLoseVersion2() {
		$sql = "select * from statistic.user_lose_version2 order by date_id desc";
		return $this->db->query ( $sql )->result_array ();
	}
	/*独立版本(2.3.3.1及以后版本)用户流失每日定时统计——结束*/
	
	/*所有版本新用户留存每日定时统计——开始*/
	/*function getNewUserRetain()
    {
        $date = date("Y-m-d",time()-24*60*60);
        $d = date("ymd",time()-24*60*60);
        
        $sql = "delete from statistic.user_tempSndaId";
        $this->db->query($sql);
        
        $sql = "insert into statistic.user_newRetain(date_id) values('$date')";
        $this->db->query($sql);        
        
        $sql = "insert into statistic.user_tempSndaId(snda_id)
                (select distinct snda_id from ns_$d.s_cs_user s where operate = 'login') ";
        $this->db->query($sql);
        
        $sql = "update statistic.user_newRetain set new =
                (select count(distinct snda_id) FROM speed.s_user_info where date_format(date_add(create_time,interval -8 hour),'%y%m%d')='$d')
                where date_id='$date'; ";
        $this->db->query($sql);        
        
        for($i=1;$i<=30;$i++)
        {
            $date2 = date("Y-m-d",strtotime($date)-$i*24*60*60);
            $d2 = date("ymd",strtotime($date)-$i*24*60*60);
            
            $sql = "update statistic.user_newRetain set d$i = (select count(*) counts from statistic.user_tempSndaId as T
                    inner join (select distinct snda_id FROM speed.s_user_info where date_format(date_add(create_time,interval -8 hour),'%y%m%d')='$d2') as T2
                    on T.snda_id = T2.snda_id)
                    where date_id='$date2'; ";
            $this->db->query($sql);
        }
        
        $sql = "delete from statistic.user_tempSndaId";
        $this->db->query($sql);
    }    
    
    function showNewUserRetain()
    {
        $sql = "select * from statistic.user_newRetain order by date_id desc";
        return $this->db->query($sql)->result_array();
    }*/
	/*所有版本新用户留存每日定时统计——结束*/
	
	//登录用户账号IP
	function getLoginUserIp($date) {
		$d = date ( "ymd", strtotime ( $date ) );
		
		$sql = "select snda_id,ip, 'no' a from ns_$d.s_cs_user group by snda_id";
		return $this->db->query ( $sql )->result_array ();
	}
	
	//获取新用户IP数
	function getNewUserIpCount($date) {
		$sql = "SELECT count(*) c FROM speed.s_user_info s where DATE_FORMAT(date_add(create_time, interval -8 hour),'%Y-%m-%d')='$date'";
		return $this->db->query ( $sql )->row ()->c;
	}
	
	//获取新用户IP
	function getNewUserIp($date) {
		$sql = "SELECT snda_id,snda_passport,last_login_ip,'no' a FROM speed.s_user_info s where DATE_FORMAT(date_add(create_time, interval -8 hour),'%Y-%m-%d')='$date' order by last_login_ip ";
		return $this->db->query ( $sql )->result_array ();
	}
	
	//获取新用户数
	function getNewUserCount($date) {
		$sql = "select count(0) c,t from (select FROM_UNIXTIME(floor(UNIX_TIMESTAMP(date_add(create_time, interval -8 hour))/300)*300) t from speed.s_user_info s
                where DATE_FORMAT(date_add(create_time, interval -8 hour),'%Y-%m-%d')='$date') as T group by t;";
		return $this->db->query ( $sql )->result_array ();
	}
	
	//添加机器码
	function insertMachineCode($code, $flag, $version, $proid) {
		$time = time ();
		$d = date ( "ymd", $time );
		
		$sql = "insert into machinecode.s_machinecode_info_$d(code,flag,version,proid,last_update_time) values('$code',$flag,$version,$proid,$time)";
		$this->db->query ( $sql );
		
		$sql = "select count(0) num from machinecode.s_machinecode_info where code = '$code' and proid = $proid and flag = $flag";
		if ($this->db->query ( $sql )->row ()->num > 0) {
			return;
		
		//$sql = " update speed.s_machinecode_info set version=$version,proid=$proid,last_update_time = $time where code = '$code' and flag = $flag ";
		} else {
			$sql = "insert into machinecode.s_machinecode_info(code,flag,version,proid,last_update_time) values('$code',$flag,$version,$proid,$time)";
		}
		
		$this->db->query ( $sql );
	}
	
	//定时统计每天的安装卸载量
	function getMachineCodeData() {
		$time = time ();
		
		$date = date ( "Y-m-d", $time - 24 * 60 * 60 );
		$d1 = date ( "ymd", $time - 24 * 60 * 60 );
		$d2 = date ( "ymd", $time + 24 * 60 * 60 );
		
		$sql = "CREATE TABLE machinecode.`s_machinecode_info_$d2` (
                            `id` int(11) NOT NULL auto_increment,
                            `code` varchar(64) NOT NULL,
                            `flag` tinyint(4) NOT NULL,
                            `version` bigint(20) unsigned NOT NULL,
                            `proid` int(10) unsigned NOT NULL,
                            `last_update_time` int(11) unsigned NOT NULL,
                            PRIMARY KEY  (`id`),
                            KEY `idx_code` (`code`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$this->db->query ( $sql );
		
		$sql = "insert into statistic.user_machineCode 
                        select '$date' date_id,a.proid,a.num install,IFNULL(b.num,'0') uninstall from (select proid,count(distinct code) num from 
                        machinecode.s_machinecode_info_$d1 where flag = 1 group by proid) as a left join (select proid,count(distinct code) num from 
                        machinecode.s_machinecode_info_$d1 where flag = 2 group by proid) b on a.proid = b.proid;";
		$this->db->query ( $sql );
	}
	
	function showMachineCodeData($datemonth, $proid) {
		$sql = "select * from statistic.user_machineCode where proid = $proid and date_format(date_id,'%Y-%m') = '$datemonth' order by date_id";
		return $this->db->query ( $sql )->result_array ();
	}
	
	function showMachineCodeSum($year, $proid) {
		$year1 = $year + 1;
		
		$sql = " select a.month,a.sum install,b.sum uninstall from
                        (select flag,count(0) sum,date_format(from_unixtime(last_update_time),'%m') month from machinecode.s_machinecode_info
                         where last_update_time>=unix_timestamp('$year-01-01') and last_update_time < unix_timestamp('$year1-01-01') and flag = 1 and proid = $proid group by month) as a left join
                        (select flag,count(0) sum,date_format(from_unixtime(last_update_time),'%m') month from machinecode.s_machinecode_info
                         where last_update_time>=unix_timestamp('$year-01-01') and last_update_time < unix_timestamp('$year1-01-01') and flag = 2 and proid = $proid group by month) as b on a.month = b.month";
		return $this->db->query ( $sql )->result_array ();
	}
	
	function showMachineCodeAllSum($proid) {
		$sql = "select count(0) sum from machinecode.s_machinecode_info where proid = $proid group by flag;";
		return $this->db->query ( $sql )->result_array ();
	}
	
	//定时统计用户在线,加速时长人数数据
	function getUserTimeData() {
		$date = date ( "ymd", time () - 24 * 60 * 60 );
		$d = date ( "ymd", time () - 24 * 60 * 60 );
		
		for($i = 0; $i < 1440; $i) {
			$sql = "insert into statistic.user_timeData(date_id,minute_id) values('$date',$i)";
			$this->db->query ( $sql );
			if ($i < 240) {
				$i = $i + 5;
			} else {
				$i = $i + 60;
			}
		}
		
		$sql = "select count(0) sum, t*5 minute from (select snda_id,floor((max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time)))/300) t from ns_$d.s_cs_user s
                group by s.token having (max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) < 14400 ) as T group by t union all
                select count(0) sum, t*60 minute from (select snda_id,floor((max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time)))/3600) t from ns_$d.s_cs_user s
                group by s.token having (max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) >= 14400 ) as T group by t ";
		$singleOnlineTimeRst = $this->db->query ( $sql )->result_array ();
		
		$sql = "select count(0) sum,t2*5 minute from (select snda_id,floor(sum(t)/300) t2 from (select snda_id,(max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) t from ns_$d.s_cs_user s
                group by s.token ) as T group by snda_id having sum(t) < 14400) as T2 group by t2 union all
                select count(0) sum,t2*60 minute from (select snda_id,floor(sum(t)/3600) t2 from (select snda_id,(max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) t from ns_$d.s_cs_user s
                group by s.token) as T group by snda_id having sum(t) >= 14400) as T2 group by t2;";
		$distinctOnlineTimeRst = $this->db->query ( $sql )->result_array ();
		
		$sql = "select count(0) sum, t*5 minute from (select snda_id,floor((max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time)))/300) t from ns_$d.s_user_proxy_info s
                group by s.token having (max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) < 14400 ) as T group by t union all
                select count(0) sum, t*60 minute from (select snda_id,floor((max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time)))/3600) t from ns_$d.s_user_proxy_info s
                group by s.token having (max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) >= 14400 ) as T group by t ";
		$singleSpeedTimeRst = $this->db->query ( $sql )->result_array ();
		
		$sql = "select count(0) sum,t2*5 minute from (select snda_id,floor(sum(t)/300) t2 from (select snda_id,(max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) t from ns_$d.s_user_proxy_info s
                group by s.token) as T group by snda_id having sum(t) < 14400) as T2 group by t2 union all
                select count(0) sum,t2*60 minute from (select snda_id,floor(sum(t)/3600) t2 from (select snda_id,(max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) t from ns_$d.s_user_proxy_info s
                group by s.token ) as T group by snda_id having sum(t) >= 14400) as T2 group by t2;";
		$distinctSpeedTimeRst = $this->db->query ( $sql )->result_array ();
		
		$singleOnlineIndex = 0;
		$singleOnlineCount = count ( $singleOnlineTimeRst );
		$distinctOnlineIndex = 0;
		$distinctOnlineCount = count ( $distinctOnlineTimeRst );
		
		$singleSpeedIndex = 0;
		$singleSpeedCount = count ( $singleSpeedTimeRst );
		$distinctSpeedIndex = 0;
		$distinctSpeedCount = count ( $distinctSpeedTimeRst );
		
		for($i = 0; $i < 1440; $i) {
			if ($singleOnlineIndex < $singleOnlineCount && $singleOnlineTimeRst [$singleOnlineIndex] ["minute"] == $i) {
				$singleOnlineTime = $singleOnlineTimeRst [$singleOnlineIndex] ["sum"];
				++ $singleOnlineIndex;
			} else {
				$singleOnlineTime = 0;
			}
			
			if ($distinctOnlineIndex < $distinctSpeedCount && $distinctOnlineTimeRst [$distinctOnlineIndex] ["minute"] == $i) {
				$distinctOnlineTime = $distinctOnlineTimeRst [$distinctOnlineIndex] ["sum"];
				++ $distinctOnlineIndex;
			} else {
				$distinctOnlineTime = 0;
			}
			
			if ($singleSpeedIndex < $singleSpeedCount && $singleSpeedTimeRst [$singleSpeedIndex] ["minute"] == $i) {
				$singleSpeedTime = $singleSpeedTimeRst [$singleSpeedIndex] ["sum"];
				++ $singleSpeedIndex;
			} else {
				$singleSpeedTime = 0;
			}
			
			if ($distinctSpeedIndex < $distinctSpeedCount && $distinctSpeedTimeRst [$distinctSpeedIndex] ["minute"] == $i) {
				$distinctSpeedTime = $distinctSpeedTimeRst [$distinctSpeedIndex] ["sum"];
				++ $distinctSpeedIndex;
			} else {
				$distinctSpeedTime = 0;
			}
			
			$sql = "update statistic.user_timeData set singleOnlineTime = $singleOnlineTime,
                                                       distinctOnlineTime = $distinctOnlineTime,
                                                       singleSpeedTime = $singleSpeedTime,
                                                       distinctSpeedTime = $distinctSpeedTime where date_id = '$date' and minute_id = $i";
			$this->db->query ( $sql );
			if ($i < 240) {
				$i = $i + 5;
			} else {
				$i = $i + 60;
			}
		}
	}
	
	function showUserTimeData($date) {
		$sql = "select * from statistic.user_timeData where date_id = '$date' order by minute_id";
		return $this->db->query ( $sql )->result_array ();
	}
	
	function showUserTimeDataSum($date) {
		$sql = "select sum(distinctOnlineTime) distinctSum,sum(singleOnlineTime) singleSum,sum(distinctSpeedTime) distinctSpeedSum,sum(singleSpeedTime) singleSpeedSum from statistic.user_timeData where date_id = '$date' ";
		return $this->db->query ( $sql )->row ();
	}
}
?>