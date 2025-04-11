<?php
$articleid=$sourceid=$matches[1];
$info_url=Url::info_url($articleid);
$index_url=Url::index_url($articleid);
if(!file_exists(__THEME_DIR__.'/tpl_indexlist.php'))header('Location:'.$info_url);
if($is_acode)
{
	$sql='SELECT articleid FROM '.$dbarr['pre'].'article_article WHERE articlecode = "'.$sourceid.'"';
	$sourceid=$db->ss_getone($sql)['articleid'];
}
elseif($is_multiple)
{
	$sourceid=ss_sourceid($articleid);
}
$subaid=intval($sourceid/1000);
$pid=isset($matches[2])?$matches[2]:1;
$per_page=$per_indexlist?:100;
$sql=$rico_sql.'AND articleid = '.$sourceid;
if(isset($redis))
{
	$infoarr=$redis->ss_redis_getrows($sql,$info_cache_time);
}
else
{
	$infoarr=$db->ss_getrows($sql);
}
if(!is_array($infoarr))Url::ss_errpage();
$articlename=$sourcename=$infoarr[0]['articlename'];
if($is_langtail===1)
{
	if($is_ft)$sourcename=Convert::jt2ft($sourcename,1);
	include_once __ROOT_DIR__.'/shipsay/include/langtail.php';
}
$author=$infoarr[0]['author'];
$author_url=$infoarr[0]['author_url'];
$keywords=$infoarr[0]['keywords'];
$img_url=$infoarr[0]['img_url'];
$sortid=$infoarr[0]['sortid'];
$sortname=$infoarr[0]['sortname'];
$isfull=$infoarr[0]['isfull'];
$words_w=$infoarr[0]['words_w'];
$intro_des=$infoarr[0]['intro_des'];
$intro_p=$infoarr[0]['intro_p'];
$allvisit=$infoarr[0]['allvisit'];
$goodnum=$infoarr[0]['goodnum'];
$sql='SELECT chapterid,chapterorder,chaptername,chaptertype,lastupdate FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid.' AND chaptertype = 0 ORDER BY chapterorder ASC';
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
		while($rows=mysqli_fetch_assoc($res))
		{
			$chapterrows[$k]['chaptertype']=$rows['chaptertype'];
			$chapterrows[$k]['lastupdate']=$rows['lastupdate'];
			$chapterrows[$k]['cname']=Text::ss_toutf8($rows['chaptername']);
			if($is_ft)$chapterrows[$k]['cname']=Convert::jt2ft($chapterrows[$k]['cname']);
			if($is_multiple)$rows['chapterid']=ss_newid($rows['chapterid']);
			if($use_orderid)$rows['chapterid']=$rows['chapterorder'];
			$chapterrows[$k]['cid_url']=Url::chapter_url($articleid,$rows['chapterid']);
			$k++;
		}
		if(isset($redis))$redis->ss_setex($sql,$info_cache_time,$chapterrows);
	}
}
$first_url=$chapterrows[0]['cid_url'];
$chapters=count($chapterrows);
$lastupdate=date('Y-m-d H:i:s',$chapterrows[$chapters-1]['lastupdate']);
$lastupdate_cn=Text::ss_lastupdate($chapterrows[$chapters-1]['lastupdate']);
$lastchapter=$chapterrows[$chapters-1]['cname'];
$last_url=$chapterrows[$chapters-1]['cid_url'];
$lastarr=array_reverse(array_slice($chapterrows,-12,12));
$rico_arr=array_chunk($chapterrows,$per_page);
if($pid>count($rico_arr))$pid=count($rico_arr);
$list_arr=$rico_arr[$pid-1];
if($pid>1)
{
	$htmltitle='<a class="index-container-btn" href="'.Url::index_url($articleid,($pid-1)).'">上一页</a>';
}
else
{
	$htmltitle='<a class="index-container-btn disabled-btn" href="javascript:void(0);">没有了</a>';
}
$htmltitle.='<select id="indexselect" onchange="self.location.href=options[selectedIndex].value">';
for($i=1;$i<=count($rico_arr);
$i++)
{
	$end=$i*$per_page>$chapters?$chapters:$i*$per_page;
	$htmltitle.='<option value="'.Url::index_url($articleid,$i).'"';
	if($i==$pid)$htmltitle.=' selected="selected"';
	$htmltitle.='>'.(($i-1)*$per_page+1).' - '.$end.'章</option>';
}
$htmltitle.='</select>';
if($pid<count($rico_arr))
{
	$htmltitle.='<a class="index-container-btn" href="'.Url::index_url($articleid,($pid+1)).'">下一页</a>';
}
else
{
	$htmltitle.='<a class="index-container-btn disabled-btn" href="javascript:void(0);">没有了</a>';
}
if($count_visit)require_once __ROOT_DIR__.'/shipsay/include/articlevisit.php';
header('Last-Modified: '.date('D, d M Y H:i:s',$chapterrows[$chapters-1]['lastupdate']-8*60*60).' GMT');
require_once __THEME_DIR__.'/tpl_indexlist.php';
?>