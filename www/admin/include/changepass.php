<?php require_once 'function.php';
$password=$_REQUEST['password'];
$uid=$_SESSION['ss_userid'];
if(User::ss_change_pass($uid,$password)=='200')echo '200';
?>