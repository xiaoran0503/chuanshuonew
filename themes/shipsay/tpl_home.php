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
    <!-- 对 SITE_NAME 进行转义 -->
    <title><?= safeEcho(SITE_NAME) ?>_书友最值得收藏的网络小说阅读网</title>
    <!-- 对关键词和描述中的 SITE_NAME 进行转义 -->
    <meta name="keywords" content="小说,小说网,最新章节免费阅读小说网,<?= safeEcho(SITE_NAME) ?>阅读网">
    <meta name="description" content="小说,小说网,最新章节免费阅读小说网,<?= safeEcho(SITE_NAME) ?>阅读网">
    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <!-- home -->
    <div class="container">
        <!-- 大神小说 -->
        <div class="side_commend side_commend_width">
            <p class="title"><i class="fa fa-thumbs-o-up fa-lg">&nbsp;</i>大神小说</p>
            <ul class="flex">
                <?php if (is_array($commend)): foreach ($commend as $k => $v): if ($k < 6): ?>
                    <li>
                        <div class="img_span">
                            <a href="<?= safeEcho($v['info_url']) ?>"><img src="<?= safeEcho($v['img_url']) ?>" title="<?= safeEcho($v['articlename']) ?>" /></a>
                            <span><?= safeEcho($v['sortname_2']) ?> / <?= safeEcho($v['isfull']) ?></span>
                        </div>
                        <div class="w100">
                            <a href="<?= safeEcho($v['info_url']) ?>"><h2><?= safeEcho($v['articlename']) ?></h2></a>
                            <p class="indent"><?= safeEcho($v['intro_des']) ?></p>
                            <div class="li_bottom">
                                <a href="<?= safeEcho($v['author_url']) ?>"><i class="fa fa-user-circle-o">&nbsp;<?= safeEcho($v['author']) ?></i></a>
                                <div>
                                    <em class="orange"><?= safeEcho($v['words_w']) ?>万字</em>
                                    <em class="blue"><?= safeEcho(Text::ss_lastupdate($v['lastupdate'])) ?></em>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endif; endforeach; endif; ?>
            </ul>
        </div>

        <!-- 热门小说 -->
        <aside>
            <p class="title"><i class="fa fa-fire fa-lg">&nbsp;</i>热门小说</p>
            <ul class="popular odd">
                <?php if (is_array($popular)): foreach ($popular as $k => $v): if ($k < 12): ?>
                    <li>
                        <a href="<?= safeEcho($v['info_url']) ?>"><?= safeEcho($v['articlename']) ?></a>
                        <a class="gray" href="<?= safeEcho($v['author_url']) ?>"><?= safeEcho($v['author']) ?></a>
                    </li>
                <?php endif; endforeach; endif; ?>
            </ul>
        </aside>
    </div>

    <div class="container">
        <div class="section flex">
            <!-- 分类 -->
            <?php for ($i = 1; $i <= 6; $i++): $tmpvar = 'sort'.$i; ?>
                <div class="sortvisit">
                    <a href="<?= safeEcho(Sort::ss_sorturl($i)) ?>"><?= safeEcho(Sort::ss_sortname($i, 1)) ?></a>
                    <ul>
                        <?php if (is_array($$tmpvar)): foreach ($$tmpvar as $k => $v): ?>
                            <?php if ($k == 0): ?>
                                <div>
                                    <a href="<?= safeEcho($v['info_url']) ?>"><img class="lazy" src="<?= safeEcho(Url::nocover_url()) ?>" data-original="<?= safeEcho($v['img_url']) ?>" title="<?= safeEcho($v['articlename']) ?>"></a>
                                    <p>
                                        <a href="<?= safeEcho($v['info_url']) ?>"><?= safeEcho($v['articlename']) ?></a>
                                        <i>&nbsp;/&nbsp;<?= safeEcho($v['author']) ?></i><br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<?= safeEcho($v['intro_des']) ?>
                                    </p>
                                </div>
                            <?php elseif ($k < 13): ?>
                                <li>
                                    <a href="<?= safeEcho($v['info_url']) ?>"><?= safeEcho(mb_substr($v['articlename'], 0, 6)) ?></a>
                                    <i>&nbsp;/ <?= safeEcho($v['author']) ?></i>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>
            <?php endfor; ?> 
        </div>
    </div>

    <div class="container">
        <!-- 最新章节 -->
        <div class="lastupdate">
            <p class="title"><i class="fa fa-clock-o fa-lg">&nbsp;</i>最新章节</p>
            <ul class="odd">
                <?php if (is_array($lastupdate)): foreach ($lastupdate as $k => $v): if ($k < 30): ?>
                    <li>
                        <span>「<?= safeEcho($v['sortname_2']) ?>」</span>
                        <a href="<?= safeEcho($v['info_url']) ?>"><?= safeEcho($v['articlename']) ?></a>
                        <a class="gray" href="<?= safeEcho($v['last_url']) ?>"><?= safeEcho($v['lastchapter']) ?></a>
                        <span>
                            <a class="gray" href="<?= safeEcho($v['author_url']) ?>"><?= safeEcho($v['author']) ?></a>&nbsp;&nbsp;
                            <?= safeEcho(date('m-d', $v['lastupdate'])) ?>
                        </span>
                    </li>
                <?php endif; endforeach; endif; ?>
            </ul>
        </div>

        <!-- 最新入库 -->
        <aside>
            <p class="title"><i class="fa fa-pencil fa-lg">&nbsp;</i>最新小说</p>
            <ul class="popular odd"> 
                <?php if (is_array($postdate)): foreach ($postdate as $k => $v): if ($k < 30): ?>
                    <li>
                        <a href="<?= safeEcho($v['info_url']) ?>"><?= safeEcho($v['articlename']) ?></a>
                        <a class="gray" href="<?= safeEcho($v['author_url']) ?>"><?= safeEcho($v['author']) ?></a>
                    </li>
                <?php endif; endforeach; endif; ?>
            </ul>
        </aside>
    </div> 

    <div class="container">
        <div class="section link">
            <p class="title"><i class="fa fa-link">&nbsp;</i>友情链接</p>
            <!-- 对链接 HTML 进行转义 -->
            <?= safeEcho($link_html) ?>
        </div>
    </div>
    <!-- /home -->
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>