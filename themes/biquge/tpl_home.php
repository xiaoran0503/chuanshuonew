<?php
// 防止直接访问该文件
if (!defined('__ROOT_DIR__')) {
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <!-- 对输出变量进行安全转义 -->
    <title><?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>_无弹窗书友最值得收藏的网络小说阅读网</title>
    <meta name="keywords" content="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>,无弹窗,小说阅读网,<?= htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8') ?>" />
    <meta name="description" content="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>是广大书友最值得收藏的网络小说阅读网，网站收录了当前最火热的网络小说，免费提供高质量的小说最新章节，是广大网络小说爱好者必备的小说阅读网。" />
    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <div class="container flex flex-wrap">
        <!-- 封推 -->
        <div class="border3 commend flex flex-between">
            <?php foreach ($commend as $k => $v): ?>
                <?php if ($k < 4): ?>
                    <div class="outdiv">
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
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <!-- 热门 -->
        <div class="border3 popular">
            <p>经典推荐</p>
            <?php foreach ($popular as $k => $v): ?>
                <?php if ($k < 8): ?>
                    <div class="list-out">
                        <!-- 对分类名、小说名和链接进行安全转义 -->
                        <span>[<?= htmlspecialchars($v['sortname_2'], ENT_QUOTES, 'UTF-8') ?>] <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8') ?></a></span>
                        <!-- 对作者名进行安全转义 -->
                        <span class="gray"><?= htmlspecialchars($v['author'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 中间6区块 -->
    <?php for ($i = 1; $i <= 6; $i++): ?>
        <?php $sortVar = 'sort' . $i; ?>
        <?php if ($i % 3 == 1 && $i > 1): ?>
            </div>
            <div class="container sort-section border3 flex flex-between flex-wrap">
        <?php endif; ?>
        <div<?php if ($i % 3 == 2): ?> class="sort-middle"<?php endif; ?>>
            <div class="sort-title">
                <!-- 对分类链接和名称进行安全转义 -->
                <a href="<?= htmlspecialchars(Sort::ss_sorturl($i), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(Sort::ss_sortname($i, 1), ENT_QUOTES, 'UTF-8') ?></a>
            </div>
            <div class="sort-bottom">
                <?php foreach ($$sortVar as $k => $v): ?>
                    <?php if ($k == 0): ?>
                        <div class="sortdiv flex">
                            <!-- 对链接和图片地址进行安全转义 -->
                            <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>"><img class="lazy" src="/static/<?= htmlspecialchars($theme_dir, ENT_QUOTES, 'UTF-8') ?>/nocover.jpg" data-original="<?= htmlspecialchars($v['img_url'], ENT_QUOTES, 'UTF-8') ?>"></a>
                            <div>
                                <!-- 对小说名和链接进行安全转义 -->
                                <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>"><h4><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8') ?></h4></a>
                                <!-- 对简介进行安全转义 -->
                                <p><?= htmlspecialchars($v['intro_des'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        </div>
                    <?php elseif ($k > 0): ?>
                        <div class="sortlist">
                            <!-- 对小说名和链接进行安全转义 -->
                            <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8') ?></a>
                            <!-- 对作者名进行安全转义 -->
                            <span class="s_gray">/<?= htmlspecialchars($v['author'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endfor; ?>
    </div>

    <!-- 最后更新和最新入库 -->
    <div class="container flex flex-wrap section-bottom">
        <!-- 最后更新 -->
        <div class="border3-1 lastupdate">
            <p>最后更新</p>
            <?php foreach ($lastupdate as $k => $v): ?>
                <div class="list-out">
                    <!-- 对分类名、小说名、链接和章节名进行安全转义 -->
                    <span class="flex w80"><em>[<?= htmlspecialchars($v['sortname'], ENT_QUOTES, 'UTF-8') ?>]</em><em><a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8') ?></a></em><em><a href="<?= htmlspecialchars($v['last_url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v['lastchapter'], ENT_QUOTES, 'UTF-8') ?></a></em></span>
                    <!-- 对作者名和日期进行安全转义 -->
                    <span class="gray dispc"><?= htmlspecialchars($v['author'], ENT_QUOTES, 'UTF-8') ?>&nbsp;&nbsp;<?= htmlspecialchars(date('m-d', $v['lastupdate']), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- 最新入库 -->
        <div class="border3-1 popular">
            <p>最新入库</p>
            <?php foreach ($postdate as $k => $v): ?>
                <div class="list-out">
                    <!-- 对分类名、小说名和链接进行安全转义 -->
                    <span>[<?= htmlspecialchars($v['sortname_2'], ENT_QUOTES, 'UTF-8') ?>] <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8') ?></a></span>
                    <!-- 对作者名进行安全转义 -->
                    <span class="gray"><?= htmlspecialchars($v['author'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- 友情链接 -->
    <div class="container flex">
        <div class="link">友情链接:</div>
    </div>
    <!-- 对 JavaScript 代码中的选择器进行安全转义 -->
    <script>$('nav a:first-child').addClass('orange');</script>
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>