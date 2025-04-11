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
    <!-- 对搜索关键字和网站名称进行转义 -->
    <title><?= safeEcho($searchkey) ?> 的搜索结果_<?= safeEcho(SITE_NAME) ?></title>
    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <div class="container">
        <div class="side_commend" style="width:100%;">
            <!-- 对搜索关键字和结果数量进行转义 -->
            <p class="title"><i class="fa fa-search">&nbsp;</i>搜索 "<?= safeEcho($searchkey) ?>" 共有 "<?= safeEcho($search_count) ?>" 条数据</p>
            <ul class="flex">
                <?php if (is_array($search_res)): ?>
                    <?php foreach ($search_res as $k => $v): ?>
                        <li class="searchresult">
                            <div class="img_span">
                                <!-- 对链接、图片地址和小说名称进行转义 -->
                                <a href="<?= safeEcho($v['info_url']) ?>"><img class="lazy" src="<?= safeEcho(Url::nocover_url()) ?>" data-original="<?= safeEcho($v['img_url']) ?>" title="<?= safeEcho($v['articlename']) ?>" />
                                <span<?php if ($v['isfull'] == '全本'): ?> class="full"<?php endif ?>><?= safeEcho($v['sortname_2']) ?> / <?= safeEcho($v['isfull']) ?></span></a>
                            </div>
                            <div>
                                <!-- 对链接、小说名称和搜索关键字高亮处理进行转义 -->
                                <a href="<?= safeEcho($v['info_url']) ?>"><h3><?= str_replace(safeEcho($searchkey), '<span class="hot">'.safeEcho($searchkey).'</span>', safeEcho($v['articlename'])) ?></h3></a>
                                <!-- 对作者、字数和更新时间进行转义 -->
                                <p><i class="fa fa-user-circle-o">&nbsp;</i><?= str_replace(safeEcho($searchkey), '<span class="hot">'.safeEcho($searchkey).'</span>', safeEcho($v['author'])) ?>&nbsp;&nbsp;<span class="s_gray"><?= safeEcho($v['words_w']) ?> 万字&nbsp;&nbsp;<?= safeEcho(Text::ss_lastupdate($v['lastupdate'])) ?></span></p>
                                <!-- 对小说简介进行转义 -->
                                <p class="searchresult_p"><?= safeEcho($v['intro_des']) ?></p>
                                <!-- 对链接和最新章节名称进行转义 -->
                                <p><a href="<?= safeEcho($v['last_url']) ?>"><?= safeEcho($v['lastchapter']) ?></a></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="s_gray tc">注意：最多显示100条结果</div>
        </div>
    </div>
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>