<?php
// 引入必要的函数文件
require_once __ROOT_DIR__ . '/shipsay/include/function.php';

// 检查模板文件是否存在
if (!file_exists(__THEME_DIR__ . '/tpl_top.php')) {
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

// 遍历分类数组
foreach ($sortarr as $k => $v) {
    // 构建不同排序规则的 SQL 查询语句
    $sql_allvisit = $rico_sql . 'AND sortid = ' . $k . ' ORDER BY allvisit DESC LIMIT 50';
    $sql_monthvisit = $rico_sql . 'AND sortid = ' . $k . ' ORDER BY monthvisit DESC LIMIT 50';
    $sql_weekvisit = $rico_sql . 'AND sortid = ' . $k . ' ORDER BY weekvisit DESC LIMIT 50';

    // 定义变量名
    $tmp_allvisit = 'allvisit' . $k;
    $tmp_monthvisit = 'monthvisit' . $k;
    $tmp_weekvisit = 'weekvisit' . $k;

    // 执行查询
    $$tmp_allvisit = executeQuery($sql_allvisit, $db, $redis, $cache_time);
    $$tmp_monthvisit = executeQuery($sql_monthvisit, $db, $redis, $cache_time);
    $$tmp_weekvisit = executeQuery($sql_weekvisit, $db, $redis, $cache_time);
}

// 引入模板文件
require_once __THEME_DIR__ . '/tpl_top.php';
?>