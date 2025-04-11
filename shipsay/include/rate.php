<?php
// 开启严格模式，增强代码的健壮性
declare(strict_types=1);

// 检查请求参数是否存在，避免潜在的未定义变量错误
if (!isset($_REQUEST['score']) || !isset($_REQUEST['sourceid'])) {
    http_response_code(400);
    echo '-1';
    exit;
}

// 过滤并验证输入，防止 SQL 注入和其他安全问题
$score = filter_var($_REQUEST['score'], FILTER_VALIDATE_INT);
$sourceid = filter_var($_REQUEST['sourceid'], FILTER_VALIDATE_INT);

// 检查过滤后的值是否有效
if ($score === false || $sourceid === false) {
    http_response_code(400);
    echo '-1';
    exit;
}

// 设置 cookie 过期时间为 12 小时
$cookie_time = time() + 12 * 60 * 60;

// 构建 SQL 查询语句，使用预处理语句或严格的类型转换来防止 SQL 注入
$sql = 'UPDATE ' . $dbarr['pre'] . 'article_article 
        SET ratenum = ratenum + 1, ratesum = ratesum + ' . $score . ' 
        WHERE articleid = ' . $sourceid;

// 执行 SQL 查询
if ($db->ss_query($sql)) {
    // 设置 cookie 以记录用户已评分
    setcookie("rated", (string)$score, $cookie_time, '/');
    // 返回成功状态码
    http_response_code(200);
    echo '200';
} else {
    // 返回失败状态码
    http_response_code(500);
    echo '-1';
}
?>