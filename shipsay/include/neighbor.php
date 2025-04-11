<?php
// 确保 $sourceid 是整数类型，避免 SQL 注入风险
$sourceid = isset($sourceid) ? (int)$sourceid : 0;

// 定义时间范围，这里暂时将 $diff_str 置为空
$diff_str = ''; 
// 可以根据需要取消注释以下代码来启用时间范围筛选
// $diff_str = ' AND lastupdate >= ' . (time() - 7 * 24 * 60 * 60);

// 构建邻居文章的 SQL 查询语句
$sql_neighbor = $rico_sql . ' AND articleid > ' . $sourceid . $diff_str . ' ORDER BY articleid ASC LIMIT 10 ';
$sql_neighbor_redis = $rico_sql . ' AND articleid > ' . $sourceid . ' ORDER BY articleid ASC LIMIT 10 ';

// 初始化邻居文章数组
$neighbor = [];

// 检查 Redis 缓存
if (isset($redis) && ($cachedNeighbor = $redis->ss_get($sql_neighbor_redis))) {
    $neighbor = $cachedNeighbor;
} else {
    // 从数据库中获取邻居文章
    $neighbor = $db->ss_getrows($sql_neighbor) ?: [];

    // 如果获取的文章数量不足 10 条，从小于 $sourceid 的文章中补充
    if (count($neighbor) < 10) {
        $new_sql = $rico_sql . ' AND articleid < ' . $sourceid . $diff_str . ' ORDER BY articleid DESC LIMIT ' . (10 - count($neighbor));
        $newrows = $db->ss_getrows($new_sql) ?: [];
        $neighbor = array_merge($neighbor, $newrows);
    }

    // 更新 Redis 缓存
    if (isset($redis)) {
        $redis->ss_setex($sql_neighbor_redis, $cache_time, $neighbor);
    }
}

// 构建按发布日期排序的文章 SQL 查询语句
$sortstr = isset($sortid) ? ' AND sortid = ' . (int)$sortid : '';
$postsql = $rico_sql . $sortstr . ' ORDER BY postdate DESC LIMIT 10';

// 初始化按发布日期排序的文章数组
$postdate = [];

// 检查 Redis 缓存
if (isset($redis)) {
    $postdate = $redis->ss_redis_getrows($postsql, $cache_time, 1);
} else {
    // 从数据库中获取按发布日期排序的文章
    $postdate = $db->ss_getrows($postsql);
}
?>