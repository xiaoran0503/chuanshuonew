<?php
// 防止直接访问该文件
if (!defined('__ROOT_DIR__')) {
    exit;
}
?>
<!-- footer -->
<div class="container">
    <div class="footer gray">
        <!-- 对输出变量进行安全转义 -->
        <p>本站所有小说为转载作品，所有章节均由网友上传，转载至本站只是为了宣传本书让更多读者欣赏。</p>
        <p>Copyright <?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?> All Rights Reserved.</p>
    </div>
</div>
<?php
// 检查 setEcho 函数是否存在
if (function_exists('setEcho')) {
    echo '<script>setEcho();</script>';
}
?>
</body>
</html>