<?php 
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
    <title><?= safeEcho($author) ?> 的全部作品_<?= safeEcho(SITE_NAME) ?></title>

    <meta name="keywords" content="<?= safeEcho($author) ?>的全部小说">
    <meta name="description" content="<?= safeEcho($author) ?>的全部小说">
    <meta property="og:title" content="<?= safeEcho($author) ?>的全部小说">
    <meta property="og:description" content="<?= safeEcho($author) ?>的全部小说"> 

    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <div class="container">
        <div class="side_commend" style="width:100%;">
            <!-- 安全转义输出的变量 -->
            <p class="title"><i class="fa fa-user-circle-o">&nbsp;</i> "<?= safeEcho($author) ?>" 共有 "<?= safeEcho($author_count) ?>" 部作品：</p>
            <ul class="flex">
                <?php if (is_array($res)): ?>
                    <?php foreach ($res as $k => $v): ?>	
                        <li class="searchresult">
                            <div class="img_span">
                                <!-- 安全转义输出的链接和图片地址 -->
                                <a href="<?= safeEcho($v['info_url']) ?>">
                                    <img class="lazy" src="<?= safeEcho(Url::nocover_url()) ?>" data-original="<?= safeEcho($v['img_url']) ?>" title="<?= safeEcho($v['articlename']) ?>" />
                                    <!-- 安全转义输出的分类和状态 -->
                                    <span<?php if ($v['isfull'] == '全本'): ?> class="full"<?php endif ?>><?= safeEcho($v['sortname_2']) ?> / <?= safeEcho($v['isfull']) ?></span>
                                </a>
                            </div>
                            <div>
                                <!-- 安全转义输出的链接和小说名 -->
                                <a href="<?= safeEcho($v['info_url']) ?>"><h3><?= safeEcho($v['articlename']) ?></h3></a>
                                <!-- 安全转义输出的作者、字数和更新时间 -->
                                <p><i class="fa fa-user-circle-o">&nbsp;</i><?= safeEcho($v['author']) ?>&nbsp;&nbsp;<span class="s_gray"><?= safeEcho($v['words_w']) ?> 万字&nbsp;&nbsp;<?= safeEcho(Text::ss_lastupdate($v['lastupdate'])) ?></span></p>
                                <!-- 安全转义输出的简介 -->
                                <p class="searchresult_p"><?= safeEcho($v['intro_des']) ?></p>
                                <!-- 安全转义输出的链接和最新章节名 -->
                                <p><a href="<?= safeEcho($v['last_url']) ?>"><?= safeEcho($v['lastchapter']) ?></a></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>