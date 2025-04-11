<?php
$score=intval($_REQUEST['score']);
$cookie_time=time()+12*60*60;
$sql='UPDATE '.$dbarr['pre'].'article_article  SET ratenum = ratenum+1, ratesum = ratesum+'.$score.'  WHERE articleid='.$_REQUEST['sourceid'];
if($db->ss_query($sql))
{
	setcookie("rated",$score,$cookie_time,'/');
	echo '200';
}
else
{
	echo '-1';
}
?>