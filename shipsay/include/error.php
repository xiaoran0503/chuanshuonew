<?php
// 移除 @ 符号，避免抑制错误信息，方便调试
header('Status: 404 Not Found', true);
header('HTTP/1.1 404 Not Found');

// 使用更现代的 HEREDOC 语法格式
$html = <<<HTML
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5; url=/">
    <title>404 Not Found</title>
</head>
<body>
    <style>
        div {
            text-align: center;
            font-size: 2em;
            margin: 20px auto;
        }
        a {
            display: table;
            margin: 0 auto;
            text-decoration: none;
            font-size: 0.5em;
            color: #000;
        }
    </style>
    <div>404 Not Found</div>
    <hr>
    <a href="/">5秒钟后返回首页</a>
</body>
</html>
HTML;

// 输出 HTML 内容
echo $html;

// 终止脚本执行
exit;