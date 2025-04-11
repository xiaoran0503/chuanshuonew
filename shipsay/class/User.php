<?php class User
{
	static function ss_check_login($cookie_userid,$cookie_pass)
	{
		global $dbarr;
		global $db;
		$sql='SELECT uid FROM '.$dbarr['pre'].'system_users WHERE uid = "'.$cookie_userid.'" AND pass = "'.$cookie_pass.'"';
		if($db->ss_getone($sql))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	static function ss_login_do($username,$password)
	{
		global $dbarr;
		global $db;
		$islogin=false;
		$low_version=$dbarr['words']=='size'?true:false;
		if($low_version)
		{
			$sql='SELECT uid,uname,pass,groupid FROM '.$dbarr['pre'].'system_users WHERE uname = "'.$username.'" AND pass = "'.md5($password).'"';
		}
		else
		{
			$sql='SELECT uid,uname,salt,pass,groupid FROM '.$dbarr['pre'].'system_users WHERE uname = "'.$username.'"';
		}
		if($resarr=$db->ss_getone($sql))
		{
			if($low_version)
			{
				$islogin=true;
			}
			else
			{
				if($resarr['pass']===md5(md5($password).$resarr['salt']))
				{
					$islogin=true;
				}
			}
		}
		if($islogin)
		{
			$ret=array();
			$ret['ss_userid']=$resarr['uid'];
			$ret['ss_username']=$resarr['uname'];
			$ret['ss_password']=$resarr['pass'];
			$ret['ss_groupid']=$resarr['groupid'];
		}
		else
		{
			$ret='login failed';
		}
		return $ret;
	}
	static function ss_new_user($username,$password,$email)
	{
		global $dbarr;
		global $db;
		$res=$db->ss_getone('SELECT uname FROM '.$dbarr['pre'].'system_users WHERE uname = "'.$username.'"');
		if($res)
		{
			return '用户名已注册';
		}
		$res=$db->ss_getone('SELECT uname FROM '.$dbarr['pre'].'system_users WHERE email = "'.$email.'"');
		if($res)
		{
			return '邮箱已注册';
		}
		$low_version=$dbarr['words']=='size'?true:false;
		if($low_version)
		{
			$sql='INSERT INTO '.$dbarr['pre'].'system_users (uname,name,pass,groupid,regdate,email) VALUES ("'.$username.'","'.$username.'","'.md5($password).'","3","'.date("U").'","'.$email.'")';
		}
		else
		{
			$salt=substr(md5(uniqid(rand(),true)),-16);
			$tmppass=md5(md5($password).$salt);
			$sql='INSERT INTO '.$dbarr['pre'].'system_users (uname,name,pass,groupid,regdate,email,salt) VALUES ("'.$username.'","'.$username.'","'.$tmppass.'","3","'.date("U").'","'.$email.'","'.$salt.'")';
		}
		$db->ss_query($sql);
		return SELF::ss_login_do($username,$password);
	}
	static function ss_change_pass($uid,$newpass)
	{
		global $dbarr;
		global $db;
		$low_version=$dbarr['words']=='size'?true:false;
		if($low_version)
		{
			$sql='UPDATE '.$dbarr['pre'].'system_users SET 
                   pass = "'.md5($newpass).' "
               WHERE uid = '.$uid;
		}
		else
		{
			$salt=substr(md5(uniqid(rand(),true)),-16);
			$pass=md5(md5($newpass).$salt);
			$sql='UPDATE '.$dbarr['pre'].'system_users SET 
                   pass = "'.$pass.'"
                   ,salt = "'.$salt.'"
               WHERE uid = '.$uid;
		}
		if($db->ss_query($sql))return "200";
	}
}
?>