<?php 
// 确保定义了 __ROOT_DIR__，避免直接访问
if (!defined('__ROOT_DIR__')) {
    exit;
}
// 定义一个函数用于转义输出，提高代码复用性
function safeEcho($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <!-- 对标题相关变量进行转义 -->
    <title>最近阅读_<?= safeEcho(SITE_NAME) ?></title>
    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <div class="container">
        <div class="section_mark" id="tempBookcase"></div>
        <script language="javascript">showtempbooks();</script>
        <!-- 右边 -->
        <aside>
            <p class="title"><i class="fa fa-fire fa-lg">&nbsp;</i>猜你喜欢</p>
            <ul class="popular odd">
                <?php if (is_array($popular)): ?>
                    <?php foreach ($popular as $v): ?>
                        <!-- 对链接和文本相关变量进行转义 -->
                        <li><a href="<?= safeEcho($v['info_url']) ?>"><?= safeEcho($v['articlename']) ?></a><a class="gray" href="<?= safeEcho($v['author_url']) ?>"><?= safeEcho($v['author']) ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </aside>
    </div>
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>