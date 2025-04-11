<?php
if(!isset($sourceid))$sourceid=0;
$diff_str=' AND lastupdate >= '.(time()-7*24*60*60);
$diff_str='';
$sql_neighbor=$rico_sql.' AND articleid > '.$sourceid.$diff_str.' ORDER BY articleid ASC LIMIT 10 ';
$sql_neighbor_redis=$rico_sql.' AND articleid > '.$sourceid.' ORDER BY articleid ASC LIMIT 10 ';
if(isset($redis)&&$redis->ss_get($sql_neighbor_redis))
{
	$neighbor=$redis->ss_get($sql_neighbor_redis);
}
else
{
	if(!$neighbor=$db->ss_getrows($sql_neighbor))$neighbor=[];
	if(count($neighbor)<10)
	{
		$new_sql=$rico_sql.' AND articleid < '.$sourceid.$diff_str.' ORDER BY articleid DESC LIMIT '.(10-count($neighbor));
		if(!$newrows=$db->ss_getrows($new_sql))$newrows=[];
		$neighbor=array_merge($neighbor,$newrows);
	}
	if(isset($redis))$redis->ss_setex($sql_neighbor_redis,$cache_time,$neighbor);
}
$sortstr=isset($sortid)?'AND sortid = '.$sortid:'';
$postsql=$rico_sql.$sortstr.' ORDER BY postdate DESC LIMIT 10';
if(isset($redis))
{
	$postdate=$redis->ss_redis_getrows($postsql,$cache_time,1);
}
else
{
	$postdate=$db->ss_getrows($postsql);
}
?>