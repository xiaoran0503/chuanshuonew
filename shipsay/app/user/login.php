<?php
if(!file_exists(__THEME_DIR__.'/user/tpl_login.php'))Url::ss_errpage();
if(isset($_REQUEST['action'])&&$_REQUEST['action']=='login'&&!empty($_POST['username'])&&!empty($_POST['password']))
{
	$username=$_REQUEST['username'];
	$password=$_REQUEST['password'];
	$userarr=User::ss_login_do($username,$password);
	if(is_array($userarr))
	{
		$cookietime=60*60*24*365;
		setcookie('ss_userid',$userarr['ss_userid'],time()+$cookietime,'/');
		setcookie('ss_username',$userarr['ss_username'],time()+$cookietime,'/');
		setcookie('ss_password',$userarr['ss_password'],time()+$cookietime,'/');
		setcookie('ss_groupid',$userarr['ss_groupid'],time()+$cookietime,'/');
		echo '<script>window.location.href="/bookcase/";</script>';
	}
	else
	{
		echo '<script>alert("登陆失败");history.go(-1);</script>';
	}
}
else
{
	require_once __THEME_DIR__.'/user/tpl_login.php';
}
?>