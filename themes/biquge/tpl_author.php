<?php
if (!defined('__ROOT_DIR__')) {
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <!-- 对 $author 和 SITE_NAME 进行安全转义 -->
    <title><?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?> 的全部作品_<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?></title>
    <!-- 对关键词和描述进行安全转义 -->
    <meta name="keywords" content="<?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?>的全部小说">
    <meta name="description" content="<?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?>的全部小说">
    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <div class="container flex flex-wrap mb20">
        <div class="border3 commend flex flex-between category-commend">
            <?php if (is_array($res)): ?>
                <?php foreach ($res as $v): ?>
                    <div class="category-div">
                        <!-- 对链接和图片地址进行安全转义 -->
                        <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>"><img class="lazy" src="/static/<?= htmlspecialchars($theme_dir, ENT_QUOTES, 'UTF-8') ?>/nocover.jpg" data-original="<?= htmlspecialchars($v['img_url'], ENT_QUOTES, 'UTF-8') ?>"></a>
                        <div>
                            <div class="flex flex-between commend-title">
                                <!-- 对小说名和链接进行安全转义 -->
                                <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>"><h3><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8') ?></h3></a> 
                                <!-- 对作者名进行安全转义 -->
                                <span><?= htmlspecialchars($v['author'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <!-- 对简介进行安全转义 -->
                            <div class="intro indent"><?= htmlspecialchars($v['intro_des'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>