<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Manage_model extends CI_Model
{
	
	function __construct() {
		parent::__construct ();
	}
	
	/*公会临时数据管理————开始*/
	function addGuild($name, $queryid, $area, $server, $rank, $gameid) {
		$name = mysql_escape_string ( $name );
		$queryid = mysql_escape_string ( $queryid );
		$area = mysql_escape_string ( $area );
		$server = mysql_escape_string ( $server );
		$rank = mysql_escape_string ( $rank );
		$gameid = mysql_escape_string ( $gameid );
		
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "insert into infodb.guildinfo(name,queryId,area,server,rank,gameid) values('$name',$queryid,'$area','$server',$rank,$gameid)";
		
		$ETdb->query ( $sql );
	}
	
	function getGuildList($gameid = 0, $pagesize = 0, $pagecount = 0) {
		$gameid = mysql_escape_string ( $gameid );
		
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "SELECT * FROM infodb.guildinfo ";
		if ($gameid > 0) {
			$sql .= " where gameid = $gameid order by rank";
		}
		if ($pagesize > 0 && $pagecount >= 0) {
			$sql .= "limit $pagecount,$pagesize";
		}
		return $ETdb->query ( $sql )->result_array ();
	}
	
	function delGuild($gid) {
		$gid = mysql_escape_string ( $gid );
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "delete from infodb.guildinfo where id = $gid";
		$ETdb->query ( $sql );
	}
	/*公会临时数据管理————结束*/
	
	/*FAQ数据管理————开始*/
	function addFaq($type, $title, $content, $list = 0) {
		$type = mysql_escape_string ( $type );
		$title = mysql_escape_string ( $title );
		$content = mysql_escape_string ( $content );
		$list = mysql_escape_string ( $list );
		
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "insert into infodb.faqinfo(type,title,content,list) values($type,'$title','$content',$list)";
		
		$ETdb->query ( $sql );
	}
	
	function getFaqList($type = 0, $pagesize = 0, $pagecount = 0) {
		$type = mysql_escape_string ( $type );
		
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "SELECT * FROM infodb.faqinfo ";
		if ($type > 0) {
			$sql .= " where type = $type ";
		}
		if ($pagesize > 0 && $pagecount >= 0) {
			$sql .= " limit $pagecount,$pagesize";
		}
		$sql .= " order by list desc";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	function getFaqById($id) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$id = mysql_escape_string ( $id );
		$str = "SELECT * FROM infodb.faqinfo where id = $id";
		return $ETdb->query ( $str )->row_array ();
	}
	
	function editFaq($fid, $type, $title, $content, $list = 0) {
		$type = mysql_escape_string ( $type );
		$title = mysql_escape_string ( $title );
		$content = mysql_escape_string ( $content );
		$list = mysql_escape_string ( $list );
		
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "update infodb.faqinfo set type=$type,title='$title',content='$content',list=$list where id = $fid ";
		
		$ETdb->query ( $sql );
	}
	
	function delFaq($fid) {
		$gid = mysql_escape_string ( $fid );
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "delete from infodb.faqinfo where id = $fid";
		$ETdb->query ( $sql );
	}
	/*FAQ数据管理————结束*/
	
	/*服务器管理————开始*/
	function checkServerId($id, $type) //检查f服务器ID是否存在
	{
		$id = mysql_escape_string ( $id );
		$type = mysql_escape_string ( $type );
		
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "select count(0) countid from infodb.serverinfo where si_serverid = $id and si_type = $type";
		return $ETdb->query ( $sql )->row ()->countid;
	}
	
	function addServer($serverid, $serverip, $serveraddress, $servertype, $servervalid = "1") {
		$serverid = mysql_escape_string ( $serverid );
		$serverip = mysql_escape_string ( $serverip );
		$serveraddress = mysql_escape_string ( $serveraddress );
		$servertype = mysql_escape_string ( $servertype );
		$servervalid = mysql_escape_string ( $servervalid );
		
		if ($this->checkServerId ( $serverid, $servertype ) > 0) {
			return - 1;
		}
		
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "insert into infodb.serverinfo(si_serverid,si_ip,si_address,si_type,si_valid,si_updatetime) values($serverid,'$serverip','$serveraddress',$servertype,$servervalid,'" . date ( "Y-m-d H:i:s" ) . "')";
		try {
			$ETdb->query ( $sql );
			return 0;
		} catch ( Exception $e ) {
			return - 999;
		}
	}
	
	function updateServer($serverid, $serverip, $serveraddress, $servertype, $servervalid = "1", $id, $oldserverid) {
		$serverid = mysql_escape_string ( $serverid );
		$serverip = mysql_escape_string ( $serverip );
		$serveraddress = mysql_escape_string ( $serveraddress );
		$servertype = mysql_escape_string ( $servertype );
		$servervalid = mysql_escape_string ( $servervalid );
		$id = mysql_escape_string ( $id );
		$oldserverid = mysql_escape_string ( $oldserverid );
		
		if ($oldserverid != $serverid) {
			if ($this->checkServerId ( $serverid, $servertype ) > 0) {
				return - 1;
			}
		}
		
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "update infodb.serverinfo set si_serverid = $serverid,si_ip='$serverip',si_address='$serveraddress',si_type=$servertype,
                si_valid=$servervalid,si_updatetime='" . date ( "Y-m-d H:i:s" ) . "' where si_id = $id";
		
		try {
			$ETdb->query ( $sql );
			return 0;
		} catch ( Exception $e ) {
			return - 999;
		}
	}
	
	function getServerList($servertype, $valid = "-1") {
		$valid = "";
		if ($valid != "-1") {
			$vaild = " and si_valid = $valid ";
		}
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "select * from infodb.serverinfo where si_type = $servertype $valid order by si_serverid";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	function getServerListValid($servertype, $valid) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "select * from infodb.serverinfo where si_type = $servertype and si_valid = $valid order by si_serverid";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	function getServerById($id) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "select * from infodb.serverinfo where si_id = $id";
		return $ETdb->query ( $sql )->row_array ();
	}
	/*服务器管理————结束*/
	
	function etserver_getLog($num, $type) //获取操作日志
	{
		$num = mysql_escape_string ( $num );
		$type = mysql_escape_string ( $type );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "SELECT * FROM etmanagedb.m_channelactionlog m where ca_type = '$type' order by ca_id desc limit $num";
		return $ETserverdb->query ( $sql )->result_array ();
	}
	
	//修改频道成功后记录日志
	function changeChannel_addLog($user, $actionType, $content) {
		$date = date ( "Y-m-d H:i:s" );
		$user = mysql_escape_string ( $user );
		$actionType = mysql_escape_string ( $actionType );
		$content = mysql_escape_string ( $content );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "insert into m_channelactionlog(ca_time,ca_useraccount,ca_type,ca_content)
                values('$date','$user','$actionType','$content') ";
		$ETserverdb->query ( $sql );
	}
	
	/*修改频道管理————开始*/
	function changeChannelId_send($cid, $editid) //发送修改请求
	{
		$cid = mysql_escape_string ( $cid );
		$editid = mysql_escape_string ( $editid );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "insert into m_channelidtos(ci_channelid,ci_channeleditid) values($cid,$editid)";
		$ETserverdb->query ( $sql );
		
		$sql = "select @@IDENTITY AS 'Identity' ";
		return $ETserverdb->query ( $sql )->row ()->Identity;
	}
	
	function changeChannelId_receive($ciId) //接收结果
	{
		sleep ( 3 );
		$ciId = mysql_escape_string ( $ciId );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "select ci_result from m_channelidfroms where ci_refid = $ciId ";
		return $ETserverdb->query ( $sql )->row ()->ci_result;
	}
	
	function changeChannelId_del($ciId) //超时删除请求
	{
		$ciId = mysql_escape_string ( $ciId );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "delete from m_channelidtos where ci_id = $ciId ";
		$ETserverdb->query ( $sql );
	}
	/*修改频道管理————结束*/
	
	/*查询频道所在服务器————开始*/
	function getChannelInfo_send($cid) //发送查询请求
	{
		$cid = mysql_escape_string ( $cid );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "insert into m_channelinfotos(ci_channelid) values($cid)";
		$ETserverdb->query ( $sql );
		
		$sql = "select @@IDENTITY AS 'Identity' ";
		return $ETserverdb->query ( $sql )->row ()->Identity;
	}
	
	function getChannelInfo_receive($ciId) //接收结果
	{
		sleep ( 3 );
		$ciId = mysql_escape_string ( $ciId );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "select ci_result from m_channelinfofroms where ci_refid = $ciId ";
		return $ETserverdb->query ( $sql )->row ()->ci_result;
	}
	
	function getChannelInfo_del($ciId) //超时删除请求
	{
		$ciId = mysql_escape_string ( $ciId );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "delete from m_channelinfotos where ci_id = $ciId ";
		$ETserverdb->query ( $sql );
	}
	
	function getChannelInfo_detail($ciId) //获取频道信息
	{
		$ciId = mysql_escape_string ( $ciId );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "select * from guildinfo where refid = $ciId ";
		return $ETserverdb->query ( $sql )->row_array ();
	}
	/*查询频道所在服务器————结束*/
	
	/*修改频道服务器————开始*/
	function changeChannelServer_send($cid, $sid) //发送请求
	{
		$cid = mysql_escape_string ( $cid );
		$sid = mysql_escape_string ( $sid );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "insert into m_channelservertos(cs_channelid,cs_serverid) values($cid,$sid)";
		$ETserverdb->query ( $sql );
		
		$sql = "select @@IDENTITY AS 'Identity' ";
		return $ETserverdb->query ( $sql )->row ()->Identity;
	}
	
	function changeChannelServer_receive($ciId) //接收结果
	{
		sleep ( 3 );
		$ciId = mysql_escape_string ( $ciId );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "select ci_result from m_channelserverfroms where ci_refid = $ciId ";
		return $ETserverdb->query ( $sql )->row ()->ci_result;
	}
	
	function changeChannelServer_del($csId) //超时删除请求
	{
		$csId = mysql_escape_string ( $csId );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "delete from m_channelservertos where cs_id = " . $csId;
		$ETserverdb->query ( $sql );
	}
	/*修改频道服务器————结束*/
	
	/*发布广播————开始*/
	function broadcastAdd_send($title, $content, $time, $count, $span, $type) //发送请求
	{
		$title = mysql_escape_string ( $title );
		$content = mysql_escape_string ( $content );
		$time = mysql_escape_string ( $time );
		$count = mysql_escape_string ( $count );
		$span = mysql_escape_string ( $span );
		$type = mysql_escape_string ( $type );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "insert into m_messagetoserver(ms_title,ms_content,ms_time,ms_count,ms_span,ms_type)
                values('$title','$content','$time',$count,$span,$type)";
		$ETserverdb->query ( $sql );
		
		$sql = "select @@IDENTITY AS 'Identity' ";
		return $ETserverdb->query ( $sql )->row ()->Identity;
	}
	
	function broadcastAdd_receive($msId) {
		sleep ( 3 );
		$msId = mysql_escape_string ( $msId );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "select ms_result from m_messagefromserver where ms_refid = $msId";
		return $ETserverdb->query ( $sql )->row ()->ms_result;
	}
	
	function broadcastAdd_insert($title, $content, $time, $count, $span, $creater, $createdate, $type, $msid) {
		$title = mysql_escape_string ( $title );
		$content = mysql_escape_string ( $content );
		$time = mysql_escape_string ( $time );
		$count = mysql_escape_string ( $count );
		$span = mysql_escape_string ( $span );
		$creater = mysql_escape_string ( $creater );
		$createdate = mysql_escape_string ( $createdate );
		$type = mysql_escape_string ( $type );
		$msid = mysql_escape_string ( $msid );
		
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		$sql = "insert into t_recentmessage(rm_title,rm_content,rm_time,rm_count,rm_span,rm_creater,rm_createdate,rm_type,ms_id)
                values('$title','$content','$time',$count,$span,'$creater','$createdate',$type,$msid) ";
		$ETserverdb->query ( $sql );
		
		return true;
	}
	
	function broadcastAdd_del($msId) {
		$msId = mysql_escape_string ( $msId );
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "delete from m_messagetoserver where ms_id = $msId ";
		$ETserverdb->query ( $sql );
	}
	/*发布广播————结束*/
	
	/*获取广播消息记录————开始*/
	function getCurrentBroadList() //当前有效消息
	{
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "select * from t_recentmessage where date_add(rm_time,interval (rm_count-1)*rm_span minute)>now() and rm_status = 1 order by rm_time desc";
		return $ETserverdb->query ( $sql )->result_array ();
	}
	
	function getHistoryBroadList() //历史消息
	{
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "select * from t_recentmessage where date_add(rm_time,interval (rm_count-1)*rm_span minute)<now() order by rm_time desc limit 20";
		return $ETserverdb->query ( $sql )->result_array ();
	}
	/*获取广播消息记录————结束*/
	
	/*删除广播————开始*/
	function broadcastDel_send($msid) {
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "insert into m_messsagetoserver(ms_rid,ms_actiontype) values($msid,1)";
		$ETserverdb->query ( $sql );
		
		$sql = "select @@IDENTITY AS 'Identity' ";
		return $ETserverdb->query ( $sql )->row ()->Identity;
	}
	
	function broadcastDel_getMsId($rmid) {
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "select ms_id from t_recentmessage where rm_id = $rmid ";
		return $ETserverdb->query ( $sql )->row ()->ms_id;
	}
	
	function broadcastDel_receive($msId) {
		return $this->broadcastAdd_receive ( $msId );
	}
	
	function broadcastDel_delSend($msId) {
		return $this->broadcastAdd_del ( $msId );
	}
	
	function broadcastDel_del($rmid) {
		$ETserverdb = $this->load->database ( 'etserver', TRUE );
		
		$sql = "update t_recentmesssage set rm_status = 0 where rm_id = $rmid ";
		return $ETserverdb->query ( $sql );
	}
	
/*删除广播————结束*/

}
?>