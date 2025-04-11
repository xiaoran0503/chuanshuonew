<?php
// 验证 $_SERVER['DOCUMENT_ROOT'] 是否存在
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    die('无法获取文档根目录');
}

// 计算总磁盘空间（GB）
$totalDiskBytes = disk_total_space($_SERVER['DOCUMENT_ROOT']);
if ($totalDiskBytes === false) {
    die('无法获取总磁盘空间');
}
$totalDiskGB = round($totalDiskBytes / (1024 ** 3), 1);

// 计算已使用磁盘空间（GB）
$freeDiskBytes = disk_free_space($_SERVER['DOCUMENT_ROOT']);
if ($freeDiskBytes === false) {
    die('无法获取可用磁盘空间');
}
$usedDiskGB = round(($totalDiskBytes - $freeDiskBytes) / (1024 ** 3), 1);

// 获取 Web 服务器软件信息
$webServer = $_SERVER['SERVER_SOFTWARE']?? '未知';
?>