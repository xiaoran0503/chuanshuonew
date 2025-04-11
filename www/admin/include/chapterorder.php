<?php require_once 'function.php';
if(empty($_REQUEST['fromid'])||(empty($_REQUEST['toid'])&&$_REQUEST['toid']!=0))die('没有选择章节');
$sourceid=intval($_REQUEST['sourceid']);
$tmparr=explode(',',$_REQUEST['fromid']);
$fromid=intval($tmparr[0]);
$chapterid=intval($tmparr[1]);
$toid=intval($_REQUEST['toid']);
if($fromid<$toid)
{
	$sql='UPDATE '.$dbarr['pre'].$db->get_cindex($sourceid).' SET 
   chapterorder = chapterorder - 1
   WHERE articleid = '.$sourceid.' AND chapterorder > '.$fromid.' AND chapterorder <= '.$toid;
	$db->ss_query($sql);
	unset($sql);
	$sql='UPDATE '.$dbarr['pre'].$db->get_cindex($sourceid).' SET 
   chapterorder = '.$toid.'
   WHERE chapterid = '.$chapterid;
}
else
{
	$sql='UPDATE '.$dbarr['pre'].$db->get_cindex($sourceid).' SET 
   chapterorder = chapterorder + 1
   WHERE articleid = '.$sourceid.' AND chapterorder < '.$fromid.' AND chapterorder > '.$toid;
	$db->ss_query($sql);
	unset($sql);
	$sql='UPDATE '.$dbarr['pre'].$db->get_cindex($sourceid).' SET
   chapterorder = '.($toid+1).'
   WHERE chapterid = '.$chapterid;
}
if(!$db->ss_query($sql))die('chapter表排序失败');
$lastid_sql='SELECT chapterid,chaptername FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid.' AND chaptertype = 0 ORDER BY chapterorder DESC LIMIT 1';
$tmparr=$db->ss_getone($lastid_sql);
$lastchapterid=$tmparr['chapterid'];
$lastchapter=$tmparr['chaptername'];
$articlesql='UPDATE '.$dbarr['pre'].'article_article SET 
   lastupdate = "'.date("U").'"
   ,lastchapter = "'.$lastchapter.'" 
   ,lastchapterid = "'.$lastchapterid.'" 
WHERE articleid = '.$sourceid;
if($db->ss_query($articlesql))echo "200";
?>