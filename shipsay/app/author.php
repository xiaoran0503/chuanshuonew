<?php
if(!file_exists(__THEME_DIR__.'/tpl_author.php'))Url::ss_errpage();
$author=urldecode($matches[1]);
if($is_ft)$author=Convert::jt2ft($author,1);
$sql=$rico_sql.'AND author = "'.$author.'" ORDER BY lastupdate DESC';
if(isset($redis))
{
	$res=$redis->ss_redis_getrows($sql,$cache_time);
}
else
{
	$res=$db->ss_getrows($sql);
}
$author_count=is_array($res)?count($res):0;
if($is_ft)$author=Convert::jt2ft($author);
require_once __THEME_DIR__.'/tpl_author.php';
?>