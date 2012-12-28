<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Data_model extends CI_Model {
	        
	function __construct()
        {
		parent::__construct ();
		$this->load->library('function_class');
                //$TTdatabase = $this->load->database('ttmobile',TRUE);
	}
	
        function fix()
        {
            $TTdb = $this->load->database('ttmobilelogin',TRUE);            
            /*$sql = "select a.u from (select distinct fuserid u from ".$this->function_class->fixTTLoginTable("20120210","fuserid,Flogintime,Flogouttime").
                   " where Flogintime <= 1328844000 and Flogouttime >= 1328844000 and Flogouttime<= 1328846400) as a left join
                     (select distinct fuserid u from ".$this->function_class->fixTTLoginTable("20120210","fuserid,Flogintime,Flogouttime").
                   " where Flogintime > 1328846400) as b on a.u = b.u where b.u IS NULL";*/
            $sql = "select * from db_user_login_reg_20120210.t_tmp";
            
            $rst = $TTdb->query($sql)->result_array();
            for($i=0,$c=count($rst);$i<$c;$i++)
            {
                echo $rst[$i]["u"]."<br/>";
            }
           
        }
        
	function createLoginUser($d)
	{
                $TTdb = $this->load->database('ttmobilelogin',TRUE);               
                               
                $sql = "select distinct fuserid from ".$this->function_class->fixTTLoginTable($d,"fuserid");                
		$u = $TTdb->query($sql)->result_array();
                
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$sql = "use db_user_login_service_$d";
		$TTdb->query($sql);
                
                $sql = "CREATE TABLE `t_distinct_user` (
                            `fuserid` bigint(20) unsigned NOT NULL DEFAULT '0',
                            PRIMARY KEY (`fuserid`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
                $TTdb->query($sql);
                
                for($i=0,$c=count($u);$i<$c;$i++)
                {
                    $sql = "insert into t_distinct_user(fuserid) values(".$u[$i]["fuserid"].");";
                    $TTdb->query($sql);                    
                }
		/*$sql = "create table `t_distinct_user` select distinct fuserid from ".$this->function_class->fixTTLoginTable($d,"fuserid");		
		$TTdb->query($sql);
		
		$sql = "alter table `t_distinct_user` add primary key(fuserid) ";
		$TTdb->query($sql);*/
	}
	
	function createLoginCode($d)
	{
                $TTdb = $this->load->database('ttmobilelogin',TRUE);
            
                $sql = "select distinct Fmachinecode from ".$this->function_class->fixTTLoginTable($d,"Fmachinecode");           
		$m = $TTdb->query($sql)->result_array();
		
                $TTdb = $this->load->database('ttmobile',TRUE);
		
		$sql = "use db_user_login_service_$d";
		$TTdb->query($sql);
		
                $sql = "CREATE TABLE `t_distinct_code` (
                            `Fmachinecode` varchar(32) NOT NULL DEFAULT '0',
                            PRIMARY KEY (`Fmachinecode`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
                $TTdb->query($sql);
                
                for($i=0,$c=count($m);$i<$c;$i++)
                {
                    $sql = "insert into t_distinct_code(Fmachinecode) values('".$m[$i]["Fmachinecode"]."');";
                    $TTdb->query($sql);                    
                }
                
		/*$sql = "create table `t_distinct_code` select distinct Fmachinecode from ".$this->function_class->fixTTLoginTable($d,"fuserid");		
		$TTdb->query($sql);
		
		$sql = "alter table `t_distinct_code` add primary key(Fmachinecode) ";
		$TTdb->query($sql);*/
	}
	
	//获取每5分钟在线人数
	function getOnlieUserCount($date)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$date = date("Ymd",strtotime($date));
		$sql = "select t,if(sum(ucount)>40000000,1,sum(ucount)) c from (select fserverid, max(Fusercount) ucount,FROM_UNIXTIME(floor(ftime/300)*300) t from 
                        (select Fserverid,Fusercount,ftime from db_user_login_service_$date.t_online_user_count order by ftime,fserverid) as T group by t,fserverid) as T2 group by t";
		return $TTdb->query($sql)->result_array();
	}
	
	//在线人数报警数据
	function getAlertOnlieUserCount()
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$date = date("Ymd");
		$sql = "select now() now, max(Fusercount) ucount,FROM_UNIXTIME(floor(ftime/300)*300) t from 
			(select Fserverid,Fusercount,ftime from db_user_login_service_$date.t_online_user_count order by ftime,fserverid) as T group by t,fserverid order by t desc limit 2";
		return $TTdb->query($sql)->result_array();
	}
	
	//获取某天在线峰值
	function getPeakOnline($date)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$date = date("Ymd",strtotime($date));
		$sql = "select fusercount c,ftime from db_user_login_service_$date.t_online_user_count order by fusercount desc limit 1;";
		return $TTdb->query($sql)->row();
	}
	
	//获取每天登陆人数,次数
	function getLoginCount($date)
        {
		$TTdb = $this->load->database('ttmobilelogin',TRUE);
		$d = date("Ymd",strtotime($date));
		$sql = "select count(distinct fuserid) dc,count(0) c from ".$this->function_class->fixTTLoginTable($d,"Floginresult,fuserid")." where Floginresult = 1";
		return $TTdb->query($sql)->row();
	}
        
        //获取某段时间内登陆人数,次数
	function getLoginCountByDays($date,$days)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select count(distinct fuserid) dc from (";
                for($i=0;$i<$days;$i++)
                {                    
                    $d = date("Ymd",strtotime($date)-$i*24*60*60);                    
                    $sql .= " select fuserid from db_user_login_service_$d.t_distinct_user ";
                    if($i<($days-1))
                    {
                        $sql .= " union all ";
                    }
                }
                $sql .= ") as T ";
		return $TTdb->query($sql)->row()->dc;
	}                
        
	//获取截止到当前的总注册人数
	function getRegCount($time = 0)
        {
                $whereTime = "";
                if($time != 0)
                {
                    $whereTime = " and Flogintime < ".($time+24*60*60);
                }                
		/*$TTdb = $this->load->database('ttmobile',TRUE);		
		$sql = "select count(distinct fmobile) c from db_background_service.t_register_list where Fresult = 0";
		return $TTdb->query($sql)->row()->c;*/
                $TTdb = $this->load->database('ttmobile',TRUE);		
		$sql = "select count(0) c from db_background_service.t_new_user_login where 1 = 1 $whereTime";
		return $TTdb->query($sql)->row()->c;                
	}
	
	//每5分钟注册用户数
	function getRegCountPer5($date)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		//$d = strtotime(date("Y-m-d 00:00:00",strtotime($date)));
                $d = strtotime($date);
		$d2 = $d + 24 * 60 * 60;
		
		/*$sql = "select t,count(0) c from (select FROM_UNIXTIME(floor(fregistertime/300)*300) t
                        from db_background_service.t_register_list where Fresult = 0 and Fregistertime >= $d and Fregistertime < $d2) as T group by t order by t";
                */
                $sql = "select t,count(0) c from (select FROM_UNIXTIME(floor(Flogintime/300)*300) t
                        from db_background_service.t_new_user_login where Flogintime >= $d  and Flogintime < $d2) as T group by t order by t";
                
		return $TTdb->query($sql)->result_array();
	}
	
	//某天的注册用户数 
	function getRegCountByDay($date)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$d = strtotime(date("Y-m-d 00:00:00",strtotime($date)));
		$d2 = $d+24*60*60;
		
		$sql = "select count(distinct fmobile) c from db_background_service.t_register_list where Fresult = 0 and Fregistertime >= $d and Fregistertime < $d2";
		return $TTdb->query($sql)->row()->c;
	}
	
	//某天新增用户数(用户第一次登陆)
	function getNewUserLogin($date)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$d = strtotime($date);
		$d2 = $d+24*60*60;
		
		$sql = "select count(0) c from db_background_service.t_new_user_login where Flogintime >= $d  and Flogintime < $d2";
		return $TTdb->query($sql)->row()->c;
	}
	
	//实时激活数据
	function getDevinfoData($date,$devid=-1)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sqlDev = "";
		if($devid>=0)
		{
			$sqlDev = " and fdevid = $devid ";

		}
		$d = date("Ymd",strtotime($date));		
		$sql = "select t,count(0) c from
		       (select fcode,from_unixtime(floor(unix_timestamp(min(fregtime))/300)*300) t  from db_devinfo_$d.t_devinfo_list
		        where 1=1 and fregtime between '$date 00:00:00' and '$date 23:59:59'  $sqlDev group by fcode) as T group by t order by t";
		return $TTdb->query($sql)->result_array();
	}
	
	/*定时综合统计相关数据————开始*/
	function getCommonData()
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		
		$date = date("Y-m-d",time()-24*60*60);
		$d = date("Ymd",time()-24*60*60);		
					
		$sql = "insert into db_background_service.t_statistic_commondata(date_id) values('$date')";
		$TTdb->query($sql);
		
		$peakData = $this->getPeakOnline($date);
		$loginCount = $this->getLoginCount($date);
		$newLoginCount = $this->getNewUserLogin($date);
		$regCount = $this->getRegCountByDay($date);
                $userCount = $this->getRegCount(strtotime($date));
                $loginCount3 = $this->getLoginCountByDays($date,3);
		$loginCount7 = $this->getLoginCountByDays($date,7);
		$loginCount15 = $this->getLoginCountByDays($date,15);
		$loginCount30 = $this->getLoginCountByDays($date,30);
		
		$sql = "update db_background_service.t_statistic_commondata set onlinePeak = ".$peakData->c.",
                                                                        onlinePeakTime = '".date("Y-m-d H:i:s",$peakData->ftime)."',
                                                                        loginCount = ".$loginCount->dc.",
                                                                        loginCount3 = ".$loginCount3.",
                                                                        loginCount7 = ".$loginCount7.",
                                                                        loginCount15 = ".$loginCount15.",
                                                                        loginCount30 = ".$loginCount30.",
                                                                        loginNum = ".$loginCount->c.",
                                                                        newLoginCount = $newLoginCount,
                                                                        userCount = $userCount,
                                                                        regCount = ".$regCount.",
									regfail = (select count(distinct mcode) c from db_background_service.t_userreg_fail where ctime
									              between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59')),
									invitesucc = (select count(0) from db_background_service.t_invite_list
										      where createtime between unix_timestamp('$date 00:00:00') and unix_timestamp('$date 23:59:59'))								
									where date_id = '$date'";
		
		$TTdb->query($sql);
                /*
                activeuser = (select m.c1-n.c2 from (select '1' a,count(distinct fcode) c1 from db_devinfo_$d.t_devinfo_list) m left join 
										      (select count(0) c2,'1' a from (select distinct fcode from db_devinfo_$d.t_devinfo_list) a
										       inner join db_background_service.t_new_imei_code b on a.fcode = b.Fimeicode
										       where b.fregtime < unix_timestamp('$date 00:00:00')) n on m.a=n.a),
									activeip = (select count(distinct fdevip) c from db_devinfo_$d.t_devinfo_list)
                */
	}
	
	function showCommonData()
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from (select * from db_background_service.t_statistic_commondata order by date_id desc limit 90) as T order by date_id";
		
		return $TTdb->query($sql)->result_array();
	}
	/*定时综合统计相关数据————结束*/
	
	/*用户流失定时统计————开始*/
	//周期流失
	function getUserLose($t)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$date = date ("Y-m-d",$t);
		$d = date("Ymd",$t);
                $d1 = $t;
                $d2 = $t+24*60*60;
		
		$sql = "delete from db_background_service.t_statistic_userlose_tempuserid";
		$TTdb->query($sql);
		
                $sql = "insert into db_background_service.t_statistic_userlose_tempuserid(userid)
                        select fuserid uid from db_background_service.t_new_user_login
                        where Flogintime >= $d1  and Flogintime < $d2 ";
		$TTdb->query($sql);
		
		$sql = "insert into db_background_service.t_statistic_userlose(date_id) values('$date')";
		$TTdb->query($sql);
		
		$sql = "update db_background_service.t_statistic_userlose set login =
                        (select count(*) counts from db_background_service.t_statistic_userlose_tempuserid)
                        where date_id='$date'; ";
		$TTdb->query($sql);		
		
		/*处理3天流失数据*/
		$date3 = date("Y-m-d",$t-3*24*60*60);
		$login3 = "";
		for($i=0; $i<3; $i++)
                {
			$d3 = date("Ymd", $t-$i*24*60*60);
			$login3 .= " select fuserid from db_user_login_service_$d3.t_distinct_user ";
			if ($i != 2)
                        {
				$login3 .= " union all ";
			}
		}
		$sql = "update db_background_service.t_statistic_userlose set d3 = login -
                        (select count(*) counts from
                         (select  T.fuserid from db_background_service.t_new_user_login as T
                          inner join (select distinct fuserid from ($login3) as u where fuserid!=0) as T2 on T.fuserid = T2.fuserid
                          where T.Flogintime >= ($t-3*24*60*60) and T.Flogintime < ($t-2*24*60*60)) as A) 
                        where date_id = '$date3'";
		$TTdb->query($sql);		
		
		/*处理7天流失数据*/
		$date7 = date("Y-m-d",$t-7*24*60*60);
		$login7 = "";
		for($i=0; $i<7; $i++)
                {
			$d7 = date("Ymd", $t-$i*24*60*60);
			$login7 .= " select fuserid from db_user_login_service_$d7.t_distinct_user ";
			if ($i != 6)
                        {
				$login7 .= " union all ";
			}
		}
		$sql = "update db_background_service.t_statistic_userlose set d7 = login -
                        (select count(*) counts from
                         (select  T.fuserid from db_background_service.t_new_user_login as T
                          inner join (select distinct fuserid from ($login7) as u where fuserid!=0) as T2 on T.fuserid = T2.fuserid
                          where T.Flogintime >= ($t-7*24*60*60) and T.Flogintime < ($t-6*24*60*60)) as A) 
                        where date_id = '$date7'";
		$TTdb->query ( $sql );
		
		/*处理15天流失数据*/
		$date15 = date("Y-m-d",$t-15*24*60*60);
		$login15 = "";
		for($i = 0; $i < 15; $i++)
                {
			$d15 = date("Ymd", $t-$i*24*60*60);
			$login15 .= " select fuserid from db_user_login_service_$d15.t_distinct_user ";
			if ($i != 14)
                        {
				$login15 .= " union all ";
			}
		}
		$sql = "update db_background_service.t_statistic_userlose set d15 = login -
                        (select count(*) counts from
                         (select  T.fuserid from db_background_service.t_new_user_login as T
                          inner join (select distinct fuserid from ($login15) as u where fuserid!=0) as T2 on T.fuserid = T2.fuserid
                          where T.Flogintime >= ($t-15*24*60*60) and T.Flogintime < ($t-14*24*60*60)) as A) 
                        where date_id = '$date15'";
		$TTdb->query($sql);
		
		/*处理30天流失数据*/
		$date30 = date("Y-m-d",$t-30*24*60*60);
		$login30 = "";
		for($i=0; $i<30; $i++)
                {
			$d30 = date("Ymd",$t-$i*24*60*60);
			$login30 .= " select fuserid from db_user_login_service_$d30.t_distinct_user ";
			if ($i != 29)
                        {
				$login30 .= " union all ";
			}
		}
		$sql = "update db_background_service.t_statistic_userlose set d30 = login -
                        (select count(*) counts from
                         (select  T.fuserid from db_background_service.t_new_user_login as T
                          inner join (select distinct fuserid from ($login30) as u where fuserid!=0) as T2 on T.fuserid = T2.fuserid
                          where T.Flogintime >= ($t-30*24*60*60) and T.Flogintime < ($t-29*24*60*60)) as A) 
                        where date_id = '$date30'";
		$TTdb->query($sql);
	}
	
	function showUserLose()
        {
		$TTdb = $this->load->database ('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_userlose order by date_id desc";
		return $TTdb->query($sql)->result_array();
	}
	
	function getUserLoseSp($t,$spid)
        {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$date = date ("Y-m-d",$t);
		$d = date("Ymd",$t);
                $d1 = $t;
                $d2 = $t+24*60*60;	
				
		$sql = "delete from db_background_service.t_statistic_userlose_sp$spid where date_id = '$date'";
		$TTdb->query($sql);
		$sql = "insert into db_background_service.t_statistic_userlose_sp$spid(date_id) values('$date')";
		$TTdb->query($sql);
		
		$sql = "update db_background_service.t_statistic_userlose_sp$spid set login =
                        (select count(0) c from db_background_service.t_new_user_login
                        where Flogintime >= $d1  and Flogintime < $d2 and Fsourceid=$spid)
                        where date_id='$date'; ";
		$TTdb->query($sql);		
	}
	
	//用户流失
	function getCommonUserLose($date)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$d = date("Ymd",strtotime($date));
				
		$sql = "insert into db_background_service.t_statistic_commonuserlose(date_id) values('$date')";
		$TTdb->query ( $sql );
		
		$sql = "update db_background_service.t_statistic_commonuserlose set login =
                        (select count(0) counts from db_user_login_service_$d.t_distinct_user)
                        where date_id='$date'; ";
		$TTdb->query ( $sql );
		
		for($i = 1; $i <= 30; $i ++)
                {
			$date2 = date("Y-m-d",strtotime($date)-$i*24*60*60);
			$d2 = date("Ymd",strtotime($date)-$i*24*60*60);
			
			$sql = "update db_background_service.t_statistic_commonuserlose set d$i = login -
                                (select count(*) counts from db_user_login_service_$d.t_distinct_user as T
                                 inner join db_user_login_service_$d2.t_distinct_user T2
                                 on T.fuserid = T2.fuserid)
                                 where date_id='$date2'; ";
			$TTdb->query($sql);
		}
	}
	
	function showCommonUserLose() {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_commonuserlose order by date_id desc";
		return $TTdb->query ( $sql )->result_array ();
	}
	
	//新手流失
	function getNewUserLose($t)
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$date = date ("Y-m-d",$t);
		$d = date("Ymd",$t);
                $d1 = $t;
                $d2 = $t+24*60*60;
			
		$sql = "insert into db_background_service.t_statistic_newuserlose(date_id) values('$date')";
		$TTdb->query ( $sql );
		
		$sql = "update db_background_service.t_statistic_newuserlose set login =
                        (select count(0) counts from db_background_service.t_new_user_login
                        where Flogintime >= $d1  and Flogintime < $d2)
                        where date_id='$date'; ";			
		$TTdb->query ( $sql );
		
		for($i = 1; $i <= 30; $i ++)
                {
			$date2 = date("Y-m-d",strtotime($date)-$i*24*60*60);
			$dd1 = $t-($i-1)*24*60*60;
			$dd2 = $t-($i)*24*60*60;
			
			$sql = "update db_background_service.t_statistic_newuserlose set d$i = login -
                                (select count(*) counts from db_background_service.t_new_user_login  T
                                 inner join db_user_login_service_$d.t_distinct_user T2
                                 on T.fuserid = T2.fuserid where T.Flogintime >= $dd2  and T.Flogintime < $dd1 )
                                 where date_id='$date2'; ";			
			//echo $sql."<br/>";
			$TTdb->query($sql);
		}
	}
	
	function showNewUserLose() {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_newuserlose order by date_id desc";
		return $TTdb->query ( $sql )->result_array ();
	}
	
	//渠道新手流失
	function createNewUserLoseBySpTable($spid)
	{		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "use db_background_service;";
		$TTdb->query($sql);
		$sql = "select count(0) c from `INFORMATION_SCHEMA`.`TABLES` where `TABLE_NAME`='t_statistic_newuserlose_sp$spid';";		
		$t = $TTdb->query($sql)->row()->c;
		if($t == 0)
		{
			$sql = "CREATE TABLE `t_statistic_newuserlose_sp$spid` (
					`date_id` date NOT NULL,
					`login` int(10) unsigned DEFAULT '0',
					`d1` int(10) unsigned DEFAULT '0',
					`d2` int(10) unsigned DEFAULT '0',
					`d3` int(10) unsigned DEFAULT '0',
					`d4` int(10) unsigned DEFAULT '0',
					`d5` int(10) unsigned DEFAULT '0',
					`d6` int(10) unsigned DEFAULT '0',
					`d7` int(10) unsigned DEFAULT '0',
					`d8` int(10) unsigned DEFAULT '0',
					`d9` int(10) unsigned DEFAULT '0',
					`d10` int(10) unsigned DEFAULT '0',
					`d11` int(10) unsigned DEFAULT '0',
					`d12` int(10) unsigned DEFAULT '0',
					`d13` int(10) unsigned DEFAULT '0',
					`d14` int(10) unsigned DEFAULT '0',
					`d15` int(10) unsigned DEFAULT '0',
					`d16` int(10) unsigned DEFAULT '0',
					`d17` int(10) unsigned DEFAULT '0',
					`d18` int(10) unsigned DEFAULT '0',
					`d19` int(10) unsigned DEFAULT '0',
					`d20` int(10) unsigned DEFAULT '0',
					`d21` int(10) unsigned DEFAULT '0',
					`d22` int(10) unsigned DEFAULT '0',
					`d23` int(10) unsigned DEFAULT '0',
					`d24` int(10) unsigned DEFAULT '0',
					`d25` int(10) unsigned DEFAULT '0',
					`d26` int(10) unsigned DEFAULT '0',
					`d27` int(10) unsigned DEFAULT '0',
					`d28` int(10) unsigned DEFAULT '0',
					`d29` int(10) unsigned DEFAULT '0',
					`d30` int(10) unsigned DEFAULT '0',
					PRIMARY KEY (`date_id`)
				      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			$TTdb->query($sql);
		}
	}
	
	function getNewUserLoseBySp($t,$spid)
	{
		set_time_limit(0);
		$this->createNewUserLoseBySpTable($spid);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$date = date ("Y-m-d",$t);
		$d = date("Ymd",$t);
                $d1 = $t;
                $d2 = $t+24*60*60;
		
		$spsql = " = $spid";
		if($spid == 600)
		{
			$spsql = " >= $spid";	
		}
		$sql = "delete from db_background_service.t_statistic_newuserlose_sp$spid where date_id = '$date'";
		$TTdb->query($sql);
		
		$sql = "insert into db_background_service.t_statistic_newuserlose_sp$spid(date_id) values('$date')";
		$TTdb->query($sql);
		
		$sql = "update db_background_service.t_statistic_newuserlose_sp$spid set login =
                        (select count(0) counts from db_background_service.t_new_user_login
                        where Fsourceid $spsql and Flogintime >= $d1  and Flogintime < $d2)
                        where date_id='$date'; ";			
		$TTdb->query($sql);
		
		for($i = 1; $i <= 30; $i ++)
                {
			$date2 = date("Y-m-d",strtotime($date)-$i*24*60*60);
			$dd1 = $t-($i-1)*24*60*60;
			$dd2 = $t-($i)*24*60*60;
			
			$sql = "update db_background_service.t_statistic_newuserlose_sp$spid set d$i = login -
                                (select count(*) counts from db_background_service.t_new_user_login  T
                                 inner join db_user_login_service_$d.t_distinct_user T2
                                 on T.fuserid = T2.fuserid where T.Fsourceid $spsql and T.Flogintime >= $dd2  and T.Flogintime < $dd1 )
                                 where date_id='$date2'; ";			
			//echo $sql."<br/>";
			$TTdb->query($sql);
		}
	}
	
	function showNewUserLoseBySp($spid) {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_newuserlose_sp$spid order by date_id desc";
		return $TTdb->query ( $sql )->result_array();
	}
	
	//渠道新手流失按机器码
	function createNewUserLoseBySpTableByCode($spid)
	{		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "use db_background_service;";
		$TTdb->query($sql);
		$sql = "select count(0) c from `INFORMATION_SCHEMA`.`TABLES` where `TABLE_NAME`='t_statistic_newuserlose_sp".$spid."_bycode';";		
		$t = $TTdb->query($sql)->row()->c;
		if($t == 0)
		{
			$sql = "CREATE TABLE `t_statistic_newuserlose_sp".$spid."_bycode` (
					`date_id` date NOT NULL,
					`login` int(10) unsigned DEFAULT '0',
					`d1` int(10) unsigned DEFAULT '0',
					`d2` int(10) unsigned DEFAULT '0',
					`d3` int(10) unsigned DEFAULT '0',
					`d4` int(10) unsigned DEFAULT '0',
					`d5` int(10) unsigned DEFAULT '0',
					`d6` int(10) unsigned DEFAULT '0',
					`d7` int(10) unsigned DEFAULT '0',
					`d8` int(10) unsigned DEFAULT '0',
					`d9` int(10) unsigned DEFAULT '0',
					`d10` int(10) unsigned DEFAULT '0',
					`d11` int(10) unsigned DEFAULT '0',
					`d12` int(10) unsigned DEFAULT '0',
					`d13` int(10) unsigned DEFAULT '0',
					`d14` int(10) unsigned DEFAULT '0',
					`d15` int(10) unsigned DEFAULT '0',
					`d16` int(10) unsigned DEFAULT '0',
					`d17` int(10) unsigned DEFAULT '0',
					`d18` int(10) unsigned DEFAULT '0',
					`d19` int(10) unsigned DEFAULT '0',
					`d20` int(10) unsigned DEFAULT '0',
					`d21` int(10) unsigned DEFAULT '0',
					`d22` int(10) unsigned DEFAULT '0',
					`d23` int(10) unsigned DEFAULT '0',
					`d24` int(10) unsigned DEFAULT '0',
					`d25` int(10) unsigned DEFAULT '0',
					`d26` int(10) unsigned DEFAULT '0',
					`d27` int(10) unsigned DEFAULT '0',
					`d28` int(10) unsigned DEFAULT '0',
					`d29` int(10) unsigned DEFAULT '0',
					`d30` int(10) unsigned DEFAULT '0',
					PRIMARY KEY (`date_id`)
				      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			$TTdb->query($sql);
		}
	}
	
	function getNewUserLoseBySpByCode($t,$spid)
	{
		set_time_limit(0);
		$this->createNewUserLoseBySpTableByCode($spid);
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		$date = date ("Y-m-d",$t);
		$d = date("Ymd",$t);
                $d1 = $t;
                $d2 = $t+24*60*60;
		
		$spsql = " = $spid";
		if($spid == 600)
		{
			$spsql = " >= $spid";	
		}
		$sql = "delete from db_background_service.t_statistic_newuserlose_sp".$spid."_bycode where date_id = '$date'";
		$TTdb->query($sql);
		
		$sql = "insert into db_background_service.t_statistic_newuserlose_sp".$spid."_bycode(date_id) values('$date')";
		$TTdb->query($sql);
		
		$sql = "update db_background_service.t_statistic_newuserlose_sp".$spid."_bycode set login =
                        (select count(0) counts from db_background_service.t_mcode_source
                        where sid $spsql and t >= $d1  and t < $d2)
                        where date_id='$date'; ";			
		$TTdb->query($sql);
		
		for($i = 1; $i <= 30; $i ++)
                {
			$date2 = date("Y-m-d",strtotime($date)-$i*24*60*60);
			$dd1 = $t-($i-1)*24*60*60;
			$dd2 = $t-($i)*24*60*60;
			
			$sql = "update db_background_service.t_statistic_newuserlose_sp".$spid."_bycode set d$i = login -
                                (select count(*) counts from db_background_service.t_mcode_source  T
                                 inner join db_user_login_service_$d.t_distinct_code T2
                                 on T.mcode = T2.Fmachinecode where T.sid $spsql and T.t >= $dd2  and T.t < $dd1 )
                                 where date_id='$date2'; ";			
			//echo $sql."<br/>";
			$TTdb->query($sql);
		}
	}
	
	function showNewUserLoseBySpByCode($spid) {
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_newuserlose_sp".$spid."_bycode order by date_id desc";
		return $TTdb->query ( $sql )->result_array();
	}	
	/*用户流失定时统计————结束*/
	
        /*获取每天各版本登录人数————开始*/
        function getVersionLoginCount($date)
        {            
                $d1 = date("Ymd",strtotime($date));
                
                $TTdb = $this->load->database('ttmobilelogin',TRUE);
		$sql = "select '$date' d,fclientverid,count(0) c,count(distinct fuserid) u from
                        ".$this->function_class->fixTTLoginTable($d1,"fclientverid,fuserid")." group by fclientverid";
                $rst = $TTdb->query($sql)->result_array();  
                    
		$TTdb = $this->load->database('ttmobile',TRUE);
                for($i=0,$c=count($rst);$i<$c;$i++)
                {
                    $sql = "insert into db_background_service.t_statistic_loginversion(date_id,ver,c,u)
                            values('".$rst[$i]["d"]."',".$rst[$i]["fclientverid"].",".$rst[$i]["c"].",".$rst[$i]["u"].")";
                       
                    $TTdb->query($sql);
                }
		
        }
        
        function showLoginVersion($date)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_loginversion where date_id = '$date' order by ver desc";
		return $TTdb->query($sql)->result_array();            
        }        
        /*获取每天各版本登录人数————结束*/
        
        /*获取各周期天各版本登录人数————开始*/
        function getVersionLoginCountByDays($date,$days,$month)
        {            
		$TTbackdb = $this->load->database('ttmobileback',TRUE);
                
                $d1 = date("Y-m-d",strtotime($date));
                $d2 = date("Y-m-d",strtotime($date) - ($days-1)*24*60*60);
		$sql = "select '$month' m,fclientverid,'$d2~$d1' period,'$days' days,count(0) c,count(distinct fuserid) u from
                        (";
                for($i=0;$i<$days;$i++)
                {
                    $d = date("Ymd",strtotime($date)-$i*24*60*60);
                    $sql .= " select fclientverid,fuserid from ".$this->function_class->fixTTLoginTable($d,"fclientverid,fuserid");
                    if($i<($days-1))
                    {
                        $sql .= "  union all ";
                    }
                }
                $sql .= ") as T group by fclientverid";
		//echo $sql;
		$rsts = $TTbackdb->query($sql)->result_array();
		
		
		$TTdb = $this->load->database('ttmobile',TRUE);
		for($i=0;$i<count($rsts);$i++)
		{			
			$sql = "insert into db_background_service.t_statistic_loginversion_month
				select '".$rsts[$i]["m"]."' m,'".$rsts[$i]["fclientverid"]."' fclientverid,
				       '".$rsts[$i]["period"]."' period,'".$rsts[$i]["days"]."' days,
				       '".$rsts[$i]["c"]."'c,'".$rsts[$i]["u"]."' u";
			$TTdb->query($sql);
		}
        }
        
        function showLoginVersionByDays($month)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_loginversion_month where month_id = '$month' order by period desc, month_id,period,ver";
		return $TTdb->query($sql)->result_array();            
        }        
        /*获取每天各版本登录人数————结束*/
        
        
        /*手机通通渠道数据————开始*/
        function getSpData($date)
        {
                $d1 = date("Y-m-d",$date);
                $d2 = date("Y-m-d 00:00:00",$date);
                $d3 = date("Y-m-d 00:00:00",$date+24*60*60);
                                
                $TTdb = $this->load->database('ttmobile',TRUE);              
               
                
                $sql = "delete from db_background_service.t_mobile_source";                        
                $TTdb->query($sql);
                
                $sql = "insert into db_background_service.t_mobile_source
                        select a.Fmobile,a.fcodeid,a.Fregistertime from db_background_service.t_register_list a,
                        (select min(Fopid) fopid,min(Fregistertime) d from db_background_service.t_register_list group by Fmobile) b
                        where a.fopid = b.fopid and a.Fregistertime = b.d;";                        
                $TTdb->query($sql);
                
                $sql = "insert into db_background_service.t_statistic_spdata(date_id,sid,regbymcode,regbymobile) 
                        select a.d,a.sid,ifnull(a.c,0) rbymcode,ifnull(b.c,0) rbymobile from (select '$d1' d, sid,count(0) c from db_background_service.t_mcode_source
                        where t between unix_timestamp('$d2') and unix_timestamp('$d3') group by sid) a left join 
                        (select '$d1' d, sid,count(0) c from db_background_service.t_mobile_source
                        where t between unix_timestamp('$d2') and unix_timestamp('$d3') group by sid) b on a.d = b.d and a.sid=b.sid
                        union  
                        select a.d,a.sid,ifnull(a.c,0) rbymcode,ifnull(b.c,0) rbymobile from (select '$d1' d, sid,count(0) c from db_background_service.t_mcode_source
                        where t between unix_timestamp('$d2') and unix_timestamp('$d3') group by sid) a right join 
                        (select '$d1' d, sid,count(0) c from db_background_service.t_mobile_source
                        where t between unix_timestamp('$d2') and unix_timestamp('$d3') group by sid) b on a.d = b.d and a.sid=b.sid";
                $TTdb->query($sql);
                
                /*$sql = "insert into  db_background_service.t_statistic_spdata
                        select '$d1' d, fcodeid,count(0) c,count(distinct Fmobile) rm,count(distinct Fmachinecode) rmc from db_background_service.t_register_list
                        where Fregistertime between unix_timestamp('$d2') and unix_timestamp('$d3') group by fcodeid;";
                $TTdb->query($sql);
                select '$d1' d, fsourceid,count(0) c from db_download_list_$d2.t_download_list group by fsourceid;*/
        }
        
        function showSpData($date,$sid="",$display="",$date2="")
        {
                if($date2 == "")
                {
                    $date2 = $date;
                }

                $d = " date_id between '$date' and '$date2' ";                
                
                if($sid != "")
                {
                    $sid = " and sid in ($sid) ";
                }
                if($display != "")
                {
                    $display = " and display = $display";
                }
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select * from db_background_service.t_statistic_spdata where $d $sid $display order by date_id desc,sid";
                return $TTdb->query($sql)->result_array();
        }
        
        function showSpDataOut($sid="",$display="")
        {
                if($sid != "")
                {
                    $sid = " and sid in ($sid) ";
                }
                if($display != "")
                {
                    $display = " and display = $display";
                }
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "select * from db_background_service.t_statistic_spdata where 1=1 $sid $display order by date_id desc,sid";
                return $TTdb->query($sql)->result_array();
        }
        
        function updateSpDataDisplay()
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                $sql = "update db_background_service.t_statistic_spdata set display = 1";
                $TTdb->query($sql);
        }
        /*手机通通渠道数据————结束*/        
	
        /*有你提供手机号与通通用户匹配————开始*/
        function import($mobile)
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "insert into db_background_service.t_temp_mobile(mobile) values($mobile)";
		$TTdb->query($sql);
        }
        
        function showimport()
        {
                $TTdb = $this->load->database('ttmobile',TRUE);
                //$sql = " select a.mobile  from db_background_service.t_temp_mobile a inner join db_background_service.t_new_user_login b on a.mobile=b.fuserid ";
                $sql = " select a.mobile mobile from db_background_service.idlist2 a where a.mobile not in (select fuserid from db_background_service.t_new_user_login)";
		return $TTdb->query($sql)->result_array();
        }
        /*有你提供手机号与通通用户匹配————结束*/
        
	/*IPHONE验证——开始*/
	function insertIphoneAuth($dev,$mobile,$authcode,$mac,$carrierName="",$authTime=0)
	{
		$dev = mysql_escape_string($dev);
		$mobile = mysql_escape_string($mobile);
		$authcode = mysql_escape_string($authcode);
		$mac = mysql_escape_string($mac);
		
		$sql = "insert into db_background_service.t_auth_iphone(dev,mobile,authcode,mac,ctime,carrierName,authTime)
			values('$dev',$mobile,'$authcode','$mac',".time().",'$carrierName',$authTime)";
		$TTdb = $this->load->database('ttmobile',TRUE);
		$TTdb->query($sql);
		return $TTdb->insert_id();
	}
	
	function insertIphoneAuthRst($authid,$rst)
	{
		$authid = mysql_escape_string($authid);
		$rst = mysql_escape_string($rst);
		
		$sql = "insert into db_background_service.t_auth_iphone_rst(authid,rst,ctime)
			values($authid,$rst,".time().")";
		$TTdb = $this->load->database('ttmobile',TRUE);
		$TTdb->query($sql);
		return $TTdb->insert_id();
	}
	/*IPHONE验证——结束*/
	
	/*下载统计————开始*/
	function insertDownloadData($date,$clientver,$num)
	{
		$date = mysql_escape_string($date);
		$clientver = mysql_escape_string($clientver);
		$num = mysql_escape_string($num);
		
		$sql = "insert into db_background_service.downloadlog(date,clientver,num)
			values('$date','$clientver',$num)";
		$TTdb = $this->load->database('ttmobile',TRUE);
		$TTdb->query($sql);
		return $TTdb->insert_id();
	}
	/*下载统计————结束*/
	
	/*登录注册按省份获取————开始*/
	function getUserStatisAreaByIp($date,$action,$operator)
	{
		$action = mysql_escape_string($action);
		$operator = mysql_escape_string($operator);
		$d = date("Ymd",strtotime($date));
		
		$sql = "select * from db_user_login_service_$d.t_user_statis_area_by_ip
		        where action_type = $action and operator_type =  $operator order by record_time desc";
		$TTdb = $this->load->database('ttmobile',TRUE);
		return $TTdb->query($sql)->result_array();
	}
	
	function getUserStatisAreaByMobile($date,$action,$operator)
	{
		$action = mysql_escape_string($action);
		$operator = mysql_escape_string($operator);
		$d = date("Ymd",strtotime($date));
		
		$sql = "select * from db_user_login_service_$d.t_user_statis_area_by_phone_number
		        where action_type = $action and operator_type =  $operator order by record_time desc";
		$TTdb = $this->load->database('ttmobile',TRUE);
		return $TTdb->query($sql)->result_array();
	}
	
	function getUserStatisOperatorByIp($date,$action)
	{
		$action = mysql_escape_string($action);
		$d = date("Ymd",strtotime($date));
		
		$sql = "select * from db_user_login_service_$d.t_user_statis_operator_by_ip
		        where action_type = $action  order by record_time desc limit 1";
		$TTdb = $this->load->database('ttmobile',TRUE);
		return $TTdb->query($sql)->result_array();
	}
	
	function getUserStatisOperatorByMobile($date,$action)
	{
		$action = mysql_escape_string($action);
		$d = date("Ymd",strtotime($date));
		
		$sql = "select * from db_user_login_service_$d.t_user_statis_operator_by_phone_number
		        where action_type = $action  order by record_time desc limit 1";
		$TTdb = $this->load->database('ttmobile',TRUE);
		return $TTdb->query($sql)->result_array();
	}
	/*登录注册按省份获取————结束*/
	
	/*系统通话数据————开始*/
	function getSystemCall($date)
	{
		$d = date("Ymd",strtotime($date));
		$TTdb = $this->load->database('ttmobilelogin',TRUE);
		
		$sql = "select sum(Fttmakesyscall) ts,sum(Fsysmakesyscall) ss from db_user_login_reg_$d.t_sys_call_satis where Fttmakesyscall != 4294967295";
		$rst = $TTdb->query($sql)->row();
		$ts = $rst->ts;
		$ss = $rst->ss;
		
		$sql = "select count(0) c from db_user_login_reg_$d.t_sys_call_satis where Fttmakesyscall != 4294967295 and Fttmakesyscall != 0";
		$cts = $TTdb->query($sql)->row()->c;
		
		$sql = "select count(0) c from db_user_login_reg_$d.t_sys_call_satis where Fsysmakesyscall != 4294967295 and Fsysmakesyscall != 0";
		$css = $TTdb->query($sql)->row()->c;
		                
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "delete from db_background_service.t_statistic_systemcalldata where date_id = '$date'";
		$TTdb->query($sql);
		
		$sql = "insert into db_background_service.t_statistic_systemcalldata(date_id,ttmakesyscall,sysmakesyscall,cts,css)
			values('$date',$ts,$ss,$cts,$css)";		
		$TTdb->query($sql);
	}
	
	function showSystemCall()
	{
		$TTdb = $this->load->database('ttmobile',TRUE);
		$sql = "select * from db_background_service.t_statistic_systemcalldata order by date_id desc limit 90";
		return $TTdb->query($sql)->result_array();
	}
	/*系统通话数据————结束*/
	
	
}
?>