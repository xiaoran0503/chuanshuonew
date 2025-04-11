<?php
// 引入必要的函数和配置文件
require_once __ROOT_DIR__ . '/shipsay/include/function.php';

// 安全过滤用户输入，防止 SQL 注入
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// 封装数据库查询逻辑，处理 Redis 缓存
function executeQuery($sql, $db, $redis, $cacheTime = null, $isArray = false) {
    if (isset($redis)) {
        $cacheKey = md5($sql);
        $result = $redis->ss_get($cacheKey);
        if ($result) {
            return $result;
        }
    }

    if ($isArray) {
        $result = $db->ss_getrows($sql);
    } else {
        $result = $db->ss_getone($sql);
    }

    if (isset($redis) && $cacheTime) {
        $redis->ss_setex($cacheKey, $cacheTime, $result);
    }

    return $result;
}

// 检查模板文件是否存在，若不存在则显示错误页面
if (!file_exists(__THEME_DIR__ . '/tpl_rank.php')) {
    Url::ss_errpage();
}

// 安全过滤用户输入的查询条件
$query = isset($matches[1]) ? sanitizeInput($matches[1]) : 'allvisit';

// 定义排行榜标题数组
$title_arr = [
    'allvisit' => '总排行榜',
    'monthvisit' => '月排行榜',
    'weekvisit' => '周排行榜',
    'dayvisit' => '日排行榜',
    'allvote' => '总推荐榜',
    'monthvote' => '月推荐榜',
    'weekvote' => '周推荐榜',
    'dayvote' => '日推荐榜',
    'goodnum' => '收藏榜'
];

// 检查查询条件是否合法，若不合法则显示错误页面
if (!in_array($query, array_keys($title_arr))) {
    Url::ss_errpage();
    die;
}

// 构建 SQL 查询条件
$tmpvar = " WHERE " . $dbarr['words'] . " > 0 ";
$page_title = $title_arr[$query];

// 处理繁体转换
if ($is_ft) {
    $page_title = Convert::jt2ft($page_title);
}

// 构建 SQL 查询语句
$sql = $rico_sql . "ORDER BY " . $query . " DESC LIMIT 50 ";

// 执行数据库查询
$articlerows = executeQuery($sql, $db, $redis, $home_cache_time, true);

// 引入排行榜模板文件
require_once __THEME_DIR__ . '/tpl_rank.php';
?>