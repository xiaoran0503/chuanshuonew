<?php 
if (!defined('__ROOT_DIR__')) exit; 

// 定义一个安全输出函数，对变量进行 HTML 实体转义
function safeEcho($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang='zh'>
<head>
    <meta charset="UTF-8">
    <!-- 对输出变量进行安全转义 -->
    <title><?= safeEcho($chaptername) ?>_<?= safeEcho($articlename) ?>_<?= safeEcho(SITE_NAME) ?></title>
    <meta name="keywords" content="<?= safeEcho($articlename) ?>, <?= safeEcho($chaptername) ?>" />
    <meta name="description" content="<?= safeEcho(SITE_NAME) ?>提供了<?= safeEcho($author) ?>创作的<?= safeEcho($sortname) ?>小说《<?= safeEcho($articlename) ?>》干净清爽无错字的文字章节： <?= safeEcho($chaptername) ?>在线阅读。" />

    <?php require_once 'tpl_header.php'; require_once __ROOT_DIR__ .'/shipsay/include/neighbor.php';?>
</head>
<body>
    <div class="container">
        <div class="border3-2" id="ss-reader-main">
            <div class="info-title">
                <!-- 对链接和文本进行安全转义 -->
                <a href="/"><?= safeEcho(SITE_NAME) ?></a> &gt; <a href="<?= safeEcho(Sort::ss_sorturl($sortid)) ?>"><?= safeEcho($sortname) ?></a> &gt; <a href="<?= safeEcho($info_url) ?>"><?= safeEcho($articlename) ?></a> &gt; <?= safeEcho($chaptername) ?>
            </div>
            <div class="reader-main">
                <script src="/static/<?= safeEcho($theme_dir) ?>/readpage.js"></script>
                <!-- 对输出变量进行安全转义 -->
                <h1><?= safeEcho($chaptername) ?>（<?= safeEcho($now_pid) ?> / <?= safeEcho($max_pid) ?>）</h1>

                <!-- 调用函数生成导航按钮 -->
                <?php include 'tpl_reader_nav.php'; ?>
            </div>

            <div class="info-commend mt8">
                推荐阅读: 
                <?php foreach($neighbor as $k => $v): ?>
                    <!-- 对链接和文本进行安全转义 -->
                    <a href="<?= safeEcho($v['info_url']) ?>" title="<?= safeEcho($articlename) ?>"><?= safeEcho($v['articlename']) ?></a>
                <?php endforeach ?>
            </div>
            <div class="reader-hr"></div>
            <!-- 正文部分 -->
            <article id="article" class="content"><?= safeEcho($rico_content) ?></article>
            <div class="reader-hr"></div>
            <!-- 调用函数生成导航按钮 -->
            <?php include 'tpl_reader_nav.php'; ?>
        </div>
    </div>

    <div class="container">
        <div class="info-commend mt8">
            最新小说: 
            <?php foreach($postdate as $k => $v): ?>
                <!-- 对链接和文本进行安全转义 -->
                <a href="<?= safeEcho($v['info_url']) ?>" title="<?= safeEcho($articlename) ?>"><?= safeEcho($v['articlename']) ?></a>
            <?php endforeach ?>
        </div>
    </div>

    <script src="/static/<?= safeEcho($theme_dir) ?>/tempbookcase.js"></script>
    <script>
        lastread.set('<?= safeEcho($articleid) ?>','<?= safeEcho($uri) ?>','<?= safeEcho($articlename) ?>','<?= safeEcho($chaptername) ?>','<?= safeEcho($author) ?>','<?= date('Y-m-d', time()) ?>');
        const preview_page = $('#prev_url').attr('href');
        const index_page = $('#info_url').attr('href');
        const next_page = $('#next_url').attr('href');
        function jumpPage(){
            const event = document.all ? window.event : arguments[0];
            if (event.keyCode == 37) document.location = preview_page;
            if (event.keyCode == 39) document.location = next_page;
            if (event.keyCode == 13) document.location = index_page;
        }
        document.onkeydown = jumpPage;
    </script>
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>