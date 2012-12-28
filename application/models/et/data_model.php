<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Data_model extends CI_Model {
	
	function __construct() {
		parent::__construct ();
	}
	
	//获取ET登录人数
	function getLoginDataSum($date, $gameid = 0, $stserverid = 0, $gameareaid = 0, $gamegroupid = 0) {
		$date1 = date ( "Y-m-d 00:00:00", strtotime ( $date ) );
		$ETdb = $this->load->database ( 'et', TRUE );
		
		$sql = "call pr_getLoginDataSum('$date1',$gameid,$stserverid,$gameareaid,$gamegroupid);";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	//获取ET语音人数
	function getTsDataSum($date, $gameid = 0, $tsserverid = 0) {
		$date1 = date ( "Y-m-d 00:00:00", strtotime ( $date ) );
		$ETdb = $this->load->database ( 'et', TRUE );
		
		$sql = "call pr_getTsDataSum('$date1',$gameid,$tsserverid)";
		
		return $ETdb->query ( $sql )->result_array ();
	}
	
	function guild() {
		$ETguilddb = $this->load->database ( 'etguild', TRUE );
		$sql = "select * from guildinfo limit 10";
		return $ETguilddb->query ( $sql )->result ();
	}
	
	/*综合数据统计————开始*/
	function getUserLose() //定时统计用户流失数
{
		$ETdb = $this->load->database ( 'et', TRUE );
		
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d = date ( "ymd", time () - 24 * 60 * 60 );
		
		$sql = "delete from datadb.userlose_tempptid";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.userlose_tempptid(ptid) SELECT distinct PtId FROM logdb.stuserlogin where DATE_FORMAT(LoginTime,'%y%m%d')='$d';";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.userlose(date_id) values('$date')";
		$ETdb->query ( $sql );
		
		$sql = "update datadb.userlose set login =
                (select count(*) counts from datadb.userlose_tempptid)
                where date_id='$date'; ";
		$ETdb->query ( $sql );
		
		for($i = 1; $i <= 30; $i ++) {
			$date2 = date ( "Y-m-d", strtotime ( $date ) - $i * 24 * 60 * 60 );
			$d2 = date ( "ymd", strtotime ( $date ) - $i * 24 * 60 * 60 );
			
			$sql = "update datadb.userlose set d$i = login -
                    (select count(*) counts from datadb.userlose_tempptid as T
                        inner join (SELECT distinct PtId FROM logdb.stuserlogin where DATE_FORMAT(LoginTime,'%y%m%d')='$d2') as T2 on T.ptid = T2.PtId)
                    where date_id='$date2'; ";
			$ETdb->query ( $sql );
		}
	}
	
	function showUserLose()
        {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "select * from datadb.userlose order by date_id desc";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	function getUserLose2point0() //定时统计ET2.0用户流失数
        {
		$ETdb = $this->load->database ( 'et', TRUE );
		
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d = date ( "ymd", time () - 24 * 60 * 60 );
		
		$sql = "delete from datadb.userlose_tempptid_2";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.userlose_tempptid_2(ptid) SELECT distinct PtId FROM logdb.stuserlogin
                        where LoginTime >= '$d' and LoginTime < DATE_ADD('$d',Interval 1 day) and gametypeid = 43 and gameareaid = 2 and gamegroupid = 1;";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.userlose_2(date_id) values('$date')";
		$ETdb->query ( $sql );
		
		$sql = "update datadb.userlose_2 set login =
                        (select count(*) counts from datadb.userlose_tempptid_2)
                        where date_id='$date'; ";
		$ETdb->query ( $sql );
		
		for($i = 1; $i <= 30; $i ++)
                {
			$date2 = date("Y-m-d",strtotime($date) - $i*24*60*60);
			$d2 = date("Y-m-d 00:00:00", strtotime($date) - $i*24*60*60);
			
			$sql = "update datadb.userlose_2 set d$i = login -
                                (select count(*) counts from datadb.userlose_tempptid_2 as T
                                 inner join (SELECT distinct PtId FROM logdb.stuserlogin
                                 where LoginTime >= '$d2' and LoginTime < DATE_ADD('$d2',Interval 1 day) and gametypeid = 43 and gameareaid = 2 and gamegroupid = 1) as T2
                                 on T.ptid = T2.PtId)
                                 where date_id='$date2'; ";
			$ETdb->query($sql);
		}
	}
	
	function showUserLose2point0() {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "select * from datadb.userlose_2 order by date_id desc";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	//获取用户登录数据
	function getLoginCountData($date, $gametypeid = 0, $gameareaid = 0, $gamegroupid = 0) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$gameSql = "";
		if ($gametypeid != 0) {
			$gameSql = " and gametypeid = $gametypeid ";
			
			if ($gameareaid != 0) {
				$gameSql .= " and gameareaid = $gameareaid and gamegroupid = $gamegroupid ";
			}
		}
		
		$date1 = date ( "Y-m-d 00:00:00", strtotime ( $date ) );
		$date2 = date ( "Y-m-d 00:00:00", strtotime ( $date ) + 24 * 60 * 60 );
		
		$sql = "SELECT count(distinct LoginIp) ip,count(distinct PtId) id,count(0) c FROM logdb.stuserlogin
                where loginTime >= '$date1' and loginTime < '$date2' $gameSql";
		return $ETdb->query ( $sql )->row ();
	}
	
	//获取用户在线情况
	function getUserOnlineData($date, $gametypeid = 0, $gameareaid = 0, $gamegroupid = 0) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$date1 = date ( "Y-m-d 00:00:00", strtotime ( $date ) );
		$date2 = date ( "Y-m-d 00:00:00", strtotime ( $date ) + 24 * 60 * 60 );
		
		$sql = "select round(avg(usersum)) avguser,max(usersum) maxuser from (select t2,sum(usercount) usersum from
                (SELECT FROM_UNIXTIME(floor(UNIX_TIMESTAMP(LogTime)/300)*300) t2,
                stid,usercount FROM stusercount_v2 where logTime>='$date1' and logTime<'$date2'
                and gametypeid = $gametypeid and gameareaid = $gameareaid and gamegroupid = $gamegroupid group by t2,stid order by t2,stid) as T group by t2) as T2;";
		//$sql = "call pr_getUserOnlineData('$date1',$gametypeid,$gameareaid,$gamegroupid)";        
		return $ETdb->query ( $sql )->row ();
	}
	
	function getTsUserData($date, $gametypeid = 0, $gameareaid = 0, $gamegroupid = 0) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$date1 = date ( "Y-m-d 00:00:00", strtotime ( $date ) );
		$date2 = date ( "Y-m-d 00:00:00", strtotime ( $date ) + 24 * 60 * 60 );
		
		$gametype = " gametypeid = 0 and hallid = 0 ";
		if ($gametypeid != 0) {
			$gametype = " gametypeid = $gametypeid and gameareaid = $gameareaid and gamegroupid = $gamegroupid";
		}
		
		$sql = "select round(avg(usersum)) avguser,max(usersum) maxuser from (select t2,sum(usercount) usersum from
                (SELECT FROM_UNIXTIME(floor(UNIX_TIMESTAMP(LogTime)/300)*300) t2,
                tsid,usercount FROM tsusercount_v2 where logTime>='$date1' and logTime<'$date2'
                and $gametype
                group by t2,tsid order by t2,tsid) as T group by t2) as T2;";
		
		return $ETdb->query ( $sql )->row ();
	}
	
	//获取创建频道数量
	function getCreateHallData($date, $gametypeid = 0) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$date1 = date ( "Y-m-d 00:00:00", strtotime ( $date ) );
		$date2 = date ( "Y-m-d 00:00:00", strtotime ( $date ) + 24 * 60 * 60 );
		
		$frmgametypeid = "";
		if ($gametypeid != 0) {
			$frmgametypeid = " and frmgametypeid = $gametypeid ";
		}
		
		$sql = "select count(0) c from logdb.stcreatehall where CreateTime>='$date1' and CreateTime<'$date2' $frmgametypeid ";
		return $ETdb->query ( $sql )->row ();
	}
	
	//获取点击加速数据
	function getProxyData($date) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$d = date ( "Ymd", strtotime ( $date ) );
		
		$sql = "SELECT count(0) c,count(distinct ptid) idcount,count(distinct userip) ipcount  FROM logdb_$d.speedinfo ";
		return $ETdb->query ( $sql )->row ();
	}
	
	//定时统计用户登录数据
	function getUserLoginData() {
		$ETdb = $this->load->database ( 'et', TRUE );
		
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		
		$loginData = $this->getLoginCountData ( $date );
		$onlineData = $this->getUserOnlineData ( $date );
		$tsData = $this->getTsUserData ( $date );
		$createHallData = $this->getCreateHallData ( $date );
		
		$sql = "delete from datadb.newuser";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.newuser select ptid,date_format(min(logintime),'%y%m%d') from logdb.stuserlogin group by ptid order by ptid";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.commondata(date_id) values('$date')";
		$ETdb->query ( $sql );
		
		$sql = "update datadb.commondata set loginIP = " . $loginData->ip . ",
                                             loginPtId = " . $loginData->id . ",
                                             loginTimes = " . $loginData->c . ",
                                             avgUser = " . $onlineData->avguser . ",
                                             maxUser = " . $onlineData->maxuser . ",
                                             avgTsUser = " . $tsData->avguser . ",
                                             maxTsUser = " . $tsData->maxuser . ",
                                             createHall = " . $createHallData->c . ",
                                             newuser = (SELECT count(0) FROM datadb.newuser n where logintime = " . date ( "ymd", strtotime ( $date ) ) . ") where date_id = '$date'";
		$ETdb->query ( $sql );
	}
	
	function showUserLoginData() {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "SELECT * FROM datadb.commondata c order by date_id desc;";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	/*定时统计ET2.0综合数据————开始*/
	function getET2point0Data() {
		$ETdb = $this->load->database ( 'et', TRUE );
		
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		
		$loginData = $this->getLoginCountData ( $date, 43, 2, 1 );
		$onlineData = $this->getUserOnlineData ( $date, 43, 2, 1 );
		$tsData = $this->getTsUserData ( $date, 43, 2, 1 );
		$proxyData = $this->getProxyData ( $date );
		
		$sql = "delete from datadb.newuser_2";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.newuser_2 select ptid,date_format(min(logintime),'%y%m%d') from logdb.stuserlogin where gametypeid=43 and gameareaid=2 and gamegroupid=1 group by ptid order by ptid";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.commondata_2(date_id) values('$date')";
		$ETdb->query ( $sql );
		
		$sql = "update datadb.commondata_2 set loginIP = " . $loginData->ip . ",
                                               loginPtId = " . $loginData->id . ",
                                               loginTimes = " . $loginData->c . ",
                                               avgUser = " . $onlineData->avguser . ",
                                               maxUser = " . $onlineData->maxuser . ",
                                               proxyStartCount = " . $proxyData->c . ",
                                               proxyIpCount = " . $proxyData->ipcount . ",
                                               proxyIdCount = " . $proxyData->idcount . ",
                                               newuser = (SELECT count(0) FROM datadb.newuser_2 n where logintime = " . date ( "ymd", strtotime ( $date ) ) . ") where date_id = '$date'";
		$ETdb->query ( $sql );
	}
	
	function showET2point0Data() {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "select * from (SELECT * FROM datadb.commondata_2 c order by date_id desc limit 90) T order by date_id";
		return $ETdb->query ( $sql )->result_array ();
	}
	/*定时统计ET2.0综合数据————结束*/
	
	/*定时统计星辰变用户登录数据——开始*/
	function getXcbUserLoginData() {
		$ETdb = $this->load->database ( 'et', TRUE );
		
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		
		$loginData = $this->getLoginCountData ( $date, 88 );
		$onlineData = $this->getUserOnlineData ( $date, 88 );
		$tsData = $this->getTsUserData ( $date, 88 );
		$createHallData = $this->getCreateHallData ( $date, 88 );
		
		$sql = "delete from datadb.newuser_xcb";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.newuser_xcb select ptid,date_format(min(logintime),'%y%m%d') from logdb.stuserlogin where gametypeid = 88 group by ptid order by ptid";
		$ETdb->query ( $sql );
		
		$sql = "insert into datadb.commondata_xcb(date_id) values('$date')";
		$ETdb->query ( $sql );
		
		$sql = "update datadb.commondata_xcb set loginIP = " . $loginData->ip . ",
                                             loginPtId = " . $loginData->id . ",
                                             loginTimes = " . $loginData->c . ",
                                             avgUser = " . $onlineData->avguser . ",
                                             maxUser = " . $onlineData->maxuser . ",
                                             avgTsUser = " . $tsData->avguser . ",
                                             maxTsUser = " . $tsData->maxuser . ",
                                             createHall = " . $createHallData->c . ",
                                             newuser = (SELECT count(0) FROM datadb.newuser_xcb n where logintime = " . date ( "ymd", strtotime ( $date ) ) . ") where date_id = '$date'";
		$ETdb->query ( $sql );
	}
	
	function showXcbUserLoginData() {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "SELECT * FROM datadb.commondata_xcb c order by date_id desc;";
		return $ETdb->query ( $sql )->result_array ();
	}
	/*定时统计星辰变用户登录数据——结束*/
	
	/*ET2.0版本新用户留存每日定时统计——开始*/
	function getNewUserRetain() {
		$date = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d = date ( "ymd", time () - 24 * 60 * 60 );
		
		$sql = "delete from statistic.user_tempSndaId";
		$this->db->query ( $sql );
		
		$sql = "insert into statistic.user_newRetain(date_id) values('$date')";
		$this->db->query ( $sql );
		
		$sql = "insert into statistic.user_tempSndaId(snda_id)
                (select distinct snda_id from ns_$d.s_cs_user s where operate = 'login') ";
		$this->db->query ( $sql );
		
		$sql = "update statistic.user_newRetain set new =
                (select count(distinct snda_id) FROM speed.s_user_info where date_format(date_add(create_time,interval -8 hour),'%y%m%d')='$d')
                where date_id='$date'; ";
		$this->db->query ( $sql );
		
		for($i = 1; $i <= 30; $i ++) {
			$date2 = date ( "Y-m-d", strtotime ( $date ) - $i * 24 * 60 * 60 );
			$d2 = date ( "ymd", strtotime ( $date ) - $i * 24 * 60 * 60 );
			
			$sql = "update statistic.user_newRetain set d$i = (select count(*) counts from statistic.user_tempSndaId as T
                    inner join (select distinct snda_id FROM speed.s_user_info where date_format(date_add(create_time,interval -8 hour),'%y%m%d')='$d2') as T2
                    on T.snda_id = T2.snda_id)
                    where date_id='$date2'; ";
			$this->db->query ( $sql );
		}
		
		$sql = "delete from statistic.user_tempSndaId";
		$this->db->query ( $sql );
	}
	
	function showNewUserRetain() {
		$sql = "select * from statistic.user_newRetain order by date_id desc";
		return $this->db->query ( $sql )->result_array ();
	}
	/*ET2.0版本新用户留存每日定时统计——结束*/
	
	function getEtWebCountData() //网站点击来源统计
{
		$ETdb = $this->load->database ( 'et', TRUE );
		$date = date ( "Ymd", time () - 24 * 60 * 60 );
		$sql = "insert into datadb.etwebcountdata(`date`,id,name,`count`) select $date,id,name,`count` from infodb.etwebcountinfo;";
		$ETdb->query ( $sql );
		$sql = "update infodb.etwebcountinfo set `count` = 0;";
		$ETdb->query ( $sql );
	}
	/*综合数据统计————结束*/
	
	//频道最大人数查询
	function getHallMaxCount($date) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$date1 = date ( "Y-m-d 00:00:00", strtotime ( $date ) );
		
		$sql = "call pr_getHallMaxCount('$date1')";
		return $ETdb->query ( $sql )->result ();
	}
	
	//频道平均人数查询
	function getHallAvgCount($date) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$date1 = date ( "Y-m-d 00:00:00", strtotime ( $date ) );
		
		$sql = "call pr_getHallAvgCount('$date1')";
		return $ETdb->query ( $sql )->result ();
	}
	
	//单频道每5分钟语音在线
	function getSingleHallOnline($date, $hallid) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$date1 = date ( "Y-m-d 00:00:00", strtotime ( $date ) );
		
		$sql = "call pr_getSingleHallOnline('$date1',$hallid)";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	/*单频道数据定时统计——开始*/
	function getSingleHallData() {
		$ETdb = $this->load->database ( 'et', TRUE );
		$date1 = date ( "Y-m-d 00:00:00", time () - 24 * 60 * 60 );
		$sql = "call pr_getHalldata('$date1')";
		$ETdb->query ( $sql );
	}
	
	function showSingleHallData($hallid, $d1, $d2) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "call logdb.pr_showSingleHallData($hallid,'$d1','$d2')";
		return $ETdb->query ( $sql )->result ();
	}
	/*单频道数据定时统计——结束*/
	
	/*各版本登录情况——开始*/
	function getUserLoginVersion() {
		$d1 = date ( "Y-m-d", time () - 24 * 60 * 60 );
		$d2 = date ( "Y-m-d", strtotime ( $d1 ) + 24 * 60 * 60 );
		
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "insert into datadb.userlogin_version(date_id,version,count,dcount) SELECT '$d1' d,`version`,count(0),count(distinct ptid) FROM logdb.stuserlogin where logintime>='$d1 00:00:00' and  logintime<'$d2 00:00:00'  group by `version` ";
		$ETdb->query ( $sql );
	}
	
	function showUserLoginVersion($date) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$sql = "select conv(version,10,16) v,date_id,version,dcount,count from datadb.userlogin_version where date_id = '$date' order by dcount desc";
		return $ETdb->query ( $sql )->result_array ();
	}
	/*各版本登录情况——结束*/
	
	//ET数据报警
	function getAlertLoginData($id) {
		$ETdb = $this->load->database ( 'et', TRUE );
		$d = date ( "ymd" );
		$sql = "SELECT logtime,usercount FROM stusercount_v2 where stid=$id and gametypeid=43 and gameareaid=2 and gamegroupid = 1 order by id desc limit 2";
		return $ETdb->query ( $sql )->result_array ();
	}
	
	/*统计ET整合版活跃用户数————开始*/
	function getActiveUserCount($d, $d2)
        {
		$ETdb = $this->load->database('et',TRUE);
		$userloginsql = "";
		$sql = "select count(0) c from
                (select distinct ptid from logdb.stuserlogin where logintime >= '$d2' and logintime < '$d') as T";
		return $ETdb->query($sql)->row()->c;	
		//echo $sql;
	}
	
	function getUserActiveData($date, $days, $month)
        {
		$ETdb = $this->load->database('et',TRUE);
		$d = date("Y-m-d",strtotime($date));
		$d2 = date("Y-m-d",strtotime($date) - ($days-1)*24*60*60);
		$rst = $this->getActiveUserCount ( $d, $d2 );
		$sql = "insert into datadb.user_activedata(month_id,peroid,days,users) values('$month','" . $d2 . "~" . $d . "',$days,$rst)";
		$ETdb->query($sql);
	}
	
	function showUserActiveData($month)
        {
		$ETdb = $this->load->database('et',TRUE);
		$sql = "select * from datadb.user_activedata where month_id = '$month' order by days,peroid";
		return $ETdb->query($sql)->result_array();
	}
	/*统计ET整合版活跃用户数————结束*/
        
        /*联合安装————开始*/
        function insertUnionInstall($proid)
        {
                $ETdb = $this->load->database('et',TRUE);
                $proid = mysql_escape_string($proid);
                $sql = "insert into datadb.union_install(sid,t) values($proid,".time().")";
                $ETdb->query($sql);
        }
        /*联合安装————结束*/
	
	function fix() //修复专用
        {
		$ETdb = $this->load->database ( 'et', TRUE );
		
		$proxyData = $this->getProxyData ( "2011-09-12" );
		
		$sql = "update datadb.commondata_2 set 
                                               proxyStartCount = " . $proxyData->c . ",
                                               proxyIpCount = " . $proxyData->ipcount . ",
                                               proxyIdCount = " . $proxyData->idcount . " where date_id = '2011-09-12'";
		$ETdb->query ( $sql );
	}
}

?>