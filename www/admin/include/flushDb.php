<?php
// 检查请求参数
if (isset($_REQUEST['flushDb'])) {
    try {
        // 引入必要的函数文件
        require_once('function.php');

        // 创建 Redis 实例
        $redis = new SsRedis($redisarr);

        // 尝试刷新 Redis 数据库
        if ($redis->ss_flushDb()) {
            echo "200";
        } else {
            // 若刷新失败，输出错误信息
            http_response_code(500);
            echo "Failed to flush Redis database.";
        }
    } catch (Exception $e) {
        // 捕获并处理异常，输出错误信息
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
} elseif (isset($_REQUEST['flushHomePage'])) {
    try {
        // 构建缓存文件路径
        $cacheFile = str_replace('\\', '/', dirname(dirname(__DIR__))) . '/index_cache.html';

        // 检查缓存文件是否存在，若存在则删除
        if (is_file($cacheFile)) {
            if (!unlink($cacheFile)) {
                // 若删除失败，输出错误信息
                http_response_code(500);
                echo "Failed to delete cache file.";
                return;
            }
        }

        // 构建网站 URL
        $siteUrl = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

        // 初始化 cURL 会话
        $ch = curl_init($siteUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // 执行 cURL 请求
        $content = curl_exec($ch);

        // 检查 cURL 请求是否成功
        if ($content === false) {
            // 若请求失败，输出错误信息
            http_response_code(500);
            echo "Failed to fetch home page content: " . curl_error($ch);
            curl_close($ch);
            return;
        }

        // 关闭 cURL 会话
        curl_close($ch);

        // 将获取到的内容写入缓存文件
        if (file_put_contents($cacheFile, $content) !== false) {
            echo '200';
        } else {
            // 若写入失败，输出错误信息
            http_response_code(500);
            echo "Failed to write to cache file.";
        }
    } catch (Exception $e) {
        // 捕获并处理异常，输出错误信息
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
}
?>