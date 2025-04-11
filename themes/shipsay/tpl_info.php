<?php if (!defined('__ROOT_DIR__')) exit; ?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <title><?= $articlename ?>(<?= $author ?>)_<?= $articlename ?>最新章节免费阅读_<?= SITE_NAME ?></title>
    <meta name="keywords" content="<?= $articlename ?>,<?= $author ?>,<?= $sortname ?>,<?= $isfull ?>">
    <meta name="description" content="《<?= $articlename ?>》<?= $intro_des ?>">
    <meta property="og:type" content="novel">
    <meta property="og:title" content="<?= $articlename ?>">
    <meta property="og:description" content="《<?= $articlename ?>》<?= $intro_des ?>">
    <meta property="og:novel:category" content="<?= $sortname ?>">
    <meta property="og:novel:author" content="<?= $author ?>">
    <meta property="og:novel:author_link" content="<?= $author_url ?>">
    <meta property="og:novel:book_name" content="<?= $articlename ?>">
    <meta property="og:novel:read_url" content="<?= $uri ?>">
    <meta property="og:novel:url" content="<?= $uri ?>">
    <meta property="og:novel:status" content="<?= $isfull ?>">
    <meta property="og:novel:update_time" content="<?= $lastupdate ?>">
    <meta property="og:novel:lastest_chapter_name" content="<?= $lastchapter ?>">
    <meta property="og:novel:lastest_chapter_url" content="<?= $last_url ?>">
    <?php require_once 'tpl_header.php'; ?>
    <div class="container">
        <section class="section">
            <div class="novel_info_main">
                <img src="<?= $img_url ?>" alt="<?= $articlename ?>" />
                <div class="novel_info_title">
                    <h1><?= $articlename ?></h1><i>作者：<a href="<?= $author_url ?>"><?= $author ?></a></i>
                    <p>
                        <span><?= $sortname ?></span><span><?= $words_w ?> 万字</span>
                        <span<?php if ($isfull != "连载") : ?> class="fullflag" <?php endif ?>><?= $isfull ?></span>
                    </p>

                    <?php if (!empty($keywords)) : ?>
                        <p>关键字：<?= $keywords ?></p>
                    <?php endif; ?>

                    <div class="flex to100">最新章节：<a href="<?= $last_url ?>"><?= $lastchapter ?></a><em class="s_gray"><?= $lastupdate_cn ?></em></div>


                    <?php if (!empty($langtailrows)) : ?>
                        <p>相关推荐：
                            <?php foreach ($langtailrows as $v) : ?>
                                <a href="<?= $v['info_url'] ?>"><?= $v['langname'] ?></a>&nbsp;
                            <?php endforeach ?>
                        </p>
                    <?php endif; ?>


                    <div class="flex">
                        <a class="l_btn" href="<?= $first_url ?>"><i class="fa fa-file-text"> 开始阅读</i></a>
                        <?php if ($enable_down) { ?>
                            <a class="l_btn_0" href="/api/txt_down.php?articleid=<?= $sourceid ?>&articlename=<?= $articlename ?>" rel="nofollow"><i class="fa fa-cloud-download"> 下载TXT</i></a>
                        <?php } else { ?>
                            <a class="l_btn_0" href="<?= $fake_recentread ?>" rel="nofollow"><i class="fa fa-tag"> 最近阅读</i></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <ul class="flex ulcard">
                <li class="act"><a id="a_info" href="javascript:a_info();">作品信息</a></li>
                <li><a id="a_catalog" href="javascript:a_catalog();">目录<span>（<?= $chapters ?>章）</span></a></li>
            </ul>

            <div id="info">
                <div class="intro">
                    <p><?= $intro_p ?></p>
                </div>
                <div class="section chapter_list">
                    <div class="title jcc">《<?= $articlename ?>》最新章节</div>
                    <ul>
                        <?php if ($lastarr != '') : ?><?php foreach ($lastarr as $k => $v) : ?>
                        <li><a href="<?= $v['cid_url'] ?>" title="<?= $articlename ?> <?= $v['cname'] ?>"><?= $v['cname'] ?></a></li>
                        <?php endforeach ?><?php endif ?>
                    </ul>
                </div>
            </div>

            <div id="catalog">
                <div class="section chapter_list">
                    <ul id="ul_all_chapters">
                        <?php if ($chapterrows != '') : ?><?php foreach ($chapterrows as $k => $v) : ?>
                        <?php if ($v['chaptertype'] == 1) : ?>
                            <li style="width:100%"><?= $v['cname'] ?></li>
                        <?php else : ?>
                            <li><a href="<?= $v['cid_url'] ?>" title="<?= $articlename ?> <?= $v['cname'] ?>"><?= $v['cname'] ?></a></li>
                        <?php endif ?>
                        <?php endforeach ?><?php endif ?>

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
                })

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