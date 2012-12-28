<?php if (! defined ( 'BASEPATH' ))exit ( 'No direct script access allowed' );

class Admin extends CI_Controller {
	
	function __construct()
	{
		parent::__construct ();
		$this->load->model("Admin_model");
	}
	
	function index()
	{
		$this->authuser_class->checkLogin();
		$this->load->view('index_view');
	}
	
	function yanzheng()
	{
		session_start();
		//生成验证码图片
		Header("Content-type: image/PNG");
		$im = imagecreate(50,25);
		$back = ImageColorAllocate($im,245,245,245);
		imagefill ($im,0,0,$back); //背景		

		$vcodes = "";
		srand ((double)microtime()*1000000);
		//生成4位数字
		for($i = 0; $i < 4; $i++)
		{
			$font = ImageColorAllocate($im,rand(100,255),rand(0,100),rand(100,255));
			$authnum = rand(1,9);
			$vcodes .= $authnum;
			imagestring($im,5,6+$i*10,5,$authnum,$font);
		}
		
		for($i = 0; $i < 100; $i++) //加入干扰象素
		{
			$randcolor = ImageColorallocate($im,rand(0,255), rand(0,255),rand(0,255));
			imagesetpixel($im,rand()%70,rand()%30,$randcolor);
		}
		ImagePNG($im);
		ImageDestroy($im);
		
		$_SESSION['VCODE'] = $vcodes;
	}
	
	function left()
	{
		$this->authuser_class->checkLogin();
		$this->load->library('function_class');
		
		$modules = $this->function_class->getModulesXml("ModulesList");
		$options = $this->function_class->getModulesXml("OptionList");
		$str = "";
		$flags = $_SESSION["adminflag"];
		
		foreach ($modules as $module)
		{
			$tempOption = "";
			$mid = $module->getAttribute("value");
			$name = $module->nodeValue;
			foreach($options as $option)
			{
				$target = "MainFrame";
				$type = $option->getAttribute("type");
				if($type == $mid)
				{
					$oid = $option->getAttribute("value");
					$url = $option->getAttribute("url");
					$oname = $option->nodeValue;
					if(in_array($oid, $flags))
					{
						$tempOption .= '<div><a target="'.$target.'" href="'.$url.'"> '.'  <img src="/res/images/admin/icon/img0.gif" />'.$oname.'</a>'.'</div>';
					}
				
				}
			}
			if($tempOption != "")
			{
				$str .= '<div style="height:100%">';
				$str .= '<div id="mo' . $mid . '">' . '  <a onclick="Options(' . $mid . ')" style="" href="javascript:void(0)">' . '    <img src="/res/images/admin/icon/img-.gif" id="img' . $mid . '" />' . $name . '  </a>' . '</div>';
				$str .= '<ul style="display: block;" id="op' . $mid . '">';
				$str .= $tempOption;
				$str .= '</ul>';
				$str .= '</div>';
			}
		}
		$modules = null;
		$options = null;
		
		$str .= '<div style="cursor:pointer;color:red;width:25px" onclick="window.top.location.href=\'/admin/logout\'">退出</div>';		
		$data ["modulesList"] = $str;
		
		$this->load->view ( 'modules/left_view', $data );
	}
	
	function left1()
	{
		//$this->authuser_class->checkLogin ();
		$this->load->library('function_class');
		
		$modules = $this->function_class->getModulesXml("ModulesList");
		$options = $this->function_class->getModulesXml("OptionList");
		$str = "";
		//$flags = $_SESSION["adminflag"];
		
		foreach ($modules as $module)
		{
			$tempOption = "";
			$mid = $module->getAttribute("value");
			$name = $module->nodeValue;
			foreach($options as $option)
			{
				$target = "MainFrame";
				$type = $option->getAttribute("type");
				if($type == $mid)
				{
					$oid = $option->getAttribute("value");
					$url = $option->getAttribute("url");
					$oname = $option->nodeValue;
					//if(in_array($oid, $flags))
					//{
						$tempOption .= '<div><a target="'.$target.'" href="'.$url.'"> '.'  <img src="/res/images/admin/icon/img0.gif" />'.$oname.'</a>'.'</div>';
					//}
				
				}
			}
			if($tempOption != "")
			{
				$str .= '<div id="mo' . $mid . '">' . '  <a onclick="Options(' . $mid . ')" style="" href="javascript:void(0)">' . '    <img src="/res/images/admin/icon/img-.gif" id="img' . $mid . '" />' . $name . '  </a>' . '</div>';
				$str .= '<ul style="display: block;" id="op' . $mid . '">';
				$str .= $tempOption;
				$str .= '</ul>';
			}
		}
		$str .= '<div style="cursor:pointer;color:red;width:25px" onclick="window.top.location.href=\'/admin/logout\'">退出</div>';
		
		$data ["modulesList"] = $str;
		
		$this->load->view ( 'modules/left_view', $data );
	}
	
	function main()
	{
		$this->authuser_class->checkLogin ();
		
		$adminLogin = $this->Admin_model->getLoginById ( $_SESSION ["adminid"] );
		$data ["list"] = "";
		foreach ( $adminLogin as $row )
		{
			$data ["list"] .= "<tr><td>".date("Y-m-d H:i:s",(strtotime($row->loginTime)-60*60*8))."</td><td>".$row->loginIP."</td></tr>";
		}
		
		$this->load->view ( 'modules/main_view', $data );
	}
	
	function getlist()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(12);
		$this->load->library("function_class");
		$groupList = $this->function_class->getGroupList();
		
		$adminList = $this->Admin_model->getList ();
		$data ["list"] = "";
		foreach ( $adminList as $row ) {
			$data ["list"] .= "<tr>" . "  <td>" . $row->id . "</td>" . "  <td>" . $row->name . "</td>" . "  <td>" . $groupList [$row->groupid] . "</td>" . "  <td>[<a href='/admin/getPermission?aid=" . $row->id . "'>权限分配</a>]" . "      [<a href='javascript:void(0)' style='color:red' onclick='checkDel(" . $row->id . ",\"" . $row->name . "\")'>删除帐号</a>]</td>" . "</tr>";
		}
		$this->load->view ( 'modules/admin/list_view', $data );
	}
	
	function getpermission() {
		$this->authuser_class->checkLogin ();
		$this->authuser_class->checkPermission ( 12 );
		
		$aid = 0;
		if ($this->input->get ( "aid" )) {
			$aid = $this->input->get ( "aid" );
			
			if ($this->input->post ( "submit" )) {
				$flags = "";
				if ($this->input->post ( "check" )) {
					$flags = implode ( ",", $this->input->post ( "check" ) );
				}
				$this->Admin_model->updateFlag ( $flags, $aid );
			}
			
			$this->load->library ( 'function_class' );
			$modules = $this->function_class->getModulesXml ( "ModulesList" );
			$options = $this->function_class->getModulesXml ( "OptionList" );
			$modulesHTML = "";
			foreach ( $modules as $module ) {
				$modulesHTML .= $module->nodeValue . ":";
				foreach ( $options as $option ) {
					$type = $option->getAttribute ( "type" );
					if ($type == $module->getAttribute ( "value" )) {
						$value = $option->getAttribute ( "value" );
						$url = $option->getAttribute ( "url" );
						$name = $option->nodeValue;
						$modulesHTML .= '<input type="checkbox" name="check[]" value="' . $value . '">' . $name . '</input>';
					}
				}
				$modulesHTML .= "<br />";
			}
			$data ["modulesHTML"] = $modulesHTML;
			$data ["aid"] = $aid;
			$data ["flag"] = $this->Admin_model->getFlag ( $aid )->flag;
		} else {
			redirect ( "/admin/main" );
		}
		
		$this->load->view ( 'modules/admin/permission_view', $data );
	}
	
	function add()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(11);
		
		$data["tips"] = "";
		
		if ($this->input->post ( "submit" ))
		{
			$name = $this->input->post ( "name" );
			$pwd = $this->input->post ( "pwd" );
			$pwdConfim = $this->input->post ( "pwdconfirm" );
			$group = $this->input->post ( "group" );
			
			if ($pwdConfim != $pwd)
			{
				$data ["tips"] = "alert('两次密码输入不一致');";
			}
			else if(trim($name)=="" || trim($pwd)=="" || trim($pwdConfim) == "")
			{
				$data ["tips"] = "alert('填写不完整');";
			}
			else
			{
				if ($this->Admin_model->insertAdmin($name,$pwd,$group) != 0)
				{
					$data["tips"] = "alert('用户名已存在');";
				}
			}
		}
		
		$this->load->view('modules/admin/add_view',$data);	
	}
	
	function del()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(12);
		
		if ($this->input->get("aid"))
		{
			$this->Admin_model->delAdmin($this->input->get("aid"));
		}
		redirect("/admin/getlist");
	}
	
	function changepwd()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(13);
		
		$data ["tips"] = "";
		
		if ($this->input->post("submit"))
		{
			$old = $this->input->post("old");
			$new = $this->input->post ("new");
			$newConfim = $this->input->post("newconfirm");
			$id = $_SESSION ["adminid"];
			if($new != $newConfim)
			{
				$data["tips"] = "alert('新密码确认失败');";
			}
			else if(trim($old) == "" || trim($new) == "" || trim($newConfim) == "")
			{
				$data["tips"] = "alert('填写不完整');";
			}
			else
			{
				if ($this->Admin_model->changePwd($id,$old,$new) != 0)
				{
					$data["tips"] = "alert('原密码错误');";
				}
				else
				{
					$data["tips"] = "alert('修改成功');";
				}
			}
		}
		
		$this->load->view('modules/admin/changepwd_view',$data);
	}
	
	function nopermission()
	{
		$this->load->view( 'modules/nopermission_view');
	}
	
	function loginold()
	{
		session_start();
		$data["tips"] = "";
		if($this->input->post("name") && $this->input->post("pwd") && $this->input->post("vcode"))
		{
			if ($_SESSION['VCODE'] == $this->input->post( "vcode" ))
			{
				$admin = $this->Admin_model->getAdmin($this->input->post("name"), $this->input->post("pwd"));
				if (count($admin) > 0)
				{
					$_SESSION['adminid'] = $admin->id;
					$_SESSION['adminname'] = $admin->name;
					$_SESSION['adminflag'] = explode(",",$admin->flag);
					$_SESSION['adminTimestamp'] = time();
					$requestIP = $this->input->ip_address();
					$this->Admin_model->insertLogin($_SESSION['adminid'], $requestIP);
					redirect ( "/" );
				}
				else
				{
					$data["tips"] = "alert('用户名密码错误')";
					$this->load->view('login_view',$data);
				}
			}
			else
			{
				$data["tips"] = "alert('验证码错误')";
				$this->load->view ( 'login_view', $data );
			}
		}
		else
		{
			$this->load->view('login_view',$data);
		}
	}
	
	function oalogin()
	{
		session_start();
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown'))
		{
			$onlineip = getenv('HTTP_CLIENT_IP');
		}
		elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown'))
		{
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'),'unknown'))
		{
			$onlineip = getenv('REMOTE_ADDR');
		}
		elseif(isset($_SERVER ['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],'unknown'))
		{
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		
		preg_match("/[\d\.]{7,15}/",$onlineip,$onlineipmatches);
		$onlineip = $onlineipmatches[0]?$onlineipmatches[0]:'127.0.0.1';
		unset($onlineipmatches);
		include_once './res/lib/uam.class.php';
		
		$UAM = new UAM ( '1789', '1' );
		
		if (!isset($_SESSION['uamResult']))
		{
			$valid = $UAM->Validate($onlineip);
			if ($valid === FALSE)
			{
				die("认证失败");
			}
			$_SESSION['uamResult'] = $valid['DomainAccount'];
		}
		$admin = $this->Admin_model->getAdminName($_SESSION['uamResult']);
		if (count($admin) > 0)
		{
			$_SESSION['adminid'] = $admin->id;
			$_SESSION['adminname'] = $admin->name;
			$_SESSION['adminflag'] = explode(",",$admin->flag);
			$_SESSION['adminTimestamp'] = time();
			$requestIP = $this->input->ip_address();
			$this->Admin_model->insertLogin($_SESSION['adminid'],$requestIP);
			redirect("/");
		}
	}
	
	function logout()
	{
		$this->authuser_class->safeQuit();
		
		$returnUrl = "http://123.103.17.22:8081/";
		$ssoLogoutUrl = "http://uam.corp.snda.com/Logout.aspx?ReturnUrl=" . $returnUrl . "&Rtype=2";
		redirect ( $ssoLogoutUrl );
	}
	
	
}

?>