<?php
/**
 * UAM包装类
 * 写日志的相关方法均无返回值
 * 用户认证的相关方法返回值为FALSE为失败，否则返回数组类型
 */
class UAM {
	const UAMLoginUrl = 'http://uam.sdo.com/Login.aspx';
	const PrivilegeService = 'http://uam.sdo.com/Service/Privilege.asmx?WSDL';
	const LogService = 'http://uam.sdo.com/Service/Log.asmx?WSDL';
	const EmailService = 'http://uam.sdo.com/Service/EmailService.asmx';
	const SNDAUserService = 'http://uam.sdo.com/Service/SNDAUserService.asmx';
	const UUIPServiceUrl = 'http://uam.sdo.com/Service/UUIP/UUIP.asmx';
	const SMSServiceUrl = 'http://uam.sdo.com/Service/SMS/SMS.asmx';
	/**
	 * 发送一个soap请求
	 */
	private function _soap($url, $para, $mothed) {
		$client = new SoapClient ( $url );
		$response = $client->__Call ( $mothed, array ('parameters' => $para ) );
		return $response;
	}
	private $SubSystemCode = '0';
	private $EntranceCode = '';
	/**
	 * 构造函数，传递子应用ID
	 */
	public function __construct($subSystemCode, $EntranceCode = '') {
		$this->SubSystemCode = $subSystemCode;
		$this->EntranceCode = $EntranceCode;
		date_default_timezone_set ( "PRC" );
	}
	/**
	 * 当前绝对URL
	 */
	public function CurrentUrl() {
		return 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
	}
	/**
	 * 认证,成功返回数组，否则返回FALSE
	 */
	
	public function RedirectToUamLogin() {
		header ( 'location:' . self::UAMLoginUrl . '?entrancecode=' . $this->EntranceCode . '&SubSystemCode=' . $this->SubSystemCode . '&ReturnUrl=' . $this->CurrentUrl () );
		exit ();
	}
	public function Validate($ip) {
		if (! isset ( $_GET ['Ticket'] )) {
			$this->RedirectToUamLogin ();
		}
		$ticket = trim ( $_GET ['Ticket'] );
		$para = array ('ticket' => $ticket, 'subSystemCode' => $this->SubSystemCode, 'IPAddress' => $ip );
		
		$soap = $this->_soap ( self::PrivilegeService, $para, 'Validate' );
		
		$result = $soap->ValidateResult;
		//
		if ($result->ExecuteStatu == 0) {
			$data = $result->Data;
			return ( array ) $data;
		}
		return FALSE;
	}
	public function Authorize($userid, $ip) {
		$para = array ('userID' => $userid, 'subSystemCode' => $this->SubSystemCode, 'IPAddress' => $ip );
		$response = $this->_soap ( self::PrivilegeService, $para, 'Validate' );
		print_r ( $response );
		$result = $response->ValidateResult;
		if ($result->ExecuteStatu == 0) {
			return array ('ExecuteStatu' => 0, 'Message' => $result->Message );
		}
		return FALSE;
	}
	public function AuthorizeWithUserType($userID, $userType, $IPAddress) {
		$para = array ('userID' => $userID, 'userType' => $userType, 'subSystemCode' => $this->SubSystemCode, 'IPAddress' => $IPAddress );
		$response = $this->_soap ( self::PrivilegeService, $para, 'AuthorizeWithUserType' );
		
		$result = $response->AuthorizeWithUserTypeResult;
		if ($result->ExecuteStatu == 0) {
			
			return array ('ExecuteStatu' => 0, 'Message' => $result->Message );
		}
		return FALSE;
	}
	/**
	 * 产生一个报警
	 */
	public function WarningWithUserType($userid, $usertype, $ipaddr, $warningID, $warningMsg) {
		$para = array ('userid' => $userid, 'usertype' => $usertype, 'ipaddr' => $ipaddr, 'appcode' => $this->SubSystemCode, 'warningID' => $warningID, 'warningMsg' => $warningMsg );
		$this->_soap ( self::LogService, $para, 'WarningWithUserType' );
	}
	/**
	 * 写入日志
	 */
	public function WriteLogWithUserType($userid, $usertype, $ipaddr, $content, $loglevel, $operateid) {
		$para = array ('userid' => $userid, 'usertype' => $usertype, 'ipaddr' => $ipaddr, 'content' => $content, 'appcode' => $this->SubSystemCode, 'loglevel' => intval ( $loglevel ), 'operateid' => intval ( $operateid ) );
		$this->_soap ( self::LogService, $para, 'WriteLogWithUserType' );
	}
	/**
	 * 写入日志并产生一个报警
	 *
	 */
	public function WriteLogWithWarning($userid, $usertype, $ipaddr, $content, $loglevel, $operateid, $warningID, $warningMsg) {
		$para = array ('userid' => $userid, 'usertype' => $usertype, 'ipaddr' => $ipaddr, 'content' => $content, 'appcode' => $this->SubSystemCode, 'loglevel' => intval ( $loglevel ), 'operateid' => intval ( $operateid ), 'warningID' => intval ( $warningID ), 'warningMsg' => $warningMsg );
		$this->_soap ( self::LogService, $para, 'WriteLogWithWarning' );
	}
	public function __destruct() {
	}
}
?>