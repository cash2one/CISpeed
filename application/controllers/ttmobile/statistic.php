<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Statistic extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("ttmobile/data_model","TT_data_model");
		$this->load->model("ttmobile/user_model","TT_user_model");	
	}
	
	function index() {}
	
	//生成登录用户相关数据
	function createLoginUser()
	{
		$this->TT_data_model->createLoginUser(date("Ymd",time()-24*60*60));
	}
	
	//生成登录机器码相关数据
	function createLoginCode()
	{
		//$t = $this->input->get("d");
		$t = date("Ymd",time()-24*60*60);
		$this->TT_data_model->createLoginCode($t);
	}
	
	//生成综合数据
	function getCommonData()
	{
		$this->TT_data_model->getCommonData ();
	}
	
	//生成系统电话
	function getSystemCall()
	{
		$t = date("Y-m-d",time()-24*60*60);
		$this->TT_data_model->getSystemCall($t);
	}
	
	//生成手机型号数据
	function getMobileTypeData()
	{
		$this->TT_user_model->getMobileTypeData(strtotime(date("Y-m-d"))-24*60*60);
	}
	
	//生成网络类型数据
	function getNetTypeData()
	{
		$this->TT_user_model->getNetTypeData(strtotime(date("Y-m-d"))-24*60*60);
	}
	
	//生成地区分布数据
	function getRegionData()
	{
		$this->TT_user_model->getRegionData(strtotime(date("Y-m-d"))-24*60*60);
	}
	
	//生成新手周期流失数据
	function getUserLoseData()
	{
		$this->TT_data_model->getUserLose (strtotime(date("Y-m-d"))-24*60*60);
	}
	
	//生成用户流失数据
	function getCommonUserLoseData()
	{
		//$this->TT_data_model->getCommonUserLose($this->input->get("d"));
		$this->TT_data_model->getCommonUserLose(date("Y-m-d",time()-24*60*60));
	}
	
	//生成新手每天流失数据
	function getNewUserLoseData()
	{
		$this->TT_data_model->getNewUserLose(strtotime(date("Y-m-d"))-24*60*60);
	}
	
	//生成渠道新手流失数据
	function getNewUserLoseBySp()
	{		
		set_time_limit(0);
		$this->load->library('function_class');
		$t = strtotime(date("Y-m-d"))-24*60*60;
		$a = $this->function_class->TTMarket();
		foreach($a as $k=>$v)
		{
			$this->TT_data_model->getNewUserLoseBySp($t,$k);
		}		
	}
	
	//生成渠道新手流失数据(按机器码)
	function getCallDataBySpByCode()
	{
		set_time_limit(0);
		$this->load->library('function_class');
		//$t = strtotime(date("Y-m-d"))-24*60*60;
		$t = strtotime($this->input->get("d"));
		$a = $this->function_class->TTMarket();
		foreach($a as $k=>$v)
		{
			$this->TT_data_model->getNewUserLoseBySpByCode($t,$k);
		}	
	}
	
	//生成通话综合数据
	function getCallCommonData()
	{		
		$this->TT_user_model->getCallCommonData(strtotime(date("Y-m-d"))-24*60*60);
	}
		
	//生成通话统计数据
	function getCallData()
	{
		$this->TT_user_model->getCallData(strtotime(date("Y-m-d"))-24*60*60);
	}
	
	//按渠道生成通话统计数据
	function getCallDataBySp()
	{
		set_time_limit(0);
		//$this->TT_user_model->getCallDataBySp(strtotime($this->input->get("d")));
		$this->load->library('function_class');
		$t = strtotime(date("Y-m-d"))-24*60*60;
		$a = $this->function_class->TTMarket();
		foreach($a as $k=>$v)
		{
			$this->TT_user_model->getCallDataBySp($t,$k);
		}	
	}
		
	function getCallData1()
	{
		$this->TT_user_model->getCallData1(strtotime($this->input->get("d")));
	}
	
	//按版本统计登录数据
	function getLoginVersion()
	{
		$this->TT_data_model->getVersionLoginCount(date("Y-m-d",time()-24*60*60));
	}
	
	//按天数统计登录版本数据
	function getVersionLoginCountByDays()
	{
		$this->TT_data_model->getVersionLoginCountByDays($this->input->get("d"),7,date("Y-m",strtotime($this->input->get("d"))));
	}
	
	//获取渠道数据
	function getSpData()
	{
		//$this->TT_data_model->getSpData(strtotime($this->input->get("d")));
		$this->TT_data_model->getSpData(strtotime(date("Y-m-d"))-24*60*60);
	}
	
	//获取talkserver数据
	function getTalkSvrStat()
	{
		$date = $this->input->get("d");
		$server = $this->input->get("s");
		$totle = $this->input->get("t");
		$nomal = $this->input->get("n");
		$abnomal = $this->input->get("abn");
		if($date && $server>=0 && $totle>=0 && $nomal>=0 && $abnomal>=0)
		{
			$this->TT_user_model->getTalkSvrStat($date,$server,$totle,$nomal,$abnomal);		
		}
	}
	
	//获取talkserverr日志记录
	function getTalkLog()
	{
		$TalkServerIP = $this->input->get("TalkServerIP");
		$strCallNo = $this->input->get("strCallNo");
		$strMyWiLanIP = $this->input->get("strMyWiLanIP");
		$nMyNetType = $this->input->get("nMyNetType");
		$nHoldOnDelay = $this->input->get("nHoldOnDelay");
		$nDelayGT500msTimes = $this->input->get("nDelayGT500msTimes");
		$nMaxTransitDelay = $this->input->get("nMaxTransitDelay");
		$fSendPlcRate = $this->input->get("fSendPlcRate");
		$nSendAudioBytes = $this->input->get("nSendAudioBytes");
		$fRecvPlcRate = $this->input->get("fRecvPlcRate");
		$nRecvAudioBytes = $this->input->get("nRecvAudioBytes");
		$nBeginAcceptTime = $this->input->get("nBeginAcceptTime");
		$nTelephoneTime  = $this->input->get("nTelephoneTime");
		$this->TT_user_model->getTalkLog($TalkServerIP,$strCallNo,$strMyWiLanIP,$nMyNetType,$nHoldOnDelay,$nDelayGT500msTimes,
			    $nMaxTransitDelay,$fSendPlcRate,$nSendAudioBytes,$fRecvPlcRate,$nRecvAudioBytes,$nBeginAcceptTime,$nTelephoneTime);
	}
	
	//默认短信工具数据
	function getFsmsData()
	{
		$this->TT_user_model->getFsmsData(strtotime(date("Y-m-d"))-24*60*60);
	}
	
	//默认通话工具数据
	function getFcallData()
	{
		$this->TT_user_model->getFcallData(strtotime(date("Y-m-d"))-24*60*60);
	}
	
	//生成成功通话数据
	function createSuccessCall()
	{
		//$this->TT_user_model->createSuccessCall($this->input->get("d"));
		$this->TT_user_model->createSuccessCall(date("Y-m-d",time()-24*60*60));
	}
	
	//月费统计数据处理
	function getMonthCallFee()
	{
		$this->TT_user_model->getMonthCallFee(date("Y-m",strtotime("-1 month")));
	}
	
	//月费详细数据处理
	function getMonthCallFeeDetail()
	{
		$this->TT_user_model->getMonthCallFeeDetail(date("Ym",strtotime("-1 month")));
	}
	
	//用户电话数数据处理
	function getUserCallCount()
	{
		$this->TT_user_model->getUserCallCount(date("Y-m-d",time()-24*60*60));
	}
	
	//好友数据处理
	function getUserFriendsData()
	{	
		$this->TT_user_model->getUserFriendsData(date("Y-m-d",time()-24*60*60));
	}
	
	//送话费数据处理
	function getSendCallFeeUser()
	{
		$this->TT_user_model->getSendCallFeeUser(date("Y-m-d",time()-24*60*60));
	}
	
	//平均登录数据
	function getAvgUserLogin()
	{			
		set_time_limit(0);
		$this->TT_user_model->getAvgUserLogin(date("Y-m-d",time()-24*60*60));
	}
	
	//30天通话频率
	function getCallFrequencyByDays()
	{
		$this->TT_user_model->getCallFrequencyByDays(30,date("Y-m-d",time()-24*60*60));
	}
	
	function fix()
	{
		/*set_time_limit(0);
		$t = $this->input->get("d");
		$this->TT_user_model->getAvgUserLogin($t);*/
		$this->TT_data_model->createLoginUser("20120131");
	}
	
	/*用户登录日志失败————开始*/
	function generateUserRegFail()
	{
		$d = date("Ymd",strtotime(date("Y-m-d",time()-24*60*60)));
		$dir = "/www/17et/res/logfail/".$d."/";
		if (is_dir($dir)) 
		{
			if ($dh = opendir($dir))
			{				
				while(($file = readdir($dh)) !== false)
				{
					if(filetype($dir.$file) == "file")
					{
						$ctime = 0;
						$etype = "";
						$emsg = "";
						$firm = "";
						$dev = "";
						$os = "";
						$mcode = "";
						$msource = 0;
						$imsi = "";
						$nettype = "";
						$netinfo = "";
						$mac = "";
						$version = 0;
						$content  = "";
						
						$ctime = filectime($dir.$file);
						$content = "http://17et.com/res/logfail/$d/$file";
						$f = fopen($dir.$file,"r");
						while(!feof($f))
						{
							$temp = fgets($f);
							if(substr_count($temp,"AuthTelePhone"))
							{
								$etype = "AuthTelePhone";
							}
							if(substr_count($temp,"InternalOARegister"))
							{
								$etype = "InternalOARegister";
							}
							if(substr_count($temp,"OnReceiveSMS strPhoneNumber:"))
							{
								$etype = "OnReceiveSMS strPhoneNumber:";
							}
							
							if(substr_count($temp,"无法获取运营商信息"))
							{
								$emsg = "无法获取运营商信息";
							}
							if(substr_count($temp,"未注册请等待"))
							{
								$emsg = "未注册请等待";
							}
							if(substr_count($temp,"sendSMS error"))
							{
								$emsg = "sendSMS error";
							}
							if(substr_count($temp,"The operation timed out"))
							{
								$emsg = "The operation timed out";
							}
							if(substr_count($temp,"手机号码非法"))
							{
								$emsg = "手机号码非法";
							}
							
							if(substr_count($temp,"machineinfo:"))
							{
								$t = explode(":",$temp);
								$tempmachineinfo = explode("|",$t[1]);
								$firm = $tempmachineinfo[0];
								$dev = $tempmachineinfo[1];
								$os = $tempmachineinfo[2];
								$mcode = $tempmachineinfo[3];
								$msource = str_replace("C","",str_replace("X","",$tempmachineinfo[4]));
							}
							
							if(substr_count($temp,"imsi:"))
							{
								$t = explode(":",$temp);
								$imsi = $t[1];
							}
							
							if(substr_count($temp,"network:"))
							{
								$t = explode("network:",$temp);
								$tt1 =  explode("Reason:",$t[1]);
								$tt2 = explode("ExtraInfo:",$tt1[0]);
								$tt3 = explode("TypeName:",$tt2[0]);
								$nettype = $tt3[1];
								if($nettype == "WIFI")
								{
									$netinfo = "WIFI";
								}
								else
								{									
									$netinfo = $tt2[1];
								}
							}
							
							if(substr_count($temp,"mac:"))
							{
								$t = explode("mac:",$temp);
								$mac = $t[1];
							}
							
							if(substr_count($temp,"clientversion:"))
							{
								$t = explode(":",$temp);
								$version = implode(explode(".",$t[1]));
							}
							
							//echo iconv("UTF-8","GBK//IGNORE",$temp)."<br/>";
						}
						/*echo $ctime."/".$etype."/".iconv("UTF-8","GBK//IGNORE",$emsg)."/".$firm."/".$dev."/".$os."/".$mcode."/".$msource."/".trim($imsi)."/".$nettype."/".$netinfo."/".trim($mac)."/".trim($version)."/".$content;
						echo "<br/><br/>";*/						
						fclose($f);
						$this->TT_user_model->insertRegLoginFail($ctime,$etype,$emsg,$firm,$dev,$os,$mcode,$msource,trim($imsi),$nettype,$netinfo,trim($mac),trim($version),$content);	
					}
				}
				closedir($dh);
			}
		}
	}
	/*用户登录日志失败————结束*/	

}
?>