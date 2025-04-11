<?php
// 过滤和转义输入数据，防止 SQL 注入
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// 封装数据库查询逻辑，处理 Redis 缓存
function executeQuery($sql, $db, $redis, $cacheTime = null, $isArray = false) {
    if (isset($redis)) {
        $cacheKey = md5($sql);
        $result = $redis->ss_get($cacheKey);
        if ($result) {
            return $isArray? unserialize($result) : $result;
        }
        $result = $isArray? $db->ss_getrows($sql) : $db->ss_getone($sql);
        if ($cacheTime) {
            $redis->ss_setex($cacheKey, $cacheTime, $isArray? serialize($result) : $result);
        }
        return $result;
    }
    return $isArray? $db->ss_getrows($sql) : $db->ss_getone($sql);
}

// 处理推荐小说查询
$commend_ids = sanitizeInput($commend_ids);
$sql = $rico_sql. 'AND articleid IN ('.$commend_ids. ' ) ORDER BY FIELD (articleid,'.$commend_ids.')';
$commend = executeQuery($sql, $db, $redis, $home_cache_time, true);

// 处理热门小说查询
$sql = $rico_sql. 'ORDER BY monthvisit DESC LIMIT 25';
$popular = executeQuery($sql, $db, $redis, $home_cache_time, true);

// 处理各分类热门小说查询
foreach ($sortarr as $k => $v) {
    $sortid = sanitizeInput($k);
    $sql = $rico_sql. 'AND sortid = '.$sortid. ' ORDER BY monthvisit DESC LIMIT 20';
    $tmpvar = 'sort'.$k;
    $$tmpvar = executeQuery($sql, $db, $redis, $home_cache_time, true);
}

// 处理最新更新小说查询
$sql = $rico_sql. 'ORDER BY lastupdate DESC LIMIT 30';
$lastupdate = executeQuery($sql, $db, $redis, $home_cache_time, true);

// 处理最新发布小说查询
$sql = $rico_sql. 'ORDER BY postdate DESC LIMIT 30';
$postdate = executeQuery($sql, $db, $redis, $home_cache_time, true);

// 引入链接配置文件
require_once __ROOT_DIR__.'/shipsay/configs/link.ini.php';
$link_html = $ShipSayLink['is_link'] == 1? $ShipSayLink['link_ini'] : '';

// 引入首页模板文件
require_once __THEME_DIR__.'/tpl_home.php';

// 引入 JavaScript 文件
echo "\n<script src='index_c.php'></script>";
?>