<?php
if(!file_exists(__THEME_DIR__.'/tpl_recentread.php'))Url::ss_errpage();
$sql=$rico_sql.'ORDER BY monthvisit DESC LIMIT 20';
if(isset($redis))
{
	$popular=$redis->ss_redis_getrows($sql,$cache_time);
}
else
{
	$popular=$db->ss_getrows($sql);
}
require_once __THEME_DIR__.'/tpl_recentread.php';
?>