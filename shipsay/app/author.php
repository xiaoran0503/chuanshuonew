<?php
// 封装检查模板文件是否存在的函数
function checkTemplateFile() {
    if (!file_exists(__THEME_DIR__ . '/tpl_author.php')) {
        Url::ss_errpage();
    }
}

// 过滤和转义用户输入
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// 主逻辑
checkTemplateFile();

try {
    // 确保 $matches 数组存在且索引 1 有值
    if (!isset($matches[1])) {
        throw new Exception('未找到有效的作者信息');
    }

    // 对作者信息进行解码和过滤
    $author = sanitizeInput(urldecode($matches[1]));

    // 若为繁体版，转换作者名
    if ($is_ft) {
        $author = Convert::jt2ft($author, 1);
    }

    // 构建 SQL 查询语句
    $sql = $rico_sql . 'AND author = "' . $author . '" ORDER BY lastupdate DESC';

    // 根据是否存在 Redis 缓存获取数据
    if (isset($redis)) {
        $res = $redis->ss_redis_getrows($sql, $cache_time);
    } else {
        $res = $db->ss_getrows($sql);
    }

    // 统计查询结果数量
    $author_count = is_array($res) ? count($res) : 0;

    // 若为繁体版，再次转换作者名
    if ($is_ft) {
        $author = Convert::jt2ft($author);
    }

    // 引入模板文件
    require_once __THEME_DIR__ . '/tpl_author.php';
} catch (Exception $e) {
    // 输出错误信息
    echo '<script>alert("发生错误: ' . $e->getMessage() . '");window.history.go(-1);</script>';
}
?>