<?php 
$sql=$rico_sql.'AND articleid IN ('.$commend_ids.' ) ORDER BY FIELD (articleid,'.$commend_ids.')';
if(isset($redis))
{
	$commend=$redis->ss_redis_getrows($sql,$home_cache_time);
}
else
{
	$commend=$db->ss_getrows($sql);
}
$sql=$rico_sql.'ORDER BY monthvisit DESC LIMIT 25';
if(isset($redis))
{
	$popular=$redis->ss_redis_getrows($sql,$home_cache_time,1);
}
else
{
	$popular=$db->ss_getrows($sql);
}
foreach($sortarr as $k=>$v)
{
	$sql=$rico_sql.'AND sortid = '.$k.' ORDER BY monthvisit DESC LIMIT 20';
	$tmpvar='sort'.$k;
	if(isset($redis))
	{
		$$tmpvar=$redis->ss_redis_getrows($sql,$home_cache_time,1);
	}
	else
	{
		$$tmpvar=$db->ss_getrows($sql);
	}
}
;
$sql=$rico_sql.'ORDER BY lastupdate DESC LIMIT 30';
if(isset($redis))
{
	$lastupdate=$redis->ss_redis_getrows($sql,$home_cache_time,1);
}
else
{
	$lastupdate=$db->ss_getrows($sql);
}
$sql=$rico_sql.'ORDER BY postdate DESC LIMIT 30';
if(isset($redis))
{
	$postdate=$redis->ss_redis_getrows($sql,$home_cache_time,1);
}
else
{
	$postdate=$db->ss_getrows($sql);
}
require_once __ROOT_DIR__.'/shipsay/configs/link.ini.php';
$link_html=$ShipSayLink['is_link']==1?$ShipSayLink['link_ini']:'';
require_once __THEME_DIR__.'/tpl_home.php';
echo "\n<script src='index_c.php'></script>";