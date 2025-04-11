<?php
if(!file_exists(__THEME_DIR__.'/user/tpl_register.php'))Url::ss_errpage();
if(isset($_REQUEST['action'])&&$_REQUEST['action']=='register'&&!empty($_POST['username'])&&!empty($_POST['password'])&&!empty($_POST['email']))
{
	$username=trim($_REQUEST['username']);
	$password=trim($_REQUEST['password']);
	$email=isset($_REQUEST['email'])?trim($_REQUEST['email']):'';
	if(preg_match('/^\s*$|^c:\\con\\con$|[@%,;:\.\|\*\"\'\\\\\/\s\t\<\>\&]|　/is',$username))die('<script>alert("用户名含有非法字符");history.go(-1);</script>');
	if(strlen($password)<6)die('<script>alert("密码长度至少6位");history.go(-1);</script>');
	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$email))die('邮箱格式错误');
	$userarr=User::ss_new_user($username,$password,$email);
	if(is_array($userarr))
	{
		$cookietime=60*60*24*365;
		setcookie('ss_userid',$userarr['ss_userid'],time()+$cookietime,'/');
		setcookie('ss_username',$userarr['ss_username'],time()+$cookietime,'/');
		setcookie('ss_password',$userarr['ss_password'],time()+$cookietime,'/');
		header('Location: /bookcase/');
	}
	else
	{
		echo $userarr;
	}
}
else
{
	require_once __THEME_DIR__.'/user/tpl_register.php';
}
?>