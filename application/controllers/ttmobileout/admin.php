<?php if (! defined ( 'BASEPATH' ))exit ( 'No direct script access allowed' );

class Admin extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct ();
		$this->load->model("ttmobile/Admin_model","TT_admin_model");
	}
	
	function index()
	{
            $this->load->view('/modules/ttmobileout/index_view');
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
		$this->authuser_class->checkTTmOutLogin();
		$this->load->library('function_class');
		
		$modules = $this->function_class->getTTmOutModulesXml("ModulesList");
		$options = $this->function_class->getTTmOutModulesXml("OptionList");
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
		$str .= '<div style="cursor:pointer;color:red;width:25px" onclick="window.top.location.href=\'/ttmobileout/admin/logout\'">退出</div>';
		
		$data ["modulesList"] = $str;		
		$this->load->view('/modules/left_view',$data);
	}
	
        function main()
        {}
	
	function add()
	{
		$this->authuser_class->checkTTmOutLogin();
		$this->authuser_class->checkTTmOutPermission(11);
		
		$data["tips"] = "";
		
		if ($this->input->post("submit"))
		{
			$name = $this->input->post("name");
			$pwd = $this->input->post("pwd");
			$pwdConfim = $this->input->post("pwdconfirm");
			$group = $this->input->post("group");
			
			if ($pwdConfim != $pwd)
			{
				$data["tips"] = "alert('两次密码输入不一致');";
			}
			else if(trim($name)=="" || trim($pwd)=="" || trim($pwdConfim) == "")
			{
				$data ["tips"] = "alert('填写不完整');";
			}
			else
			{
				if($this->TT_admin_model->insertAdmin($name,$pwd,$group) != 0)
				{
					$data["tips"] = "alert('用户名已存在');";
				}
			}
		}
		
		$this->load->view('/modules/ttmobileout/admin/add_view',$data);	
	}
	
	function changepwd()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(13);
		
		$data["tips"] = "";
		
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
				if ($this->TT_admin_model->changePwd($id,$old,$new) != 0)
				{
					$data["tips"] = "alert('原密码错误');";
				}
				else
				{
					$data["tips"] = "alert('修改成功');";
				}
			}
		}
		
		$this->load->view('/modules/ttmobileout/admin/changepwd_view',$data);
	}
	
	function del()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(12);
		
		if ($this->input->get("aid"))
		{
			$this->TT_admin_model->delAdmin($this->input->get("aid"));
		}
		redirect("/ttmobileout/admin/getlist");
	}
	
	function getlist()
	{
		$this->authuser_class->checkTTmOutLogin();
		$this->authuser_class->checkTTmOutPermission(12);
		$this->load->library("function_class");
		$groupList = $this->function_class->getGroupList();
		
		$adminList = $this->TT_admin_model->getList();
		$data ["list"] = "";
		foreach ($adminList as $row)
		{
			$data["list"] .= "<tr><td>".$row->id."</td><td>".$row->name."</td><td>".$groupList[$row->groupid]."</td>
			                      <td>[<a href='/ttmobileout/admin/getPermission?aid=".$row->id."'>权限分配</a>]
						  [<a href='/ttmobileout/admin/getSpPermission?aid=".$row->id."'>渠道权限</a>]
						  [<a href='javascript:void(0)' style='color:red' onclick='checkDel(".$row->id.",\"".$row->name."\")'>删除帐号</a>]
					          </td></tr>";
		}
		$this->load->view('/modules/ttmobileout/admin/list_view', $data);
	}
	
	function getpermission()
	{
		$this->authuser_class->checkTTmOutLogin();
		$this->authuser_class->checkTTmOutPermission(12);
		
		$aid = 0;
		if ($this->input->get("aid"))
		{
			$aid = $this->input->get("aid");
			
			if($this->input->post ("submit"))
			{
				$flags = "";
				if ($this->input->post("check"))
				{
					$flags = implode(",",$this->input->post("check"));
				}
				$this->TT_admin_model->updateFlag($flags,$aid);
			}
			
			$this->load->library('function_class');
			$modules = $this->function_class->getTTmOutModulesXml("ModulesList");
			$options = $this->function_class->getTTmOutModulesXml("OptionList");
			$modulesHTML = "";
			foreach ($modules as $module)
			{
				$modulesHTML .= $module->nodeValue.":";
				foreach($options as $option)
				{
					$type = $option->getAttribute("type");
					if ($type == $module->getAttribute("value"))
					{
						$value = $option->getAttribute("value");
						$url = $option->getAttribute("url");
						$name = $option->nodeValue;
						$modulesHTML .= '<input type="checkbox" name="check[]" value="' . $value . '">' . $name . '</input>';
					}
				}
				$modulesHTML .= "<br />";
			}
			$data["modulesHTML"] = $modulesHTML;
			$data["aid"] = $aid;
			$data["flag"] = $this->TT_admin_model->getFlag($aid)->flag;
		}
		else
		{
			redirect("/ttmobileout/admin/main");
		}
		
		$this->load->view('/modules/ttmobileout/admin/permission_view',$data);
	}
	
	
	
	function login()
	{
		session_start();
		$data["tips"] = "";
		if($this->input->post("name") && $this->input->post("pwd") && $this->input->post("vcode"))
		{
			if ($_SESSION['VCODE'] == $this->input->post( "vcode" ))
			{
				$admin = $this->TT_admin_model->getAdmin($this->input->post("name"), $this->input->post("pwd"));
				if (count($admin) > 0)
				{
					$_SESSION['adminid'] = $admin->id;
					$_SESSION['adminname'] = $admin->name;
					$_SESSION['adminflag'] = explode(",",$admin->flag);
					$_SESSION['adminspflag'] = $admin->spflag;
					$_SESSION['adminTimestamp'] = time();
					$requestIP = $this->input->ip_address();
					$this->TT_admin_model->insertLogin($_SESSION['adminid'], $requestIP);
					redirect("http://115.182.3.50:9901");
				}
				else
				{
					$data["tips"] = "alert('用户名密码错误')";
					$this->load->view('/modules/ttmobileout/login_view',$data);
				}
			}
			else
			{
				$data["tips"] = "alert('验证码错误')";
				$this->load->view('/modules/ttmobileout/login_view', $data);
			}
		}
		else
		{
			$this->load->view('/modules/ttmobileout/login_view',$data);
		}
	}
	
	function nopermission()
	{
		$this->load->view('/modules/ttmobileout/nopermission_view');
	}
	
	function logout()
	{
		$this->authuser_class->safeQuit();
		
		$returnUrl = "http://115.182.3.50:9901/";
		redirect($returnUrl);
	}
	
	function getsppermission()
	{
		$this->authuser_class->checkTTmOutLogin();
		$this->authuser_class->checkTTmOutPermission(12);
		
		$aid = 0;
		if ($this->input->get("aid"))
		{
			$aid = $this->input->get("aid");
			
			if($this->input->post ("submit"))
			{
				$flags = "";
				if ($this->input->post("check"))
				{
					$flags = implode(",",$this->input->post("check"));
				}
				$this->TT_admin_model->updateSpFlag($flags,$aid);
			}
			
			$modules = $this->TT_admin_model->getSpList();
			$modulesHTML = "";
			foreach ($modules as $module)
			{
				$options = explode(",",$module["detail"]); 
				$modulesHTML .= $module["name"].":";
				foreach($options as $option)
				{
					$modulesHTML .= '<input type="checkbox" name="check[]" value="'.$option.'">'.$option.'</input>';
					
				}
				$modulesHTML .= "<br />";
			}
			$data["modulesHTML"] = $modulesHTML;
			$data["aid"] = $aid;
			$data["flag"] = $this->TT_admin_model->getSpFlag($aid)->spflag;
		}
		else
		{
			redirect("/ttmobileout/admin/main");
		}
		
		$this->load->view('/modules/ttmobileout/admin/sppermission_view',$data);
	}
	
	function addsp()
	{
		$data["tips"] = "";
		if ($this->input->post("submit"))
		{
			$name = $this->input->post("name");
			$detail = $this->input->post("detail");
			
			
			if(trim($name)=="" || trim($detail)=="")
			{
				$data["tips"] = "alert('填写不完整');";
			}
			else
			{
				if($this->TT_admin_model->addSp($name,$detail) != 0)
				{
					$data["tips"] = "alert('用户名已存在');";
				}
			}
		}
		$this->load->view("/modules/ttmobileout/admin/addsp_view",$data);		
	}
	
	function getsplist()
	{
		$this->authuser_class->checkTTmOutLogin();
		$this->authuser_class->checkTTmOutPermission(15);
		
		$spList = $this->TT_admin_model->getSpList();
		$data["list"] = "";
		foreach ($spList as $row)
		{
			$data["list"] .= "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>".$row["detail"]."</td>
			                      <td>[<a href='/ttmobileout/admin/getSP?sid=".$row["id"]."'>修改</a>]</td>
					  </tr>";
						  /*[<a href='javascript:void(0)' style='color:red' onclick='checkDel(".$row->id.",\"".$row->name."\")'>删除帐号</a>]*/
		}
		$this->load->view('/modules/ttmobileout/admin/splist_view', $data);
	}
	
	function getsp()
	{
		$this->authuser_class->checkTTmOutLogin();
		$this->authuser_class->checkTTmOutPermission(15);
		
		$sid = 0;
		if ($this->input->get("sid"))
		{
			$sid = $this->input->get("sid");
			
			if($this->input->post("submit"))
			{
				$name = $this->input->post("name");
				$detail = $this->input->post("detail");
				
				if(trim($name)=="" || trim($detail)=="")
				{
					$data ["tips"] = "alert('填写不完整');";
				}
				else
				{
					$this->TT_admin_model->updateSp($sid,$name,$detail);					
				}
			}
			$sp = $this->TT_admin_model->getSp($sid);
			$data["sid"] = $sid;
			$data["name"] = $sp->name;
			$data["detail"] = $sp->detail;
		}
		else
		{
			redirect("/ttmobileout/admin/main");
		}
		
		$this->load->view('/modules/ttmobileout/admin/spedit_view',$data);
	}
	
	function delsp()
	{
		$this->authuser_class->checkLogin();
		$this->authuser_class->checkPermission(15);
		
		if ($this->input->get("sid"))
		{
			$this->TT_admin_model->delSp($this->input->get("sid"));
		}
		redirect("/modules/ttombileout/admin/splist_view");
	}		
}

?>