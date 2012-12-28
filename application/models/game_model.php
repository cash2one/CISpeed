<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Game_model extends CI_Model
{	
	function __construct()
	{
		parent::__construct ();
	}
	
	/*游戏信息配置————开始*/
	function getGameInfoList($used = -1, $db="default")
	{
		$usedQuery = "";
		
		if ($used > 0)
		{
			$usedQuery = " where game_use = $used ";
		}
		
		$Gamedb = $this->load->database($db,TRUE);
		$database = "netspeed_sys";
		if ($db == 'et')
		{
			$database = "logdb";
		}
		$sql = "select * from $database.ns_game_info order by game_order ";
		return $Gamedb->query($sql)->result_array();
	}
	
	function getGameInfoById($id)
	{
		$Gamedb = $this->load->database('default',TRUE);
		$sql = "select * from netspeed_sys.ns_game_info where game_id = $id";
		return $Gamedb->query($sql)->row();
	}
	
	function updateGameInfo($id, $name, $order, $key, $use, $hallid, $hallname, $mod, $start = 0)
	{
		$id = mysql_escape_string($id);
		$name = mysql_escape_string($name);
		$order = mysql_escape_string($order);
		$key = mysql_escape_string($key);
		$use = mysql_escape_string($use);
		$hallid = mysql_escape_string($hallid);
		$hallname = mysql_escape_string($hallname);
		$mod = mysql_escape_string($mod);
		
		$Gamedb = $this->load->database('default', TRUE);
		$sql = "update netspeed_sys.ns_game_info set game_order = $order,game_name = '$name',game_keyword = '$key',game_use = $use,
	                game_hallid = $hallid,game_hallname = '$hallname',game_mod = $mod,game_start = $start where game_id = $id";
		$Gamedb->query($sql);
	}
	
	function addGameInfo($name, $order, $key, $use, $hallid, $hallname, $mod, $start = 0)
	{
		$name = mysql_escape_string($name);
		$order = mysql_escape_string($order);
		$key = mysql_escape_string($key);
		$use = mysql_escape_string($use);
		$hallid = mysql_escape_string($hallid);
		$hallname = mysql_escape_string($hallname);
		$mod = mysql_escape_string($mod);
		
		$Gamedb = $this->load->database('default',TRUE);
		$sql = "insert into netspeed_sys.ns_game_info(game_order,game_name,game_keyword,game_use,game_hallid ,game_hallname ,game_mod,game_start)
	                values($order,'$name','$key',$use,$hallid,'$hallname',$mod,$start)";
		$Gamedb->query($sql);
	}
	
	function delGameInfo($id)
	{
		$Gamedb = $this->load->database ( 'default', TRUE );
		$sql = "call netspeed_sys.pr_delGameConfig($id)";
		$Gamedb->query($sql);
	}
	
	function getGameServer($id) {
		$Gamedb = $this->load->database ( 'default', TRUE );
		$sql = "select * from netspeed_sys.ns_game_server where game_id = $id ";
		return $Gamedb->query ( $sql )->result_array ();
	}
	
	function getGameServerInfoById($id) {
		$Gamedb = $this->load->database ( 'default', TRUE );
		$sql = "select * from netspeed_sys.ns_game_server where server_id = $id ";
		return $Gamedb->query ( $sql )->row ();
	}
	
	function addGameServer($gameid, $name, $use, $line) {
		$gameid = mysql_escape_string ( $gameid );
		$name = mysql_escape_string ( $name );
		$use = mysql_escape_string ( $use );
		$line = mysql_escape_string ( $line );
		
		$Gamedb = $this->load->database ( 'default', TRUE );
		$sql = "insert into netspeed_sys.ns_game_server(game_id,server_name,server_use,server_line)
	                                  values($gameid,'$name',$use,$line)";
		$Gamedb->query ( $sql );
	}
	
	function updateGameServer($id, $name, $use, $line) {
		$id = mysql_escape_string ( $id );
		$name = mysql_escape_string ( $name );
		$use = mysql_escape_string ( $use );
		$line = mysql_escape_string ( $line );
		
		$Gamedb = $this->load->database ( 'default', TRUE );
		$sql = "update netspeed_sys.ns_game_server set server_name = '$name',server_use = $use,server_line = $line where server_id = $id";
		$Gamedb->query ( $sql );
	}
	
	function delGameServer($id) {
		$Gamedb = $this->load->database ( 'default', TRUE );
		$sql = "delete from netspeed_sys.ns_game_server where server_id = $id";
		$Gamedb->query ( $sql );
	}
	
	function getGameProList($id) {
		$Gamedb = $this->load->database ( 'default', TRUE );
		$sql = "select * from netspeed_sys.ns_game_sign where game_id = $id";
		return $Gamedb->query ( $sql )->result_array ();
	}
	
	function addGamePro($gameid, $name) {
		$gameid = mysql_escape_string ( $gameid );
		$name = mysql_escape_string ( $name );
		
		$Gamedb = $this->load->database ( 'default', TRUE );
		$sql = "insert into netspeed_sys.ns_game_sign(game_id,sign_name)
	                                values($gameid,'$name')";
		$Gamedb->query ( $sql );
	}
	
	function delGamePro($id) {
		$Gamedb = $this->load->database ( 'default', TRUE );
		$sql = "delete from netspeed_sys.ns_game_sign where sign_id = $id";
		$Gamedb->query ( $sql );
	}
	/*游戏信息配置————结束*/
	
	function getGameSpeeding() {
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d = date ( "ymd", time () - 24 * 60 * 60 );
		$str = "insert into statistic.game_speedingCount(date_id,game_name,counts,num) ";
		$str .= "(select '$date' date_id,game_name,count(distinct snda_id) counts,count(distinct snda_id,process_pid) num from ";
		$str .= " (select snda_id,process_pid,game_name from ns_$d.s_userNode union all ";
		for($x = 1; $x < 64; $x ++) {
			$str .= " select snda_id,process_pid,game_name from ns_$d.s_userNode_$x ";
			if ($x < 63) {
				$str .= " union all ";
			}
		}
		$str .= ") as T";
		$str .= " group by game_name)";
		$this->db->query ( $str );
	}
	
	function showGameSpeeding($date) {
		$sql = "select game_name,counts,num from statistic.game_speedingCount where date_id = '$date' order by counts desc ";
		return $this->db->query ( $sql )->result_array ();
	}
	
	function getGameSpeedingByNode() {
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d = date ( "ymd", time () - 24 * 60 * 60 );
		$str = "insert into statistic.game_speedingNodeCount(date_id,nodeid,game_name,counts,num) ";
		$str .= "(select '$date' date_id,nodeid,game_name,count(distinct snda_id) counts,count(distinct snda_id,process_pid) num from ";
		
		$str .= " (select nodeid, snda_id,process_pid,game_name from ns_$d.s_userNode union all ";
		for($x = 1; $x < 64; $x ++) {
			$str .= " select nodeid,snda_id,process_pid,game_name from ns_$d.s_userNode_$x ";
			if ($x < 63) {
				$str .= " union all ";
			}
		}
		$str .= ") as T group by nodeid, game_name) ";
		$this->db->query ( $str );
	}
	
	function showGameSpeedingByNode($date, $node) {
		$sql = "select game_name,sum(counts) counts,sum(num) num from statistic.game_speedingNodeCount where date_id = '$date' and nodeid in($node) group by game_name order by counts desc ";
		return $this->db->query ( $sql )->result_array ();
	}
	
	/*获取各游戏加速数据————开始*/
	function getGameSpeedingProxy() {
		$Gamedb = $this->load->database ( 'et', TRUE );
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d = date ( "Ymd", time () - 24 * 60 * 60 );
		$str = "insert into datadb.game_speedingCount_proxy(date_id,game_id,counts,num) 
			(select '$date' date_id,speedgametype,count(distinct ptid) counts,count(0) num
			from logdb_$d.speedinfo group by speedgametype);";
		$Gamedb->query ( $str );
	}
	
	function showGameSpeedingProxy($date)
	{
		$Gamedb = $this->load->database ( 'et', TRUE );
		$sql = "select game_name,counts,num from datadb.game_speedingCount_proxy a
		        left join logdb.ns_game_info b on a.game_id = b.game_id where a.date_id = '$date' order by counts desc;";
		return $Gamedb->query ( $sql )->result_array ();
	}
	function showGameSpeedingProxy1($date)
	{
		$sql = "select game_name,counts,num from statistic.game_speedingCount_proxy where date_id = '$date' order by counts desc ";
		return $this->db->query($sql)->result_array();
	}
	
	/*获取各游戏加速数据————结束*/
	
	/*获取各游戏各节点加速数据————开始*/
	function getGameSpeedingByNodeProxy()
	{
		$Gamedb = $this->load->database('et',TRUE);
		$date = date("Y-m-d",time()-24*60*60);
		$d = date("Ymd",time()-24*60*60 );
		$str = "insert into datadb.game_speedingNodeCount_proxy(date_id,nodeid,game_id,counts,num)
			select '$date' date_id,speednodeid,speedgametype,count(distinct ptid) counts,count(0) num from 
			logdb_$d.speedinfo group by speednodeid, speedgametype;";
		$Gamedb->query($str);
	}
	
	function showGameSpeedingByNodeProxy($date, $node) {
		$Gamedb = $this->load->database ( 'et', TRUE );
		$sql = "select game_name,sum(counts) counts,sum(num) num from datadb.game_speedingNodeCount_proxy a
			left join logdb.ns_game_info b on a.game_id = b.game_id
			where a.date_id = '$date' and a.nodeid in($node) group by a.game_id order by counts desc";
		//echo $sql;
		return $Gamedb->query ( $sql )->result_array ();
	}
	
	function showGameSpeedingByNodeProxy1($date, $node) {
		$sql = "select game_name,sum(counts) counts,sum(num) num from statistic.game_speedingNodeCount_proxy a
		left join netspeed_sys.ns_sys_node_table b on a.nodeid=b.group_id
		where a.date_id = '$date' and b.group_id in($node) group by game_name order by counts desc";
		return $this->db->query ( $sql )->result_array ();
	}
	/*获取各游戏各节点加速数据————结束*/
	
	function getNodeSpeedingByGame1($date, $game, $time = "0") {
		$where = "a.game_name='$game'";
		if ($time != "0") {
			$where .= " and a.time_login < date_add('$time',interval 8 hour) and a.last_update_time > date_add('$time',interval 8 hour)";
		}
		
		if ($date < 110424) {
			$str = "select count(distinct snda_id) counts,count(distinct snda_id,process_pid) num,b.name,b.ip from ns_110416.s_userNode a " . "left join netspeed_sys.ns_sys_node_table b on a.nodeid = b.id where $where group by a.nodeid order by counts desc";
		} else {
			$str = "select count(distinct snda_id) counts,count(distinct snda_id,process_pid) num,b.name,b.ip from ";
			$str .= " (select time_login,last_update_time,snda_id,process_pid,game_name,nodeid from ns_$date.s_userNode union all ";
			for($i = 1; $i < 64; $i ++) {
				$str .= " select time_login,last_update_time,snda_id,process_pid,game_name,nodeid from ns_$date.s_userNode_$i ";
				if ($i < 63) {
					$str .= " union all ";
				}
			}
			$str .= ") as a left join netspeed_sys.ns_sys_node_table b on a.nodeid = b.id where $where group by a.nodeid order by counts desc";
		}
		return $this->db->query ( $str )->result_array ();
	}
	
	function getNodeSpeedingByGame($date, $game, $time = 0, $date2 = "0") {
		$where = "";
		if ($time != 0) {
			$where .= " and a.recordtime >= '$time' and a.recordtime < date_add('$date',Interval 1 DAY) and date_add(a.recordtime,Interval (-a.speededinterval) SECOND )< '$time' ";
		} else {
			$where .= " and a.recordtime >= '$date' and a.recordtime < date_add('$date2',Interval 1 DAY ) ";
		}
		
		$sql = "select count(distinct ptid) counts,count(ptid) num,b.name,b.ip from logdb.speedstartinfo a
	        left join logdb.ns_sys_node_table b on a.speednodeid = b.group_id where a.speedgametype = $game 
		$where  group by a.speednodeid order by counts desc;";
		$Gamedb = $this->load->database ( 'et', TRUE );
		return $Gamedb->query ( $sql )->result_array ();
	}
	
	function getNodeSpeedingByPeriodGame($date1, $date2, $game) {
		$all = "";
		
		while ( strtotime ( $date1 ) <= strtotime ( $date2 ) ) {
			$d = date ( "ymd", strtotime ( $date1 ) );
			$all .= "select count(distinct snda_id) counts,count(distinct snda_id,process_pid) num,nodeid from ns_$d.s_userNode where game_name='$game' group by nodeid union all ";
			for($i = 1; $i < 64; $i ++) {
				$all .= " select count(distinct snda_id) counts,count(distinct snda_id,process_pid) num,nodeid from ns_$d.s_userNode_$i where game_name='$game' group by nodeid ";
				if ($i < 63) {
					$all .= " union all ";
				}
			}
			if (strtotime ( $date1 ) != strtotime ( $date2 )) {
				$date1 = date ( "Y-m-d", strtotime ( $date1 ) + 24 * 60 * 60 );
				$all .= " union all ";
			}
		
		}
		
		$str = "select sum(counts) counts,sum(num) num,b.name,b.ip from ($all) as T left join netspeed_sys.ns_sys_node_table b on T.nodeid = b.id group by nodeid order by counts desc";
		return $this->db->query ( $str )->result_array ();
	}
	
	/*获取各游戏每5分钟加速人数————开始*/
	function getGameSpeedingOnlinePer5old($date, $game) {
		if ($date < 110424) {
			$str = "select count(distinct snda_id) counts, FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from 
			(select snda_id,date_add(last_update_time,interval -8 hour) t from ns_$date.s_userNode 
			where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= '$date' and game_name = '$game') as T group by t2";
		} else {
			$str = "select count(distinct snda_id) counts, FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from ";
			$str .= " (select snda_id,date_add(last_update_time,interval -8 hour) t from ns_$date.s_userNode 
			   where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= '$date' and game_name = '$game' union all ";
			for($i = 1; $i < 64; $i ++) {
				$str .= " select snda_id,date_add(last_update_time,interval -8 hour) t from ns_$date.s_userNode_$i 
				  where date_format(date_add(last_update_time,interval -8 hour),'%y%m%d')= '$date' and game_name = '$game' ";
				if ($i < 63) {
					$str .= " union all ";
				}
			}
			$str .= ")as T group by t2";
		}
		return $this->db->query ( $str )->result_array ();
	}
	
	function getGameSpeedingOnlinePer5old2($date, $game) {
		$Gamedb = $this->load->database ( 'et', TRUE );
		$sql = "select count(distinct ptid) counts, FROM_UNIXTIME(floor(UNIX_TIMESTAMP(t)/300)*300) t2 from 
			(select ptid,recordtime t from logdb.speedstopinfo
			where  recordtime< DATE_ADD('$date',Interval 1 day) and recordtime>='$date' and speedgametype = $game ) as T group by t2;";
		return $Gamedb->query ( $sql )->result_array ();
	}
	
	function getGameSpeedingOnlinePer5($date, $game) {
		$Gamedb = $this->load->database ( 'et', TRUE );
		$d = date("Ymd",strtotime($date));
		$sql = "select usercount counts, FROM_UNIXTIME(floor(UNIX_TIMESTAMP(recordtime)/300)*300) t2 from 
			logdb_$d.sgusercount where speedgametype = $game  group by t2;";
		return $Gamedb->query ( $sql )->result_array();
	}
	/*获取各游戏每5分钟加速人数————结束*/
	
	function fix($date) {
		$sql = "select date_id,proxyCount,proxyIPcount,proxyIDcount from statistic.speed_data where date_id = '$date' ";
		$rst = $this->db->query ( $sql )->row ();
		
		$Gamedb = $this->load->database ( 'et', TRUE );
		$sql = "update datadb.commondata_2 set proxyStartCount= " . $rst->proxyCount . ",
	                                       proxyIpCount= " . $rst->proxyIPcount . ",
					       proxyIdCount= " . $rst->proxyIDcount . " where date_id = '$date'";
		$Gamedb->query ( $sql );
	}
}
?>