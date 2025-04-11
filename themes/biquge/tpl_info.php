<?php
// 防止直接访问该文件
if (!defined('__ROOT_DIR__')) {
    exit;
}
?>

<!DOCTYPE html>
<html lang='zh'>
<head>
    <meta charset="UTF-8">
    <!-- 对输出变量进行安全转义 -->
    <title><?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>_无弹窗书友最值得收藏的网络小说阅读网</title>
    <meta name="keywords" content="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>,无弹窗,小说阅读网,<?= htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8') ?>" />
    <meta name="description" content="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>是广大书友最值得收藏的网络小说阅读网，网站收录了当前最火热的网络小说，免费提供高质量的小说最新章节，是广大网络小说爱好者必备的小说阅读网。" />
    <?php
    require_once 'tpl_header.php';
    require_once __ROOT_DIR__ . '/shipsay/include/neighbor.php';
    ?>
</head>
<body>
    <div class="container">
        <div class="border3-2">
            <div class="info-title">
                <!-- 对链接和文本进行安全转义 -->
                <a href="/"><?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?></a> &gt; <a href="<?= htmlspecialchars(Sort::ss_sorturl($sortid), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8') ?></a> &gt; <?= htmlspecialchars($articlename, ENT_QUOTES, 'UTF-8') ?>最新章节列表
            </div>
            <div class="info-main">
                <!-- 对图片地址进行安全转义 -->
                <img class="lazy" src="/static/<?= htmlspecialchars($theme_dir, ENT_QUOTES, 'UTF-8') ?>/nocover.jpg" data-original="<?= htmlspecialchars($img_url, ENT_QUOTES, 'UTF-8') ?>">
                <div class="w100">
                    <h1><?= htmlspecialchars($articlename, ENT_QUOTES, 'UTF-8') ?></h1>
                    <div class="w100 dispc">
                        <!-- 对链接和文本进行安全转义 -->
                        <span><a href="<?= htmlspecialchars($author_url, ENT_QUOTES, 'UTF-8') ?>">作&nbsp;&nbsp;&nbsp;&nbsp;者：<?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?></a></span>
                        动&nbsp;&nbsp;&nbsp;&nbsp;做：<a href="<?= htmlspecialchars($first_url, ENT_QUOTES, 'UTF-8') ?>">开始阅读</a>，<?= htmlspecialchars($isfull, ENT_QUOTES, 'UTF-8') ?>，<a href="javascript:gofooter();">直达底部</a>
                    </div>
                    <div class="dispc">
                        <!-- 对链接和文本进行安全转义 -->
                        <span>最后更新：<?= htmlspecialchars($lastupdate, ENT_QUOTES, 'UTF-8') ?></span>
                        <a href="<?= htmlspecialchars($last_url, ENT_QUOTES, 'UTF-8') ?>">最新章节：<?= htmlspecialchars($lastchapter, ENT_QUOTES, 'UTF-8') ?></a>
                    </div>
                    <div class="info-main-intro">
                        <!-- 对文本进行安全转义 -->
                        <?= htmlspecialchars($intro_p, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
            </div>
            <div class="info-commend">
                推荐阅读: 
                <?php foreach ($neighbor as $k => $v): ?>
                    <!-- 对链接和文本进行安全转义 -->
                    <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($articlename, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8') ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="diswap info-main-wap border3-1">
            <!-- 对链接和文本进行安全转义 -->
            <a href="<?= htmlspecialchars($author_url, ENT_QUOTES, 'UTF-8') ?>"><p>作&nbsp;&nbsp;&nbsp;&nbsp;者：<?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?></p></a>
            <p>最后更新：<?= htmlspecialchars($lastupdate, ENT_QUOTES, 'UTF-8') ?>&nbsp;&nbsp;<a href="javascript:gofooter();">直达底部</a></p>
            <a href="<?= htmlspecialchars($last_url, ENT_QUOTES, 'UTF-8') ?>"><p>最新章节：<?= htmlspecialchars($lastchapter, ENT_QUOTES, 'UTF-8') ?></p></a>
        </div>
    </div>
    <div class="container border3-2 mt8">
        <div class="info-chapters-title">
            <!-- 对文本进行安全转义 -->
            <strong>《<?= htmlspecialchars($articlename, ENT_QUOTES, 'UTF-8') ?>》最新章节</strong>
            <span class="dispc">（提示：已启用缓存技术，最新章节可能会延时显示。）</span>
        </div>
        <div class="info-chapters flex flex-wrap">
            <?php foreach ($lastarr as $k => $v): ?>
                <!-- 对链接和文本进行安全转义 -->
                <a href="<?= htmlspecialchars($v['cid_url'], ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($articlename, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($v['cname'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v['cname'], ENT_QUOTES, 'UTF-8') ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container border3-2 mt8 mb20">
        <div class="info-chapters-title">
            <!-- 对文本进行安全转义 -->
            <strong>《<?= htmlspecialchars($articlename, ENT_QUOTES, 'UTF-8') ?>》正文</strong>
        </div>
        <div class="info-chapters flex flex-wrap">
            <?php foreach ($chapterrows as $k => $v): ?>
                <!-- 对链接和文本进行安全转义 -->
                <a href="<?= htmlspecialchars($v['cid_url'], ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($articlename, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($v['cname'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v['cname'], ENT_QUOTES, 'UTF-8') ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container">
        <div class="info-commend">
            最新小说: 
            <?php foreach ($postdate as $k => $v): ?>
                <!-- 对链接和文本进行安全转义 -->
                <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($articlename, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8') ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <button class="gotop" onclick="javascript:gotop();">顶部</button>
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>