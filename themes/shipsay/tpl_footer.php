<?php 
// 确保定义了 __ROOT_DIR__，避免直接访问
if (!defined('__ROOT_DIR__')) {
    exit;
}

// 对网站名称进行转义，防止 XSS 攻击
$siteName = htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8');
?>

<!-- footer -->
<div id="footer">
    <footer class="container">
        <p><i class="fa fa-flag"></i>&nbsp;<a href="/"><?= $siteName ?></a>&nbsp;书友最值得收藏的网络小说阅读网</p>
        <p><a href="javascript:zh_tran('s');" class="zh_click" id="zh_click_s">简体版</a> · <a href="javascript:zh_tran('t');" class="zh_click" id="zh_click_t">繁體版</a></p>
    </footer>
</div>
<script>setEcho();</script>
<?php 
// 安全地包含配置文件
$countConfigFile = __ROOT_DIR__ . '/shipsay/configs/count.ini.php';
if (is_file($countConfigFile) && is_readable($countConfigFile)) {
    include_once $countConfigFile;
    foreach ($count as $v) {
        if ($v['enable']) {
            // 对可能的 HTML 内容进行转义
            echo htmlspecialchars($v['html'], ENT_QUOTES, 'UTF-8');
        }
    }
}
?>
</body></html>