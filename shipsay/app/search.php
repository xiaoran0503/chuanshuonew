<?php
include_once __ROOT_DIR__.'/shipsay/configs/search.ini.php';
if($ShipSaySearch['delay']===0)
{
	echo '<script>alert("对不起,本站禁止搜索");window.history.go(-1);</script>';
	die;
}
$search_count=0;
$searchkey=$search_res='';
if(isset($_REQUEST['searchkey'])&&$_REQUEST['searchkey']!="")
{
	if(isset($_COOKIE["ss_search_delay"]))
	{
		echo '<script>alert("搜索间隔: '.$ShipSaySearch['delay'].' 秒");window.history.go(-1);</script>';
		die;
	}
	$searchkey=trim($_REQUEST['searchkey']);
	if(strlen($searchkey)<intval($ShipSaySearch['min_words']))
	{
		echo '<script>alert("关键字最少 '.$ShipSaySearch['min_words'].' 个字符");window.history.go(-1);</script>';
		die;
	}
	if($is_ft)$searchkey=Convert::jt2ft($searchkey,1);
	$res_limit=$ShipSaySearch['limit']?:100;
	$search_cache_time=$ShipSaySearch['cache_time']?:0;
	$query=$rico_sql.'AND (articlename LIKE "%'.$searchkey.'%" OR author LIKE "%'.$searchkey.'%") ORDER BY lastupdate DESC LIMIT '.$res_limit;
	if(isset($redis))
	{
		$search_res=$redis->ss_redis_getrows($query,$search_cache_time);
	}
	else
	{
		$search_res=$db->ss_getrows($query);
	}
	$search_count=is_array($search_res)?count($search_res):0;
	if($is_ft)$searchkey=Convert::jt2ft($searchkey);
	if(!setcookie('ss_search_delay',true,time()+$ShipSaySearch['delay'],'/'))die('need cookie');
	if($ShipSaySearch['is_record'])
	{
		$search_sql='INSERT INTO shipsay_article_search (searchtime,keywords,results,searchsite) VALUES ("'.date("U").'","'.$searchkey.'","'.$search_count.'","'.$site_url.'")';
		$db->ss_query($search_sql);
	}
}
if($search_count==0)
{
	$sql=$rico_sql.' ORDER BY allvisit DESC LIMIT 50';
	if(isset($redis))
	{
		$articlerows=$redis->ss_redis_getrows($sql,$search_cache_time);
	}
	else
	{
		$articlerows=$db->ss_getrows($sql);
	}
}
require_once __THEME_DIR__.'/tpl_search.php';
?>