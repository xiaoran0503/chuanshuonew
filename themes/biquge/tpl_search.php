<?php 
// 防止直接访问文件
if (!defined('__ROOT_DIR__')) exit; 

// 定义安全输出函数
function safeEcho($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <!-- 安全转义输出的变量 -->
    <title>搜索_<?= safeEcho(SITE_NAME) ?></title>
    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <div class="container flex flex-wrap mb20">
        <div class="border3 commend flex flex-between category-commend">
            <?php if ($search_count > 0): ?>
                <?php foreach ($search_res as $k => $v): ?>
                    <div class="category-div">
                        <!-- 安全转义输出的链接和图片地址 -->
                        <a href="<?= safeEcho($v['info_url']) ?>">
                            <img class="lazy" src="/static/<?= safeEcho($theme_dir) ?>/nocover.jpg" data-original="<?= safeEcho($v['img_url']) ?>">
                        </a>
                        <div>
                            <div class="flex flex-between commend-title">
                                <!-- 安全转义输出的链接和小说名 -->
                                <a href="<?= safeEcho($v['info_url']) ?>"><h3><?= safeEcho($v['articlename']) ?></h3></a> 
                                <!-- 安全转义输出的作者名 -->
                                <span><?= safeEcho($v['author']) ?></span>
                            </div>
                            <!-- 安全转义输出的简介 -->
                            <div class="intro indent"><?= safeEcho($v['intro_des']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>