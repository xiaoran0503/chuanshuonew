<?php
@header('Status: 404 Not Found',true);
@header("http/1.1 404 Not Found");
echo <<<EOD
    <!DOCTYPE html>
    <html lang="zh">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content='5; url=/'>
    <title>404 Not Found</title>
    </head>
    <body>
    <style>
    div{text-align:center;font-size:2em;margin:20px auto;}
    a{display:table;margin:0 auto;text-decoration: none;font-size:0.5em;color:#000;}
    </style>
    <div>404 Not Found</div>
    <hr>
    <a href="/">5秒钟后返回首页</a>
    </body>
    </html>
EOD;
exit;