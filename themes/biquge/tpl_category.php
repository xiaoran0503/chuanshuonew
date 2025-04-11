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
    <title><?php if ($sortname !== ''):?><?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>_好看的<?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>_<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8')?><?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>小说排行榜<?php else:?>小说书库<?php endif ?>_<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8')?></title>
    <meta name="keywords" content="<?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>,好看的<?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>,<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8')?><?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>排行榜" />
    <meta name="description" content="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8')?>是广大书友最值得收藏的<?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>阅读网，网站收录了当前最好看的<?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>，免费提供高质量的<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8')?><?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>排行榜，是广大<?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>爱好者必备的小说阅读网。" />
    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <div class="container flex flex-wrap">
        <!-- 带封面六本 -->
        <div class="border3 commend flex flex-between category-commend">
            <?php foreach ($retarr as $k => $v): ?>
                <?php if ($k < 6): ?>
                    <div class="category-div">
                        <!-- 对链接和图片地址进行安全转义 -->
                        <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8')?>"><img class="lazy" src="/static/<?= htmlspecialchars($theme_dir, ENT_QUOTES, 'UTF-8')?>/nocover.jpg" data-original="<?= htmlspecialchars($v['img_url'], ENT_QUOTES, 'UTF-8')?>"></a>
                        <div>
                            <div class="flex flex-between commend-title">
                                <!-- 对小说名和链接进行安全转义 -->
                                <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8')?>"><h3><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8')?></h3></a> 
                                <!-- 对作者名进行安全转义 -->
                                <span><?= htmlspecialchars($v['author'], ENT_QUOTES, 'UTF-8')?></span>
                            </div>
                            <!-- 对简介进行安全转义 -->
                            <div class="intro indent"><?= htmlspecialchars($v['intro_des'], ENT_QUOTES, 'UTF-8')?></div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 最后更新和最新入库 -->
    <div class="container flex flex-wrap section-bottom mb20">
        <!-- 最后更新 -->
        <div class="border3-1 lastupdate">
            <p>最后更新的<?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>小说</p>
            <?php foreach ($retarr as $k => $v): ?>
                <?php if ($k >= 6): ?>
                    <div class="list-out">
                        <!-- 对分类名、小说名、链接和章节名进行安全转义 -->
                        <span class="flex w80"><em>[<?= htmlspecialchars($v['sortname'], ENT_QUOTES, 'UTF-8')?>]</em><em><a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8')?>"><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8')?></a></em><em><a href="<?= htmlspecialchars($v['last_url'], ENT_QUOTES, 'UTF-8')?>"><?= htmlspecialchars($v['lastchapter'], ENT_QUOTES, 'UTF-8')?></a></em></span>
                        <!-- 对作者名和日期进行安全转义 -->
                        <span class="gray dispc"><?= htmlspecialchars($v['author'], ENT_QUOTES, 'UTF-8')?>&nbsp;&nbsp;<?= htmlspecialchars(date('m-d', $v['lastupdate']), ENT_QUOTES, 'UTF-8')?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- 最新入库 -->
        <div class="border3-1 popular">
            <p>最新<?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?>小说</p>
            <?php
            $limit = count($sort_postdate) - 6;
            foreach ($sort_postdate as $k => $v):
                if ($k < $limit):
            ?>
                <div class="list-out">
                    <!-- 对分类名、小说名和链接进行安全转义 -->
                    <span>[<?= htmlspecialchars($v['sortname_2'], ENT_QUOTES, 'UTF-8')?>] <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8')?>"><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8')?></a></span>
                    <!-- 对作者名进行安全转义 -->
                    <span class="gray"><?= htmlspecialchars($v['author'], ENT_QUOTES, 'UTF-8')?></span>
                </div>
            <?php
                endif;
            endforeach;
            ?>
        </div>
    </div>
    <!-- 对 JavaScript 代码中的变量进行安全转义 -->
    <script>$('nav a:nth-child(<?= htmlspecialchars($sortid + 1, ENT_QUOTES, 'UTF-8')?>)').addClass('orange');</script>
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>