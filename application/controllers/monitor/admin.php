<?php if (! defined ( 'BASEPATH' ))exit ( 'No direct script access allowed' );

class Admin extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct ();
		$this->load->model("monitor/manage_model","Monitor_manage_model");
	}
	
	function index()
	{
            $this->load->view('/modules/monitor/index_view');
	}
	
        function main(){}
        
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
		$this->authuser_class->checkMonitorLogin();	
		$modules = $this->function_class->getMonitorModulesXml("ModulesList");
		$options = $this->function_class->getMonitorModulesXml("OptionList");
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
				$str .= '<div id="mo' . $mid . '">' . '  <a onclick="Options(' . $mid . ')" style="" href="javascript:void(0)">' . '    <img src="/res/images/admin/icon/img-.gif" id="img' . $mid . '" />' . $name . '  </a>' . '</div>';
				$str .= '<ul style="display: block;" id="op' . $mid . '">';
				$str .= $tempOption;
				$str .= '</ul>';
			}
		}
		$str .= '<div style="cursor:pointer;color:red;width:25px" onclick="window.top.location.href=\'/monitor/admin/logout\'">退出</div>';
		
		$data ["modulesList"] = $str;		
		$this->load->view('/modules/left_view',$data);
	}	    
	
	function login()
	{
		session_start();
		$data["tips"] = "";
		if($this->input->post("name") && $this->input->post("pwd") /*&& $this->input->post("vcode")*/)
		{
			/*if ($_SESSION['VCODE'] == $this->input->post( "vcode" ))
			{*/
				$admin = $this->Monitor_manage_model->getAdmin($this->input->post("name"), $this->input->post("pwd"));
				if (count($admin) > 0)
				{
					$_SESSION['adminid'] = $admin->UserName;
					$_SESSION['adminname'] = $admin->UserName;
					$_SESSION['adminrole'] = $admin->Role;
                                        if($admin->Role == 0)
                                        {                                            
                                            $_SESSION['adminflag'] = explode(",","10,11,12,13,14");
                                        }
                                        else
                                        {
                                            $_SESSION['adminflag'] = explode(",","12,13,14");
                                        }
					$_SESSION['adminTimestamp'] = time();
					$requestIP = $this->input->ip_address();
					redirect("/monitor/admin");
				}
				else
				{
					$data["tips"] = "alert('用户名密码错误')";
					$this->load->view('/modules/monitor/login_view',$data);
				}
			/*}
			else
			{
				$data["tips"] = "alert('验证码错误')";
				$this->load->view('/modules/monitor/login_view', $data);
			}*/
		}
		else
		{
			$this->load->view('/modules/monitor/login_view',$data);
		}
	}
	
	function nopermission()
	{
		$this->load->view('/modules/monitor/nopermission_view');
	}
	
	function logout()
	{
		$this->authuser_class->safeQuit();
		
		$returnUrl = "/monitor/admin";
		redirect($returnUrl);
	}
}

?>