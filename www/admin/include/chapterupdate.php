<?php require_once 'function.php';
switch($_REQUEST['do'])
{
	case 'update':$sql='UPDATE '.$dbarr['pre'].$db->get_cindex($_REQUEST['sourceid']).' SET 
               chaptername = "'.$_REQUEST['chaptername'].'"
               ,'.$dbarr['words'].' = '.intval(mb_strlen($_REQUEST['content'])).' 
           WHERE chapterid = '.intval($_REQUEST['chapterid']);
	if(ss_writefile($_REQUEST['chapter_file'],$_REQUEST['content'])&&$db->ss_query($sql))
	{
		echo "200";
	}
	;
	break;
	case 'delete':$sourceid=intval($_REQUEST['sourceid']);
	$chapterids=$_REQUEST['ids'];
	$err=false;
	foreach($chapterids as $k=>$v)
	{
		$chapter_file=$root_dir.'/files/article/txt/'.intval($sourceid/1000).'/'.$sourceid.'/'.intval($v).'.txt';
		$sql='DELETE FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE chapterid = '.intval($v);
		if(!($db->ss_query($sql)&&unlink($chapter_file)))
		{
		}
	}
	$lastid_sql='SELECT chapterid,chaptername FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid.' AND chaptertype = 0 ORDER BY chapterorder DESC LIMIT 1';
	$tmparr=$db->ss_getone($lastid_sql);
	$lastchapterid=$tmparr['chapterid'];
	$lastchapter=$tmparr['chaptername'];
	$articlesql='UPDATE '.$dbarr['pre'].'article_article SET 
           lastupdate = "'.date("U").'"
           ,lastchapter = "'.$lastchapter.'" 
           ,lastchapterid = "'.$lastchapterid.'" 
           ,chapters = chapters - '.count($chapterids).'
       WHERE articleid = '.$sourceid;
	if($db->ss_query($articlesql)&&!$err)echo "200";
	break;
	case 'clean':$sourceid=intval($_REQUEST['sourceid']);
	$err=false;
	$txt_folder=$root_dir.'/files/article/txt/'.intval($sourceid/1000).'/'.$sourceid;
	$att_folder=$root_dir.'/files/article/attachement/'.intval($sourceid/1000).'/'.$sourceid;
	Ss::ss_delfolder($txt_folder);
	Ss::ss_delfolder($att_folder);
	$sql='DELETE FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.intval($sourceid);
	if(!$db->ss_query($sql))die('删除chapter表的相关内容失败');
	$lastid_sql='SELECT chapterid,chaptername FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid.' AND chaptertype = 0 ORDER BY chapterorder DESC LIMIT 1';
	$lastchapterid=0;
	$lastchapter='';
	$articlesql='UPDATE '.$dbarr['pre'].'article_article SET 
           '.$dbarr['words'].' = 0
           ,lastchapter = "'.$lastchapter.'" 
           ,lastchapterid = "'.$lastchapterid.'" 
       WHERE articleid = '.$sourceid;
	if($db->ss_query($articlesql))echo "200";
	break;
	case 'newchapter':$sourceid=intval($_REQUEST['sourceid']);
	$chaptername=$_REQUEST['chaptername'];
	$content=$_REQUEST['content'];
	$words=strlen($content);
	$wordstr=$sys_ver<2.4?'size':'words';
	$sql='SELECT MAX(chapterorder) as maxid FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid;
	if($res=$db->ss_getone($sql))
	{
		$chapterorder=intval($res['maxid'])+1;
	}
	else
	{
		die('没有该小说数据');
	}
	$articlename=$_REQUEST['articlename'];
	$insertsql='INSERT INTO '.$dbarr['pre'].$db->get_cindex($sourceid).' 
                   (articleid,articlename,chaptername,postdate,lastupdate,chapterorder,'.$wordstr.',chaptertype,posterid,poster) VALUES 
                   ("'.$sourceid.'","'.$articlename.'","'.$chaptername.'","'.date("U").'","'.date("U").'","'.$chapterorder.'","'.$words.'","0","1","admin")';
	if(!$db->ss_query($insertsql))die('chapter表写入失败');
	$chapteridsql='SELECT chapterid FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid.' AND chaptertype = 0 ORDER BY chapterorder DESC LIMIT 1';
	$chapterid=$db->ss_getone($chapteridsql)['chapterid'];
	$chapter_file=$root_dir.'/files/article/txt/'.intval($sourceid/1000).'/'.$sourceid.'/'.$chapterid.'.txt';
	$articlesql='UPDATE '.$dbarr['pre'].'article_article SET 
           lastupdate = "'.date("U").'"
           ,'.$wordstr.' = '.$wordstr.' + '.$words.'
           ,lastchapter = "'.$chaptername.'" 
           ,lastchapterid = "'.$chapterid.'" 
           ,chapters = chapters + 1
       WHERE articleid = '.$sourceid;
	if(ss_writefile($chapter_file,$content)&&$db->ss_query($articlesql))echo "200";
	break;
}
?>