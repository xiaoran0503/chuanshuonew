<?php
if(isset($_REQUEST['articleid'])&&isset($_COOKIE['ss_userid'])&&isset($_COOKIE['ss_username'])&&isset($_COOKIE['ss_password']))
{
	$userid=$_COOKIE['ss_userid'];
	if(!User::ss_check_login($userid,$_COOKIE['ss_password']))header('Location: /login/');
	$source_aid=$is_multiple?ss_sourceid($_REQUEST['articleid']):$_REQUEST['articleid'];
	if(isset($_REQUEST['chapterid']))
	{
		$source_cid=$is_multiple?ss_sourceid($_REQUEST['chapterid']):$_REQUEST['chapterid'];
	}
	else
	{
		$source_cid=0;
	}
	$articlename=isset($_REQUEST['articlename'])?$_REQUEST['articlename']:'';
	$chaptername=isset($_REQUEST['chaptername'])?$_REQUEST['chaptername']:'';
	$res=$db->ss_getone('SELECT caseid,articlename FROM '.$dbarr['pre'].'article_bookcase WHERE articleid = "'.$source_aid.'" AND userid = "'.$userid.'"');
	if($res)
	{
		$upsql='UPDATE '.$dbarr['pre'].'article_bookcase SET 
                   chapterid = "'.$source_cid.'",
                   chaptername = "'.$chaptername.'" 
                   WHERE articleid = "'.$source_aid.'" AND userid = "'.$userid.'"';
	}
	else
	{
		$upsql='INSERT INTO '.$dbarr['pre'].'article_bookcase 
               (articleid,articlename,userid,username,chapterid,chaptername) 
               VALUES 
               ("'.$source_aid.'","'.$articlename.'","'.$userid.'","'.$_COOKIE['ss_username'].'","'.$source_cid.'","'.$chaptername.'")';
		$db->ss_query('UPDATE '.$dbarr['pre'].'article_article SET goodnum = goodnum + 1 WHERE articleid = '.$source_aid);
	}
	if($db->ss_query($upsql))
	{
		die('添加成功');
	}
	else
	{
		die('添加失败');
	}
}
else
{
	header('Location: /login/');
}
?>