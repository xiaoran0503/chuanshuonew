<?php if (!defined('__ROOT_DIR__')) exit; ?>

<!DOCTYPE html>
<html lang='zh'>
<head>
<meta charset="UTF-8">
    <title><?=SITE_NAME?>_无弹窗书友最值得收藏的网络小说阅读网</title>
    <meta name="keywords" content="<?=SITE_NAME?>,无弹窗,小说阅读网,<?=SITE_URL?>" />
    <meta name="description" content="<?=SITE_NAME?>是广大书友最值得收藏的网络小说阅读网，网站收录了当前最火热的网络小说，免费提供高质量的小说最新章节，是广大网络小说爱好者必备的小说阅读网。" />

<?php require_once 'tpl_header.php'; require_once __ROOT_DIR__ .'/shipsay/include/neighbor.php';?>
<div class="container">
    <div class="border3-2">
        <div class="info-title">
            <a href="/"><?=SITE_NAME?></a> &gt; <a href="<?=Sort::ss_sorturl($sortid)?>"><?=$sortname?></a> &gt; <?=$articlename?>最新章节列表
        </div>
        <div class="info-main">
        <img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$img_url?>">
            <div class="w100">
                <h1><?=$articlename?></h1>
                <div class="w100 dispc"><span><a href="<?=$author_url?>">作&nbsp;&nbsp;&nbsp;&nbsp;者：<?=$author?></span>动&nbsp;&nbsp;&nbsp;&nbsp;做：<a href="<?=$first_url?>">开始阅读</a>，<?=$isfull?>，<a href="javascript:gofooter();">直达底部</a></div>
                <div class="dispc"><span>最后更新：<?=$lastupdate?></span><a href="<?=$last_url?>">最新章节：<?=$lastchapter?></a></div>
                <div class="info-main-intro"><?=$intro_p?></div>
            </div>
        </div>

        <div class="info-commend">推荐阅读: 
            <?php foreach($neighbor as $k => $v): ?>
                <a href="<?=$v['info_url'] ?>" title="<?=$articlename?>"><?=$v['articlename'] ?></a>
            <?php endforeach ?>
        </div>


    </div>
    <div class="diswap info-main-wap border3-1">
        <a href="<?=$author_url?>"><p>作&nbsp;&nbsp;&nbsp;&nbsp;者：<?=$author?></p></a>
        <p>最后更新：<?=$lastupdate?>&nbsp;&nbsp;<a href="javascript:gofooter();">直达底部</a></p>
        <a href="<?=$last_url?>"><p>最新章节：<?=$lastchapter?></p></a>
    </div>
</div>

<div class="container border3-2 mt8">
    <div class="info-chapters-title"><strong>《<?=$articlename?>》最新章节</strong><span class="dispc">（提示：已启用缓存技术，最新章节可能会延时显示。）</span></div>
    <div class="info-chapters flex flex-wrap">
        <?php foreach($lastarr as $k => $v): ?>
            <a href="<?=$v['cid_url'] ?>" title="<?=$articlename?> <?=$v['cname'] ?>"><?=$v['cname'] ?></a>
        <?php endforeach ?>
    </div>
</div>

<div class="container border3-2 mt8 mb20">
    <div class="info-chapters-title"><strong>《<?=$articlename?>》正文</strong></div>
    <div class="info-chapters flex flex-wrap">
        <?php foreach($chapterrows as $k => $v): ?>
            <a href="<?=$v['cid_url'] ?>" title="<?=$articlename?> <?=$v['cname'] ?>"><?=$v['cname'] ?></a>
        <?php endforeach ?>
    </div>
</div>

<div class="container">
    <div class="info-commend">最新小说: 
        <?php foreach($postdate as $k => $v): ?>
            <a href="<?=$v['info_url'] ?>" title="<?=$articlename?>"><?=$v['articlename'] ?></a>
        <?php endforeach ?>
    </div>
</div>
<button class="gotop" onclick="javascript:gotop();">顶部</button>

<?php require_once 'tpl_footer.php'; ?>
