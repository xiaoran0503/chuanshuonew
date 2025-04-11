<?php
// 引入搜索配置文件
include_once __ROOT_DIR__.'/shipsay/configs/search.ini.php';

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

// 检查是否禁止搜索
if ($ShipSaySearch['delay'] === 0) {
    echo '<script>alert("对不起,本站禁止搜索");window.history.go(-1);</script>';
    die;
}

$search_count = 0;
$searchkey = $search_res = '';

// 检查是否有搜索关键字
if (isset($_REQUEST['searchkey']) && $_REQUEST['searchkey'] !== "") {
    // 检查搜索间隔
    if (isset($_COOKIE["ss_search_delay"])) {
        echo '<script>alert("搜索间隔: '.$ShipSaySearch['delay'].' 秒");window.history.go(-1);</script>';
        die;
    }

    // 过滤搜索关键字，防止 SQL 注入
    $searchkey = trim($_REQUEST['searchkey']);
    $searchkey = mysqli_real_escape_string($db->getConnection(), $searchkey);

    // 检查关键字长度
    if (strlen($searchkey) < intval($ShipSaySearch['min_words'])) {
        echo '<script>alert("关键字最少 '.$ShipSaySearch['min_words'].' 个字符");window.history.go(-1);</script>';
        die;
    }

    // 繁体转换
    if ($is_ft) {
        $searchkey = Convert::jt2ft($searchkey, 1);
    }

    // 设置搜索结果限制和缓存时间
    $res_limit = $ShipSaySearch['limit'] ?: 100;
    $search_cache_time = $ShipSaySearch['cache_time'] ?: 0;

    // 构建搜索查询语句
    $query = $rico_sql.'AND (articlename LIKE "%'.$searchkey.'%" OR author LIKE "%'.$searchkey.'%") ORDER BY lastupdate DESC LIMIT '.$res_limit;

    // 执行搜索查询
    $search_res = executeQuery($query, $db, $redis, $search_cache_time);

    // 统计搜索结果数量
    $search_count = is_array($search_res) ? count($search_res) : 0;

    // 繁体转换
    if ($is_ft) {
        $searchkey = Convert::jt2ft($searchkey);
    }

    // 设置搜索间隔 Cookie
    if (!setcookie('ss_search_delay', true, time() + $ShipSaySearch['delay'], '/')) {
        die('need cookie');
    }

    // 记录搜索记录
    if ($ShipSaySearch['is_record']) {
        $search_sql = 'INSERT INTO shipsay_article_search (searchtime,keywords,results,searchsite) VALUES ("'.date("U").'","'.$searchkey.'","'.$search_count.'","'.$site_url.'")';
        $db->ss_query($search_sql);
    }
}

// 如果没有搜索结果，显示热门文章
if ($search_count == 0) {
    $sql = $rico_sql.' ORDER BY allvisit DESC LIMIT 50';
    $articlerows = executeQuery($sql, $db, $redis, $search_cache_time);
}

// 引入搜索结果模板文件
require_once __THEME_DIR__.'/tpl_search.php';
?>