<?php
// 引入必要的函数文件
require_once __ROOT_DIR__ . '/shipsay/include/function.php';

// 检查模板文件是否存在
if (!file_exists(__THEME_DIR__ . '/tpl_recentread.php')) {
    Url::ss_errpage();
}

// 封装数据库查询逻辑，处理 Redis 缓存
function executeQuery($sql, $db, $redis, $cacheTime = null) {
    if (isset($redis)) {
        $cacheKey = md5($sql);
        $result = $redis->ss_redis_getrows($cacheKey);
        if ($result) {
            return $result;
        }
    }

    $result = $db->ss_getrows($sql);

    if (isset($redis) && $cacheTime) {
        $redis->ss_redis_setex($cacheKey, $cacheTime, $result);
    }

    return $result;
}

// 构建 SQL 查询语句
$sql = $rico_sql . 'ORDER BY monthvisit DESC LIMIT 20';

// 执行查询
$popular = executeQuery($sql, $db, $redis, $cache_time);

// 引入模板文件
require_once __THEME_DIR__ . '/tpl_recentread.php';
?>