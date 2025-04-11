<?php
// 定义项目根目录，将反斜杠替换为正斜杠以确保在不同操作系统上的兼容性
define('__ROOT_DIR__', str_replace('\\', '/', dirname(__DIR__)));

// 包含配置文件
include_once(__ROOT_DIR__ . '/shipsay/configs/config.ini.php');

// 获取当前请求的 URI，并对其进行过滤，防止 XSS 攻击
$sourceUri = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
$uri = $sourceUri;

// 如果 URI 中包含查询字符串，则截取 URI 直到查询字符串之前的部分
if (strpos($uri, '?')!== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}

// 引入路由类文件
require_once '../shipsay/class/router.php';
?>