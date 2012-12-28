<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
//加速器数据统计类
class Speed_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct ();
	}
	
	private function fixdate($date)
	{
		$str = "";
		if($date>110930 && $date < 111101)
		{		    
		    $temp = str_split($date);
		    for($i=0;$i<count($temp);$i++)
		    {
			$str .= $temp[$i];
			if($i==1)
			{
			    $str .= "0";
			}
		    }
		}
		else
		{
		    $str = $date;
		}
		return $str;
	}
	
	//获取独立登录账号和IP
	function getLoginIPandID($date)
	{
		$data = mysql_escape_string($date);
		$sql = "SELECT count(distinct ip) ipcount,count(distinct snda_id) idcount FROM ns_".$this->fixdate($date).".s_cs_user s
		        where operate = 'login'";
		return $this->db->query($sql)->row();
	}
	
	//获取加速的独立账号和IP
	function getSpeedingIPandID($date)
	{
		if($date < 110424)
		{
			$sql = "SELECT count(distinct ip) ipcount,count(distinct snda_id) idcount FROM ns_".$this->fixdate($date).".s_userNode s";
		}
		else
		{
			$sql = "SELECT count(distinct ip) ipcount,count(distinct snda_id) idcount FROM ";
			$sql .= " (select ip,snda_id from ns_".$this->fixdate($date).".s_userNode union all ";
			for($i = 1; $i < 64; $i++)
			{
				$sql .= " select ip,snda_id from ns_".$this->fixdate($date).".s_userNode_$i ";
				if($i < 63)
				{
					$sql .= " union all ";
				}
			}
			$sql .= ") as T ";
		}
		return $this->db->query($sql)->row();
	}
	
	//获取点击加速的总数、独立IP和ID
	function getProxyIPandID($date)
	{
		$data = mysql_escape_string($date);
		$sql = "SELECT count(0) allcount,count(distinct ip) ipcount,count(distinct snda_id) idcount FROM ns_".$this->fixdate($date).".s_user_proxy_info s where operate = 'start'";
		return $this->db->query($sql)->row();
	}
	
	//获取点击结束的总数
	function getProxyStopCount($date)
	{
		$data = mysql_escape_string($date);
		$sql = "SELECT count(0) allcount FROM ns_".$this->fixdate($date).".s_user_proxy_info s where operate = 'stop'";
		return $this->db->query($sql)->row();
	}
	
	//获取在线用户峰值
	function getUserOnlinePeak($date)
	{
		if ($date < 110510)
		{
			$sql = "select user_sum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from (select user_sum, date_add(record_time,interval -8 hour) t " . " from speed.s_cs_info where date_format(date_add(record_time,interval -8 hour),'%y%m%d') = '$date') as T group by t2 " . " order by user_sum desc limit 1;";
		}
		else
		{
			$sql = "select sum(user_sum) user_sum,t2 from (select cs_id,user_sum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from
				(select cs_id, user_sum, date_add(record_time,interval -8 hour) t from ns_".$this->fixdate($date).".s_cs_info
				where date_format(date_add(record_time,interval -8 hour),'%y%m%d') = '$date') as T group by t2,cs_id) as T2 group by t2
				order by user_sum desc limit 1;";
		}
		return $this->db->query($sql)->row();
	}
	
	//获取平均在线用户
	function getUserOnlineAvg($date)
	{
		$sql = "select round(avg(user_sum)) user_avg from (select sum(user_sum) user_sum,t2 from
			(select cs_id,user_sum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from
			(select cs_id, user_sum, date_add(record_time,interval -8 hour) t from ns_".$this->fixdate($date).".s_cs_info
			where date_format(date_add(record_time,interval -8 hour),'%y%m%d') = '$date') as T
			group by t2,cs_id) as T2 group by t2) as T3;";
		return $this->db->query($sql)->row();
	}
	
	//获取加速人数峰值
	function getSpeedingListPeak($date)
	{
		$sql = "select sum(usum) sum,t2 t from " . "  (select nodeid,max(usum) usum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2
		        from (select nodeid ,user_sum usum,date_add(last_update_time,interval -8 hour) t from ns_".$this->fixdate($date).".s_node_info
			where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= '$date' order by t,nodeid) as T
			group by t2,nodeid order by t2,nodeid) as T2 group by t2 order by sum desc limit 1;";
		return $this->db->query($sql)->row();
	}
	
	//获取新用户数和IP
	function getNewUserCount($date)
	{
		$sql = "SELECT count(0) count,count(distinct last_login_ip) ipcount FROM speed.s_user_info s
		        where DATE_FORMAT(date_add(create_time, interval -8 hour),'%y%m%d')='$date';";
		return $query = $this->db->query($sql)->row();
	}
	
	//获取流量信息
	function getBandwidth($date,$startT='',$endT='')
	{
		$sql = "SELECT sum(upload_size)/count(distinct nodeid) uploadsum,sum(download_size)/count(distinct nodeid) downloadsum
		        FROM ns_".$this->fixdate($date).".s_node_info s ";
		if ($startT && $endT)
		{
			$sql .= "where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= '$date' and
			         DATE_FORMAT(date_add(last_update_time, interval -8 hour), '%H:%i:%s') >= '$startT' " . " and
				 DATE_FORMAT(date_add(last_update_time, interval -8 hour), '%H:%i:%s')< '$endT';";
		}
		return $this->db->query($sql)->row();
	}
	
	//定时生成、显示综合统计数据————开始
	function generateSpeedData()
	{
		$date = date("Y-m-d",time()-24*60*60);
		$d = date("ymd",time()-24*60*60);
		
		$sql = "insert into statistic.speed_data(date_id) values('$date')";
		$this->db->query($sql);
		
		$loginDistinct = $this->getLoginIPandID($d);
		$onlinePeak = $this->getUserOnlinePeak($d);
		$onlineAvg = $this->getUserOnlineAvg($d);
		$speedingDistinct = $this->getSpeedingIPandID($d);
		$speedingPeak = $this->getSpeedingListPeak($d);
		$proxyDistinct = $this->getProxyIPandID($d);
		$proxyStopCount = $this->getProxyStopCount($d);
		$newUser = $this->getNewUserCount($d);
		$bandwidth = $this->getBandwidth($d);
		$bandwidth1 = $this->getBandwidth($d, "00:00:00", "03:00:00");
		$bandwidth2 = $this->getBandwidth($d, "03:00:00", "09:00:00");
		$bandwidth3 = $this->getBandwidth($d, "09:00:00", "15:00:00");
		$bandwidth4 = $this->getBandwidth($d, "15:00:00", "19:00:00");
		$bandwidth5 = $this->getBandwidth($d, "19:00:00", "24:00:00");
		
		$sql = "update statistic.speed_data set loginIPcount = ".$loginDistinct->ipcount.",
						    loginIDcount = ".$loginDistinct->idcount.",
						    onlinePeak = ".$onlinePeak->user_sum.",
						    onlinePeakTime = '".$onlinePeak->t2."',
						    onlineAvg = ".$onlineAvg->user_avg.",
						    newUser = ".$newUser->count.",
						    newUserIP = ".$newUser->ipcount.",
						    speedIPcount = ".$speedingDistinct->ipcount.",
						    speedIDcount = ".$speedingDistinct->idcount.",
						    speedPeak = ".$speedingPeak->sum.",
						    speedPeakTime = '".$speedingPeak->t."',
						    proxyCount = ".$proxyDistinct->allcount.",
						    proxyCountStop = ".$proxyStopCount->allcount.",
						    proxyIPcount = ".$proxyDistinct->ipcount.",
						    proxyIDcount = ".$proxyDistinct->idcount.",
						    uploadAll = ".$bandwidth->uploadsum.",
						    downloadAll = ".$bandwidth->downloadsum.",
						    upload1 = ".(isset($bandwidth1->uploadsum)?$bandwidth1->uploadsum:0).",
						    download1 = ".(isset($bandwidth1->downloadsum)?$bandwidth1->downloadsum:0).",
						    upload2 = ".(isset($bandwidth2->uploadsum)?$bandwidth2->uploadsum:0).",
						    download2 = ".(isset($bandwidth2->downloadsum)?$bandwidth2->downloadsum:0).",
						    upload3 = ".(isset($bandwidth3->uploadsum)?$bandwidth3->uploadsum:0).",
						    download3 = ".(isset($bandwidth3->downloadsum)?$bandwidth3->downloadsum:0).",
						    upload4 = ".(isset($bandwidth4->uploadsum)?$bandwidth4->uploadsum:0).",
						    download4 = ".(isset($bandwidth4->downloadsum)?$bandwidth4->downloadsum:0).",
						    upload5 = ".(isset($bandwidth5->uploadsum)?$bandwidth5->uploadsum:0).",
						    download5 = ".(isset($bandwidth5->downloadsum)?$bandwidth5->downloadsum:0)." where date_id ='$date'";
		$this->db->query($sql);
	}
	
	function showSpeedData($date)
	{
		$sql = "select * from statistic.speed_data where date_id='$date'";
		return $this->db->query($sql)->row();
	}
	
	function showSpeedDataList() {
		$sql = "select * from statistic.speed_data order by date_id desc limit 0,60";
		return $this->db->query ( $sql )->result ();
	}
	//定时生成、显示综合统计数据————结束
	

	//每5分钟加速人数统计
	function getSpeedingListPer5($date)
	{
		$sql = "select count(distinct nodeid) nodes, sum(usum) sum,sum(sock_usum) sock_sum,sum(vpn_usum) vpn_sum, t2 t from
		       (select nodeid,max(usum) usum,max(sock_user_sum) sock_usum,max(vpn_user_sum) vpn_usum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from " . "    (select nodeid ,user_sum usum,sock_user_sum,vpn_user_sum,date_add(last_update_time,interval -8 hour) t from ns_".$this->fixdate($date).".s_node_info " . "     where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= '$date' order by t,nodeid) as T " . "     group by t2,nodeid order by t2,nodeid) as T2 group by t2;";
		return $this->db->query ( $sql )->result_array ();
	}
	
	
		
	//每5分钟在线人数统计
	function getUserOnlineSumPer5($date)
	{
		if ($date < 110510)
		{
			$sql = "select user_sum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from (select user_sum, date_add(record_time,interval -8 hour) t " . " from speed.s_cs_info where date_format(date_add(record_time,interval -8 hour),'%y%m%d') = '$date') as T group by t2;";
		}
		else
		{
			$sql = "select sum(user_sum) user_sum,t2 from (select cs_id,user_sum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from
	            (select cs_id, user_sum, date_add(record_time,interval -8 hour) t from ns_".$this->fixdate($date).".s_cs_info where date_format(date_add(record_time,interval -8 hour),'%y%m%d') = '$date') as T group by t2,cs_id) as T2 group by t2;";
		}
		return $this->db->query ( $sql )->result_array ();
	}
	
	//最近加速节点情况列表
	function getSpeedingNodeListRec($date) {
		$sql = "select ip,name, nodeid, usum,sock_user_sum,vpn_user_sum,max(FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300)) t2 from " . " (select nodeid ,user_sum usum,sock_user_sum,vpn_user_sum,date_add(last_update_time,interval -8 hour) t from " . " ns_".$this->fixdate($date).".s_node_info where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= " . " '$date' order by t desc,nodeid) as T left join netspeed_sys.ns_sys_node_table A on A.id = T.nodeid  group by nodeid order by usum;";
		return $this->db->query ( $sql )->result_array ();
	}
	
	//获取节点
	function getNodeList() {
		$sql = "select ip,id from netspeed_sys.ns_sys_node_table where valid = 1 order by ip;";
		return $this->db->query ( $sql )->result_array ();
	}
	
	//按节点获取加速情况统计
	function getSpeedingListPer5ByNode($date, $nodeid)
	{
		$sql = "select nodeid,name,usum sum,sock_user_sum,vpn_user_sum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2
		        from (select nodeid ,user_sum usum,sock_user_sum,vpn_user_sum,date_add(last_update_time,interval -8 hour) t
			      from ns_".$this->fixdate($date).".s_node_info where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= '$date' and nodeid=$nodeid order by t) as T
			      left join netspeed_sys.ns_sys_node_table A on A.id = T.nodeid group by t2;";
		return $this->db->query($sql)->result_array();
	}
	
	/*获取每天节点峰值人数时间————开始*/
	function getNodesPeakCount($date)
	{
		$d = date("ymd",strtotime($date));
		
		$sql = "insert into statistic.speed_nodepeakdata 
		        select '$date' d,nodeid,max(sum),t2 from (select nodeid,usum sum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from
			(select nodeid ,user_sum usum,sock_user_sum,vpn_user_sum,date_add(last_update_time,interval -8 hour) t 
			from ns_".$this->fixdate($d).".s_node_info where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= '$d'
			order by t) as T group by nodeid,t2 order by nodeid,sum desc) as T2 group by nodeid;";		
		$this->db->query($sql);
	}
	
	function showNodesPeakCount($date)
	{
		$sql = "select ip,a.nodeid nodeid,name,a.c c,t,T.c peakc from statistic.speed_nodepeakdata a
			left join netspeed_sys.ns_sys_node_table b on b.id = a.nodeid
			left join (SELECT nodeid,max(c) c FROM statistic.speed_nodepeakdata group by nodeid) as T on T.nodeid = a.nodeid
			where a.date_id = '$date' order by T.c desc;";
		return $this->db->query($sql)->result_array();
	}
	/*获取每天节点峰值人数时间————结束*/
	
	//加速节点流量统计
	function getSpeedingLoadSize($date, $nodeid)
	{
		$sql = "select nodeid,name,max(upload_size) upload,max(download_size) download,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from " . "(select nodeid,upload_size,download_size,date_add(last_update_time,interval -8 hour) t from ns_".$this->fixdate($date).".s_node_info  " . " where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= '$date' and nodeid=$nodeid order by t) as T " . " left join netspeed_sys.ns_sys_node_table A on A.id = T.nodeid group by t2;";
		return $this->db->query ( $sql )->result_array ();
	}
	
	//用户加速游戏按节点统计
	function getUserSpeedingGameByNode($date, $time, $node)
	{
		$date = mysql_escape_string($date);
		$time = mysql_escape_string($time);
		$node = mysql_escape_string($node);
		
		if ($date < 110424)
		{
			$str = "select distinct snda_id,game_name from ns_" . date ( "ymd", strtotime ( $date ) ) . ".s_userNode " . " where nodeid = $node and time_login < date_add('$time',interval 8 hour) and last_update_time > date_add('$time',interval 8 hour) order by snda_id;";
		}
		else
		{
			$str = "select distinct snda_id,game_name from ";
			$str .= " (select snda_id,game_name from ns_" . date ( "ymd", strtotime ( $date ) ) . ".s_userNode " . " where nodeid = $node and time_login < date_add('$time',interval 8 hour) and last_update_time > date_add('$time',interval 8 hour) union all ";
			for($i = 1; $i < 64; $i ++)
			{
				$str .= " (select snda_id,game_name from ns_" . date ( "ymd", strtotime ( $date ) ) . ".s_userNode_$i " . " where nodeid = $node and time_login < date_add('$time',interval 8 hour) and last_update_time > date_add('$time',interval 8 hour) ";
				if ($i < 63)
				{
					$str .= " union all ";
				}
			}
			$str .= "） as T order by snda_id";
		
		}
		return $this->db->query ( $str )->result_array ();
	}
	
	//用户加速游戏比较统计
	function getUserSpeedingGameByNodeCompare($date, $time, $node, $time2)
	{
		$date = mysql_escape_string($date);
		$time = mysql_escape_string($time);
		$node = mysql_escape_string($node);
		$time2 = mysql_escape_string($time2);
		
		$str = "select distinct a.snda_id,concat(a.game_name,'/',ifnull(b.game_name,'未加速')) game_name from ns_".date("ymd",strtotime($date)).".s_userNode a
			left join (select distinct snda_id,game_name from ns_".date("ymd",strtotime($date)).".s_userNode
        		           where nodeid=$node and time_login < date_add('$time2',interval 8 hour) and last_update_time > date_add('$time2',interval 8 hour)) b
			on a.snda_id = b.snda_id 
			where a.nodeid=$node and 
		        a.time_login < date_add('$time',interval 8 hour) and 
		        a.last_update_time > date_add('$time',interval 8 hour) and a.game_name != ifnull(b.game_name,'no')
			order by a.snda_id ;";
		return $this->db->query ( $str )->result_array ();
	}
	
	//节点加速人数报警数据
	function getAlertSpeedData($nodeid)
	{
		$d = date("ymd");
		$sql = "SELECT  user_sum,DATE_ADD(last_update_time,Interval -8 hour) last_update_time
		        FROM ns_".$this->fixdate($d).".s_node_info where nodeid=$nodeid order by id desc limit 2; ";
		return $this->db->query($sql)->result_array();
	}
	
	function fix($id = 0) //获取用户IP,游戏,路径信息
	{
		if ($id == 0) {
			$i = "";
		} else {
			$i = "_$id";
		}
		
		$sql = "select snda_id,inet_ntoa(ip) ip,game_name,game_realm,process_name,process_path,process_pid,proecess_md5
		from ns_110609.s_userNode$i";
		
		$this->load->dbutil ();
		$this->load->helper ( 'file' );
		$this->load->helper ( 'download' );
		
		$query = $this->db->query ( $sql );
		$data = $this->dbutil->csv_from_result ( $query );
		$data = iconv ( "UTF-8", "GB2312//IGNORE", $data );
		
		$Date = date ( "YmdHis" );
		$Filename = "$id.csv";
		force_download ( $Filename, $data );
	}
	
	function fix2() //获取所有节点加速人数
	{
		$sql = "select nodeid,usum sum,sock_user_sum,vpn_user_sum,FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from 
		(select nodeid ,user_sum usum,sock_user_sum,vpn_user_sum,date_add(last_update_time,interval -8 hour) t from ns_110616.s_node_info 
		where date_format(date_add(last_update_time,interval -8 hour),'110616')= '110616' order by nodeid,t) as T group by t2,nodeid order by nodeid,t2 desc;";
		
		$this->load->dbutil ();
		$this->load->helper ( 'file' );
		$this->load->helper ( 'download' );
		
		$query = $this->db->query ( $sql );
		$data = $this->dbutil->csv_from_result ( $query );
		$data = iconv ( "UTF-8", "GB2312//IGNORE", $data );
		
		$Date = date ( "YmdHis" );
		$Filename = "$Date.csv";
		force_download ( $Filename, $data );
	}
	
	function fix3() //1.	上周7天内使用4天以上的，本周未使用的。
	{	
		/*$date = "2011-06-13";
	
	$sql = "insert into statistic.ttt(snda_id) select snda_id from (";
	
	for($i=0;$i<7;$i++)
	{
	    $d = date("ymd",strtotime($date)+$i*24*60*60);
	    $sql .= "select distinct snda_id from ns_$d.s_cs_user where operate='login' ";	    
	    if($i<6)
	    {
		    $sql  .= " union all ";
	    }
	}
	
	$sql .= ") as T";*/
		
		$date = "2011-06-20";
		
		$sql = "insert into statistic.ttt1(snda_id) select snda_id from (";
		
		for($i = 0; $i < 4; $i ++) {
			$d = date ( "ymd", strtotime ( $date ) + $i * 24 * 60 * 60 );
			$sql .= "select distinct snda_id from ns_$d.s_cs_user where operate='login' ";
			if ($i < 3) {
				$sql .= " union all ";
			}
		}
		
		$sql .= ") as T";
		
		$this->db->query ( $sql );
	
		//select snda_id from statistic.ttt group by snda_id having count(0)>3;  //41724
	/*select count(0) from (select snda_id from statistic.ttt group by snda_id having count(0)>3) as a inner join 
			       (select distinct snda_id from statistic.ttt1) as b on a.snda_id = b.snda_id; 
	*/
	//34626
	}
	
	function fix4() //2.	周一至周三，每天登录后未加速的用户数。
	{
		//$date = "2011-06-20";//登录49251 //加速13098
		//$date = "2011-06-21";//登录50425 //加速12837
		//$date = "2011-06-22";//登录50344 //加速37668	
		

		//$date = "2011-06-13";//登录49975 //加速12778
		//$date = "2011-06-14";//登录48726 //加速11781
		//$date = "2011-06-15";//登录49051 //加速11820	
		

		//$date = "2011-06-06";//登录50118 //加速31614
		//$date = "2011-06-07";//登录50068 //加速26259
		$date = "2011-06-08"; //登录55000 //加速37668
		$d = date ( "ymd", strtotime ( $date ) );
		$sql = "select count(0) num from (select distinct snda_id from ns_$d.s_cs_user where operate = 'login')as a inner join
	                             (select distinct snda_id from (";
		$sql .= "select distinct snda_id from ns_$d.s_userNode union all ";
		for($j = 1; $j < 64; $j ++) {
			$sql .= " select distinct snda_id from ns_$d.s_userNode_$j ";
			if ($j < 63) {
				$sql .= " union all ";
			}
		}
		
		$sql .= ") as T) as b on a.snda_id = b.snda_id";
		
		echo $this->db->query ( $sql )->row ()->num;
	}
	
	function fix5() //匹配登录时间
	{
		$sql = "select distinct snda_id from ns_110620.s_user_proxy_info s group by s.token having (max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) < 300  and (max(unix_timestamp(s.record_time))-min(unix_timestamp(s.record_time))) <> 0;";
	}
}
?>