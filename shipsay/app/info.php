<?php
$articleid=$sourceid=$matches[1];
$index_url=Url::index_url($articleid);
if(!file_exists(__THEME_DIR__.'/tpl_info.php'))header('Location:'.$index_url);
if($is_multiple)$sourceid=ss_sourceid($articleid);
if($is_acode)
{
	$sql=$rico_sql.'AND articlecode = "'.$sourceid.'"';
}
else
{
	$sql=$rico_sql.'AND articleid = '.$sourceid;
}
if(isset($redis))
{
	$infoarr=$redis->ss_redis_getrows($sql,$info_cache_time);
}
else
{
	$infoarr=$db->ss_getrows($sql);
}
if(!is_array($infoarr))Url::ss_errpage();
if($is_acode)$sourceid=$infoarr[0]['articleid'];
$articlename=$sourcename=$infoarr[0]['articlename'];
if($is_langtail===1)
{
	if($is_ft)$sourcename=Convert::jt2ft($sourcename,1);
	include_once __ROOT_DIR__.'/shipsay/include/langtail.php';
}
$author=$infoarr[0]['author'];
$author_arr=explode(',',$author);
$author_url=$infoarr[0]['author_url'];
$keywords=$infoarr[0]['keywords'];
$keywords_arr=explode(',',$keywords);
$img_url=$infoarr[0]['img_url'];
$sortid=$infoarr[0]['sortid'];
$sortname=$infoarr[0]['sortname'];
$isfull=$infoarr[0]['isfull'];
$words_w=$infoarr[0]['words_w'];
$intro_des=$infoarr[0]['intro_des'];
$intro_p=$infoarr[0]['intro_p'];
$allvisit=$infoarr[0]['allvisit'];
$goodnum=$infoarr[0]['goodnum'];
$ratenum=$infoarr[0]['ratenum'];
$ratesum=$infoarr[0]['ratesum'];
$score=$ratenum>0?sprintf("%.1f ",$ratesum/$ratenum):'0.0';
$sql='SELECT chapterid,chaptername,lastupdate,chaptertype,chapterorder FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid.' AND chaptertype = 0 ORDER BY chapterorder ASC';
$chapterrows=array();
if(isset($redis)&&$redis->ss_get($sql))
{
	$chapterrows=$redis->ss_get($sql);
}
else
{
	$res=$db->ss_query($sql);
	if($res->num_rows)
	{
		$k=0;
		while($row=mysqli_fetch_assoc($res))
		{
			$cid=$use_orderid?$row['chapterorder']:$row['chapterid'];
			if($is_multiple)$cid=ss_newid($cid);
			$chapterrows[$k]['chaptertype']=$row['chaptertype'];
			$chapterrows[$k]['lastupdate']=$row['lastupdate'];
			$chapterrows[$k]['cid_url']=Url::chapter_url($articleid,$cid);
			$chapterrows[$k]['cname']=Text::ss_toutf8($row['chaptername']);
			if($is_ft)$chapterrows[$k]['cname']=Convert::jt2ft($chapterrows[$k]['cname']);
			$k++;
		}
		if(isset($redis))$redis->ss_setex($sql,$info_cache_time,$chapterrows);
	}
}
$first_url=$chapterrows[0]['cid_url'];
$chapters=count($chapterrows);
$lastupdate_stamp=$chapterrows[$chapters-1]['lastupdate'];
$lastupdate=date('Y-m-d H:i:s',$lastupdate_stamp);
$lastupdate_cn=Text::ss_lastupdate($lastupdate_stamp);
$lastchapter=$chapterrows[$chapters-1]['cname'];
$last_url=$chapterrows[$chapters-1]['cid_url'];
$lastarr=array_reverse(array_slice($chapterrows,-12,12));
if($count_visit)require_once __ROOT_DIR__.'/shipsay/include/articlevisit.php';
header('Last-Modified: '.date('D, d M Y H:i:s',$lastupdate_stamp-8*60*60).' GMT');
require_once __THEME_DIR__.'/tpl_info.php';
?>