<?php
if(isset($_REQUEST['articleid'])&&isset($_COOKIE['ss_userid'])&&isset($_COOKIE['ss_username'])&&isset($_COOKIE['ss_password']))
{
	$userid=$_COOKIE['ss_userid'];
	if(!User::ss_check_login($userid,$_COOKIE['ss_password']))header('Location: /login/');
	$source_aid=$is_multiple?ss_sourceid($_REQUEST['articleid']):$_REQUEST['articleid'];
	$db->ss_query('UPDATE '.$dbarr['pre'].'article_article SET goodnum = goodnum - 1 WHERE articleid = '.$source_aid);
	$sql='DELETE FROM '.$dbarr['pre'].'article_bookcase WHERE articleid = "'.$source_aid.'" AND userid = "'.$userid.'"';
	if($db->ss_query($sql))
	{
		die('删除成功');
	}
	else
	{
		die('删除失败');
	}
}
else
{
	header('Location: /login/');
}
?>