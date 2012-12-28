<?php
if (!defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
        
class Function_class {
	
	function __construct() {
	}
	
        function saveFile($path, $name, $content) {
		header ( "content-Type: text/html; charset=Utf-8" );
		$fp = fopen ( $path . $name . '', 'w+' ); //fopen()的其它开关请参看相关函数
		$str = $content;
		fputs ( $fp, $str );
		fclose ( $fp );
	}
	
        function stringToByteArray($str) {    
            $str = iconv('UTF-8','UTF-8',$str);  
            preg_match_all('/(.)/s',$str,$bytes);  
            $bytes=array_map('ord',$bytes[1]) ;  
            return $bytes;            
        }
        
        function byteArrayToString($bytes) {   
            $bytes=array_map('chr',$bytes);  
            $str=implode('',$bytes);  
            $str = iconv('UTF-8','UTF-8',$str);
            return $str;            
        }  
        
        function genRandomString($len) {
		$chars = array ("1", "2", "3", "4", "5", "6", "7", "8", "9" );
		$charsLen = count ( $chars ) - 1;
		shuffle ( $chars ); // 将数组打乱 
		$output = "";
		for($i = 0; $i < $len; $i ++) {
			$output .= $chars [mt_rand ( 0, $charsLen )];
		}
		return $output;
	}
	
	function autowrapstring($str, $len) {
		$content = "";
		for($i = 0; $i < mb_strlen ( $str ); $i ++) {
			$letter [] = mb_substr ( $str, $i, 1 );
		}
		for($k = 0; $k < count ( $letter ); $k ++) {
			$content .= $letter [$k];
			if (fmod ( $k, $len ) == 0 && $k != 0) {
				$content .= "<br />";
			}
		}
		return $content;
	}
	
	function getXml($file) {
		$doc = new DOMDocument ();
		$doc->load ( $file );
		return $doc;
	}
	
	function getModulesXml($node) {
		$m = $this->getXml($_SERVER['DOCUMENT_ROOT'].'/res/config/modules.xml');
		$r = $m->getElementsByTagName("Menu")->item(0)->getElementsByTagName($node)->item(0)->getElementsByTagName("name");
		return $r;
	}
        
        function getTTmOutModulesXml($node) {
		$m = $this->getXml($_SERVER['DOCUMENT_ROOT'].'/res/config/modules_ttmobileout.xml');
		$r = $m->getElementsByTagName("Menu")->item(0)->getElementsByTagName($node)->item(0)->getElementsByTagName("name");
		return $r;
	}
	
	function getMonitorModulesXml($node) {
		$m = $this->getXml($_SERVER['DOCUMENT_ROOT'].'/res/config/modules_monitor.xml');
		$r = $m->getElementsByTagName("Menu")->item(0)->getElementsByTagName($node)->item(0)->getElementsByTagName("name");
		return $r;
	}
	
	function getNodesXml($node) {
		$m = getXml ( $_SERVER ['DOCUMENT_ROOT'] . '/res/config/node.xml' );
		$r = $m->getElementsByTagName ( "Nodes" )->item ( 0 )->getElementsByTagName ( $node )->item ( 0 )->getElementsByTagName ( "name" );
		return $r;
	}
	
	function getGameXml($node) {
		$m = $this->getXml ( $_SERVER ['DOCUMENT_ROOT'] . '/res/config/game.xml' );
		$r = $m->getElementsByTagName ( "Games" )->item ( 0 )->getElementsByTagName ( $node )->item ( 0 )->getElementsByTagName ( "GameInfo" );
		return $r;
	}
	
	function getGameList($node) {
		$m = $this->getXml ( $_SERVER ['DOCUMENT_ROOT'] . '/res/config/game/game.xml' );
		$r = $m->getElementsByTagName ( "Games" )->item ( 0 )->getElementsByTagName ( $node )->item ( 0 )->getElementsByTagName ( "GameInfo" );
		return $r;
	}
	
	function changeXmlNode($parent, $node, $index1, $index2) {
		// 从上一级节点获取node节点
		$nodes = $parent->getElementsByTagName ( $node );
		
		// 分别找出2个节点的内容
		$node1 = $nodes->item ( $index1 );
		$node2 = $nodes->item ( $index2 );
		
		//创建临时节点
		$tmp1 = $node1->cloneNode ( true );
		$tmp2 = $node2->cloneNode ( true );
		
		// 替换相应的内容
		$parent->replaceChild ( $tmp2, $node1 );
		$parent->replaceChild ( $tmp1, $node2 );
	}
	
	function getGroupList() {
		return array ("管理员", "运营", "运维", "研发", "第三方" );
	}
	
	function getByteToMb($bytes = 0) {
		return ceil ( $bytes / 1024 / 1024 );
	}
	
	function getByteToKbPerS($bytes = 0) {
		return ceil ( $bytes / 1024 / 300 );
	}
	
	function getFormattime($num, $arr = array()) {
		if ($num >= 86400) {
			$arr ["day"] = sprintf ( '%d', ($num / 86400) );
			$num -= $arr ['day'] * 86400;
			if ($num) {
				return getFormattime ( $num, $arr );
			} else {
				return $arr;
			}
		} elseif ($num >= 3600) {
			$arr ["hour"] = sprintf ( "%d", ($num / 3600) );
			$num -= $arr ['hour'] * 3600;
			return getFormattime ( $num, $arr );
		} elseif ($num >= 60) {
			$arr ["minute"] = sprintf ( "%d", ($num / 60) );
			$arr ["second"] = sprintf ( "%d", $num - $arr ['minute'] * 60 );
			return $arr;
		} elseif ($num < 60) {
			$arr ["second"] = $num;
			return $arr;
		}
	}
	
	function getFormattime1($num) {
		$timeall = getFormattime ( $num );
		if (! isset ( $timeall ["day"] )) {
			$timeall ["day"] = 0;
		}
		if (! isset ( $timeall ["hour"] )) {
			$timeall ["hour"] = 0;
		}
		if (! isset ( $timeall ["minute"] )) {
			$timeall ["minute"] = 0;
		}
		return $timeall ["day"] . "天" . $timeall ["hour"] . "小时" . $timeall ["minute"] . "分钟" . $timeall ["second"] . "秒";
	}
	
	function getFormattime2($minute) {
		$h = floor ( $minute / 60 );
		$m = $minute % 60;
		
		if ($minute < 240) {
			$h2 = floor ( ($minute + 5) / 60 );
			$m2 = ($minute + 5) % 60;
		} else {
			$h2 = floor ( ($minute + 60) / 60 );
			$m2 = ($minute + 60) % 60;
		}
		
		return $h . "时" . $m . "分-" . $h2 . "时" . $m2 . "分";
	}
	
	function getFormattime3($second) {
		$s = $second % 60;
		$minute = floor ( $second / 60 );
		$m = $minute % 60;
		$h = floor ( $minute / 60 );
		
		return $h . "时" . $m . "分" . $s . "秒";
	}
	
	function bubble_sort($array, $field) {
		$count = count ( $array );
		if ($count <= 0)
			return false;
		for($i = 0; $i < $count; $i ++) {
			for($j = $count - 1; $j > $i; $j --) {
				if ($array [$j] ["$field"] > $array [$j - 1] ["$field"]) {
					$tmp = $array [$j];
					$array [$j] = $array [$j - 1];
					$array [$j - 1] = $tmp;
				}
			}
		}
		return $array;
	}
	
	function IPtoLongInt($ip) {
		return bindec ( decbin ( ip2long ( $ip ) ) );
	}
	
	function IntToIP($ip) {
		$ip_string = "";
		$ip_string = ($ip & 255);
		$ip >>= 8;
		$ip_string .= ".";
		$ip_string .= ($ip & 255);
		$ip >>= 8;
		$ip_string .= ".";
		$ip_string .= ($ip & 255);
		$ip >>= 8;
		$ip_string .= ".";
		$ip_string .= ($ip & 255);
		return $ip_string;
	}
	
	function fetchUrl($url) {
		//初始化 curl
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		//curl_setopt($ch,CURLOPT_REFERER,"");
		//curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		//curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		$html = curl_exec ( $ch );
		curl_close ( $ch );
		return $html;
	}
	
	function sendTTkey($msg, $mobile) {
		$msg = urlencode ( iconv ( "UTF-8", "gb2312//IGNORE", $msg ) );
		$url = "http://sms.sdo.com:9090/submit.asp?cpid=SDCHDB&pwd=U82HYT30&pid=500108&phone=$mobile&msg=$msg";
		$sendrst = $this->fetchUrl ( $url );
	}
	
	function dec_to_hex($dec) {
		$h = "";
		//$sign = ""; // suppress errors
		//if( $dec < 0){ $sign = "-"; $dec = abs($dec); }
		

		$hex = Array (0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 'a', 11 => 'b', 12 => 'c', 13 => 'd', 14 => 'e', 15 => 'f' );
		
		do {
			$h = $hex [($dec & 15)] . $h;
			$dec >>= 4; ///= 16;
		} while ( $dec >= 1 );
		
		return $h;
	}
	
	function str4empty($str) {
		$strLen = strlen ( $str );
		$stri = $strLen % 4;
		$strNew = hexdec ( substr ( $str, 0, $stri ) );
		for($i = 1; $i <= intval ( $strLen / 4 ); $i ++) {
			$strNew = $strNew . '.' . hexdec ( substr ( $str, $stri, 4 ) );
			$stri = $stri + 4;
		}
		return trim ( $strNew );
	}
	
	//转换ET版本号
	function formatEtVersion($version) {
		return $this->str4empty ( $version );
	
		//return $this->dec_to_hex($version);       
	}
	
	function getTopUrl() {
		$url = 'http://' . $_SERVER ['SERVER_NAME'] . (($_SERVER ["SERVER_PORT"] === "80") ? "" : ":" . $_SERVER ["SERVER_PORT"]) . $_SERVER ['REQUEST_URI'];
		return $url;
	}
	
        function clearDir($pathdir)   
        {       
                $d=dir($pathdir);   
                while($a=$d->read())   
                {
                    if(is_file($pathdir.'/'.$a) && ($a!='.') && ($a!='..')){unlink($pathdir.'/'.$a);}  
                }   
                $d->close();              
        }

	function convertip($ip)
        {		
		$return = '';
		
		if (preg_match ( "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip )) {
			
			$iparray = explode ( '.', $ip );
			
			if ($iparray [0] == 10 || $iparray [0] == 127 || ($iparray [0] == 192 && $iparray [1] == 168) || ($iparray [0] == 172 && ($iparray [1] >= 16 && $iparray [1] <= 31))) {
				$return = '- LAN';
			} elseif ($iparray [0] > 255 || $iparray [1] > 255 || $iparray [2] > 255 || $iparray [3] > 255) {
				$return = '- Invalid IP Address';
			} else {
				//$tinyipfile = $_SERVER["DOCUMENT_ROOT"].'/res/iptable/tinyipdata.dat';
				$fullipfile = $_SERVER ["DOCUMENT_ROOT"] . '/res/iptable/qqwry.dat';
				/*if(@file_exists($tinyipfile)) {
				$return = convertip_tiny($ip, $tinyipfile);
			} elseif(@file_exists($fullipfile)) {
				$return = convertip_full($ip, $fullipfile);
			}*/
				$return = $this->convertip_full ( $ip, $fullipfile );
			}
		}
		
		return $return;
	
	}
	
	function convertip_tiny($ip, $ipdatafile)
        {
		
		static $fp = NULL, $offset = array (), $index = NULL;
		
		$ipdot = explode ( '.', $ip );
		$ip = pack ( 'N', ip2long ( $ip ) );
		
		$ipdot [0] = ( int ) $ipdot [0];
		$ipdot [1] = ( int ) $ipdot [1];
		
		if ($fp === NULL && $fp = @fopen ( $ipdatafile, 'rb' )) {
			$offset = unpack ( 'Nlen', fread ( $fp, 4 ) );
			$index = fread ( $fp, $offset ['len'] - 4 );
		} elseif ($fp == FALSE) {
			return '- Invalid IP data file';
		}
		
		$length = $offset ['len'] - 1028;
		$start = unpack ( 'Vlen', $index [$ipdot [0] * 4] . $index [$ipdot [0] * 4 + 1] . $index [$ipdot [0] * 4 + 2] . $index [$ipdot [0] * 4 + 3] );
		
		for($start = $start ['len'] * 8 + 1024; $start < $length; $start += 8) {
			
			if ($index {$start} . $index {$start + 1} . $index {$start + 2} . $index {$start + 3} >= $ip) {
				$index_offset = unpack ( 'Vlen', $index {$start + 4} . $index {$start + 5} . $index {$start + 6} . "\x0" );
				$index_length = unpack ( 'Clen', $index {$start + 7} );
				break;
			}
		}
		
		fseek ( $fp, $offset ['len'] + $index_offset ['len'] - 1024 );
		if ($index_length ['len']) {
			return '- ' . fread ( $fp, $index_length ['len'] );
		} else {
			return '- Unknown';
		}
	
	}
	
	function convertip_full($ip, $ipdatafile)
        {
		
		if (! $fd = @fopen ( $ipdatafile, 'rb' )) {
			return '- Invalid IP data file';
		}
		
		$ip = explode ( '.', $ip );
		$ipNum = $ip [0] * 16777216 + $ip [1] * 65536 + $ip [2] * 256 + $ip [3];
		
		if (! ($DataBegin = fread ( $fd, 4 )) || ! ($DataEnd = fread ( $fd, 4 )))
			return;
		@$ipbegin = implode ( '', unpack ( 'L', $DataBegin ) );
		if ($ipbegin < 0)
			$ipbegin += pow ( 2, 32 );
		@$ipend = implode ( '', unpack ( 'L', $DataEnd ) );
		if ($ipend < 0)
			$ipend += pow ( 2, 32 );
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
		
		$BeginNum = $ip2num = $ip1num = 0;
		$ipAddr1 = $ipAddr2 = '';
		$EndNum = $ipAllNum;
		
		while ( $ip1num > $ipNum || $ip2num < $ipNum ) {
			$Middle = intval ( ($EndNum + $BeginNum) / 2 );
			
			fseek ( $fd, $ipbegin + 7 * $Middle );
			$ipData1 = fread ( $fd, 4 );
			if (strlen ( $ipData1 ) < 4) {
				fclose ( $fd );
				return '- System Error';
			}
			$ip1num = implode ( '', unpack ( 'L', $ipData1 ) );
			if ($ip1num < 0)
				$ip1num += pow ( 2, 32 );
			
			if ($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}
			
			$DataSeek = fread ( $fd, 3 );
			if (strlen ( $DataSeek ) < 3) {
				fclose ( $fd );
				return '- System Error';
			}
			$DataSeek = implode ( '', unpack ( 'L', $DataSeek . chr ( 0 ) ) );
			fseek ( $fd, $DataSeek );
			$ipData2 = fread ( $fd, 4 );
			if (strlen ( $ipData2 ) < 4) {
				fclose ( $fd );
				return '- System Error';
			}
			$ip2num = implode ( '', unpack ( 'L', $ipData2 ) );
			if ($ip2num < 0)
				$ip2num += pow ( 2, 32 );
			
			if ($ip2num < $ipNum) {
				if ($Middle == $BeginNum) {
					fclose ( $fd );
					return '- Unknown';
				}
				$BeginNum = $Middle;
			}
		}
		
		$ipFlag = fread ( $fd, 1 );
		if ($ipFlag == chr ( 1 )) {
			$ipSeek = fread ( $fd, 3 );
			if (strlen ( $ipSeek ) < 3) {
				fclose ( $fd );
				return '- System Error';
			}
			$ipSeek = implode ( '', unpack ( 'L', $ipSeek . chr ( 0 ) ) );
			fseek ( $fd, $ipSeek );
			$ipFlag = fread ( $fd, 1 );
		}
		
		if ($ipFlag == chr ( 2 )) {
			$AddrSeek = fread ( $fd, 3 );
			if (strlen ( $AddrSeek ) < 3) {
				fclose ( $fd );
				return '- System Error';
			}
			$ipFlag = fread ( $fd, 1 );
			if ($ipFlag == chr ( 2 )) {
				$AddrSeek2 = fread ( $fd, 3 );
				if (strlen ( $AddrSeek2 ) < 3) {
					fclose ( $fd );
					return '- System Error';
				}
				$AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
				fseek ( $fd, $AddrSeek2 );
			} else {
				fseek ( $fd, - 1, SEEK_CUR );
			}
			
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr2 .= $char;
			
			$AddrSeek = implode ( '', unpack ( 'L', $AddrSeek . chr ( 0 ) ) );
			fseek ( $fd, $AddrSeek );
			
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr1 .= $char;
		} else {
			fseek ( $fd, - 1, SEEK_CUR );
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr1 .= $char;
			
			$ipFlag = fread ( $fd, 1 );
			if ($ipFlag == chr ( 2 )) {
				$AddrSeek2 = fread ( $fd, 3 );
				if (strlen ( $AddrSeek2 ) < 3) {
					fclose ( $fd );
					return '- System Error';
				}
				$AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
				fseek ( $fd, $AddrSeek2 );
			} else {
				fseek ( $fd, - 1, SEEK_CUR );
			}
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr2 .= $char;
		}
		fclose ( $fd );
		
		if (preg_match ( '/http/i', $ipAddr2 )) {
			$ipAddr2 = '';
		}
		$ipaddr = "$ipAddr1 $ipAddr2";
		$ipaddr = preg_replace ( '/CZ88\.NET/is', '', $ipaddr );
		$ipaddr = preg_replace ( '/^\s*/is', '', $ipaddr );
		$ipaddr = preg_replace ( '/\s*$/is', '', $ipaddr );
		if (preg_match ( '/http/i', $ipaddr ) || $ipaddr == '') {
			$ipaddr = '- Unknown';
		}
		
		return '- ' . $ipaddr;	
	}
        
                        
        function TTMarket()
        {
            $market = array("101"=>"N多网",
                            "102"=>"机锋市场",
                            "103"=>"安卓市场",
                            "104"=>"安卓官网",
                            "105"=>"安智市场",
                            "106"=>"十字猫",
                            "107"=>"优亿",
                            "108"=>"应用汇",
                            "109"=>"木蚂蚁",
                            "110"=>"安卓在线",
                            "111"=>"雨林木风",
                            "112"=>"pchome",
                            "113"=>"pconline",
                            "114"=>"张江周边",
                            "115"=>"张江饭店",
                            "116"=>"张江学生公寓",
                            "117"=>"墨迹天气",
                            "118"=>"三星APP",
                            "119"=>"SDO官网",
                            "120"=>"ET加速器",
                            "121"=>"天天动听",
                            "122"=>"安丰网",
                            "123"=>"爱卓网",
                            "124"=>"万普世纪",
                            "125"=>"统一下载",
                            "126"=>"网易",
                            "127"=>"爱扒啦安卓网",
                            "128"=>"机安市场",
                            "129"=>"Hot安卓市场",
                            "130"=>"安卓软件园",
                            "131"=>"指点传媒",
                            "132"=>"指点传媒",
                            "133"=>"指点传媒",
                            "134"=>"安机市场",
                            "135"=>"安极网",
                            "136"=>"360",
                            "137"=>"UCWEB",
                            "138"=>"移动MM商城",
                            "200"=>"官网",
                            "201"=>"邀请",
                            "202"=>"有你",
                            "300"=>"其余",
                            "401"=>"索爱合作",
                            "402"=>"校推",
                            "403"=>"校推",
                            "404"=>"校推",
                            "405"=>"校推",
                            "406"=>"校推",
                            "500"=>"统一推荐",
                            "501"=>"网航",
                            "502"=>"万普世纪",
                            "503"=>"中润无限",
                            "504"=>"中润无限",
                            "505"=>"中润无限",
                            "506"=>"中润无限",
                            "507"=>"中润无限",
                            "508"=>"中润无限",
                            "509"=>"中润无限",
                            "510"=>"中润无限",
                            "511"=>"指点传媒",
                            "512"=>"指点传媒",
                            "513"=>"指点传媒",
                            "514"=>"指点传媒",
                            "515"=>"指点传媒",
                            "516"=>"指点传媒",
                            "517"=>"指点传媒",
                            "518"=>"琴剑听月",
                            "519"=>"中润",
                            "520"=>"安丰网",
                            "521"=>"文件大师",
                            "522"=>"智娱乐",
                            "523"=>"琴剑听月",
                            "524"=>"安智个人",
                            "525"=>"当乐安致",
                            "526"=>"宏恩汇通",
                            "529"=>"点知",
                            "530"=>"点知",
                            "531"=>"点知",
                            "532"=>"点知",
                            "533"=>"万谱世纪",
                            "534"=>"万谱世纪",
                            "535"=>"万谱世纪",
                            "536"=>"九牛传媒",
                            "537"=>"万谱世纪",
                            "538"=>"万谱世纪",
                            "539"=>"中润无限",
                            "540"=>"中润无限",
                            "541"=>"中润无限",
                            "542"=>"中润无限",
                            "543"=>"中润无限",
                            "544"=>"九牛传媒",
                            "545"=>"九牛传媒",
                            "546"=>"九牛传媒",
                            "547"=>"九牛传媒",
                            "548"=>"黄历天气",
                            "549"=>"海豚浏览器",
                            "550"=>"点入广告",
                            "568"=>"未知",
                            "579"=>"AppStore",                            
                            "601"=>"HTC所有",
                            "602"=>"三星I9100/900",
                            "603"=>"LG970/990/920",
                            "604"=>"摩托戴妃",
                            "605"=>"HTC A510/S510/S710",
                            "606"=>"三星所有",
                            "607"=>"HTC全部",
                            "608"=>"HTC全部",
                            "609"=>"HTC全部",
                            "610"=>"HTC全部",
                            "611"=>"HTC全部",
                            "612"=>"HTC全部",
                            "613"=>"HTC全部",
                            "614"=>"HTC全部",
                            "615"=>"HTC全部",
                            "616"=>"HTC全部",
                            "617"=>"HTC全部",
                            "618"=>"HTC全部",
                            "619"=>"HTC全部",
                            "620"=>"HTC全部",
                            "621"=>"HTC全部",
                            "622"=>"HTC全部",
                            "623"=>"HTC全部",
                            "624"=>"HTC全部",
                            "625"=>"HTC全部",
                            "626"=>"HTC全部",
                            "627"=>"北京帆悦",
                            "628"=>"北京帆悦",
                            "629"=>"北京帆悦",
                            "630"=>"北京帆悦",
                            "631"=>"北京帆悦",
                            "632"=>"北京帆悦",
                            "633"=>"安丰",
                            "634"=>"安丰",
                            "635"=>"安丰",
                            "636"=>"安丰",
                            "637"=>"安丰",
                            "638"=>"北京帆悦",
                            "639"=>"北京帆悦",
                            "640"=>"北京帆悦",
                            "641"=>"新星科技",
                            "642"=>"新星科技",
                            "5629"=>"北京帆悦",
                            "5630"=>"北京帆悦",
                            "5631"=>"北京帆悦",
                            "5632"=>"北京帆悦",
                            "633"=>"安丰",
                            "634"=>"安丰",
                            "635"=>"安丰",
                            "636"=>"安丰",
                            "637"=>"安丰",
                            "638"=>"北京帆悦",
                            "639"=>"北京帆悦",
                            "640"=>"北京帆悦",
                            "641"=>"新星科技",
                            "642"=>"新星科技",
                            "643"=>"华友",
                            "644"=>"华友",
                            "645"=>"华友",
                            "646"=>"渠道7",
                            "647"=>"渠道7",
                            "648"=>"渠道7",
                            "649"=>"渠道7",
                            "650"=>"渠道7",
                            "651"=>"渠道8",
                            "652"=>"渠道8",
                            "653"=>"渠道8",
                            "654"=>"渠道8",
                            "655"=>"渠道8",
                            "656"=>"讯捷",
                            "657"=>"新星科技",
                            "658"=>"待定",
                            "659"=>"待定",
                            "701"=>"weibo",
                            "702"=>"renren",
                            "801"=>"活动1",
                            "1000"=>"91助手",
                            "1001"=>"威锋",
                            "1002"=>"同步推",
                            "10000"=>"AppStore",
                            "200005"=>"海豚浏览器ios版",
                            "200006"=>"墨迹天气ios版",
                            "310001"=>"S60v3 官网",
                            "310002"=>"S60v3 腾讯",
                            "310003"=>"S60v3 当乐",
                            "310004"=>"S60v3 3G门户",
                            "310005"=>"S60v3 360",
                            "310006"=>"S60v3 中关村",
                            "310007"=>"S60v3 易查",
                            "310008"=>"S60v3 千尺",
                            "320001"=>"S60v5 官网",
                            "320002"=>"S60v5 腾讯",
                            "320003"=>"S60v5 当乐",
                            "320004"=>"S60v5 3G门户",
                            "320005"=>"S60v5 360",
                            "320006"=>"S60v5 中关村",
                            "320007"=>"S60v5 易查",
                            "320008"=>"S60v3 千尺",
                            "330001"=>"Symbian^3 官网",
                            "330002"=>"Symbian^3腾讯",
                            "330003"=>"Symbian^3 当乐",
                            "330004"=>"Symbian^3 3G门户",
                            "330005"=>"Symbian^3 360",
                            "330006"=>"Symbian^3 中关村",
                            "330007"=>"Symbian^3 易查",
                            "330008"=>"Symbian^3 千尺",
                            "0"=>"未标记");
            return $market;
        }
        
	function telcomMobile()
	{
		$r = array(134,135,136,137,138,139,150,151,152,157,158,159,182,187,188);
		return $r;
	}
	function telcomUnion()
	{
		$r = array(130,131,132,155,156,185,186);
		return $r;
	}
	function telcomTel()
	{
		$r = array(133,153,180,189);
		return $r;
	}
	
	function getTelCom($m)
	{
		$mobile = $this->telcomMobile();
		$union = $this->telcomUnion();
		$tel = $this->telcomTel();
		
		if(in_array($m,$mobile))
		{
			return "移动";
		}
		if(in_array($m,$union))
		{
			return "联通";
		}
		if(in_array($m,$tel))
		{
			return "电信";
		}
	}
	
        function getTTmMarket($sid)
	{
            $markets = $this->TTMarket();
            return $markets["$sid"];	
	}
        
        function getTTmMarketSelect()
        {
            $str = "";
            $markets = $this->TTMarket();
            foreach($markets as $k=>$v)
	    {
		$str .= "<option value='$k'>$k $v</option>";
            }
            return $str;
        }
        
        function fixdate($date)
	{
		$str = "";
		if($date>110930 && $date < 120101)
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
        
        function fixTTLoginTable($d,$f="*")
        {/*db_user_login_service_*/
		if($d <=20111220)
		{
			return "db_user_login_service_$d.t_login_list";
		}		
		else
		{
			$str = "(";
			for($i=0;$i<128;$i++)
			{			
				$str .= "select $f from db_user_login_reg_$d.t_login_list_$i ";
				if($i != 127)
				{
					$str .= "union all ";
				}
			}
			$str .= ") as TTLoginTable";
			return $str;
		}
        }
        
        function GetIP()  
        {  
            if(!empty($_SERVER["HTTP_CLIENT_IP"]))  
               $cip = $_SERVER["HTTP_CLIENT_IP"];  
            else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))  
               $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
            else if(!empty($_SERVER["REMOTE_ADDR"]))  
               $cip = $_SERVER["REMOTE_ADDR"];  
            else  
               $cip = "0";  
            return $cip;  
        }
	
	function NtoAreaList()
	{
		$area = array(	"n1"=>"北京",
				"n2"=>"天津",
				"n3"=>"河北",
				"n4"=>"山西",
				"n5"=>"内蒙古",
				"n6"=>"辽宁",
				"n7"=>"吉林",
				"n8"=>"黑龙江",
				"n9"=>"上海",
				"n10"=>"江苏",
				"n11"=>"浙江",
				"n12"=>"安徽",
				"n13"=>"福建",
				"n14"=>"江西",
				"n15"=>"山东",
				"n16"=>"河南",
				"n17"=>"湖北",
				"n18"=>"湖南",
				"n19"=>"广东",
				"n20"=>"广西",
				"n21"=>"海南",
				"n22"=>"重庆",
				"n23"=>"四川",
				"n24"=>"贵州",
				"n25"=>"云南",
				"n26"=>"西藏",
				"n27"=>"山西",
				"n28"=>"甘肃",
				"n29"=>"青海",
				"n30"=>"宁夏",
				"n31"=>"新疆",
				"n32"=>"香港",
				"n33"=>"澳门",
				"n34"=>"台湾",
				"n35"=>"其他"
			      );		
		return $area;
	}
	
	function vipkfAgentState()
	{
		$s = array(
			"1"=>"签出",
			"2"=>"签入",
			"3"=>"准备",
			"4"=>"离席",
			"5"=>"正在呼叫用户",
			"6"=>"正在被呼叫",
			"7"=>"通话中",
			"8"=>"候线中",
			"9"=>"后处理中"
			);		
		return $s;
	}
	
	function vipkfCustomRole()
	{
		$s = array(
			"1"=>"签出",
			"2"=>"签入",
			"3"=>"准备",
			"4"=>"离席",
			"5"=>"正在呼叫用户",
			"6"=>"正在被呼叫",
			"7"=>"通话中",
			"8"=>"候线中",
			"9"=>"后处理中"
			);		
		return $s;
	}
	
	function vipQos()
	{
		$s = array(
			"1"=>"好",
			"2"=>"一般",
			"3"=>"差"
			);		
		return $s;
	}
	
	function vipQosDetail()
	{
		$s = array(
			"1"=>"有杂音",
			"2"=>"有断续",
			"3"=>"杂音断续都有",
			"4"=>"没有杂音断续"
			);		
		return $s;
	}
	
	function vipQuestion()
	{
		$s = array(
			"100"=>"通话故障",
			"101"=>"无声电话",
			"102"=>"杂音太大听不到用户声音",
			"103"=>"声音太小无法听清",
			"104"=>"对话中突然中断",
			"105"=>"用户听不到客服声音",
			"106"=>"闪断",
			"107"=>"其他异常情况",
			"200"=>"问题反馈",
			"201"=>"无法注册，验证",
			"202"=>"无法登陆",
			"203"=>"通话声音异常",
			"204"=>"无法关闭免提",
			"205"=>"无法添加或添加后看不到好友",
			"206"=>"屏幕显示错误或黑屏",
			"207"=>"功能无法使用",
			"208"=>"其他问题",
			"300"=>"常规咨询",
			"301"=>"如何使用通通功能",
			"302"=>"流量资费相关",
			"303"=>"打错电话",
			"304"=>"用户测试",
			"305"=>"其他咨询",
			"400"=>"意见建议",
			"401"=>"意见类",
			"402"=>"建议类"
			);		
		return $s;
	}
	
	function fusionData($d,&$dataset,$value,&$chartPotCount)
	{
		$tempH = date("H",strtotime($d));
		$tempI = date("i",strtotime($d));
		while(($tempH*12 + $tempI/5) > $chartPotCount)
		{
			++$chartPotCount;
			$dataset .= "<set value='' />";
		}
		++$chartPotCount;
		$dataset .= "<set value='$value' />";
	}
	
	function fusionCategory(&$category)
	{
		for($i = 0 ; $i < 12*24 ; $i++)
		{
			$text = floor($i/12).":".str_pad(($i%12)*5,2,0,STR_PAD_LEFT);
			$show = ($i%12) == 0?"1":"0";
			$category .= "<category label='$text' showName='$show'/>";
		}
	}
	
	function TTSysMsg_Type()
	{
		$s = array(
			"1"=>"安装",
			"2"=>"升级",
			"3"=>"邀请",
			"4"=>"文本",
			"5"=>"消息链接"
			);		
		return $s;
	}
	
	function TTSysMsg_ShowType()
	{
		$s = array(
			"1"=>"默认提示",
			"2"=>"消息任务栏",
			"3"=>"消息弹框提示",
			"4"=>"默认提示"
			);		
		return $s;
	}
	
	function TTSysMsg_VoiceType()
	{
		$s = array(
			"0"=>"没有声音提示",
			"1"=>"有声音提示"
			);		
		return $s;
	}
	
	function TTSysMsg_SendType()
	{
		$s = array(
			"1"=>"所有在线用户",
			"2"=>"指定用户列表"
			);		
		return $s;
	}
	
	function TTSysMsg_Stauts()
	{
		$s = array(
			"1"=>"待发送",
			"2"=>"发送完毕",
			"3"=>"待取消发送",
			"4"=>"取消发送完毕"
			);		
		return $s;
	}
	
	function TTSysMsg_Mode()
	{
		$s = array(
			"1"=>"立即发送",
			"2"=>"定时发送"
			);		
		return $s;
	}
	
	function spDiscount($d,$v,$sid,&$t)
	{				
		if(strtotime($d) >= strtotime("2011-12-02"))
		{
			if($sid >= 607 && $sid <= 626)
			{
				if($d == "2011-12-02")
				{
					$t = round($v*0.9,0);
				}
				if($d == "2011-12-03")
				{
					$t = round($v*0.85,0);
				}
				if(strtotime($d) >= strtotime("2011-12-04"))
				{
					$t = round($v*0.8,0);
				}
			}
			if($sid >= 601 && $sid <= 603)
			{
				if($d == "2011-12-02")
				{
					$t = round($v*0.95,0);
				}
				if($d == "2011-12-03")
				{
					$t = round($v*0.90,0);
				}
				if($d == "2011-12-04")
				{
					if($sid == 602)
					{
						$t = round($v*0.865,0);
					}
					else
					{
						$t = round($v*0.85,0);
					}
				}
				if(strtotime($d) >= strtotime("2011-12-05"))
				{
					if($sid == 601)
					{
						$t = round($v*0.83,0);
					}
					if($sid == 602)
					{
						$t = round($v*0.865,0);
					}
					if($sid == 603)
					{
						$t = round($v*0.82,0);
					}
					if(strtotime($d) >= strtotime("2012-01-05"))
					{
						$t = round($t*0.9,0);
					}
				}
			}
			if($sid >= 5629 && $sid <= 5632)
			{
				if(strtotime($d) == strtotime("2011-12-14") || strtotime($d) == strtotime("2011-12-15"))
				{
					$t = round($v*0.8,0);
				}
			}	
		}
		if(strtotime($d) >= strtotime("2011-12-29"))
		{
			if($sid == 121)
			{
				$t = round($v*0.95,0);
			}
		}
		if(strtotime($d) == strtotime("2012-01-04"))
		{
			if($sid == 131 || $sid == 503 || $sid == 507 ||
				($sid >= 510 && $sid <= 515))
			{
				$t = round($v*0.95,0);
			}
		}
		if(strtotime($d) == strtotime("2012-01-05"))
		{
			if($sid == 131 || $sid == 503 || $sid == 507 ||
				($sid >= 510 && $sid <= 515))
			{
				$t = round($v*0.9,0);
			}
			if($sid==642)
			{
				$t = round($v*0.95,0);
			}
		}	
		if(strtotime($d) >= strtotime("2012-01-06"))
		{
			if($sid == 131 || $sid == 503 || $sid == 507 ||
				($sid >= 510 && $sid <= 515))
			{
				$t = round($v*0.85,0);
			}
			if($sid==642)
			{
				$t = round($v*0.9,0);
			}
		}
		if(strtotime($d) >= strtotime("2012-01-10"))
		{
			if($sid == 132 || $sid == 504 || $sid == 505 || $sid == 508 ||$sid == 520 ||$sid == 525 ||$sid == 526 ||$sid == 532)
			{
				$t = round($v*0.85,0);
			}
		}
		if(strtotime($d) >= strtotime("2012-01-12"))
		{
			if($sid==529 || ($sid >= 533 && $sid <=535))
			{
				$t = round($v*0.85,0);
			}
		}		
		if(strtotime($d) >= strtotime("2012-01-30"))
		{
			if(($sid >= 131 && $sid <=132) || ($sid >= 511 && $sid <=515))
			{
				$t = round($v*0.78,0);
			}
		}
		if(strtotime($d) >= strtotime("2012-02-02"))
		{
			if($sid == 519)
			{
				$t = round($v*0.8,0);
			}
			else if($sid == 627)
			{
				$t = round($v*0.87,0);
			}
			else if($sid == 638)
			{
				$t = round($v*0.87,0);
			}
		}
		if(strtotime($d) >= strtotime("2012-02-06"))
		{
			if($sid == 604 ||$sid == 652 ||$sid == 654 ||$sid == 656)
			{
				$t = round($v*0.8,0);
			}
		}
		if(strtotime($d) >= strtotime("2012-02-07"))
		{
			if($sid == 121)
			{
				$t = round($v*0.78,0);
			}
			if($sid == 131)
			{
				$t = round($v*0.68,0);
			}
			if($sid == 132 || ($sid>=503 && $sid <= 535))
			{
				$t = round($v*0.7,0);
			}
		}
		if(strtotime($d) >= strtotime("2012-02-08"))
		{
			if($sid == 536)
			{
				$t = round($v*0.7,0);
			}
		}
		if(strtotime($d) >= strtotime("2012-02-10"))
		{
			if($sid == 542)
			{
				$t = round($v*0.7,0);
			}
			if($sid == 651)
			{
				$t = round($v*0.8,0);
			}			
		}
		if(strtotime($d) >= strtotime("2012-02-11"))
		{
			if($sid == 539)
			{
				$t = round($v*0.7,0);
			}		
		}
		if(strtotime($d) >= strtotime("2012-02-13"))
		{
			if($sid == 543)
			{
				$t = round($v*0.7,0);
			}
			if($sid == 549 || $sid == 658)
			{
				$t = round($v*0.68,0);
			}			
		}
	}
}
?>