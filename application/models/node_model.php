<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Node_model extends CI_Model
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
	
	//获取节点列表
	function getNodeList($valid=-1)
	{
		$whereValid = "";
		
		if ($valid == 1 || $valid == 0)
		{
			$whereValid = "where a.valid = $valid";
		}
		$str = "select a.*,b.name groupname from netspeed_sys.ns_sys_node_table a
		        left join netspeed_sys.ns_sys_group_table b on a.group_id = b.id $whereValid order by a.id;";
		return $this->db->query($str)->result_array();
	}
	
	//获取节点信息
	function getNodeById($id)
	{
		$id = mysql_escape_string($id);
		$str = "select a.*,b.name groupname from netspeed_sys.ns_sys_node_table a
		        left join netspeed_sys.ns_sys_group_table b on a.group_id = b.id where a.id = $id";
		return $this->db->query ( $str )->row_array ();
	}
	
	//检查节点是否存在
	function checkNodeId($id)
	{
		$id = mysql_escape_string($id);
		
		$str = "select count(0) countid from netspeed_sys.ns_sys_node_table where id = $id";
		return $this->db->query($str)->row()->countid;
	}
	
	//获取groupl列表
	function getGroupList()
	{
		$str = "SELECT * from netspeed_sys.ns_sys_group_table order by id desc";
		return $this->db->query($str)->result_array();
	}
	
	//获取group信息
	function getGroupById($id)
	{
		$id = mysql_escape_string ( $id );
		$str = "SELECT * from netspeed_sys.ns_sys_group_table where id = $id";
		return $this->db->query ( $str )->row_array ();
	}
	
	//检查group是否存在
	function checkGroupId($id) {
		$id = mysql_escape_string ( $id );
		
		$str = "select count(0) countid from netspeed_sys.ns_sys_group_table where id = $id";
		return $this->db->query ( $str )->row ()->countid;
	}
	
	//插入group
	function insertGroup($id, $name, $stat)
	{
		$id = mysql_escape_string ( $id );
		$name = mysql_escape_string ( $name );
		$stat = mysql_escape_string ( $stat );
		if ($this->checkGroupId ( $id ) > 0) {
			return - 1;
		}
		
		$str = "insert into netspeed_sys.ns_sys_group_table(id,name,valid) values($id,'$name',$stat)";
		$sql = "insert into logdb.ns_sys_group_table(id,name,valid) values($id,'$name',$stat)";
		try
		{
			$this->db->query ( $str );
			$Gamedb = $this->load->database ( 'et', TRUE );
			$Gamedb->query ( $sql );
			return 0;
		}
		catch(Exception $e)
		{
			return - 999;
		}
	}
	
	//插入节点
	function insertNode($id, $ip, $name, $port, $pingport, $group, $stat)
	{
		$id = mysql_escape_string($id);
		$ip = mysql_escape_string($ip);
		$name = mysql_escape_string($name);
		$port = mysql_escape_string($port);
		$pingport = mysql_escape_string($pingport);
		$stat = mysql_escape_string($stat);
		if ($this->checkNodeId($id)>0)
		{
			return - 1;
		}
		
		$str = "insert into netspeed_sys.ns_sys_node_table(id,ip,name,port,ping_port,group_id,valid) values($id,'$ip','$name',$port,$pingport,$group,$stat)";
		$sql = "insert into logdb.ns_sys_node_table(id,ip,name,port,ping_port,group_id,valid) values($id,'$ip','$name',$port,$pingport,$group,$stat)";
		
		try {
			$this->db->query ( $str );
			$Gamedb = $this->load->database ( 'et', TRUE );
			$Gamedb->query ( $sql );
			return 0;
		} catch ( Exception $e ) {
			return - 999;
		}
	}
	
	function updateGroup($id, $name, $stat, $oldid) {
		$id = mysql_escape_string ( $id );
		$name = mysql_escape_string ( $name );
		$stat = mysql_escape_string ( $stat );
		$oldid = mysql_escape_string ( $oldid );
		if ($id != $oldid) {
			if ($this->checkGroupId ( $id ) > 0) {
				return - 1;
			}
		}
		
		$str = "update netspeed_sys.ns_sys_group_table set id=$id,name='$name',valid=$stat,last_update_time='" . date ( "Y-m-d H:i:s", time () + 60 * 60 * 8 ) . "' where id = $oldid";
		$sql = "update logdb.ns_sys_group_table set id=$id,name='$name',valid=$stat,last_update_time='" . date ( "Y-m-d H:i:s", time () + 60 * 60 * 8 ) . "' where id = $oldid";
		
		try {
			$this->db->query ( $str );
			$Gamedb = $this->load->database ( 'et', TRUE );
			$Gamedb->query ( $sql );
			return 0;
		} catch ( Exception $e ) {
			return - 999;
		}
	}
	
	function updateNode($id, $ip, $name, $port, $pingport, $group, $stat, $oldid) {
		$id = mysql_escape_string ( $id );
		$name = mysql_escape_string ( $name );
		$ip = mysql_escape_string ( $ip );
		$port = mysql_escape_string ( $port );
		$pingport = mysql_escape_string ( $pingport );
		$group = mysql_escape_string ( $group );
		$stat = mysql_escape_string ( $stat );
		$oldid = mysql_escape_string ( $oldid );
		if ($id != $oldid) {
			if ($this->checkNodeId ( $id ) > 0) {
				return - 1;
			}
		}
		$str = "update netspeed_sys.ns_sys_node_table set id=$id,ip='$ip',name='$name',port=$port,ping_port=$pingport,group_id=$group,valid=$stat,last_update_time='" . date ( "Y-m-d H:i:s", time () + 60 * 60 * 8 ) . "' where id = $oldid";
		$sql = "update logdb.ns_sys_node_table set id=$id,ip='$ip',name='$name',port=$port,ping_port=$pingport,group_id=$group,valid=$stat,last_update_time='" . date ( "Y-m-d H:i:s", time () + 60 * 60 * 8 ) . "' where id = $oldid";
		
		try {
			$this->db->query ( $str );
			$Gamedb = $this->load->database ( 'et', TRUE );
			$Gamedb->query ( $sql );
			return 0;
		} catch ( Exception $e ) {
			return - 999;
		}
	}
	
	function getNodeIdName() {
		$str = "select distinct id,group_id,name,ip from netspeed_sys.ns_sys_node_table order by group_id";
		return $this->db->query ( $str )->result_array ();
	}
	
	/*记录节点重启信息————开始*/
	function recordNodeRestart($nodeid)
	{
		$date = date ( "ymd" );
		$sql = "SELECT user_sum FROM ns_".$this->fixdate($date).".s_node_info where nodeid = $nodeid order by id desc limit 1";
		$count = $this->db->query($sql)->row()->user_sum;
		
		$sql = "insert into netspeed_sys.ns_sys_node_restart_table(nodeid,restarttime,restartcount) values($nodeid," . time () . ",$count)";
		$this->db->query ( $sql );
		
		$sql = "select name from netspeed_sys.ns_sys_node_table where id = $nodeid";
		return $count = $this->db->query ( $sql )->row ()->name;
	}
	
	function showNodeRestart()
	{
		$sql = "SELECT a.*,b.ip,b.name  FROM netspeed_sys.ns_sys_node_restart_table a
		        left join netspeed_sys.ns_sys_node_table b on a.nodeid = b.id order by a.id desc limit 60";
		return $this->db->query($sql)->result_array();
	}
	/*记录节点重启信息————结束*/
	
	/*ET加速器节点推送相关————开始*/
	function getGameNodeDataPerDay($date)
	{
		$ETdb = $this->load->database('et',TRUE);
		$d = date("Ymd",strtotime($date));
		$sql = "CREATE TABLE `logdb_$d`.`speednode` (
			    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			    `areaid` int(10) unsigned DEFAULT '0',
			    `netmode` int(10) unsigned DEFAULT '0',
			    `gameid` int(10) unsigned DEFAULT '0',
			    `gameareaid` int(10) unsigned DEFAULT '0',
			    `nodeid` int(10) unsigned DEFAULT '0',
			    `c` int(10) unsigned DEFAULT '0',
			    PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$ETdb->query($sql);
		/*0电信1网通2其他*/
		$sql = "insert into logdb_$d.speednode(areaid,netmode,gameid,gameareaid,nodeid,c)
			SELECT areaid,netmode,speedgametype,speedgamearea,speednodeid,count(0) c FROM
			logdb_$d.speedinfo where speededinterval > 1200000 group by areaid,netmode,speedgametype,speedgamearea,speednodeid;";
		$ETdb->query($sql);
	}
	
	function generateGameNodeDataList($date) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "delete from datadb.speednode where hand = 0";
		$ETdb->query ( $sql );
		
		$all = "";
		while ( date ( "Ymd", strtotime ( $date ) ) > 20110907 ) {
			$d = date ( "Ymd", strtotime ( $date ) );
			$all .= " select areaid,netmode,gameid,gameareaid,nodeid,c from logdb_$d.speednode  ";
			$date = date ( "Y-m-d", strtotime ( $date ) - 24 * 60 * 60 );
			if ($d > 20110908) {
				$all .= " union all ";
			}
		}
		
		$sql = "insert into datadb.speednode(areaid,netmode,gameid,gameareaid,nodeid,c)
	        select areaid,netmode,gameid,gameareaid,nodeid,sum(c) s from ($all) as T group by areaid,netmode,gameid,gameareaid,nodeid ";
		//echo $sql;
		$ETdb->query ( $sql );
	}
	
	function addGameNodePush() {
	
	}
	
	function getGameNodePushData($pagesize = -1, $pagecount = -1, $areaid = -1, $netmode = -1, $gameid = -1, $gameareaid = -1) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$where = "";
		if ($areaid >= 0) {
			$where .= " and a.areaid = $areaid ";
		}
		if ($netmode >= 0) {
			$where .= " and a.netmode = $netmode ";
		}
		if ($gameid >= 0) {
			$where .= " and a.gameid = $gameid ";
		}
		if ($gameareaid >= 0) {
			$where .= " and a.gameareaid = $gameareaid ";
		}
		$limit = "";
		if ($pagesize >= 0 && $pagecount >= 0) {
			$limit = " limit $pagecount,$pagesize ";
		}
		$sql = "select a.areaid,a.netmode,a.gameid,a.gameareaid,a.nodeid,b.areaname,c.game_name,d.server_name,e.name nodename from datadb.speednode a
	        left join iptables.areainfo b on a.areaid = b.areaid
		left join logdb.ns_game_info c on a.gameid = c.game_id 
		left join logdb.ns_game_server d on a.gameareaid = d.server_id 
		left join logdb.ns_sys_node_table e on a.nodeid = e.group_id 
		where 1=1 $where order by areaid,netmode,gameid,gameareaid,c desc $limit ";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	function getGameNodePushDataCount($areaid = -1, $netmode = -1, $gameid = -1, $gameareaid = -1) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$where = "";
		if ($areaid >= 0) {
			$where .= " and areaid = $areaid ";
		}
		if ($netmode >= 0) {
			$where .= " and netmode = $netmode ";
		}
		if ($gameid >= 0) {
			$where .= " and gameid = $gameid ";
		}
		if ($gameareaid >= 0) {
			$where .= " and gameareaid = $gameareaid ";
		}
		
		$sql = "select count(0) c from datadb.speednode where 1=1 $where ";
		return $ETdb->query ( $sql )->row ()->c;
	}
	
	function getEtAreaInfo() {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "select * from iptables.areainfo";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	/*ET加速器节点推送相关————结束*/
}
?>