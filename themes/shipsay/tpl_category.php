<?php
// 确保在文件开头定义了 __ROOT_DIR__
if (!defined('__ROOT_DIR__')) {
    exit;
}

// 输出 HTML 文档头部
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <!-- 设置页面标题 -->
    <title><?php if ($sortname!== ''):?><?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?><?php else:?>小说书库<?php endif?>_书友最值得收藏的网络小说阅读网</title>
    <!-- 设置页面关键词 -->
    <meta name="keywords" content="小说,小说网,最新章节免费阅读小说网,<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8')?>阅读网">
    <!-- 设置页面描述 -->
    <meta name="description" content="小说,小说网,最新章节免费阅读小说网,<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8')?>阅读网">
    <!-- 引入头部模板文件 -->
    <?php require_once 'tpl_header.php'; ?>
</head>
<body>
    <!-- sort -->
    <div class="store">
        <div class="store_left">
            <!-- 菜单图标，点击显示筛选菜单 -->
            <i id="store_menu" class="fa fa-bars fa-3x" onclick="javascript: store_menu();" title="筛选菜单"></i>
            <div class="side_commend">
                <!-- 分类标题 -->
                <div class="title"><?= htmlspecialchars($sortname, ENT_QUOTES, 'UTF-8')?></div>
                <!-- wap 端 -->
                <div id="after_menu">
                    <div>
                        <a href="#"></a>
                        <!-- 只看全本复选框 -->
                        <a href="javascript:" onclick="document.location='<?= htmlspecialchars($full_url, ENT_QUOTES, 'UTF-8')?>'">
                            <label><input type="checkbox"<?php if ($fullflag):?> checked="checked"<?php endif?> /> 只看全本</label>
                        </a>
                    </div>
                    <div>
                        <!-- 全部分类链接 -->
                        <a href="<?= htmlspecialchars($allbooks_url, ENT_QUOTES, 'UTF-8')?>" <?php if ($sortid === -1):?> class="onselect"<?php endif?>>全部分类</a>
                        <?php
                        // 遍历分类列表
                        if (is_array($sortcategory)) {
                            foreach ($sortcategory as $k => $v) {
                                echo '<a href="'.htmlspecialchars($v['sorturl'], ENT_QUOTES, 'UTF-8').'"';
                                if ($sortid === $k) {
                                    echo ' class="onselect"';
                                }
                                echo '>'.htmlspecialchars($v['sortname'], ENT_QUOTES, 'UTF-8').'</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <!-- /wap 端 -->
                <ul class="flex">
                    <?php
                    // 遍历小说列表
                    if (is_array($retarr)) {
                        foreach ($retarr as $k => $v) {
                            ?>
                            <li>
                                <!-- 小说封面和分类、状态信息 -->
                                <div class="img_span">
                                    <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8')?>">
                                        <img class="lazy" src="<?= htmlspecialchars(Url::nocover_url(), ENT_QUOTES, 'UTF-8')?>" data-original="<?= htmlspecialchars($v['img_url'], ENT_QUOTES, 'UTF-8')?>" title="<?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8')?>" />
                                        <span<?php if ($v['isfull']!== '连载'):?> class="full"<?php endif?>><?= htmlspecialchars($v['sortname_2'], ENT_QUOTES, 'UTF-8')?> / <?= htmlspecialchars($v['isfull'], ENT_QUOTES, 'UTF-8')?></span>
                                    </a>
                                </div>
                                <div class="w100">
                                    <!-- 小说名称 -->
                                    <a href="<?= htmlspecialchars($v['info_url'], ENT_QUOTES, 'UTF-8')?>"><h2><?= htmlspecialchars($v['articlename'], ENT_QUOTES, 'UTF-8')?></h2></a>
                                    <!-- 小说简介 -->
                                    <p class="indent"><?= htmlspecialchars($v['intro_des'], ENT_QUOTES, 'UTF-8')?></p>
                                    <div class="li_bottom">
                                        <!-- 小说作者 -->
                                        <a href="<?= htmlspecialchars($v['author_url'], ENT_QUOTES, 'UTF-8')?>"><i class="fa fa-user-circle-o">&nbsp;<?= htmlspecialchars($v['author'], ENT_QUOTES, 'UTF-8')?></i></a>
                                        <div>
                                            <!-- 小说字数和更新时间 -->
                                            <em class="orange"><?= htmlspecialchars($v['words_w'], ENT_QUOTES, 'UTF-8')?>万字</em>
                                            <em class="blue"><?= htmlspecialchars(Text::ss_lastupdate($v['lastupdate']), ENT_QUOTES, 'UTF-8')?></em>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <!-- 分页链接 -->
                <div class="pages"><div class="pagelink" id="pagelink"><?= htmlspecialchars($jump_html, ENT_QUOTES, 'UTF-8')?></div></div>
            </div>
        </div>
        <!-- 右边 -->
        <div id="store_right">
            <ul>
                <!-- 全部分类链接 -->
                <li><a href="<?= htmlspecialchars($allbooks_url, ENT_QUOTES, 'UTF-8')?>" <?php if ($sortid === -1):?> class="onselect"<?php endif?>>全部分类</a></li>
            </ul>
            <ul>
                <?php
                // 遍历分类列表
                if (is_array($sortcategory)) {
                    foreach ($sortcategory as $k => $v) {
                        echo '<li><a href="'.htmlspecialchars($v['sorturl'], ENT_QUOTES, 'UTF-8').'"';
                        if ($sortid === $k) {
                            echo ' class="onselect"';
                        }
                        echo '>'.htmlspecialchars($v['sortname'], ENT_QUOTES, 'UTF-8').'</a></li>';
                    }
                }
                ?>
            </ul>
            <ul>
                <li onclick="javascript: document.location='<?= htmlspecialchars($full_url, ENT_QUOTES, 'UTF-8')?>'">
                    <!-- 只看全本复选框 -->
                    <label><input type="checkbox"<?php if ($fullflag):?> checked="checked"<?php endif?> /> 只看全本</label>
                </li>
            </ul>
        </div>
    </div>
    <!-- /sort -->
    <!-- 引入页脚模板文件 -->
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>