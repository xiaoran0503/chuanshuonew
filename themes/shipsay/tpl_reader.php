<?php 
// 确保定义了 __ROOT_DIR__，避免直接访问
if (!defined('__ROOT_DIR__')) {
    exit;
}
// 引入报告配置文件
require_once __ROOT_DIR__ . '/shipsay/configs/report.ini.php';

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
    <title><?= safeEcho($articlename) ?>(<?= safeEcho($author) ?>)_<?= safeEcho($chaptername) ?>（<?= safeEcho($now_pid) ?> / <?= safeEcho($max_pid) ?>）_<?= safeEcho($articlename) ?>最新章节免费阅读无弹窗_<?= safeEcho(SITE_NAME) ?></title>
    <!-- 对关键词和描述相关变量进行转义 -->
    <meta name="keywords" content="<?= safeEcho($chaptername) ?>,<?= safeEcho($articlename) ?>,<?= safeEcho($author) ?>,<?= safeEcho($sortname) ?>,<?= safeEcho($isfull) ?>">
    <meta name="description" content="《<?= safeEcho($articlename) ?>》<?= safeEcho($chaptername) ?>：<?= safeEcho($reader_des) ?>">
    <meta property="og:type" content="novel">
    <meta property="og:title" content="<?= safeEcho($articlename) ?>">
    <meta property="og:description" content="《<?= safeEcho($articlename) ?>》<?= safeEcho($chaptername) ?>：<?= safeEcho($reader_des) ?>">
    <meta property="og:novel:category" content="<?= safeEcho($sortname) ?>小说">
    <meta property="og:novel:author" content="<?= safeEcho($author) ?>">
    <meta property="og:novel:book_name" content="<?= safeEcho($articlename) ?>">
    <meta property="og:novel:index_url" content="<?= safeEcho($info_url) ?>">
    <meta property="og:novel:info_url" content="<?= safeEcho($info_url) ?>">
    <meta property="og:novel:status" content="<?= safeEcho($isfull) ?>">
    <meta property="og:novel:chapter_name" content="<?= safeEcho($chaptername) ?>">
    <meta property="og:novel:chapter_url" content="<?= safeEcho($uri) ?>">
    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <div class="read_bg">
        <main class="container">
            <section class="section_style">
                <div class="text">
                    <div class="text_set">
                        <i class="fr cog fa fa-cog fa-3x" onclick="javascript:cog();"></i>
                        <div id="text_control">
                            <div class="fontsize">
                                <button onclick="javascript:changeSize('min');" title="缩小字号">A-</button> 
                                <button onclick="javascript:changeSize('normal');" title="标准字号"> A</button> 
                                <button onclick="javascript:changeSize('plus');" title="放大字号">A+</button>
                            </div>
                            <div>
                                <a href="javascript:alert('敬请期待')" title="加入书签"><i class="fa fa-bookmark"></i></a>
                                <a href="javascript:isnight();" title="白天夜间模式"><i class="fa fa-moon-o fa-flip-horizontal"></i></a>
                                <a href="javascript:;" title="极简模式"><i id="ismini" class="fa fa-minus-square" onclick="ismini();" ></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text_title">
                    <!-- 对章节名和页码相关变量进行转义 -->
                    <p class="style_h1"><?= safeEcho($chaptername) ?>（<?= safeEcho($now_pid) ?> / <?= safeEcho($max_pid) ?>）</p>
                    <div class="text_info">
                        <!-- 对链接和文本相关变量进行转义 -->
                        <span><a href="<?= safeEcho($info_url) ?>"><i class="fa fa-book"> <?= safeEcho($articlename) ?></i></a></span>
                        <span><a href="<?= safeEcho($author_url) ?>"><i class="fa fa-user-circle-o"> <?= safeEcho($author) ?></i></a></span>
                        <span><i class="fa fa-list-ol"> <?= safeEcho($chapterwords) ?> 字</i></span>
                        <span><i class="fa fa-clock-o"> <?= safeEcho(Text::ss_lastupdate($lastupdate)) ?></i></span>
                    </div>
                </div>
                <!-- 对文章内容进行转义 -->
                <article id="article" class="content"><?= safeEcho($rico_content) ?></article>
                <?php if(!empty($ShipSayReport['on'])) : ?>
                    <div style="text-align:center;margin: -10px 0 10px;">
                        <a style="color:red;font-size:16px;font-weight:700" href="javascript:report();">举报本章错误( 无需登录 ) </a>
                    </div>
                <?php endif ?>
                <div class="s_gray tc"><script>tips('<?= safeEcho($articlename) ?>');</script></div>
            </section>
            <div class="read_nav">
                <?php if($prevpage_url != ''): ?>
                    <!-- 对上一页链接进行转义 -->
                    <a id="prev_url" href="<?= safeEcho($prevpage_url) ?>"><i class="fa fa-backward"></i> 上一页</a>
                <?php else: ?>
                    <?php if($pre_cid == 0): ?>
                        <!-- 对没有上一页的链接进行转义 -->
                        <a id="prev_url" href="<?= safeEcho($info_url) ?>" class="w_gray"><i class="fa fa-stop"></i> 没有了</a>
                    <?php else: ?>
                        <!-- 对上一章链接进行转义 -->
                        <a id="prev_url" href="<?= safeEcho($pre_url) ?>"><i class="fa fa-backward"></i> 上一章</a>
                    <?php endif ?>
                <?php endif ?>

                <!-- 对书页/目录链接进行转义 -->
                <a id="info_url" href="<?= safeEcho($info_url) ?>">书页/目录</a> 

                <?php if($nextpage_url != ''): ?>
                    <!-- 对下一页链接进行转义 -->
                    <a id="next_url" href="<?= safeEcho($nextpage_url) ?>"><i class="fa fa-forward"></i> 下一页</a>
                <?php else: ?>
                    <?php if($next_cid == 0): ?>
                        <!-- 对没有下一页的链接进行转义 -->
                        <a id="next_url" href="<?= safeEcho($info_url) ?>" class="w_gray">没有了 <i class="fa fa-stop"></i></a>
                    <?php else: ?>
                        <!-- 对下一章链接进行转义 -->
                        <a id="next_url" href="<?= safeEcho($next_url) ?>">下一章 <i class="fa fa-forward"></i></a>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </main>
    </div>

    <script>
        lastread.set('<?= safeEcho($info_url) ?>','<?= safeEcho($uri) ?>','<?= safeEcho($articlename) ?>','<?= safeEcho($chaptername) ?>','<?= safeEcho($author) ?>','<?= safeEcho($img_url) ?>');
        document.onkeydown = function() {
            if (event.keyCode === 37) {
                window.location = document.querySelector('#prev_url').getAttribute('href');
            }
            if (event.keyCode === 39) {
                window.location = document.querySelector('#next_url').getAttribute('href');
            }
            if (event.keyCode === 13) {
                window.location = document.querySelector('#info_url').getAttribute('href');
            }
        };

        function report() {
            if ($.cookie('report')) {
                alert('请不要过于频繁,<?= safeEcho($ShipSayReport['delay']) ?>秒可操作一次');
                return false;
            } else {
                let date = new Date();
                date.setTime(date.getTime() + (parseInt(<?= safeEcho($ShipSayReport['delay']) ?>) * 1000)); // n秒一次.
                $.cookie('report', true, {expires: date, path: '/'});
                $.ajax({
                    type: "post",
                    url: "/api/report.php?do=report",
                    data: {
                        articleid: '<?= safeEcho($articleid) ?>',
                        chapterid: '<?= safeEcho($chapterid) ?>',
                        articlename: '<?= safeEcho($articlename) ?>',
                        chaptername: '<?= safeEcho($chaptername) ?>',
                        repurl: window.location.href
                    },
                    success: function(state) {
                        state === '200' ? alert('成功！\n我们会尽快处理！\n感谢您对本站的支持') : alert('失败！\n请联系网站管理员');
                    }
                });
            }
        }
    </script>
    <?php require_once 'tpl_footer.php'; ?>
    <script src="/static/<?= safeEcho($theme_dir) ?>/style.js"></script>
</body>
</html>