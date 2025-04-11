<?php require_once 'function.php';
unset($_SESSION['ss_userid']);
unset($_SESSION['ss_username']);
unset($_SESSION['ss_password']);
unset($_SESSION['ss_groupid']);
$username=$_REQUEST['username'];
$password=$_REQUEST['password'];
$userarr=User::ss_login_do($username,$password);
if(is_array($userarr))
{
	if($userarr['ss_groupid']==2)
	{
		$_SESSION['ss_userid']=$userarr['ss_userid'];
		$_SESSION['ss_username']=$userarr['ss_username'];
		$_SESSION['ss_password']=$userarr['ss_password'];
		$_SESSION['ss_groupid']=$userarr['ss_groupid'];
		echo '200';
	}
}
?>