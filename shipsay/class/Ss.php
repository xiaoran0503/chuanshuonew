<?php class Ss
{
	static function ss_get_ip()
	{
		$ip=FALSE;
		if(!empty($_SERVER["HTTP_CLIENT_IP"]))
		{
			$ip=$_SERVER["HTTP_CLIENT_IP"];
		}
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ips=explode(", ",$_SERVER['HTTP_X_FORWARDED_FOR']);
			if($ip)
			{
				array_unshift($ips,$ip);
				$ip=FALSE;
			}
			for($i=0;$i<count($ips);
			$i++)
			{
				if(!preg_match('/^(10│172.16│192.168)./is',$ips[$i]))
				{
					$ip=$ips[$i];
					break;
				}
			}
		}
		return($ip?$ip:$_SERVER['REMOTE_ADDR']);
	}
	static function ss_delfolder($dirname)
	{
		$dirname=trim($dirname);
		$handle=@opendir($dirname);
		if($handle===false)
		{
			return true;
		}
		while(($file=@readdir($handle))!==false)
		{
			if($file!='.'&&$file!='..')
			{
				if(is_dir($dirname.DIRECTORY_SEPARATOR.$file))
				{
					SELF::ss_delfolder($dirname.DIRECTORY_SEPARATOR.$file,true);
				}
				else
				{
					@unlink($dirname.DIRECTORY_SEPARATOR.$file);
				}
			}
		}
		closedir($handle);
		rmdir($dirname);
		return true;
	}
	static function is_mobile()
	{
		if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
		{
			return true;
		}
		if(isset($_SERVER['HTTP_CLIENT'])&&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
		{
			return true;
		}
		if(isset($_SERVER['HTTP_VIA']))
		{
			return stristr($_SERVER['HTTP_VIA'],'wap')?true:false;
		}
		if(isset($_SERVER['HTTP_USER_AGENT']))
		{
			$clientkeywords=array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile',);
			if(preg_match("/(".implode('|',$clientkeywords).")/i",strtolower($_SERVER['HTTP_USER_AGENT'])))
			{
				return true;
			}
		}
		if(isset($_SERVER['HTTP_ACCEPT']))
		{
			if((strpos($_SERVER['HTTP_ACCEPT'],'vnd.wap.wml')!==false)&&(strpos($_SERVER['HTTP_ACCEPT'],'text/html')===false||(strpos($_SERVER['HTTP_ACCEPT'],'vnd.wap.wml')<strpos($_SERVER['HTTP_ACCEPT'],'text/html'))))
			{
				return true;
			}
		}
		return false;
	}
	static function is_robot()
	{
		$agent=strtolower($_SERVER['HTTP_USER_AGENT']);
		if(preg_match('/spider|sogou|soso|bing|google/is',$agent))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>