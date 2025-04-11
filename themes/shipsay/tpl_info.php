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
    <meta charset="UTF-8">
    <!-- 对标题相关变量进行转义 -->
    <title><?= safeEcho($articlename) ?>(<?= safeEcho($author) ?>)_<?= safeEcho($articlename) ?>最新章节免费阅读_<?= safeEcho(SITE_NAME) ?></title>
    <!-- 对关键词和描述相关变量进行转义 -->
    <meta name="keywords" content="<?= safeEcho($articlename) ?>,<?= safeEcho($author) ?>,<?= safeEcho($sortname) ?>,<?= safeEcho($isfull) ?>">
    <meta name="description" content="《<?= safeEcho($articlename) ?>》<?= safeEcho($intro_des) ?>">
    <meta property="og:type" content="novel">
    <meta property="og:title" content="<?= safeEcho($articlename) ?>">
    <meta property="og:description" content="《<?= safeEcho($articlename) ?>》<?= safeEcho($intro_des) ?>">
    <meta property="og:novel:category" content="<?= safeEcho($sortname) ?>">
    <meta property="og:novel:author" content="<?= safeEcho($author) ?>">
    <meta property="og:novel:author_link" content="<?= safeEcho($author_url) ?>">
    <meta property="og:novel:book_name" content="<?= safeEcho($articlename) ?>">
    <meta property="og:novel:read_url" content="<?= safeEcho($uri) ?>">
    <meta property="og:novel:url" content="<?= safeEcho($uri) ?>">
    <meta property="og:novel:status" content="<?= safeEcho($isfull) ?>">
    <meta property="og:novel:update_time" content="<?= safeEcho($lastupdate) ?>">
    <meta property="og:novel:lastest_chapter_name" content="<?= safeEcho($lastchapter) ?>">
    <meta property="og:novel:lastest_chapter_url" content="<?= safeEcho($last_url) ?>">
    <?php require_once 'tpl_header.php'; ?>
</head>

<body>
    <div class="container">
        <section class="section">
            <div class="novel_info_main">
                <!-- 对图片相关变量进行转义 -->
                <img src="<?= safeEcho($img_url) ?>" alt="<?= safeEcho($articlename) ?>" />
                <div class="novel_info_title">
                    <!-- 对小说名和作者相关变量进行转义 -->
                    <h1><?= safeEcho($articlename) ?></h1><i>作者：<a href="<?= safeEcho($author_url) ?>"><?= safeEcho($author) ?></a></i>
                    <p>
                        <!-- 对分类、字数和状态相关变量进行转义 -->
                        <span><?= safeEcho($sortname) ?></span><span><?= safeEcho($words_w) ?> 万字</span>
                        <span<?php if (safeEcho($isfull) != "连载") : ?> class="fullflag" <?php endif ?>><?= safeEcho($isfull) ?></span>
                    </p>

                    <?php if (!empty($keywords)) : ?>
                        <!-- 对关键字相关变量进行转义 -->
                        <p>关键字：<?= safeEcho($keywords) ?></p>
                    <?php endif; ?>

                    <div class="flex to100">
                        <!-- 对最新章节相关变量进行转义 -->
                        最新章节：<a href="<?= safeEcho($last_url) ?>"><?= safeEcho($lastchapter) ?></a><em class="s_gray"><?= safeEcho($lastupdate_cn) ?></em>
                    </div>

                    <?php if (!empty($langtailrows)) : ?>
                        <p>相关推荐：
                            <?php foreach ($langtailrows as $v) : ?>
                                <!-- 对相关推荐链接和名称进行转义 -->
                                <a href="<?= safeEcho($v['info_url']) ?>"><?= safeEcho($v['langname']) ?></a>&nbsp;
                            <?php endforeach ?>
                        </p>
                    <?php endif; ?>

                    <div class="flex">
                        <!-- 对开始阅读链接和下载/最近阅读链接进行转义 -->
                        <a class="l_btn" href="<?= safeEcho($first_url) ?>"><i class="fa fa-file-text"> 开始阅读</i></a>
                        <?php if ($enable_down) { ?>
                            <a class="l_btn_0" href="/api/txt_down.php?articleid=<?= safeEcho($sourceid) ?>&articlename=<?= safeEcho($articlename) ?>" rel="nofollow"><i class="fa fa-cloud-download"> 下载TXT</i></a>
                        <?php } else { ?>
                            <a class="l_btn_0" href="<?= safeEcho($fake_recentread) ?>" rel="nofollow"><i class="fa fa-tag"> 最近阅读</i></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <ul class="flex ulcard">
                <li class="act"><a id="a_info" href="javascript:a_info();">作品信息</a></li>
                <!-- 对目录章节数进行转义 -->
                <li><a id="a_catalog" href="javascript:a_catalog();">目录<span>（<?= safeEcho($chapters) ?>章）</span></a></li>
            </ul>

            <div id="info">
                <div class="intro">
                    <!-- 对作品简介进行转义 -->
                    <p><?= safeEcho($intro_p) ?></p>
                </div>
                <div class="section chapter_list">
                    <!-- 对小说名进行转义 -->
                    <div class="title jcc">《<?= safeEcho($articlename) ?>》最新章节</div>
                    <ul>
                        <?php if ($lastarr != '') : foreach ($lastarr as $k => $v) : ?>
                            <!-- 对章节链接和名称进行转义 -->
                            <li><a href="<?= safeEcho($v['cid_url']) ?>" title="<?= safeEcho($articlename) ?> <?= safeEcho($v['cname']) ?>"><?= safeEcho($v['cname']) ?></a></li>
                        <?php endforeach; endif ?>
                    </ul>
                </div>
            </div>

            <div id="catalog">
                <div class="section chapter_list">
                    <ul id="ul_all_chapters">
                        <?php if ($chapterrows != '') : foreach ($chapterrows as $k => $v) : ?>
                            <?php if ($v['chaptertype'] == 1) : ?>
                                <!-- 对章节名称进行转义 -->
                                <li style="width:100%"><?= safeEcho($v['cname']) ?></li>
                            <?php else : ?>
                                <!-- 对章节链接和名称进行转义 -->
                                <li><a href="<?= safeEcho($v['cid_url']) ?>" title="<?= safeEcho($articlename) ?> <?= safeEcho($v['cname']) ?>"><?= safeEcho($v['cname']) ?></a></li>
                            <?php endif ?>
                        <?php endforeach; endif ?>
                    </ul>
                </div>
                <i id="gotop" class="fa fa-sign-in" onclick="gotop();"></i><i id="gofooter" class="fa fa-sign-in" onclick="gofooter();"></i>
            </div>
        </section>
    </div>

    <script>
        function vote(aid, maxnum) {
            if ($.cookie('ss_userid') && $.cookie('PHPSESSID') != -1) {
                if ($.cookie('articlevote') >= maxnum) {
                    alert('推荐票 ( ' + maxnum + ' ) 已用完, 请明日再来.');
                    return;
                }
                $.ajax({
                    type: 'post',
                    url: '/api/articlevisit.php',
                    data: {
                        vote: true,
                        articleid: aid
                    },
                    success: function(data) {
                        if (data == '200') {
                            tmpstr = $.cookie('articlevote') + ' / ' + maxnum;
                            alert('推荐成功: ( ' + tmpstr + ' )');
                        } else {
                            alert('推荐失败');
                        }
                    }
                });
            } else {
                if (window.confirm("\n当前功能需要登录才能使用，转到登录页面吗？")) {
                    window.location.href = "/login/";
                } else {
                    return;
                }
            }
        }
    </script>

    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>