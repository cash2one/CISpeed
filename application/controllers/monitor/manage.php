<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Manage extends CI_Controller
{	
	function __construct()
	{
		parent::__construct ();
		$this->load->model("monitor/manage_model", "Monitor_manage_model");
	}
	
	function index() {}
        
        function soundserver()
        {
            $data["jstips"] = "";
            
            if($this->input->post("add"))
            {
                $id =  $this->input->post("serverid");
                $pwd =  $this->input->post("pwd");
                $ip =  $this->input->post("serverip");
                
                if($id && $pwd && $ip)
                {
                    $this->Monitor_manage_model->addSoundServer($id,$pwd,$ip);
                }
                else
                {
                    $data["jstips"] .= "alert('请完整填写信息')";
                }
            }
            if($this->input->get("type"))
            {
                $type = $this->input->get("type");
                if($type = "del")
                {
                    $id = $this->input->get("id");
                    $this->Monitor_manage_model->delSoundServer($id);
                }
            }
            
            $rst = $this->Monitor_manage_model->showSoundServer();
            $data["list"] = "";
            for($i=0,$c=count($rst);$i<$c;$i++)
            {
                $data["list"] .= "<tr>
                                    <td><a href='?type=del&id=".$rst[$i]["SoundID"]."'>删除</a></td>
                                    <td>".$rst[$i]["SoundID"]."</td>
                                    <td>".$rst[$i]["Passwd"]."</td>
                                    <td>".$rst[$i]["IpAddr"]."</td>
                                  </tr>";
            }
            
            $this->load->view("modules/monitor/manage/soundserver_view.php",$data);
        }
        
	  function generate_userid_list_file()
	  {
	    //连接数据库
	    $db_conn = mysql_connect("localhost:3307","root","passme");
	    if (!$db_conn) {
	  	  die("connect database error!");
	    }
	    
	    //获取当前监控列表版本号
	    $file_ver = 0;
	    $file_ver_rec_noexist = 1;
	    $old_conf_file="./recorder_files/Monitorlist.conf";
	    $query_sql="select * from db_recorder_monitor.t_monitored_userid_file_ver";
	    $result = mysql_query($query_sql,$db_conn);	
	  	if (empty($result)) {  		
	  	  $file_ver = 0;  	  	  	 	  	 
	  	}
	  	else {
	  	  if ($row = mysql_fetch_array($result)) {
	  	    $file_ver = $row["FileVer"];
	  	    $old_conf_file = $row["FilePath"];
	  	    $file_ver_rec_noexist = 0;	  	    
	  	  }	  	  
	  	}
	  	
	  	//读取监控号码列表
	  	$query_sql="select * from db_recorder_monitor.t_sound_server_userid_list";
	    $result = mysql_query($query_sql,$db_conn);	
	  	if (empty($result)) {  		
	  	  mysql_close($db_conn);
	  	  return -1;
	  	}
	  	  	
	  	$new_conf_file="./recorder_files/Monitorlist.conf";
	  	$file = fopen($new_conf_file, "w");
	  	if (!$file) {  		
	  	  return -1;
	  	}
	  	
	  	//先写版本号
	  	$file_ver++;
	  	$buf = "ver=$file_ver\n";
	  	fwrite($file,$buf);  	
	  	
	  	//写入列表
	  	while($row = mysql_fetch_array($result))  {
	  	  $buf = $row["PhoneID"]."\n";
	  	  
	  	  //如果该号码已经没有人监听了的话，删除掉
	  	  $phoneid_cnt = 0;
	  	  $query_sql="select count(*) from db_recorder_monitor.t_manager_monitor_list where PhoneID=$buf";
	      $phoneid_result = mysql_query($query_sql,$db_conn) or die(mysql_error());
	      if ($phoneid_row = mysql_fetch_array($phoneid_result)) {
	  	    $phoneid_cnt = $phoneid_row[0];
	      }

	      if (0==$phoneid_cnt)  {
	      	//从总的监听列表中删除
	        $query_sql="delete from db_recorder_monitor.t_sound_server_userid_list where PhoneID=$buf";
	        mysql_query($query_sql,$db_conn) or die(mysql_error());
	      }
	      else
	  	    fwrite($file,$buf);  	  	
	  	}
	  	
	  	fclose($file);
	  	
	  	//更新版本列表
	  	$now_time = time();
	  	if ($file_ver_rec_noexist)  {
	  	  $query_sql="insert into db_recorder_monitor.t_monitored_userid_file_ver values($file_ver,'$old_conf_file',$now_time)";
	      $result = mysql_query($query_sql,$db_conn) or die(mysql_error());	      	
	  	}
	  	else {
	  	  $query_sql="update db_recorder_monitor.t_monitored_userid_file_ver set FileVer=$file_ver,CreateTime=$now_time";
	      $result = mysql_query($query_sql,$db_conn) or die(mysql_error());	      
	  	}
	  }
  
        function managemonitor()
        {
	    session_start();
            $data["jstips"] = "";
	    $data["adddisplay"] = "inline";
	    
	    if($_SESSION['adminrole'] == 1)
	    {
		 $data["adddisplay"] = "none";		
	    }
	    
            if($this->input->post("add"))
            {
                $manageid =  $this->input->post("manageid");
		if($_SESSION['adminrole'] == 1)
		{
		     $manageid = $_SESSION['adminname'];		
		}
                $phone =  $this->input->post("phone")?$this->input->post("phone"):0;
                $imid =  $this->input->post("imid")?$this->input->post("imid"):0;
                
                try{                        
                    $this->Monitor_manage_model->addManageMonitor($manageid,$phone,$imid);
                    $this->generate_userid_list_file();
                }
                catch(Exception $e){
                    $data["jstips"] .= "alert('请完整正确的填写信息')";
                }
            }
            if($this->input->get("type"))
            {
                $type = $this->input->get("type");
                if($type = "del")
                {
                    $id = $this->input->get("id");
                    $this->Monitor_manage_model->delManageMonitor($id);
                    $this->generate_userid_list_file();
                }
            }
            $pagesize = 20;
            $this->load->library('pagination');
            $config['base_url'] = "/monitor/manage/managemonitor?v=1";
            $config['total_rows'] = $this->Monitor_manage_model->getManageMonitorCount($_SESSION['adminrole'],$_SESSION['adminname']);
            $config['per_page'] = $pagesize;
            $config['num_links'] = 5;
            $config['page_query_string'] = true;
            $config['first_link'] = '<<';
            $config['last_link'] = '>>';
            $config['full_tag_open'] = '<div class="pagination">';
            $config['full_tag_close'] = '</div>';
            $this->pagination->initialize($config);
            $data["page"] = $this->pagination->create_links();
	
	    $data["list"] = "";
            $rst = $this->Monitor_manage_model->getManageMonitor($_SESSION['adminrole'],$_SESSION['adminname'],$pagesize,$this->input->get("per_page")?$this->input->get("per_page"):0);
            
            for($i=0;$i<count($rst);$i++)
            {
                    $data ["list"] .= "<tr>                                           
                                           </td>
                                           <td>".$rst[$i]["id"]."</td>
                                           <td>".$rst[$i]["ManagerID"]."</td>
                                           <td>".$rst[$i]["PhoneID"]."</td>
                                           <td><a href='?type=del&id=".$rst[$i]["id"]."'>删除   </a>
                                               <a href='recordfile?phone=".$rst[$i]["PhoneID"]."'>监听历史记录</a>
                                       </tr>";
            }
            $this->load->view("modules/monitor/manage/managemonitor_view.php",$data);
        }
	
        function recordfile()
        {
            $data["jstips"] = "";
            $data["page"] = "";
            $data["list"] = "";
            
            if($this->input->get_post("phone"))
            {
                $phoneid = $this->input->get_post("phone");
                 
                $pagesize = 20;
                
                $this->load->library('pagination');
                $config['base_url'] = "/monitor/manage/recordfile?v=1";
                $config['total_rows'] = $this->Monitor_manage_model->getRecordFileCount($phoneid,2);
                $config['per_page'] = $pagesize;
                $config['num_links'] = 5;
                $config['page_query_string'] = true;
                $config['first_link'] = '<<';
                $config['last_link'] = '>>';
                $config['full_tag_open'] = '<div class="pagination">';
                $config['full_tag_close'] = '</div>';
                $this->pagination->initialize($config);
                $data["page"] = $this->pagination->create_links();
            
               
                $rst = $this->Monitor_manage_model->getRecordFile($phoneid,2,$pagesize,$this->input->get("per_page")?$this->input->get("per_page"):0);
                
                for($i=0;$i<count($rst);$i++)
                {
                	//modified by gary 20120208
                	$play_line = '<script type="text/javascript" src="/script/forbid.js"></script>';
                	$play_line = $play_line.'<object id="MediaPlayer" align=middle classid=CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95 class=OBBJECT id=MediaPlayer width="300" height="45">';
                	$play_line = $play_line.'<param name=Filename value="/'.$rst[$i]["fileurl"].'">';
                	$play_line = $play_line.'<param name=ShowStatusBar value=0><param name=AutoStart value=0><param name=Volume value=0></object>';
                	                	
                    $data ["list"] .= "<tr>                                               
                                          <td>".$rst[$i]["id"]."</td>
                                          <td>".$rst[$i]["userid"]."</td>
                                          <td>".$rst[$i]["callid"]."</td>
                                          <td>".date("Y-m-d H:i:s",$rst[$i]["starttime"])."</td>
                                          <td>".date("Y-m-d H:i:s",$rst[$i]["endtime"])."</td>
                                          <td>$play_line</td>
                                       </tr>";
                   /*     $data ["list"] .= "<tr>                                               
                                               <td>".$rst[$i]["id"]."</td>
                                               <td>".$rst[$i]["userid"]."</td>
                                               <td>".$rst[$i]["callid"]."</td>
                                               <td>".date("Y-m-d H:i:s",$rst[$i]["starttime"])."</td>
                                               <td>".date("Y-m-d H:i:s",$rst[$i]["endtime"])."</td>
                                               <td><a href='/".$rst[$i]["fileurl"]."' target='_blank'>播 放</a></td>
                                           </tr>";
                                           */
                }
            }
            $this->load->view("modules/monitor/manage/recordfile_view.php",$data);
        }
        
        function recordfileincall()
        {
                $data["jstips"] = "";
                $data["page"] = "";
                $data["list"] = "";
                $pagesize = 20;
                
                $this->load->library('pagination');
                $config['base_url'] = "/monitor/manage/recordfileincall?v=1";
                $config['total_rows'] = $this->Monitor_manage_model->getRecordFileCount(0,1);
                $config['per_page'] = $pagesize;
                $config['num_links'] = 5;
                $config['page_query_string'] = true;
                $config['first_link'] = '<<';
                $config['last_link'] = '>>';
                $config['full_tag_open'] = '<div class="pagination">';
                $config['full_tag_close'] = '</div>';
                $this->pagination->initialize($config);
                $data["page"] = $this->pagination->create_links();
            
               
                $rst = $this->Monitor_manage_model->getRecordFile(0,1,$pagesize,$this->input->get("per_page")?$this->input->get("per_page"):0);
                
                for($i=0;$i<count($rst);$i++)
                {
                	//modified by gary 20120208
                	$play_line = '<script type="text/javascript" src="/script/forbid.js"></script>';
                	$play_line = $play_line.'<object id="MediaPlayer" align=middle classid=CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95 class=OBBJECT id=MediaPlayer width="300" height="45">';
                	$play_line = $play_line.'<param name=Filename value="/'.$rst[$i]["fileurl"].'">';
                	$play_line = $play_line.'<param name=ShowStatusBar value=0><param name=AutoStart value=0><param name=Volume value=0></object>';
                	                	
                    $data ["list"] .= "<tr>                                               
                                          <td>".$rst[$i]["id"]."</td>
                                          <td>".$rst[$i]["userid"]."</td>
                                          <td>".$rst[$i]["callid"]."</td>
                                          <td>".date("Y-m-d H:i:s",$rst[$i]["starttime"])."</td>
                                          <td>".date("Y-m-d H:i:s",$rst[$i]["endtime"])."</td>
                                          <td>$play_line</td>
                                       </tr>";
/*                                       
                        $data ["list"] .= "<tr>                                               
                                               <td>".$rst[$i]["id"]."</td>
                                               <td>".$rst[$i]["userid"]."</td>
                                               <td>".$rst[$i]["callid"]."</td>
                                               <td>".date("Y-m-d H:i:s",$rst[$i]["starttime"])."</td>
                                               <td>".date("Y-m-d H:i:s",$rst[$i]["endtime"])."</td>
                                               <td><a href='".$rst[$i]["fileurl"]."' target='_blank'></a></td>
                                           </tr>";
*/
                }
                
                $this->load->view("modules/monitor/manage/recordfileincall_view.php",$data);
        }
	
	function adduser()
	{
	    $data["jstips"] = "";
            
            if($this->input->post("add"))
            {
                $username =  $this->input->post("username");
                $pwd =  $this->input->post("pwd");
                $role =  $this->input->post("role");
                
                if($username && $pwd && $role)
                {
                    $this->Monitor_manage_model->addUser($username,$pwd,$role);
                }
                else
                {
                    $data["jstips"] .= "alert('请完整填写信息')";
                }
            }
            if($this->input->get("type"))
            {
                $type = $this->input->get("type");
                if($type = "del")
                {
                    $id = $this->input->get("id");
                    $this->Monitor_manage_model->delUser($id);
                }
            }
            
            $rst = $this->Monitor_manage_model->getUserList();
            $data["list"] = "";
            for($i=0,$c=count($rst);$i<$c;$i++)
            {
                $data["list"] .= "<tr>
                                    <td><a href='?type=del&id=".$rst[$i]["UserName"]."'>删除</a></td>
                                    <td>".$rst[$i]["UserName"]."</td>
                                    <td>".($rst[$i]["Role"]==0?"管理员":"普通")."</td>
                                  </tr>";
            }
            
            $this->load->view("modules/monitor/manage/adduser_view.php",$data);
	}
}
?>