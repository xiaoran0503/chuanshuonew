<?php
if(!file_exists(__THEME_DIR__.'/tpl_rank.php'))Url::ss_errpage();
$query=$matches[1]?:'allvisit';
$title_arr=['allvisit'=>'总排行榜','monthvisit'=>'月排行榜','weekvisit'=>'周排行榜','dayvisit'=>'日排行榜','allvote'=>'总推荐榜','monthvote'=>'月推荐榜','weekvote'=>'周推荐榜','dayvote'=>'日推荐榜','goodnum'=>'收藏榜'];
if(!in_array($query,array_keys($title_arr)))
{
	Url::ss_errpage();
	die;
}
$tmpvar=' WHERE '.$dbarr['words'].' > 0 ';
$page_title=$title_arr[$query];
if($is_ft)$page_title=Convert::jt2ft($page_title);
$sql=$rico_sql.'ORDER BY '.$query.' DESC LIMIT 50 ';
if(isset($redis))
{
	$articlerows=$redis->ss_redis_getrows($sql,$home_cache_time,1);
}
else
{
	$articlerows=$db->ss_getrows($sql);
}
require_once __THEME_DIR__.'/tpl_rank.php';
?>