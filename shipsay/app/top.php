<?php
if(!file_exists(__THEME_DIR__.'/tpl_top.php'))Url::ss_errpage();
foreach($sortarr as $k=>$v)
{
	$sql_allvisit=$rico_sql.'AND sortid = '.$k.' ORDER BY allvisit DESC LIMIT 50';
	$sql_monthvisit=$rico_sql.'AND sortid = '.$k.' ORDER BY monthvisit DESC LIMIT 50';
	$sql_weekvisit=$rico_sql.'AND sortid = '.$k.' ORDER BY weekvisit DESC LIMIT 50';
	$tmp_allvisit='allvisit'.$k;
	$tmp_monthvisit='monthvisit'.$k;
	$tmp_weekvisit='weekvisit'.$k;
	if(isset($redis))
	{
		$$tmp_allvisit=$redis->ss_redis_getrows($sql_allvisit,$cache_time);
		$$tmp_monthvisit=$redis->ss_redis_getrows($sql_monthvisit,$cache_time);
		$$tmp_weekvisit=$redis->ss_redis_getrows($sql_weekvisit,$cache_time);
	}
	else
	{
		$$tmp_allvisit=$db->ss_getrows($sql_allvisit);
		$$tmp_monthvisit=$db->ss_getrows($sql_monthvisit);
		$$tmp_weekvisit=$db->ss_getrows($sql_weekvisit);
	}
}
;
require_once __THEME_DIR__.'/tpl_top.php';
?>